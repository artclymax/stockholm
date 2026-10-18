<?php
/**
 * Cart totals
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;

?>
<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<h2 class="cart_totals__title title-2">
		<?php esc_html_e('Ваш заказ', 'woocommerce'); ?>
	</h2>

	<div class="cart_totals__table">

		<!-- Товары, N шт. -->
		<div class="cart_totals__row">
			<div class="cart_totals__label">
				<?php
				$count = WC()->cart->get_cart_contents_count();
				echo esc_html(sprintf('Товары, %d шт.', $count));
				?>
			</div>
			<div class="cart_totals__value">
				<?php wc_cart_totals_subtotal_html(); ?>
			</div>
		</div>

		<!-- Доставка -->
		<div class="cart_totals__row">
			<div class="cart_totals__label">
				<?php esc_html_e('Доставка', 'woocommerce'); ?>
			</div>
			<div class="cart_totals__value cart_totals__value--shipping">
				<?php esc_html_e('Рассчитывается при оформлении', 'woocommerce'); ?>
			</div>
		</div>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<!-- Итого -->
		<div class="cart_totals__row cart_totals__row--total">
			<div class="cart_totals__label">
				<?php esc_html_e('Итого', 'woocommerce'); ?>
			</div>
			<div class="cart_totals__value">
				<?php wc_cart_totals_order_total_html(); ?>
			</div>
		</div>

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</div>

	<div class="wc-proceed-to-checkout">
		<?php do_action('woocommerce_proceed_to_checkout'); ?>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>