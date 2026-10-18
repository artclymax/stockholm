<?php

$class = $args['class'] ?? '';
$page = get_queried_object();

$title = get_field('look_title', $page) ?? '';
$descr = get_field('look_descr', $page) ?? '';
$button = get_field('look_button', $page);
$image = get_field('look_image', $page);
?>

<?php if ($title || $descr || ($image && is_array($image) && !empty($image['ID']))): ?>
	<section class="section section-about-look <?php echo esc_attr($class); ?>" id="section-about-look">
		<div class="section-about-look__container un-container">
			<?php if ($image && is_array($image) && !empty($image['ID'])): ?>
				<div class="section-about-look__image-box image-box un-container__full">
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

			<div class="section-about-look__content un-container__content">
				<?php if ($title): ?>
					<h2 class="section-about-look__title title-2">
						<?php echo wp_kses_post($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="section-about-look__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>

				<?php if ($button && !empty($button['url'])): ?>
					<a class="section-about-look__btn btn" href="<?php echo esc_url($button['url']); ?>" <?php if (!empty($button['target'])): ?>target="
				<?php echo esc_attr($button['target']); ?>" <?php endif; ?>>
						<?php echo esc_html($button['title']); ?>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</section>
<?php endif; ?>