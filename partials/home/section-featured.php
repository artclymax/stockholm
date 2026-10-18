<?php
/**
 * Шаблон секции "Новинки" (Featured Products)
 */

$page = $args['page'] ?? get_queried_object();

$title = get_field('featured_title', $page) ?? 'Новинки';
$descr = get_field('featured_descr', $page) ?? 'Новые поступления для женского и мужского гардероба.';
$view_all_link = get_field('featured_view_all_link', $page);
$products_count = get_field('featured_products_count', $page) ?? 4;

if (empty($view_all_link)) {
	$view_all_link = [
		'url' => wc_get_page_permalink('shop'),
		'title' => 'Смотреть все',
	];
}

$args_products = array(
	'post_type' => 'product',
	'posts_per_page' => $products_count,
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'product_visibility',
			'field' => 'name',
			'terms' => 'featured',
			'operator' => 'IN',
		),
	),
);

$products_query = new WP_Query($args_products);

wp_enqueue_script('wc-add-to-cart-variation');
?>

<?php if ($title || $descr || ($products_query && $products_query->have_posts())): ?>
	<section class="section section-featured" id="section-featured">
		<div class="container section-featured__container section-gap">
			<div class="section-featured__header">
				<?php get_template_part('partials/section-header', null, [
					'title' => $title,
					'descr' => $descr
				]); ?>

				<?php if (!empty($view_all_link)): ?>
					<a class="section-featured__view-all btn-link" href="<?php echo esc_attr($view_all_link['url']); ?>">
						<?php echo esc_html($view_all_link['title']); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ($products_query && $products_query->have_posts()): ?>
				<div class="section-featured__grid woocommerce grid product-grid">
					<?php while ($products_query->have_posts()):
						$products_query->the_post();

						global $product;
						$product = wc_get_product(get_the_ID());
						get_template_part('woocommerce/content-product-card');
						?>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>