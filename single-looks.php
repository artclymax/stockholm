<?php
/**
 * Шаблон одиночной записи "Готовый образ"
 */

get_header();

$look_id = get_the_ID();
$title = get_the_title($look_id);
$excerpt = apply_filters('the_content', get_the_content($look_id));
$thumbnail_id = get_post_thumbnail_id($look_id);

$products = get_field('look_products', $look_id) ?? [];
?>

<section class="section single-look" id="single-look">
	<div class="container single-look__container">
		<div class="single-look__breadcrumbs page-breadcrumbs">
			<?php if (function_exists('bcn_display')) {
				bcn_display();
			} ?>
		</div>

		<div class="single-look__header">
			<?php if ($title): ?>
				<h1 class="single-look__title title-1">
					<?php echo esc_html($title); ?>
				</h1>
			<?php endif; ?>

			<?php if ($excerpt): ?>
				<div class="single-look__descr descr">
					<?php echo wp_kses_post($excerpt); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="single-look__content">
			<?php if ($thumbnail_id): ?>
				<div class="single-look__image-box image-box">
					<?php echo wp_get_attachment_image(
						$thumbnail_id,
						'large',
						false,
						[
							'class' => 'img',
							'alt' => $title,
							'loading' => 'eager'
						]
					); ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($products)): ?>
				<div class="single-look__products">
					<h2 class="single-look__products-title title-3">В образе</h2>

					<div class="single-look__products-list">
						<?php foreach ($products as $product_item):
							$product_id = $product_item['product'] ?? null;
							if (!$product_id)
								continue;

							$product_obj = wc_get_product($product_id);
							if (!$product_obj)
								continue;

							$product_permalink = get_permalink($product_id);
							$product_title = get_the_title($product_id);
							$product_price = $product_obj->get_price_html();

							$card_image_id = $product_item['look_card_image']['ID'] ?? null;
							if (!$card_image_id) {
								$card_image_id = get_post_thumbnail_id($product_id);
							}
							?>
							<article class="single-look__product-card look-product-card">
								<?php if ($card_image_id): ?>
									<div class="look-product-card__image-box image-box">
										<a href="<?php echo esc_url($product_permalink); ?>">
											<?php echo wp_get_attachment_image(
												$card_image_id,
												'large',
												false,
												[
													'class' => 'img',
													'alt' => $product_title,
													'loading' => 'lazy'
												]
											); ?>
										</a>
									</div>
								<?php endif; ?>

								<div class="look-product-card__content">
									<h3 class="look-product-card__title">
										<a href="<?php echo esc_url($product_permalink); ?>">
											<?php echo esc_html($product_title); ?>
										</a>
									</h3>

									<div class="look-product-card__price">
										<?php echo $product_price; ?>
									</div>
								</div>
								<a class="look-product-card__btn btn btn--stroke-accent"
									href="<?php echo esc_url($product_permalink); ?>">
									Смотреть товар
								</a>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>