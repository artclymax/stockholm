<?php
/**
 * Empty cart page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');
?>

<div class="cart-empty">
	<h1 class="cart-empty__title title-2">
		<?php esc_html_e('В корзине пока ничего нет', 'woocommerce'); ?>
	</h1>
	<div class="cart-empty__descr descr">
		<p class="cart-empty__subtitle text-body">
			<?php esc_html_e('Добавьте товары из каталога, чтобы оформить заказ', 'woocommerce'); ?>
		</p>
	</div>

	<?php if (wc_get_page_id('shop') > 0): ?>
		<a class="cart-empty__btn btn"
			href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php esc_html_e('Перейти в каталог', 'woocommerce'); ?>
		</a>
	<?php endif; ?>
</div>