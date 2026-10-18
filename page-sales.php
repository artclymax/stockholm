<?php
/**
 * Template Name: Товары со скидкой
 */

defined('ABSPATH') || exit;

get_header('shop');
get_template_part('partials/components/hero-page-sales');

$page = get_queried_object();
$posts_per_page = get_field('sales_posts_per_page', $page) ?? 8;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Получаем ВСЕ товары со скидкой
$all_products = wc_get_products(array(
	'limit' => -1,
	'status' => 'publish',
	'return' => 'ids',
));

$sale_product_ids = array();
foreach ($all_products as $product_id) {
	$product = wc_get_product($product_id);
	if (!$product)
		continue;

	if ($product->is_type('simple')) {
		if ($product->is_on_sale()) {
			$sale_product_ids[] = $product_id;
		}
	} elseif ($product->is_type('variable')) {
		$variations = $product->get_available_variations();
		foreach ($variations as $variation) {
			if (!empty($variation['display_price']) && $variation['display_price'] < $variation['display_regular_price']) {
				$sale_product_ids[] = $product_id;
				break;
			}
		}
	}
}

$sale_product_ids = array_unique($sale_product_ids);

// Текущая сортировка
$current_orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : 'popularity';
$current_order = isset($_GET['order']) ? wc_clean(wp_unslash($_GET['order'])) : 'DESC';

// Аргументы запроса
$args = array(
	'post_type' => 'product',
	'posts_per_page' => $posts_per_page,
	'paged' => $paged,
	'post_status' => 'publish',
	'post__in' => $sale_product_ids,
);

// Применяем сортировку
switch ($current_orderby) {
	case 'price':
		$args['meta_key'] = '_price';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'ASC';
		break;
	case 'price-desc':
		$args['meta_key'] = '_price';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'DESC';
		break;
	case 'date':
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
		break;
	case 'rating':
		$args['meta_key'] = '_wc_average_rating';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'DESC';
		break;
	case 'popularity':
	default:
		$args['meta_key'] = 'total_sales';
		$args['orderby'] = 'meta_value_num';
		$args['order'] = 'DESC';
		break;
}

$sales_query = new WP_Query($args);
$base_url = get_permalink($page);
?>

<section class="section section-sales" id="section-sales">
	<div class="container section-sales__container">
		<div class="section-sales__header">
			<h2 class="section-sales__title title-1">
				Товары со скидкой
			</h2>
			<div class="section-sales__toolbar shop-toolbar">
				<form class="woocommerce-ordering" method="get" action="<?php echo esc_url($base_url); ?>">
					<select name="orderby" class="orderby" aria-label="<?php esc_attr_e('Shop order', 'woocommerce'); ?>">
						<?php
						$catalog_orderby_options = apply_filters('woocommerce_catalog_orderby', array(
							'popularity' => __('По популярности', 'woocommerce'),
							'rating' => __('По рейтингу', 'woocommerce'),
							'date' => __('По новизне', 'woocommerce'),
							'price' => __('По возрастанию цены', 'woocommerce'),
							'price-desc' => __('По убыванию цены', 'woocommerce'),
						));

						foreach ($catalog_orderby_options as $id => $name):
							$selected = ($current_orderby === $id) ? ' selected="selected"' : '';
							?>
							<option value="<?php echo esc_attr($id); ?>" <?php echo $selected; ?>>
								<?php echo esc_html($name); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</form>
			</div>
		</div>
		<div class="section-sales__catalog">
			<?php if ($sales_query->have_posts()): ?>
				<div class="section-sales__grid products-grid woocommerce product-grid" id="sales-products-grid"
					data-product-ids="<?php echo esc_attr(implode(',', $sale_product_ids)); ?>">
					<?php while ($sales_query->have_posts()):
						$sales_query->the_post(); ?>
						<?php wc_get_template('woocommerce/content-product-card.php'); ?>
					<?php endwhile; ?>
				</div>

				<?php if ($sales_query->max_num_pages > 1): ?>
					<div class="section-sales__loadmore-wrap">
						<button type="button" class="btn btn--stroke-accent js-sales-load-more" data-page="1"
							data-max-page="<?php echo esc_attr($sales_query->max_num_pages); ?>"
							data-posts-per-page="<?php echo esc_attr($posts_per_page); ?>"
							data-orderby="<?php echo esc_attr($current_orderby); ?>"
							data-order="<?php echo esc_attr($current_order); ?>">
							Показать ещё
						</button>
					</div>
				<?php endif; ?>

			<?php else: ?>
				<p class="section-sales__empty"><?php esc_html_e('Товары со скидкой не найдены.', 'woocommerce'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ПРЯМОЙ ТЕГ SCRIPT - самый надёжный способ -->
<script>
	(function () {
		'use strict';

		// Ждём полной загрузки DOM
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initSalesLoadMore);
		} else {
			initSalesLoadMore();
		}

		function initSalesLoadMore() {
			// Проверяем наличие jQuery
			if (typeof jQuery === 'undefined') {
				console.error('Sales: jQuery не найден');
				return;
			}

			var $ = jQuery;
			var btn = $('.js-sales-load-more');
			var grid = $('#sales-products-grid');

			console.log('Sales: инициализация');
			console.log('Sales: кнопка найдена:', btn.length);
			console.log('Sales: сетка найдена:', grid.length);

			if (!btn.length) {
				console.log('Sales: кнопка не найдена - все товары загружены');
				return;
			}

			// Автоотправка формы сортировки
			$('.woocommerce-ordering select').on('change', function () {
				$(this).closest('form').submit();
			});

			// Обработчик клика
			btn.on('click', function () {
				var self = $(this);
				if (self.prop('disabled')) {
					console.log('Sales: кнопка уже disabled');
					return;
				}

				var currentPage = parseInt(self.data('page')) + 1;
				var maxPage = parseInt(self.data('max-page'));
				var postsPerPage = parseInt(self.data('posts-per-page'));
				var orderby = self.data('orderby');
				var order = self.data('order');

				// Берём product_ids из сетки (а не из кнопки - там может быть обрезано)
				var productIds = grid.data('product-ids');

				console.log('Sales: загрузка страницы', currentPage);
				console.log('Sales: orderby:', orderby, 'order:', order);
				console.log('Sales: product_ids length:', productIds ? productIds.length : 0);

				self.prop('disabled', true).text('Загрузка...');

				$.ajax({
					url: '<?php echo admin_url("admin-ajax.php"); ?>',
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'load_more_sales',
						page: currentPage,
						posts_per_page: postsPerPage,
						orderby: orderby,
						order: order,
						product_ids: productIds
					},
					success: function (res) {
						console.log('Sales: ответ сервера', res);

						if (res.success && res.data && res.data.html) {
							console.log('Sales: HTML получен, длина:', res.data.html.length);
							grid.append(res.data.html);

							if (currentPage < maxPage) {
								self.data('page', currentPage).prop('disabled', false).text('Показать ещё');
							} else {
								console.log('Sales: все страницы загружены');
								self.fadeOut(300, function () { $(this).remove(); });
							}
						} else {
							console.error('Sales: ошибка в ответе', res);
							self.fadeOut(300, function () { $(this).remove(); });
						}
					},
					error: function (xhr, status, error) {
						console.error('Sales: AJAX ошибка', status, error);
						console.error('Sales: ответ сервера:', xhr.responseText);
						self.prop('disabled', false).text('Показать ещё');
					}
				});
			});
		}
	})();
</script>

<?php get_footer('shop'); ?>