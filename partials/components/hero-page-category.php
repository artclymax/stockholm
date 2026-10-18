<?php
/**
 * Шаблон hero для категории товаров
 */

$class = $args['class'] ?? '';
$term = get_queried_object();

// Получаем данные категории
$title = single_term_title('', false);
$descr = term_description();

// Получаем изображение категории
$term_image_id = get_term_meta($term->term_id, 'thumbnail_id', true);

// Если есть ACF поле с изображением, используем его
$acf_image_id = get_field('term_image', $term);
if ($acf_image_id) {
	$term_image_id = $acf_image_id;
}
?>

<?php if ($title || $descr || $term_image_id): ?>
	<section class="hero-page hero-page-category <?php echo esc_attr($class); ?>" id="hero-page-category">
		<div class="container hero-page__container">

			<!-- Хлебные крошки -->
			<?php if (function_exists('bcn_display')): ?>
				<div class="hero-page__breadcrumbs page-breadcrumbs">
					<?php bcn_display(); ?>
				</div>
			<?php endif; ?>

			<div class="hero-page__content">
				<?php if ($title): ?>
					<h1 class="hero-page__title title-1"><?php echo esc_html($title); ?></h1>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="hero-page__descr text-body-xl">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ($term_image_id): ?>
			<div class="hero-page__image image-box">
				<?php echo wp_get_attachment_image(
					$term_image_id,
					'full',
					false,
					array(
						'class' => 'img',
						'alt' => $title,
						'loading' => 'eager'
					)
				); ?>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>