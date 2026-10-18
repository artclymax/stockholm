<?php
$class = $args['class'] ?? '';
$page = get_queried_object();

$title = get_field('about_intro_title', $page) ?? '';
$descr = get_field('about_intro_descr', $page) ?? '';
$image = get_field('about_intro_image', $page);
?>

<?php if ($title || $descr || ($image && is_array($image) && !empty($image['ID']))): ?>
	<section class="section section-about-intro <?php echo esc_attr($class); ?>" id="section-about-intro">
		<div class="section-about-intro__container un-container un-container--reverse">

			<div class="section-about-intro__content un-container__content">
				<?php if ($title): ?>
					<h2 class="section-about-intro__title title-2 descr">
						<?php echo wp_kses_post($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="section-about-intro__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($image && is_array($image) && !empty($image['ID'])): ?>
				<div class="section-about-intro__image-box image-box un-container__full">
					<?php echo wp_get_attachment_image(
						$image['ID'],
						'full',
						false,
						array(
							'class' => 'img',
							'alt' => $image['alt'] ?: $title,
							'loading' => 'lazy'
						)
					); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>