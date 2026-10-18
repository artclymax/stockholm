<?php
/**
 * Секция "История основателя"
 */

$class = $args['class'] ?? '';
$page = get_queried_object();

$label = get_field('history_label', $page) ?? '';
$title = get_field('history_title', $page) ?? '';
$descr = get_field('history_descr', $page) ?? '';
$quote = get_field('history_quote', $page) ?? '';
$founder_name = get_field('history_founder_name', $page) ?? '';
$image = get_field('history_image', $page);
?>

<?php if ($title || $descr || $quote || ($image && is_array($image) && !empty($image['ID']))): ?>
	<section class="section section-about-history <?php echo esc_attr($class); ?>" id="section-about-history">
		<div class="section-about-history__container un-container">

			<?php if ($image && is_array($image) && !empty($image['ID'])): ?>
				<div class="section-about-history__image-box image-box un-container__full">
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

			<div class="section-about-history__content un-container__content">
				<?php if ($label): ?>
					<div class="section-about-history__label text-label">
						<?php echo esc_html($label); ?>
					</div>
				<?php endif; ?>

				<?php if ($title): ?>
					<h2 class="section-about-history__title title-2">
						<?php echo wp_kses_post($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="section-about-history__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>

				<?php if ($quote): ?>
					<blockquote class="section-about-history__quote">
						<div class="section-about-history__quote-descr descr">
							<?php echo wp_kses_post($quote); ?>
						</div>
						<?php if ($founder_name): ?>
							<h3 class="section-about-history__quote-founder">
								<?php echo wp_kses_post($founder_name); ?>
							</h3>
						<?php endif; ?>
					</blockquote>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>