<?php
/**
 * Template Name: Success Order Page
 * Шаблон страницы успешного заказа
 */
get_header();

// Если у вас есть общий блок для шапки (как на checkout), раскомментируйте строку ниже
// get_template_part('partials/components/hero-page'); 

// Получаем ID заказа из URL
$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$order = $order_id ? wc_get_order($order_id) : false;
?>

<section class="success-page section">
	<div class="container success-page__container">
		<div class="success-page__breadcrumbs page-breadcrumbs">
			<?php if (function_exists('bcn_display')) {
				bcn_display();
			} ?>
		</div>
		<?php if ($order):
			$order_num = $order->get_order_number();
			$delivery = $order->get_meta('_custom_delivery_method_name') ?: $order->get_shipping_method();
			$payment = $order->get_payment_method_title();
			$total = wp_strip_all_tags($order->get_formatted_order_total());
			?>
			<div class="success-page__header">
				<span class="success-page__order-label">Заказ №
					<?php echo esc_html($order_num); ?>
				</span>
				<h1 class="success-page__title title-2">Спасибо за заказ!</h1>
				<p class="success-page__descr descr">
					Мы получили ваш заказ и проверим наличие товаров.<br>
					После подтверждения свяжемся с вами по указанному номеру телефона.
				</p>
			</div>

			<div class="success-page__info-box">
				<div class="success-page__info-item">
					<span class="success-page__info-label">Номер заказа</span>
					<span class="success-page__info-value">№
						<?php echo esc_html($order_num); ?>
					</span>
				</div>
				<div class="success-page__info-item-decor"></div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Способ получения</span>
					<span class="success-page__info-value">
						<?php echo esc_html($delivery); ?>
					</span>
				</div>
				<div class="success-page__info-item-decor"></div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Способ оплаты</span>
					<span class="success-page__info-value">
						<?php echo esc_html($payment); ?>
					</span>
				</div>
				<div class="success-page__info-item-decor"></div>
				<div class="success-page__info-item">
					<span class="success-page__info-label">Сумма заказа</span>
					<span class="success-page__info-value">
						<?php echo esc_html($total); ?>
					</span>
				</div>
			</div>

			<div class="success-page__actions">
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="success-page__btn-primary btn">Вернуться
					в каталог</a>
				<a href="<?php echo esc_url(home_url('/')); ?>" class="success-page__btn-secondary btn-link">На главную</a>
			</div>

		<?php else: ?>

			<div class="success-page__header">
				<h1 class="success-page__title title-2">Заказ не найден</h1>
				<p class="success-page__descr descr">К сожалению, мы не смогли найти информацию о вашем заказе.</p>
			</div>
			<div class="success-page__actions">
				<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="success-page__btn-primary btn">Перейти в
					каталог</a>
			</div>

		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>