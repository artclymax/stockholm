<?php
/**
 * Cart Page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="cart-page">
	<div class="cart-page__header">
		<div class="cart-page__count text-caption">
			<?php
			$count = WC()->cart->get_cart_contents_count();
			echo esc_html(sprintf(_n('%d товар', '%d товара', $count, 'woocommerce'), $count));
			?>
		</div>
	</div>

	<div class="cart-page__layout">
		<!-- Левая колонка: форма со списком товаров -->
		<div class="cart-page__main">
			<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
				<?php do_action('woocommerce_before_cart_table'); ?>

				<div class="cart-items">
					<?php do_action('woocommerce_before_cart_contents'); ?>

					<?php
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
						$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

						if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
							$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
							?>
							<div
								class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

								<!-- Фото товара -->
								<div class="cart-item__thumb">
									<?php
									$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);

									if (!$product_permalink) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
									}
									?>
								</div>

								<!-- Описание и атрибуты -->
								<div class="cart-item__info">
									<h3 class="cart-item__title">
										<?php
										$clean_title = $_product->is_type('variation') ? $_product->get_parent_data()['title'] : $_product->get_title();

										if (!$product_permalink) {
											echo esc_html($clean_title);
										} else {
											printf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($clean_title));
										}
										?>
									</h3>

									<?php if (!empty($cart_item['variation'])): ?>
										<div class="cart-item__attrs">
											<?php foreach ($cart_item['variation'] as $attr_name => $attr_value):
												// Получаем красивое название таксономии (например, "Цвет" или "Размер")
												$taxonomy = str_replace('attribute_', '', $attr_name);
												$label = wc_attribute_label($taxonomy, $_product);

												// Получаем название термина (например, "серый", "M")
												$term = get_term_by('slug', $attr_value, $taxonomy);
												$value_title = $term ? $term->name : $attr_value;
												?>
												<div class="cart-item__attr-row">
													<span class="cart-item__attr-label"><?php echo esc_html($label); ?>:</span>
													<span class="cart-item__attr-val"><?php echo esc_html($value_title); ?></span>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>

									<?php
									// Уведомление об предзаказе (если включено в товаре)
									if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
									}
									?>
								</div>

								<!-- Управление количеством -->
								<div class="cart-item__quantity">
									<div class="qty-control">
										<button type="button" class="qty-control__btn qty-control__btn--minus"
											aria-label="Уменьшить">-</button>
										<?php
										if ($_product->is_sold_individually()) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name' => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value' => $max_quantity,
												'min_value' => $min_quantity,
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);

										echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
										?>
										<button type="button" class="qty-control__btn qty-control__btn--plus"
											aria-label="Увеличить">+</button>
									</div>
								</div>

								<!-- Цена и кнопка удаления -->
								<div class="cart-item__side">
									<div class="cart-item__price">
										<?php
										echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
										?>
									</div>

									<div class="cart-item__remove">
										<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="cart-item__remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                   <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.24902 1C9.41313 0.999628 9.57582 1.03218 9.72754 1.09473C9.87959 1.15745 10.0185 1.24893 10.1348 1.36523C10.2511 1.48154 10.3426 1.62041 10.4053 1.77246C10.4678 1.92422 10.5004 2.08683 10.5 2.25098V3H13.5C13.7761 3 14 3.22386 14 3.5C14 3.77614 13.7761 4 13.5 4H12.9697L12.374 13.5303C12.3245 14.3403 11.722 15 10.875 15H5.125C4.28599 15 3.66941 14.346 3.62598 13.5283L3.03027 4H2.5C2.22386 4 2 3.77614 2 3.5C2 3.22386 2.22386 3 2.5 3H5.5V2.25098C5.49963 2.08687 5.53218 1.92419 5.59473 1.77246C5.65745 1.62041 5.74893 1.48154 5.86523 1.36523C5.98154 1.24893 6.12041 1.15745 6.27246 1.09473C6.42419 1.03218 6.58687 0.999628 6.75098 1H9.24902ZM4.62402 13.4688C4.62414 13.4706 4.62393 13.4728 4.62402 13.4746C4.6414 13.811 4.86511 14 5.125 14H10.875C11.1323 14 11.3548 13.8152 11.376 13.4697L11.9678 4H4.03223L4.62402 13.4688ZM5.73242 5C6.00827 4.9903 6.24015 5.20655 6.25 5.48242L6.5 12.4824C6.50971 12.7583 6.29346 12.9901 6.01758 13C5.74174 13.0097 5.50985 12.7935 5.5 12.5176L5.25 5.51758C5.2403 5.24174 5.45655 5.00985 5.73242 5ZM8 5C8.27614 5 8.5 5.22386 8.5 5.5V12.5C8.5 12.7761 8.27614 13 8 13C7.72386 13 7.5 12.7761 7.5 12.5V5.5C7.5 5.22386 7.72386 5 8 5ZM10.2676 5C10.5434 5.00985 10.7597 5.24174 10.75 5.51758L10.5 12.5176C10.4901 12.7935 10.2583 13.0097 9.98242 13C9.70655 12.9901 9.49029 12.7583 9.5 12.4824L9.75 5.48242C9.75985 5.20655 9.99174 4.9903 10.2676 5ZM6.74902 2C6.71638 1.9999 6.6835 2.00612 6.65332 2.01855C6.62312 2.03101 6.59536 2.04917 6.57227 2.07227C6.54917 2.09536 6.53101 2.12312 6.51855 2.15332C6.50612 2.1835 6.4999 2.21638 6.5 2.24902V3H9.5V2.24902C9.5001 2.21638 9.49389 2.1835 9.48145 2.15332C9.46899 2.12312 9.45083 2.09536 9.42773 2.07227C9.40464 2.04917 9.37688 2.03101 9.34668 2.01855C9.31665 2.00618 9.28443 1.99997 9.25195 2H6.74902Z" fill="currentColor"/>
</svg>

                                                    <span>Удалить</span>
                                                </a>',
												esc_url(wc_get_cart_remove_url($cart_item_key)),
												esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name()))),
												esc_attr($product_id),
												esc_attr($_product->get_sku())
											),
											$cart_item_key
										);
										?>
									</div>
								</div>

							</div>
							<?php
						}
					}
					?>

					<?php do_action('woocommerce_cart_contents'); ?>

					<!-- Скрытая кнопка для нативного обновления корзины скриптами WooCommerce -->
					<button type="submit" class="button" name="update_cart"
						value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" style="display:none;">
						<?php esc_html_e('Update cart', 'woocommerce'); ?>
					</button>

					<?php do_action('woocommerce_cart_actions'); ?>
					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					<?php do_action('woocommerce_after_cart_contents'); ?>
				</div>

				<?php do_action('woocommerce_after_cart_table'); ?>
			</form>

			<div class="cart-page__back">
				<a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"
					class="cart-page__back-link link-arrow link-arrow--reverse">
					<span>Вернуться к покупкам</span>
				</a>
			</div>
		</div>

		<!-- Правая колонка: Ваш заказ (Totals) -->
		<div class="cart-page__sidebar">
			<div class="cart-collaterals">
				<?php
				/**
				 * Cart collaterals hook.
				 *
				 * @hooked woocommerce_cross_sell_display
				 * @hooked woocommerce_cart_totals - 10
				 */
				do_action('woocommerce_cart_collaterals');
				?>
			</div>
		</div>
	</div>
</div>

<?php do_action('woocommerce_after_cart'); ?>