<?php
/**
 * Компонент карточки товара
 * Используется в секциях и архивах каталога
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
	return;
}

$is_variable = $product->is_type('variable');
$product_id = $product->get_id();
?>

<div
	class="products-grid__item product-card <?php echo $is_variable ? 'product-card--variable' : 'product-card--simple'; ?>"
	data-product-id="<?php echo esc_attr($product_id); ?>">

	<div class="product-card__mobile-trigger" data-product-id="<?php echo esc_attr($product_id); ?>"></div>

	<div class="product-card__image-box">
		<a class="product-card__image-link image-box" href="<?php the_permalink() ?>">
			<?php
			if (has_post_thumbnail()) {
				echo wp_get_attachment_image(
					get_post_thumbnail_id(),
					'large',
					false,
					array(
						'class' => 'product-card__image img',
						'alt' => get_the_title(),
						'loading' => 'lazy',
					)
				);
			} else {
				echo wc_placeholder_img('large');
			}
			?>
		</a>

		<?php if ($product->is_on_sale()): ?>
			<span class="product-card__badge product-card__badge--sale">
				<?php echo esc_html__('Sale', 'woocommerce'); ?>
			</span>
		<?php endif; ?>

		<?php if (!$product->is_in_stock()): ?>
			<span class="product-card__badge product-card__badge--out">
				<?php echo esc_html__('Нет в наличии', 'woocommerce'); ?>
			</span>
		<?php endif; ?>

		<div class="product-card__quick-cart hidden-mobile">
			<?php if ($is_variable): ?>
				<span class="product-card__quick-title text-product-title">Выберите размер</span>
				<?php woocommerce_variable_add_to_cart(); ?>
			<?php else: ?>
				<?php woocommerce_simple_add_to_cart(); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="product-card__info">
		<h3 class="product-card__title title-5">
			<a href="<?php echo esc_url(get_permalink()); ?>">
				<?php echo esc_html(get_the_title()); ?>
			</a>
		</h3>
		<div class="product-card__price">
			<?php echo $product->get_price_html(); ?>
		</div>
	</div>
</div>

<div class="product-card__mobile-drawer" id="mobile-drawer-<?php echo esc_attr($product_id); ?>"
	data-product-id="<?php echo esc_attr($product_id); ?>">
	<div class="product-card__drawer-overlay"></div>
	<div class="product-card__drawer-content">
		<div class="product-card__drawer-handle"></div>

		<div class="product-card__drawer-header">
			<?php if ($is_variable): ?>
				<h3 class="product-card__drawer-title">Выберите размер</h3>
			<?php else: ?>
				<h3 class="product-card__drawer-title">Добавить в корзину</h3>
			<?php endif; ?>
		</div>

		<a class="product-card__drawer-product" href="<?php echo esc_url(get_permalink()); ?>">
			<div class="product-card__drawer-image">
				<?php
				if (has_post_thumbnail()) {
					echo wp_get_attachment_image(
						get_post_thumbnail_id(),
						'large',
						false,
						array(
							'class' => 'img',
							'alt' => get_the_title(),
						)
					);
				}
				?>
			</div>
			<div class="product-card__drawer-product-info">
				<h4 class="product-card__drawer-product-title">
					<?php echo esc_html(get_the_title()); ?></h4>
					<div class="product-card__drawer-price">
						<?php echo $product->get_price_html(); ?>
					</div>
			</div>
		</a>

		<?php if ($is_variable): ?>
			<div class="product-card__drawer-variations">
				<?php woocommerce_variable_add_to_cart(); ?>
			</div>
		<?php else: ?>
			<div class="product-card__drawer-simple">
				<?php woocommerce_simple_add_to_cart(); ?>
			</div>
		<?php endif; ?>

		<div class="product-card__drawer-footer">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="product-card__drawer-view-product btn-link">
				Смотреть товар
			</a>
		</div>
	</div>
</div>