<?php
$page = get_queried_object();
$class = $args['class'] ?? '';

$title = '';
$descr = '';

// 1. ПРОВЕРКА СОРТИРОВКИ (ФИЛЬТРОВ)
if (!empty($_GET['orderby'])) {
	$orderby = wc_clean($_GET['orderby']);

	// Получаем заголовки из ACF Options
	$sort_titles = stock_get_sort_titles();

	if (isset($sort_titles[$orderby])) {
		$title = $sort_titles[$orderby]['title'] ?? '';
		$descr = $sort_titles[$orderby]['descr'] ?? '';
	}
}

// 2. FALLBACK: Если сортировка не задана или её нет в списке — берём из ACF полей
if (!$title || !$descr) {
	if (is_tax() || is_product_category()) {
		$term = get_queried_object();
		$title = $title ?: (get_field('hero_title', $term) ?? '');
		$descr = $descr ?: (get_field('hero_descr', $term) ?? '');

		if (!$title) {
			$title = single_term_title('', false);
		}
	} else {
		if (is_archive()) {
			$archive_name = '';
			$queried = get_queried_object();
			$archive_name = is_string($queried->has_archive ?? null) ? $queried->has_archive : ($queried->name ?? '');
			$archive_page = $archive_name ? get_page_by_path($archive_name, OBJECT, 'page') : null;
			if ($archive_page) {
				$page = $archive_page;
			}
		}

		$title = $title ?: (get_field('hero_title', $page) ?? '');
		$descr = $descr ?: (get_field('hero_descr', $page) ?? '');

		if (!$title) {
			if (is_post_type_archive()) {
				$title = post_type_archive_title('', false);
			} else {
				$title = get_the_title();
			}
		}
	}
}

if ($title):
	?>
	<section class="hero-page hero-page-simple <?php echo esc_attr($class); ?>" id="hero-page-simple">
		<div class="container hero-page__container">
			<div class="hero-page__breadcrumbs page-breadcrumbs">
				<?php if (function_exists('bcn_display')) {
					bcn_display();
				} ?>
			</div>

			<div class="hero-page__content">
				<h1 class="hero-page__title title-1 descr">
					<?php echo wp_kses_post($title); ?>
				</h1>

				<?php if (!empty($descr)): ?>
					<div class="hero-page__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>