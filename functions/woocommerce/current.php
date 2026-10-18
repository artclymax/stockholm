<?php
/**
 * AJAX добавление всего комплекта/образа в корзину за один запрос
 */
add_action('wp_ajax_add_bundle_to_cart', 'custom_add_bundle_to_cart');
add_action('wp_ajax_nopriv_add_bundle_to_cart', 'custom_add_bundle_to_cart');

function custom_add_bundle_to_cart()
{
	if (!isset($_POST['items']) || !is_array($_POST['items'])) {
		wp_send_json_error(array('message' => 'Нет товаров для добавления'));
	}

	$added_items = array();

	foreach ($_POST['items'] as $item) {
		$product_id = isset($item['product_id']) ? absint($item['product_id']) : 0;
		$quantity = isset($item['quantity']) ? wc_stock_amount($item['quantity']) : 1;
		$variation_id = isset($item['variation_id']) ? absint($item['variation_id']) : 0;
		$variations = array();

		if (!empty($item['attributes']) && is_array($item['attributes'])) {
			foreach ($item['attributes'] as $attr_key => $attr_val) {
				$variations[sanitize_text_field($attr_key)] = sanitize_text_field($attr_val);
			}
		}

		if ($product_id > 0) {
			$cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations);
			if ($cart_item_key) {
				$added_items[] = $cart_item_key;
			}
		}
	}

	if (!empty($added_items)) {
		WC_AJAX::get_refreshed_fragments();
	} else {
		wp_send_json_error(array('message' => 'Не удалось добавить товары в корзину'));
	}

	wp_die();
}

/**
 * AJAX подгрузка товаров по кнопке "Показать еще"
 */
add_action('wp_ajax_load_more_products', 'ajax_load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'ajax_load_more_products');

function ajax_load_more_products()
{
	$category_id = isset($_POST['category']) ? absint($_POST['category']) : 0;
	$exclude_ids = !empty($_POST['exclude_ids']) ? array_map('absint', (array) $_POST['exclude_ids']) : array();

	// Получаем значение сортировки
	$default_orderby = apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
	$orderby_raw = (!empty($_POST['orderby']) && $_POST['orderby'] !== 'undefined') ? sanitize_text_field($_POST['orderby']) : $default_orderby;

	// Количество товаров для догрузки берем равным системному или 4 (как в секции)
	$posts_per_page = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());
	if (!$posts_per_page || $posts_per_page < 1) {
		$posts_per_page = 4;
	}

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
		'post__not_in' => $exclude_ids, // Исключаем уже показанные товары
		'ignore_sticky_posts' => 1,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field' => 'name',
				'terms' => array('exclude-from-catalog'),
				'operator' => 'NOT IN',
			),
		),
	);

	// Фильтр по категории
	if ($category_id > 0) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $category_id,
		);
	}

	// Сортировка WooCommerce
	$ordering_args = WC()->query->get_catalog_ordering_args($orderby_raw);
	$args['orderby'] = $ordering_args['orderby'];
	$args['order'] = $ordering_args['order'];

	if (!empty($ordering_args['meta_key'])) {
		$args['meta_key'] = $ordering_args['meta_key'];
	}

	$loop = new WP_Query($args);

	ob_start();

	$loaded_ids = array();

	if ($loop->have_posts()) {
		while ($loop->have_posts()) {
			$loop->the_post();
			$loaded_ids[] = get_the_ID();
			wc_get_template('woocommerce/content-product-card.php');
		}
		wp_reset_postdata();
	}

	$html = ob_get_clean();

	// Считаем, сколько всего товаров осталось без учета текущих
	$remaining_args = $args;
	$remaining_args['posts_per_page'] = 1;
	$remaining_args['post__not_in'] = array_merge($exclude_ids, $loaded_ids);
	$remaining_query = new WP_Query($remaining_args);
	$has_more = ($remaining_query->found_posts > 0);

	wp_send_json_success(array(
		'html' => $html,
		'has_more' => $has_more,
	));

	wp_die();
}

remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);

//!===========checkout
// ============================================
// ОБРАБОТКА КАСТОМНОГО ОФОРМЛЕНИЯ ЗАКАЗА
// ============================================

add_action('wp_ajax_process_custom_checkout', 'handle_process_custom_checkout');
add_action('wp_ajax_nopriv_process_custom_checkout', 'handle_process_custom_checkout');

function handle_process_custom_checkout()
{
	// 1. Проверка безопасности (Nonce)
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'custom_checkout_nonce')) {
		wp_send_json_error(['message' => 'Ошибка безопасности. Пожалуйста, обновите страницу.']);
	}

	// 2. Проверка корзины
	if (!WC()->cart || WC()->cart->is_empty()) {
		wp_send_json_error(['message' => 'Ваша корзина пуста.']);
	}

	try {
		// 3. Создаем новый заказ WooCommerce
		$order = wc_create_order();

		// 4. Добавляем товары из корзины в заказ
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$order->add_product($cart_item['data'], $cart_item['quantity']);
		}

		// 5. Заполняем контактные данные (Billing)
		$billing_address = array(
			'first_name' => sanitize_text_field($_POST['billing_first_name'] ?? ''),
			'phone' => sanitize_text_field($_POST['billing_phone'] ?? ''),
			'email' => sanitize_email($_POST['billing_email'] ?? ''),
		);
		$order->set_address($billing_address, 'billing');

		// 6. Обработка способа получения и адреса доставки (Shipping)
		$delivery_method = sanitize_text_field($_POST['delivery_method'] ?? 'cdek');
		$shipping_cost = floatval($_POST['shipping_cost'] ?? 0);

		// Читабельные названия методов
		$method_titles = [
			'cdek' => 'СДЭК',
			'evropochta' => 'Европочта',
			'belpochta' => 'Белпочта',
			'pickup' => 'Самовывоз'
		];
		$method_title_name = $method_titles[$delivery_method] ?? 'Доставка';

		// 6.1. Всегда добавляем метод доставки в заказ (чтобы он был виден в итогах)
		$shipping_item = new WC_Order_Item_Shipping();
		$shipping_item->set_method_title($method_title_name);
		$shipping_item->set_method_id($delivery_method);
		$shipping_item->set_total($shipping_cost);
		$order->add_item($shipping_item);

		// 6.2. Сохраняем базовый адрес для WooCommerce
		$shipping_address = array(
			'first_name' => $billing_address['first_name'],
			'city' => sanitize_text_field($_POST['shipping_city'] ?? ''),
		);
		if ($delivery_method === 'belpochta') {
			$shipping_address['postcode'] = sanitize_text_field($_POST['shipping_zip'] ?? '');
			$shipping_address['address_1'] = sanitize_text_field($_POST['shipping_address'] ?? '');
		}
		$order->set_address($shipping_address, 'shipping');

		// 6.3. Сохраняем наши кастомные поля как метаданные (Meta Data)
		$order->update_meta_data('_custom_delivery_method_name', $method_title_name);

		if ($delivery_method === 'cdek') {
			$order->update_meta_data('_shipping_custom_city', sanitize_text_field($_POST['shipping_city'] ?? ''));
			$order->update_meta_data('_shipping_custom_point', sanitize_text_field($_POST['shipping_pickup_point'] ?? ''));
		} elseif ($delivery_method === 'evropochta') {
			$order->update_meta_data('_shipping_custom_city', sanitize_text_field($_POST['shipping_city'] ?? ''));
			$order->update_meta_data('_shipping_custom_branch', sanitize_text_field($_POST['shipping_branch'] ?? ''));
		} elseif ($delivery_method === 'belpochta') {
			$order->update_meta_data('_shipping_custom_city', sanitize_text_field($_POST['shipping_city'] ?? ''));
			$order->update_meta_data('_shipping_custom_zip', sanitize_text_field($_POST['shipping_zip'] ?? ''));
			$order->update_meta_data('_shipping_custom_address', sanitize_text_field($_POST['shipping_address'] ?? ''));
			$order->update_meta_data('_shipping_custom_branch', sanitize_text_field($_POST['shipping_branch'] ?? ''));
		}

		// 7. Комментарий клиента к заказу
		$customer_comments = sanitize_textarea_field($_POST['order_comments'] ?? '');
		if (!empty($customer_comments)) {
			$order->set_customer_note($customer_comments);
		}

		// 8. Установка метода оплаты
		$payment_method_id = sanitize_text_field($_POST['payment_method'] ?? '');
		$available_gateways = WC()->payment_gateways->payment_gateways();

		if (isset($available_gateways[$payment_method_id])) {
			$order->set_payment_method($available_gateways[$payment_method_id]);
		} else {
			// Фолбэк, если используются заглушки из вашего шаблона, а не реальные плагины оплат
			$order->set_payment_method($payment_method_id);
			$order->set_payment_method_title($payment_method_id);
		}

		// 9. Пересчет итогов и сохранение заказа
		$order->calculate_totals();
		$order->update_status('pending', 'Заказ создан через кастомную страницу оформления.');

		// 10. Очистка корзины
		WC()->cart->empty_cart();

		// 11. Формирование ссылки для редиректа
		// Наша кастомная ссылка
		$custom_success_url = add_query_arg('order_id', $order->get_id(), home_url('/success-order/'));
		$redirect_url = $custom_success_url;

		// Обработка платежного шлюза
		if (isset($available_gateways[$payment_method_id])) {
			$payment_result = $available_gateways[$payment_method_id]->process_payment($order->get_id());

			if (isset($payment_result['result']) && $payment_result['result'] === 'success') {
				// Если шлюз вернул стандартную ссылку order-received, меняем её на нашу
				if (strpos($payment_result['redirect'], 'order-received') !== false) {
					$redirect_url = $custom_success_url;
				} else {
					// Если шлюз возвращает внешнюю ссылку (например, на оплату картой в банке), оставляем её
					$redirect_url = $payment_result['redirect'];
				}
			}
		}

		// 12. Отправляем успешный ответ обратно в JavaScript
		wp_send_json_success([
			'order_id' => $order->get_id(),
			'redirect' => $redirect_url
		]);
	} catch (Exception $e) {
		wp_send_json_error([
			'message' => $e->getMessage(),
			'error_code' => 'order_creation_failed'
		]);
	}
}

// Вывод кастомных полей доставки в админке заказа WooCommerce
add_action('woocommerce_admin_order_data_after_shipping_address', 'display_custom_shipping_data_in_admin');
function display_custom_shipping_data_in_admin($order)
{
	$method_name = $order->get_meta('_custom_delivery_method_name');

	if (!$method_name)
		return; // Если заказ старый или без этих данных - ничего не выводим

	echo '<h3>Детали доставки (' . esc_html($method_name) . ')</h3>';
	echo '<div style="background: #f9f9f9; padding: 10px; border-radius: 4px; border: 1px solid #ddd; margin-top: 10px;">';

	$city = $order->get_meta('_shipping_custom_city');
	if ($city)
		echo '<p><strong>Населенный пункт:</strong> ' . esc_html($city) . '</p>';

	$point = $order->get_meta('_shipping_custom_point');
	if ($point)
		echo '<p><strong>Пункт выдачи СДЭК:</strong> ' . esc_html($point) . '</p>';

	$branch = $order->get_meta('_shipping_custom_branch');
	if ($branch)
		echo '<p><strong>Номер/адрес отделения:</strong> ' . esc_html($branch) . '</p>';

	$zip = $order->get_meta('_shipping_custom_zip');
	if ($zip)
		echo '<p><strong>Индекс:</strong> ' . esc_html($zip) . '</p>';

	$address = $order->get_meta('_shipping_custom_address');
	if ($address)
		echo '<p><strong>Адрес (улица, дом, кв):</strong> ' . esc_html($address) . '</p>';

	if ($method_name === 'Самовывоз') {
		echo '<p>Самовывоз из магазина STOCK HOLM</p>';
	}

	echo '</div>';
}

// Вывод кастомных полей доставки в email письмах WooCommerce
add_action('woocommerce_email_order_meta', 'display_custom_shipping_data_in_emails', 10, 3);
function display_custom_shipping_data_in_emails($order, $sent_to_admin, $plain_text)
{
	$method_name = $order->get_meta('_custom_delivery_method_name');

	if (!$method_name)
		return;

	if ($plain_text) {
		// Для писем в простом текстовом формате (встречается редко)
		echo "=== Детали доставки (" . $method_name . ") ===\n";
		$city = $order->get_meta('_shipping_custom_city');
		if ($city)
			echo "Населенный пункт: " . $city . "\n";
		$point = $order->get_meta('_shipping_custom_point');
		if ($point)
			echo "Пункт СДЭК: " . $point . "\n";
		$branch = $order->get_meta('_shipping_custom_branch');
		if ($branch)
			echo "Отделение: " . $branch . "\n";
		$address = $order->get_meta('_shipping_custom_address');
		if ($address)
			echo "Адрес: " . $address . "\n";
	} else {
		// Для стандартных HTML-писем
		echo '<div style="margin-bottom: 40px; border: 1px solid #e5e5e5; padding: 20px;">';
		echo '<h2 style="margin-top: 0;">Детали доставки (' . esc_html($method_name) . ')</h2>';
		echo '<ul>';

		$city = $order->get_meta('_shipping_custom_city');
		if ($city)
			echo '<li><strong>Населенный пункт:</strong> ' . esc_html($city) . '</li>';

		$point = $order->get_meta('_shipping_custom_point');
		if ($point)
			echo '<li><strong>Пункт выдачи СДЭК:</strong> ' . esc_html($point) . '</li>';

		$branch = $order->get_meta('_shipping_custom_branch');
		if ($branch)
			echo '<li><strong>Номер/адрес отделения:</strong> ' . esc_html($branch) . '</li>';

		$zip = $order->get_meta('_shipping_custom_zip');
		if ($zip)
			echo '<li><strong>Индекс:</strong> ' . esc_html($zip) . '</li>';

		$address = $order->get_meta('_shipping_custom_address');
		if ($address)
			echo '<li><strong>Адрес:</strong> ' . esc_html($address) . '</li>';

		if ($method_name === 'Самовывоз') {
			echo '<li>Самовывоз из магазина STOCK HOLM</li>';
		}

		echo '</ul>';
		echo '</div>';
	}
}

// page-looks
// Добавьте это в functions.php вашей темы
add_action('wp_ajax_load_more_looks', 'load_more_looks_handler');
add_action('wp_ajax_nopriv_load_more_looks', 'load_more_looks_handler');

function load_more_looks_handler()
{
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 4;

	if ($page < 1)
		$page = 1;
	if ($posts_per_page < 1)
		$posts_per_page = 4;

	$args = array(
		'post_type' => 'looks',
		'posts_per_page' => $posts_per_page,
		'post_status' => 'publish',
		'paged' => $page,
		'orderby' => 'date',
		'order' => 'DESC',
	);

	$query = new WP_Query($args);
	$response = array(
		'html' => '',
		'has_more' => false,
		'page' => $page,
	);

	if ($query->have_posts()) {
		ob_start();
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('partials/looks/looks-card');
		}
		wp_reset_postdata();
		$response['html'] = ob_get_clean();
		$response['has_more'] = ($page < $query->max_num_pages);
	}

	wp_send_json_success($response);
}

//!====================
// ВАЖНО: этот код должен быть в самом functions.php, не внутри функций!
add_action('wp_ajax_load_more_sales', 'load_more_sales_handler');
add_action('wp_ajax_nopriv_load_more_sales', 'load_more_sales_handler');

function load_more_sales_handler()
{
	error_log('=== AJAX HANDLER: load_more_sales вызван ===');

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 8;
	$orderby = isset($_POST['orderby']) ? wc_clean($_POST['orderby']) : 'popularity';
	$order = isset($_POST['order']) ? wc_clean($_POST['order']) : 'DESC';
	$product_ids_raw = isset($_POST['product_ids']) ? $_POST['product_ids'] : '';

	error_log('Page: ' . $page);
	error_log('Posts per page: ' . $posts_per_page);
	error_log('Orderby: ' . $orderby);
	error_log('Product IDs raw: ' . substr($product_ids_raw, 0, 100));

	// Парсим product_ids
	$product_ids = array();
	if (!empty($product_ids_raw)) {
		$product_ids = array_map('intval', explode(',', $product_ids_raw));
		$product_ids = array_filter($product_ids); // убираем нули
	}

	error_log('Product IDs count: ' . count($product_ids));

	if (empty($product_ids)) {
		error_log('ERROR: product_ids пустой!');
		wp_send_json_error(array('message' => 'No product IDs'));
		return;
	}

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => $posts_per_page,
		'paged' => $page,
		'post_status' => 'publish',
		'post__in' => $product_ids,
	);

	switch ($orderby) {
		case 'price':
			$args['meta_key'] = '_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
			break;
		case 'price-desc':
			$args['meta_key'] = '_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
			break;
		case 'date':
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			break;
		case 'rating':
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
			break;
		case 'popularity':
		default:
			$args['meta_key'] = 'total_sales';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
			break;
	}

	error_log('Final args: ' . print_r($args, true));

	$query = new WP_Query($args);

	error_log('Found posts: ' . $query->found_posts);
	error_log('Max pages: ' . $query->max_num_pages);

	$response = array(
		'html' => '',
		'has_more' => false,
	);

	if ($query->have_posts()) {
		ob_start();
		while ($query->have_posts()) {
			$query->the_post();
			wc_get_template('woocommerce/content-product-card.php');
		}
		wp_reset_postdata();
		$response['html'] = ob_get_clean();
		$response['has_more'] = ($page < $query->max_num_pages);

		error_log('HTML length: ' . strlen($response['html']));
	} else {
		error_log('NO POSTS FOUND');
	}

	wp_send_json_success($response);
}

//!====================
/**
 * Получить заголовки для вариантов сортировки из ACF Options
 * Возвращает массив вида: ['date' => ['title' => '...', 'descr' => '...'], ...]
 */
function stock_get_sort_titles()
{
	$rows = get_field('shop_sort_titles', 'option') ?? [];
	$titles = [];

	foreach ($rows as $row) {
		$key = $row['sort_key'] ?? '';
		if (!$key)
			continue;

		$titles[$key] = [
			'title' => $row['sort_title'] ?? '',
			'descr' => $row['sort_descr'] ?? '',
		];
	}

	return $titles;
}