<?php
$page = get_queried_object();
$class = $args['class'] ?? '';

if (!empty($args['page'])) {
	$page = get_page_by_path($args['page'], OBJECT, 'page');
}

if (is_archive()) {
	$archive_name = '';

	$queried = get_queried_object();
	$archive_name = is_string($queried->has_archive ?? null) ? $queried->has_archive : ($queried->name ?? '');


	$archive_page = $archive_name ? get_page_by_path($archive_name, OBJECT, 'page') : null;

	if ($archive_page) {
		$page = $archive_page;
	}
}
if (is_tax()) {
	$term = get_queried_object();
	if (!empty($args['page'])) {
		$term = get_page_by_path($args['page'], OBJECT, 'page');
	}
	$title = get_field('hero_title', $term) ?? '';
	$descr = get_field('hero_descr', $term) ?? '';

} else {
	$title = get_field('hero_title', $page) ?? '';
	$descr = get_field('hero_descr', $page) ?? '';

}

if (!$title) {
	if (is_tax() || is_category() || is_tag()) {
		$title = single_term_title('', false);
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} else {
		$title = get_the_title();
	}
}



if ($title || $descr || ($image && is_array($image) && !empty($image['ID'])) || ($image_mobile && is_array($image_mobile) && !empty($image_mobile['ID']))):
	?>
	<section class="hero-page <?php echo esc_attr($class); ?>" id="hero-page">
		<div class="container hero-page__container">
			<div class="hero-page__breadcrumbs page-breadcrumbs">
				<?php if (function_exists('bcn_display')) {
					bcn_display();
				} ?>
			</div>

			<?php if ($title): ?>
				<h1 class="hero-page__title title-1 descr"><?php echo wp_kses_post($title); ?></h1>
			<?php endif; ?>
			<?php if ($descr): ?>
				<div class="hero-page__descr descr"><?php echo wp_kses_post($descr); ?></div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>