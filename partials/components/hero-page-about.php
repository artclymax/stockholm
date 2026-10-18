<?php

$class = $args['class'] ?? '';
$page = get_queried_object();

$title = get_field('hero_title', $page) ?? get_the_title();
$descr = get_field('hero_descr', $page) ?? '';
$image = get_field('hero_image', $page);
?>

<?php if ($title || $descr || ($image && is_array($image) && !empty($image['ID']))): ?>
	<section class="hero-page hero-page-about <?php echo esc_attr($class); ?>" id="hero-page-about">
		<div class="hero-page-about__top-container container">
			<div class="hero-page-about__breadcrumbs page-breadcrumbs">
				<?php if (function_exists('bcn_display')) {
					bcn_display();
				} ?>
			</div>
		</div>
		<div class="un-container un-container--reverse hero-page-about__container">
			<div class="un-container__content hero-page-about__content">
				<div class="hero-page-about__header">
					<?php if ($title): ?>
						<h1 class="hero-page-about__title title-1 descr">
							<?php echo wp_kses_post($title); ?>
						</h1>
					<?php endif; ?>

					<?php if ($descr): ?>
						<div class="hero-page-about__descr descr">
							<?php echo wp_kses_post($descr); ?>
						</div>
					<?php endif; ?>
				</div>

			</div>
			<?php if ($image && is_array($image) && !empty($image['ID'])): ?>
				<div class="hero-page-about__image-box image-box un-container__full">
					<?php echo wp_get_attachment_image(
						$image['ID'],
						'full',
						false,
						array(
							'class' => 'img',
							'alt' => $image['alt'] ?: '',
							'loading' => 'eager'
						)
					); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>