<?php
/**
 * Каталог товаров (Shop / Category Archive)
 */

defined('ABSPATH') || exit;

get_header('shop');

if (is_product_category()) {
	get_template_part('partials/components/hero-page-category');
} else {
	get_template_part('partials/components/hero-page-simple');
}

wp_enqueue_script('wc-add-to-cart-variation');

// Текущий термин (если мы на странице категории)
$current_term = is_product_category() ? get_queried_object() : null;
$current_term_id = $current_term ? $current_term->term_id : 0;


// Получаем пункты меню по названию или слагу меню (например: 'shop-menu')
$locations = get_nav_menu_locations();

$menu_id = $locations['shop-menu'] ?? null;

$menu_items = $menu_id ? wp_get_nav_menu_items($menu_id) : array();

// Определяем текущий открытый URL
$current_url = home_url(add_query_arg([], $GLOBALS['wp']->request) . '/');


// Глобальный запрос WooCommerce
global $wp_query;
$max_num_pages = $wp_query->max_num_pages;
?>

<section class="section-shop" id="section-shop">
	<div class="container section-shop__container">
		<div class="section-shop__header">
			<!-- 1. Вкладки категорий -->
			<nav class="shop-tabs">
				<ul class="shop-tabs__list">
					<?php if (!empty($menu_items)): ?>
						<?php foreach ($menu_items as $item):
							$is_active = (trailingslashit($item->url) === $current_url);
							?>
							<li class="shop-tabs__item <?php echo $is_active ? 'is-active' : ''; ?>">
								<a href="<?php echo esc_url($item->url); ?>" class="shop-tabs__link">
									<?php echo esc_html($item->title); ?>
								</a>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</nav>
			<!-- 2. Панель сортировки WooCommerce -->
			<div class="shop-toolbar">
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		</div>

		<!-- 3. Сетка товаров -->
		<div class="shop-catalog">
			<?php if (woocommerce_product_loop()): ?>

				<div class="section-shop__grid products-grid woocommerce product-grid" id="shop-products-grid">
					<?php while (have_posts()):
						the_post(); ?>
						<?php wc_get_template('woocommerce/content-product-card.php'); ?>
					<?php endwhile; ?>
				</div>

				<!-- 4. Кнопка "Показать еще" -->
				<?php if ($max_num_pages > 1): ?>
					<div class="shop-catalog__loadmore-wrap">
						<?php
						// Вычисляем текущую сортировку
						$current_orderby = isset($_GET['orderby'])
							? wc_clean(wp_unslash($_GET['orderby']))
							: apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
						?>
						<button type="button" class="btn btn--stroke-accent  js-shop-load-more" data-page="1"
							data-max-page="<?php echo esc_attr($max_num_pages); ?>"
							data-category="<?php echo esc_attr($current_term_id); ?>"
							data-orderby="<?php echo esc_attr($current_orderby); ?>">
							Показать ещё
						</button>
					</div>
				<?php endif; ?>

			<?php else: ?>
				<?php wc_get_template('loop/no-products-found.php'); ?>
			<?php endif; ?>
		</div>

	</div>
</section>

<?php
get_footer('shop');