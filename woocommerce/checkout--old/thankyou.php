<?php
/**
 * Template Name: Custom Thank You Page
 * Переопределение шаблона страницы успешного заказа WooCommerce
 */
defined('ABSPATH') || exit;

// Получаем объект заказа, если он передан WooCommerce
$order = isset($order) ? $order : false;
?>

<section class="success-page">
	<div class="container">
		<?php if ($order):
			// Получаем данные заказа
			$order_num = $order->get_order_number();

			// Получаем кастомный метод доставки из меты (который мы делали на предыдущем шаге)
			$delivery = $order->get_meta('_custom_delivery_method_name');
			if (!$delivery) {
				$delivery = $order->get_shipping_method(); // Фолбэк на стандартный метод
			}

			$payment = $order->get_payment_method_title();

			// Очищаем итоговую сумму от лишних HTML-тегов, чтобы красиво вывести
			$total = wp_strip_all_tags($order->get_formatted_order_total());
			?>

			<div class="success-page__header">
				<span class="success-page__order-label">Заказ №<?php echo esc_html($order_num); ?></span>
				<h1 class="success-page__title">Спасибо за заказ!</h1>
				<p class="success-page__desc">
					Мы получили ваш заказ и проверим наличие товаров.<br>
					После подтверждения свяжемся с вами по указанному номеру телефона.
				</p>
			</div>

			<div class="success-page__info-box">
				<div class="success-page__info-item">
					<span class="success-page__info-label">Номер заказа</span>
					<span class="success-page__info-value">№<?php echo esc_html($order_num); ?></span>
				</div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Способ получения</span>
					<span class="success-page__info-value"><?php echo esc_html($delivery); ?></span>
				</div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Способ оплаты</span>
					<span class="success-page__info-value"><?php echo esc_html($payment); ?></span>
				</div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Сумма заказа</span>
					<span class="success-page__info-value"><?php echo esc_html($total); ?></span>
				</div>
			</div>

			<div class="success-page__actions">
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="success-page__btn-primary">Вернуться
					в каталог</a>
				<a href="<?php echo esc_url(home_url('/')); ?>" class="success-page__btn-secondary">На главную</a>
			</div>

		<?php else: ?>

			<!-- Фолбэк, если пользователь зашел на страницу без номера заказа -->
			<div class="success-page__header">
				<h1 class="success-page__title">Заказ не найден</h1>
				<p class="success-page__desc">К сожалению, мы не смогли найти информацию о вашем заказе.</p>
			</div>
			<div class="success-page__actions">
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="success-page__btn-primary">Перейти в
					каталог</a>
			</div>

		<?php endif; ?>
	</div>
</section>