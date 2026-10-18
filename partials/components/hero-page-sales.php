<?php
/**
 * Шаблон hero для страницы "Товары со скидкой"
 */

$class = $args['class'] ?? '';
$page = get_queried_object();

// Получаем данные страницы
$title = get_field('hero_title', $page) ?? get_the_title($page);
$descr = get_field('hero_descr', $page) ?? '';

// Получаем изображение
$image_id = get_field('hero_image', $page);
if ($image_id) {
	$image_id = $image_id['ID'];
} else {
	$image_id = get_post_thumbnail_id($page);

}
?>

<?php if ($title || $descr || $image_id): ?>
	<section class="hero-page hero-page-sales <?php echo esc_attr($class); ?>" id="hero-page-sales">
		<div class="container hero-page__container">

			<!-- Хлебные крошки -->
			<?php if (function_exists('bcn_display')): ?>
				<div class="hero-page__breadcrumbs page-breadcrumbs">
					<?php bcn_display(); ?>
				</div>
			<?php endif; ?>

			<div class="hero-page__content">
				<?php if ($title): ?>
					<h1 class="hero-page__title title-1 descr">
						<?php echo wp_kses_post($title); ?>
					</h1>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="hero-page__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ($image_id): ?>
			<div class="hero-page__image image-box">
				<?php echo wp_get_attachment_image(
					$image_id,
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