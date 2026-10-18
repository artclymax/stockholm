<?php
$page = $args['page'] ?? get_queried_object();

// Контент
$title = get_field('categories_title', $page) ?? 'Всё для вашего гардероба';
$descr = get_field('categories_descr', $page) ?? 'Одежда, обувь и аксессуары для женских и мужских образов';

// Категории
$manual_categories = get_field('categories_list', $page) ?? [];

// Получаем категории
$categories = [];

if (!empty($manual_categories)) {
	// Используем вручную выбранные категории
	foreach ($manual_categories as $item) {
		$category_id = $item['category'] ?? null;
		if ($category_id) {
			$term = get_term($category_id, 'product_cat');
			if ($term && !is_wp_error($term)) {
				$categories[] = $term;
			}
		}
	}
} else {
	// Fallback: последние 4 категории
	$args_cats = array(
		'taxonomy' => 'product_cat',
		'orderby' => 'date',
		'order' => 'DESC',
		'hide_empty' => false,
		'number' => 4,
	);
	$categories = get_terms($args_cats);
}
?>

<?php if ($title || $descr || !empty($categories)): ?>
	<section class="section section-categories" id="section-categories">
		<div class="container section-categories__container">
			<?php get_template_part('partials/section-header', null, [
				'title' => $title,
				'descr' => $descr
			]); ?>

			<?php if (!empty($categories)): ?>
				<?php foreach ($categories as $index => $category):
					$category_id = $category->term_id;
					$category_name = $manual_categories[$index]['title'] ?? $category->name;
					$category_link = get_term_link($category);

					// Получаем изображение категории
					// $category_image = ; //get_field('category_image', 'product_cat_' . $category_id)
					// Если изображения нет, берем первое изображение товаров из этой категории
		
					?>
					<a class="section-categories__item category-card" href="<?php echo esc_url($category_link); ?>">


						<div class="category-card__image image-box">
							<?php echo wp_get_attachment_image(
								$manual_categories[$index]['image']['ID'],
								'large',
								false,
								array(
									'class' => 'img',
									'alt' => $manual_categories[$index]['image']['alt'] ?: $manual_categories[$index]['title']
								)
							); ?>
						</div>

						<div class="category-card__content">
							<h3 class="category-card__item-title title-3">
								<?php echo esc_html($category_name); ?>
							</h3>
							<?php if ($category_link && !is_wp_error($category_link)): ?>
								<span class="category-card__link btn-link">
									Смотреть коллекцию
								</span>
							<?php endif; ?>
						</div>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>