<?php
/**
 * Template Name: Checkout Page
 * Шаблон страницы оформления заказа
 */

// ============================================
// 1. ПРОВЕРКА: ЕСЛИ ЭТО СТРАНИЦА "СПАСИБО ЗА ЗАКАЗ"
// ============================================
if (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('order-received')) {
	get_header();
	get_template_part('partials/components/hero-page-simple');

	$order_id = absint(get_query_var('order-received'));
	$order = wc_get_order($order_id);

	// Подгружаем наш шаблон success-page, который вы создали на предыдущем шаге
	wc_get_template('checkout/thankyou.php', array('order' => $order));

	get_footer();
	exit; // Завершаем выполнение, чтобы форма оформления ниже не загружалась
}


// ============================================
// 2. СТАНДАРТНОЕ ОФОРМЛЕНИЕ ЗАКАЗА
// ============================================
get_header();

// ПРОВЕРКА КОРЗИНЫ
if (!class_exists('WooCommerce') || !WC()->cart || WC()->cart->is_empty()) {
	wp_redirect(home_url('/shop/'));
	exit;
}

$cart = WC()->cart;
$cart_items = $cart->get_cart();
$cart_subtotal = $cart->get_subtotal();
$items_count = $cart->get_cart_contents_count();

// ============================================
// СТОИМОСТЬ ДОСТАВКИ ПО МЕТОДАМ
// ============================================
$shipping_costs = [
	'cdek' => 0,
	'evropochta' => 0,
	'belpochta' => 0,
	'pickup' => 0,
];

// Пытаемся получить стоимость из shipping zones WooCommerce
$shipping_zones = WC_Shipping_Zones::get_zones();
foreach ($shipping_zones as $zone) {
	$zone_obj = new WC_Shipping_Zone($zone['id']);
	$methods = $zone_obj->get_shipping_methods();
	foreach ($methods as $method_id => $method) {
		if (!$method->is_enabled())
			continue;
		$cost = floatval($method->get_option('cost', 0));
		$mid = strtolower($method_id);
		if (strpos($mid, 'cdek') !== false)
			$shipping_costs['cdek'] = $cost;
		elseif (strpos($mid, 'evropochta') !== false || strpos($mid, 'euro') !== false)
			$shipping_costs['evropochta'] = $cost;
		elseif (strpos($mid, 'belpochta') !== false || strpos($mid, 'bel') !== false)
			$shipping_costs['belpochta'] = $cost;
	}
}

// ============================================
// МЕТОДЫ ОПЛАТЫ
// ============================================
$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();

// ============================================
// ВАЛЮТА И NONCE
// ============================================
$currency_symbol = trim(get_woocommerce_currency_symbol());
$checkout_nonce = wp_create_nonce('custom_checkout_nonce');
?>

<section class="checkout-page" id="checkout-page">
	<div class="container">
		<div class="checkout-page__header">
			<div class="checkout-page__breadcrumbs page-breadcrumbs">
				<?php if (function_exists('bcn_display')) {
					bcn_display();
				} ?>
			</div>
			<h1 class="checkout-page__title title-2 descr">
				<?php echo wp_kses_post(get_field('hero_title') ?: 'Оформление заказа'); ?>
			</h1>
			<div class="checkout-page__subtitle descr">
				<?php echo wp_kses_post(get_field('hero_descr') ?: 'Оформление заказа'); ?>
			</div>
		</div>

		<div class="checkout-layout">
			<!-- ЛЕВАЯ КОЛОНКА: ФОРМА -->
			<div class="checkout-form-col">
				<form class="checkout-form" id="checkout-form" novalidate>
					<div id="checkout-errors" class="checkout-errors"></div>
					<!-- 01 КОНТАКТНЫЕ ДАННЫЕ -->
					<div class="checkout-section">
						<div class="checkout-section__header">
							<span class="checkout-section__number title-3">01</span>
							<h2 class="checkout-section__title title-3">Контактные данные</h2>
						</div>
						<div class="checkout-form__row">
							<div class="checkout-form__field">
								<label class="checkout-form__label">Имя <span class="required">*</span></label>
								<input type="text" class="checkout-form__input" name="billing_first_name" required>
							</div>
							<div class="checkout-form__field">
								<label class="checkout-form__label">Телефон <span class="required">*</span></label>
								<input type="tel" class="checkout-form__input" name="billing_phone"
									placeholder="+375 (__) ___-__-__" required>
							</div>
						</div>
						<div class="checkout-form__row">
							<div class="checkout-form__field">
								<label class="checkout-form__label">Электронная почта <span class="required">*</span></label>
								<input type="email" class="checkout-form__input" name="billing_email" required>
							</div>
						</div>
					</div>

					<!-- 02 СПОСОБ ПОЛУЧЕНИЯ -->
					<div class="checkout-section">
						<div class="checkout-section__header">
							<span class="checkout-section__number title-3">02</span>
							<h2 class="checkout-section__title title-3">Способ получения</h2>
						</div>

						<div class="delivery-tabs">
							<label class="delivery-tab">
								<input type="radio" name="delivery_method" value="cdek" checked>
								<span class="delivery-tab__label">
									<span class="delivery-tab__radio"></span>
									СДЭК
								</span>
							</label>
							<label class="delivery-tab">
								<input type="radio" name="delivery_method" value="evropochta">
								<span class="delivery-tab__label">
									<span class="delivery-tab__radio"></span>
									Европочта
								</span>
							</label>
							<label class="delivery-tab">
								<input type="radio" name="delivery_method" value="belpochta">
								<span class="delivery-tab__label">
									<span class="delivery-tab__radio"></span>
									Белпочта
								</span>
							</label>
							<label class="delivery-tab">
								<input type="radio" name="delivery_method" value="pickup">
								<span class="delivery-tab__label">
									<span class="delivery-tab__radio"></span>
									Самовывоз
								</span>
							</label>
						</div>

						<!-- СДЭК -->
						<div class="delivery-panel active" data-panel="cdek">
							<h3 class="delivery-panel__title">Доставка в выбранный пункт выдачи СДЭК</h3>
							<div class="checkout-form__row">
								<div class="checkout-form__field">
									<label class="checkout-form__label">Населенный пункт <span class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_city"
										placeholder="Введите город" data-required-for="cdek">
								</div>
								<div class="checkout-form__field">
									<label class="checkout-form__label">Пункт выдачи СДЭК <span class="required">*</span></label>
									<!-- Заменили select на input -->
									<input type="text" class="checkout-form__input" name="shipping_pickup_point"
										placeholder="Введите адрес пункта выдачи" data-required-for="cdek">
								</div>
							</div>
						</div>

						<!-- ЕВРОПОЧТА -->
						<div class="delivery-panel" data-panel="evropochta">
							<div class="checkout-form__row">
								<div class="checkout-form__field">
									<label class="checkout-form__label">Населенный пункт <span class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_city"
										placeholder="Введите город" data-required-for="evropochta">
								</div>
								<div class="checkout-form__field">
									<label class="checkout-form__label">Номер или адрес отделения <span
											class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_branch"
										placeholder="Номер или адрес отделения" data-required-for="evropochta">
								</div>
							</div>
						</div>

						<!-- БЕЛПОЧТА -->
						<div class="delivery-panel" data-panel="belpochta">
							<div class="checkout-form__row">
								<div class="checkout-form__field">
									<label class="checkout-form__label">Населенный пункт <span class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_city"
										placeholder="Введите город" data-required-for="belpochta">
								</div>
								<div class="checkout-form__field">
									<label class="checkout-form__label">Почтовый индекс <span class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_zip" placeholder="Индекс"
										data-required-for="belpochta">
								</div>
							</div>
							<div class="checkout-form__row">
								<div class="checkout-form__field">
									<label class="checkout-form__label">Адрес <span class="required">*</span></label>
									<input type="text" class="checkout-form__input" name="shipping_address"
										placeholder="Улица, дом, квартира" data-required-for="belpochta">
								</div>
								<div class="checkout-form__field">
									<label class="checkout-form__label">Номер отделения</label>
									<input type="text" class="checkout-form__input" name="shipping_branch"
										placeholder="Номер отделения">
								</div>
							</div>
						</div>

						<!-- САМОВЫВОЗ -->
						<div class="delivery-panel" data-panel="pickup">
							<div class="pickup-info">
								<h3 class="delivery-panel__title">Самовывоз из магазина STOCK HOLM
								</h3>
								<div class="pickup-info__row">
									<span class="pickup-info__label">Адрес:</span>
									<span class="pickup-info__value">222515, Минская обл., г.Борисов, пр-т Революции, д.26</span>
								</div>
								<div class="pickup-info__row">
									<span class="pickup-info__label">Режим работы:</span>
									<span class="pickup-info__value">10:00 до 20:00 без выходных</span>
								</div>
								<div class="pickup-info__note">
									Дождитесь подтверждения готовности заказа перед посещением магазина.
								</div>
							</div>
						</div>
					</div>

					<!-- 03 СПОСОБ ОПЛАТЫ -->
					<div class="checkout-section">
						<div class="checkout-section__header">
							<span class="checkout-section__number title-3">03</span>
							<h2 class="checkout-section__title title-3">Способ оплаты</h2>
						</div>

						<div class="payment-methods">
							<?php
							// Если есть WooCommerce gateways — используем их, иначе — дефолтные
							if (!empty($available_gateways)) {
								$payment_options = [];
								foreach ($available_gateways as $gid => $gateway) {
									$payment_options[$gid] = [
										'title' => $gateway->get_title(),
										'desc' => $gateway->get_description()
									];
								}
							} else {
								$payment_options = [
									'card_online' => [
										'title' => 'Онлайн банковской картой',
										'desc' => 'Оплата картой после подтверждения заказа.'
									],
									'card_instore' => [
										'title' => 'Наличными/картой в магазине',
										'desc' => 'Оплата при получении в магазине STOCK HOLM'
									],
									'cod' => [
										'title' => 'Наложенным платежом',
										'desc' => 'С предоплатой'
									]
								];
							}

							$first_payment = true;
							foreach ($payment_options as $pay_id => $pay_data): ?>
								<label class="payment-method">
									<input type="radio" name="payment_method" value="<?php echo esc_attr($pay_id); ?>" <?php checked($first_payment); ?>>
									<span class="payment-method__label">
										<span class="payment-method__radio"></span>
										<span class="payment-method__content">
											<span class="payment-method__title"><?php echo esc_html($pay_data['title']); ?></span>
											<?php if (!empty($pay_data['desc'])): ?>
												<p class="payment-method__desc"><?php echo esc_html($pay_data['desc']); ?></p>
											<?php endif; ?>
										</span>
									</span>
								</label>
								<?php
								$first_payment = false;
							endforeach; ?>
						</div>
					</div>

					<!-- 04 КОММЕНТАРИЙ К ЗАКАЗУ -->
					<div class="checkout-section">
						<div class="checkout-section__header">
							<span class="checkout-section__number title-3">04</span>
							<h2 class="checkout-section__title title-3">Комментарий к заказу</h2>
						</div>
						<div class="checkout-form__row checkout-form__row--full">
							<div class="checkout-form__field">
								<textarea class="checkout-form__input" name="order_comments"
									placeholder="Например, пожелания по доставке или удобное время для звонка"></textarea>
							</div>
						</div>
					</div>


				</form>
			</div>

			<!-- ПРАВАЯ КОЛОНКА: SIDEBAR -->
			<div class="checkout-sidebar-col">
				<div class="checkout-sidebar">
					<h2 class="checkout-sidebar__title title-3">Ваш заказ</h2>

					<div class="checkout-sidebar__items">
						<?php
						foreach ($cart_items as $cart_item_key => $cart_item):
							$product = $cart_item['data'];
							$quantity = $cart_item['quantity'];
							$product_price = $product->get_price();

							// Очищаем название от вариаций (чтобы они не дублировались в заголовке)
							$clean_title = $product->is_type('variation') ? $product->get_parent_data()['title'] : $product->get_name();
							?>
							<div class="checkout-sidebar__item">

								<!-- Картинка товара -->
								<div class="checkout-sidebar__item-image">
									<?php
									// get_image() автоматически выводит тег <img> с нужными классами и fallback-картинкой
									echo $product->get_image('thumbnail');
									?>
								</div>

								<div class="checkout-sidebar__item-info">
									<h3 class="checkout-sidebar__item-title"><?php echo esc_html($clean_title); ?></h3>

									<!-- Вывод атрибутов (Цвет, Размер и т.д.) -->
									<?php if (!empty($cart_item['variation'])): ?>
										<div class="checkout-sidebar__item-meta">

											<?php foreach ($cart_item['variation'] as $attr_name => $attr_value):
												// Получаем красивое название таксономии и термина (как в вашей корзине)
												$taxonomy = str_replace('attribute_', '', $attr_name);
												$label = wc_attribute_label($taxonomy, $product);
												$term = get_term_by('slug', $attr_value, $taxonomy);
												$value_title = $term ? $term->name : $attr_value;
												?>
												<div class="checkout-sidebar__item-attr">
													<span class="checkout-sidebar__attr-label"><?php echo esc_html($label); ?>:</span>
													<span class="checkout-sidebar__attr-value"><?php echo esc_html($value_title); ?></span>
												</div>
											<?php endforeach; ?>

										</div>
									<?php endif; ?>

									<!-- Цена -->
									<div class="checkout-sidebar__item-price">
										<?php echo wc_price($product_price * $quantity); ?>
									</div>

								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="checkout-sidebar__totals">
						<div class="checkout-sidebar__total-row">
							<span class="checkout-sidebar__total-label">Товары, <?php echo $items_count; ?> шт.</span>
							<span class="checkout-sidebar__total-value checkout-sidebar__total-value--price"
								id="cart-subtotal"><?php echo wc_price($cart_subtotal); ?></span>
						</div>
						<div class="checkout-sidebar__total-row">
							<span class="checkout-sidebar__total-label">Доставка</span>
							<span class="checkout-sidebar__total-value" id="cart-shipping">Рассчитывается при оформлении</span>
						</div>
						<div class="checkout-sidebar__total-row checkout-sidebar__total-row--final">
							<span class="checkout-sidebar__total-label">Итого</span>
							<span class="checkout-sidebar__total-value checkout-sidebar__total-value--price"
								id="cart-total"><?php echo wc_price($cart_subtotal); ?></span>
						</div>
					</div>

					<label class="checkout-sidebar__terms">
						<input type="checkbox" name="privacy_policy" required>
						<span class="checkout-sidebar__terms-icon"></span>
						<span>Продолжая, вы соглашаетесь с <a href="/privacy-policy/" target="_blank">политикой
								конфиденциальности</a></span>
					</label>

					<button type="button" class="checkout-sidebar__submit" id="checkout-submit-btn">Оформить заказ</button>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		console.log('✅ Checkout Page Initialized');

		const currencySymbol = '<?php echo esc_js($currency_symbol); ?>';
		const checkoutNonce = '<?php echo $checkout_nonce; ?>';
		const cartSubtotal = <?php echo floatval($cart_subtotal); ?>;
		const shippingCosts = <?php echo json_encode($shipping_costs); ?>;

		let currentShippingCost = 0;
		let currentDeliveryMethod = 'cdek';

		// ========== 1. ПЕРЕКЛЮЧЕНИЕ ТАБОВ СПОСОБА ПОЛУЧЕНИЯ ==========
		const deliveryTabs = document.querySelectorAll('input[name="delivery_method"]');
		const deliveryPanels = document.querySelectorAll('.delivery-panel');
		const cartShipping = document.getElementById('cart-shipping');
		const cartTotal = document.getElementById('cart-total');

		deliveryTabs.forEach(tab => {
			tab.addEventListener('change', function () {
				const method = this.value;
				currentDeliveryMethod = method;

				deliveryPanels.forEach(p => p.classList.remove('active'));
				const target = document.querySelector('.delivery-panel[data-panel="' + method + '"]');
				if (target) target.classList.add('active');

				// Сбрасываем required у полей неактивной панели, ставим у активной
				document.querySelectorAll('.checkout-form__input[data-required-for]').forEach(inp => {
					if (inp.dataset.requiredFor === method) {
						inp.setAttribute('required', '');
					} else {
						inp.removeAttribute('required');
					}
				});

				currentShippingCost = shippingCosts[method] || 0;
				updateTotals();
				console.log('🚚 Delivery:', method, '| Cost:', currentShippingCost);
			});
		});

		function updateTotals() {
			const shippingText = currentShippingCost > 0 ? formatPrice(currentShippingCost) : 'Рассчитывается при оформлении';
			cartShipping.textContent = shippingText;
			cartTotal.textContent = formatPrice(cartSubtotal + currentShippingCost);
		}

		function formatPrice(amount) {
			const rounded = Math.round(amount * 100) / 100;
			return (Number.isInteger(rounded) ? rounded.toString() : rounded.toFixed(2)) + ' ' + currencySymbol;
		}

		// Инициализация required для первого метода
		document.querySelectorAll('.checkout-form__input[data-required-for]').forEach(inp => {
			if (inp.dataset.requiredFor === currentDeliveryMethod) {
				inp.setAttribute('required', '');
			} else {
				inp.removeAttribute('required');
			}
		});
		updateTotals();

		// ========== 2. ОТПРАВКА ФОРМЫ ==========
		const submitBtn = document.getElementById('checkout-submit-btn');
		const checkoutForm = document.getElementById('checkout-form');
		const errorContainer = document.getElementById('checkout-errors');
		let originalBtnText = submitBtn.textContent;

		submitBtn.addEventListener('click', function (e) {
			e.preventDefault();
			// Сброс общих ошибок
			errorContainer.style.display = 'none';
			errorContainer.innerHTML = '';

			// Сброс ошибок полей и удаление старых подсказок
			checkoutForm.querySelectorAll('.checkout-form__input').forEach(i => i.classList.remove('error'));
			checkoutForm.querySelectorAll('.field-error-msg').forEach(msg => msg.remove());

			// Чекбокс согласия
			const privacyPolicy = document.querySelector('input[name="privacy_policy"]');
			if (!privacyPolicy.checked) {
				showError('Пожалуйста, согласитесь с политикой конфиденциальности');
				errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
				return;
			}

			// Валидация обязательных полей
			const requiredFields = checkoutForm.querySelectorAll('[required]');
			let hasError = false;

			requiredFields.forEach(field => {
				if (field.type !== 'checkbox' && !field.value.trim()) {
					field.classList.add('error');
					hasError = true;

					// Создаем и добавляем текст "Обязательное поле" под инпутом
					const errorMsg = document.createElement('span');
					errorMsg.className = 'field-error-msg';
					errorMsg.textContent = 'Обязательное поле';
					field.parentNode.appendChild(errorMsg);
				}
			});

			if (hasError) {
				showError('Пожалуйста, заполните все обязательные поля');
				// Скроллим к общему сообщению об ошибке (наверх)
				errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
				return;
			}

			// Загрузка
			console.log(' Оформление заказа...');
			checkoutForm.classList.add('is-loading');
			submitBtn.disabled = true;
			submitBtn.textContent = 'Оформление заказа...';

			const formData = new FormData(checkoutForm);
			formData.append('action', 'process_custom_checkout');
			formData.append('nonce', checkoutNonce);
			formData.append('delivery_method', currentDeliveryMethod);
			formData.append('shipping_cost', currentShippingCost);

			fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
				method: 'POST',
				body: formData
			})
				.then(r => r.json())
				.then(data => {
					console.log('📦 Response:', data);
					if (data.success) {
						console.log('✅ Order ID:', data.data.order_id);
						if (data.data.redirect) {
							setTimeout(() => { window.location.href = data.data.redirect; }, 500);
						} else {
							showError('Заказ создан, но не удалось перенаправить на страницу подтверждения.');
							resetLoadingState();
						}
					} else {
						console.error('❌ Error:', data);
						const errorCode = data.data.error_code || 'system_error';
						const errorMessage = encodeURIComponent(data.data.message || 'Произошла ошибка при оформлении заказа');
						const orderId = data.data.order_id || '';
						const errorUrl = '<?php echo home_url('/checkout-error/'); ?>?' +
							'error_code=' + errorCode +
							'&error_message=' + errorMessage +
							(orderId ? '&order_id=' + orderId : '');
						setTimeout(() => { window.location.href = errorUrl; }, 500);
					}
				})
				.catch(err => {
					console.error('🌐 Network error:', err);
					const errorUrl = '<?php echo home_url('/checkout-error/'); ?>?' +
						'error_code=system_error' +
						'&error_message=' + encodeURIComponent('Ошибка сети. Проверьте подключение к интернету.');
					setTimeout(() => { window.location.href = errorUrl; }, 500);
				});
		});

		function resetLoadingState() {
			checkoutForm.classList.remove('is-loading');
			submitBtn.disabled = false;
			submitBtn.textContent = originalBtnText;
		}

		function showError(msg) {
			errorContainer.innerHTML = msg;
			errorContainer.style.display = 'block';
		}
	});
</script>

<?php get_footer(); ?>