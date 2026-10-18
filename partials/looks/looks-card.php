<?php
$card_id = get_the_ID();
$permalink = get_permalink($card_id);
$title = get_the_title($card_id);
$excerpt = get_the_excerpt($card_id);
$thumbnail_id = get_post_thumbnail_id($card_id);
?>

<article class="looks-grid__item looks-card">
	<a class="looks-card__link" href="<?php echo esc_url($permalink); ?>">
		<div class="looks-card__image-box image-box">
			<?php if ($thumbnail_id): ?>
				<?php echo wp_get_attachment_image(
					$thumbnail_id,
					'large',
					false,
					array(
						'class' => 'img',
						'alt' => $title,
						'loading' => 'lazy'
					)
				); ?>
			<?php endif; ?>
		</div>

		<div class="looks-card__content">
			<h2 class="looks-card__title title-3"><?php echo esc_html($title); ?></h2>

			<?php if ($excerpt): ?>
				<div class="looks-card__descr descr"><?php echo wp_kses_post($excerpt); ?></div>
			<?php endif; ?>

			<span class="looks-card__more link-arrow link-arrow--accent">
				Смотреть образ
			</span>
		</div>
	</a>
</article>