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
/**
 * ============================================
 * ОБРАБОТКА ОФОРМЛЕНИЯ ЗАКАЗА (AJAX)
 * Формат shipping_method: zone_id:method_id (например, "1:flat_rate")
 * ============================================
 */
add_action('wp_ajax_process_custom_checkout', 'handle_custom_checkout');
add_action('wp_ajax_nopriv_process_custom_checkout', 'handle_custom_checkout');

function handle_custom_checkout() {
	// 1. Проверка безопасности
	if (!check_ajax_referer('custom_checkout_nonce', 'nonce', false)) {
		wp_send_json_error([
			'message'    => 'Ошибка безопасности. Обновите страницу и попробуйте снова.',
			'error_code' => 'invalid_nonce',
		]);
	}

	// 2. Проверка корзины
	if (!WC()->cart || WC()->cart->is_empty()) {
		wp_send_json_error([
			'message'    => 'Ваша корзина пуста.',
			'error_code' => 'empty_cart',
		]);
	}

	// 3. Получение данных
	$first_name    = sanitize_text_field($_POST['billing_first_name'] ?? '');
	$phone         = sanitize_text_field($_POST['billing_phone'] ?? '');
	$email         = sanitize_email($_POST['billing_email'] ?? '');
	$city          = sanitize_text_field($_POST['shipping_city'] ?? '');
	$address       = sanitize_text_field($_POST['shipping_address_1'] ?? '');
	$country       = sanitize_text_field($_POST['shipping_country'] ?? '');
	$comments      = sanitize_textarea_field($_POST['order_comments'] ?? '');
	$shipping_comments = sanitize_textarea_field($_POST['shipping_comments'] ?? '');

	$shipping_method = sanitize_text_field($_POST['shipping_method'] ?? '');
	$payment_method  = sanitize_text_field($_POST['payment_method'] ?? '');

	$privacy_policy = isset($_POST['privacy_policy']) && $_POST['privacy_policy'] === '1';
	$public_offer   = isset($_POST['public_offer']) && $_POST['public_offer'] === '1';

	// 4. УМНАЯ ВАЛИДАЦИЯ (зависит от способа доставки)
	$errors = [];
	if (empty($first_name)) $errors[] = 'Введите ваше имя.';
	if (empty($phone)) $errors[] = 'Введите номер телефона.';
	if (empty($email) || !is_email($email)) $errors[] = 'Введите корректный E-mail.';

	// Проверяем, является ли метод самовывозом (ищем слово 'pickup' в ID метода)
	$is_pickup = (stripos($shipping_method, 'pickup') !== false);

	// Если это доставка, адрес и город обязательны. Для самовывоза - нет.
	if (!$is_pickup) {
		if (empty($city)) $errors[] = 'Введите город.';
		if (empty($address)) $errors[] = 'Введите адрес или пункт выдачи.';
	}

	if (empty($shipping_method)) $errors[] = 'Выберите способ доставки.';
	if (empty($payment_method)) $errors[] = 'Выберите способ оплаты.';
	if (!$privacy_policy) $errors[] = 'Необходимо согласие с политикой обработки данных.';
	if (!$public_offer) $errors[] = 'Необходимо согласие с условиями публичной оферты.';

	if (!empty($errors)) {
		if (WP_DEBUG) {
			error_log('❌ ОШИБКА ВАЛИДАЦИИ CHECKOUT: ' . implode(', ', $errors));
			error_log('📦 POST ДАННЫЕ: ' . print_r($_POST, true));
		}
		wp_send_json_error([
			'message'    => implode('<br>', $errors),
			'error_code' => 'validation_error',
		]);
	}

	// 5. Создание заказа
	$order = wc_create_order();
	$order->set_status('pending');

	$order->set_billing_first_name($first_name);
	$order->set_billing_phone($phone);
	$order->set_billing_email($email);

	$order->set_shipping_city($city);
	$order->set_shipping_address_1($address);

	// Маппинг названия страны в код ISO
	$country_code_map = [
		'Беларусь' => 'BY',
		'Россия'   => 'RU',
		'Казахстан' => 'KZ',
		'Украина'  => 'UA',
	];
	$country_code = $country_code_map[$country] ?? '';
	if (!empty($country_code)) {
		$order->set_shipping_country($country_code);
	}

	// Комментарии
	$final_note = '';
	if (!empty($shipping_comments)) $final_note .= "Доставка: " . $shipping_comments . "\n";
	if (!empty($comments)) $final_note .= "Общий: " . $comments;
	if (!empty($final_note)) {
		$order->set_customer_note(trim($final_note));
	}

	// 6. Добавление товаров
	try {
		foreach (WC()->cart->get_cart() as $cart_item) {
			$order->add_product($cart_item['data'], $cart_item['quantity']);
		}
	} catch (Exception $e) {
		error_log('❌ Error adding products to order: ' . $e->getMessage());
		wp_send_json_error([
			'message'    => 'Ошибка при добавлении товаров в заказ.',
			'error_code' => 'system_error',
		]);
	}

	// 7. 🚚 Добавление shipping item (формат zone_id:method_id)
	if ($shipping_method) {
		$shipping_zones = WC_Shipping_Zones::get_zones();
		$found_method = null;

		foreach ($shipping_zones as $zone) {
			$zone_obj = new WC_Shipping_Zone($zone['id']);
			$methods = $zone_obj->get_shipping_methods();

			foreach ($methods as $method_id => $method) {
				$full_id = $zone['id'] . ':' . $method_id;
				if ($full_id === $shipping_method && $method->is_enabled()) {
					$cost = 0;
					if (method_exists($method, 'get_option')) {
						$cost = floatval($method->get_option('cost', 0));
					}

					$found_method = [
						'label'       => $method->get_title(),
						'method_id'   => $method_id,
						'instance_id' => $method->instance_id ?? 0,
						'cost'        => $cost,
					];
					break 2;
				}
			}
		}

		if ($found_method) {
			$item = new WC_Order_Item_Shipping();
			$item->set_props([
				'method_title' => $found_method['label'],
				'method_id'    => $found_method['method_id'],
				'instance_id'  => $found_method['instance_id'],
				'total'        => $found_method['cost'],
				'taxes'        => [],
			]);
			$order->add_item($item);
		} else {
			error_log('❌ Shipping method not found: ' . $shipping_method);
			wp_send_json_error([
				'message'    => 'Не удалось найти выбранный способ доставки.',
				'error_code' => 'shipping_error',
				'order_id'   => $order->get_id(),
			]);
		}
	}

	// 8. 💳 Способ оплаты
	$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
	if (isset($available_gateways[$payment_method])) {
		$order->set_payment_method($available_gateways[$payment_method]);
		$order->set_payment_method_title($available_gateways[$payment_method]->get_title());
	} else {
		error_log('❌ Payment gateway not found: ' . $payment_method);
		wp_send_json_error([
			'message'    => 'Не удалось найти выбранный способ оплаты.',
			'error_code' => 'payment_failed',
			'order_id'   => $order->get_id(),
		]);
	}

	// 9. Пересчёт и сохранение
	try {
		$order->calculate_totals();
		$order_id = $order->save();
	} catch (Exception $e) {
		error_log(' Error saving order: ' . $e->getMessage());
		wp_send_json_error([
			'message'    => 'Не удалось сохранить заказ.',
			'error_code' => 'system_error',
		]);
	}

	if (!$order_id) {
		wp_send_json_error([
			'message'    => 'Не удалось создать заказ.',
			'error_code' => 'system_error',
		]);
	}

	if (WP_DEBUG) {
		error_log('✅ Заказ создан. ID: ' . $order_id);
	}

	// 10. 📧 Отправка уведомлений
	$mailer = WC()->mailer();
	$emails = $mailer->get_emails();

	if (!empty($emails['WC_Email_New_Order'])) {
		$emails['WC_Email_New_Order']->trigger($order_id);
	}

	if (!$order->needs_payment()) {
		$order->update_status('processing', 'Оплата при доставке');
		if (!empty($emails['WC_Email_Customer_Processing_Order'])) {
			$emails['WC_Email_Customer_Processing_Order']->trigger($order_id);
		}
	} else {
		if (!empty($emails['WC_Email_Customer_On_Hold_Order'])) {
			$emails['WC_Email_Customer_On_Hold_Order']->trigger($order_id);
		}
	}

	// 11. Очистка корзины
	WC()->cart->empty_cart();

	// 12. Редирект на страницу успешного заказа
	$thank_you_page = get_page_by_path('success-order');

	if ($thank_you_page && $thank_you_page->post_status === 'publish') {
		$redirect_url = add_query_arg('order_id', $order_id, get_permalink($thank_you_page->ID));
	} else {
		$redirect_url = $order->get_checkout_order_received_url();
	}

	wp_send_json_success([
		'redirect' => $redirect_url,
		'order_id' => $order_id,
	]);
}