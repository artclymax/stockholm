<?php
/**
 * Секция "Все необходимое для вашего гардероба"
 */

$class = $args['class'] ?? '';
$page = get_queried_object();

// Получаем данные из ACF полей
$title = get_field('categories_title', $page) ?? '';
$categories = get_field('categories_list', $page) ?? [];
?>

<?php if ($title || !empty($categories)): ?>
	<section class="section section-about-categories <?php echo esc_attr($class); ?>" id="section-about-categories">
		<div class="container section-about-categories__container">
			<?php if ($title): ?>
				<h2 class="section-about-categories__title title-2 descr">
					<?php echo wp_kses_post($title); ?>
				</h2>
			<?php endif; ?>

			<?php if (!empty($categories)): ?>
				<div class="section-about-categories__links">
					<?php foreach ($categories as $category_item):
						$link_url = '';
						$link_text = '';

						// 1. Определяем URL
						if (!empty($category_item['category_term'])) {
							$term = get_term($category_item['category_term']);
							if ($term && !is_wp_error($term)) {
								$link_url = get_term_link($term);
							} else {
								continue;
							}
						} elseif (!empty($category_item['custom_link']['url'])) {
							$link_url = $category_item['custom_link']['url'];
						} else {
							continue;
						}

						// 2. Определяем текст ссылки (приоритет: кастомный title → название термина → title кастомной ссылки)
						if (!empty($category_item['link_title'])) {
							$link_text = $category_item['link_title'];
						} elseif (!empty($category_item['category_term'])) {
							$term = get_term($category_item['category_term']);
							$link_text = $term && !is_wp_error($term) ? $term->name : '';
						} elseif (!empty($category_item['custom_link']['title'])) {
							$link_text = $category_item['custom_link']['title'];
						}

						if (!$link_text)
							continue;

						$link_target = '';
						if (!empty($category_item['custom_link']['target'])) {
							$link_target = $category_item['custom_link']['target'];
						}
						?>
						<a class="section-about-categories__link btn-link" href="<?php echo esc_url($link_url); ?>" <?php if ($link_target): ?>target="<?php echo esc_attr($link_target); ?>" <?php endif; ?>>
							<?php echo esc_html($link_text); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>