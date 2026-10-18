<?php

$page = $args['page'] ?? get_queried_object();

// Контент
$label = get_field('section_plus_size_label', $page) ?? '';
$title = get_field('section_plus_size_title', $page) ?? '';
$descr = get_field('section_plus_size_descr', $page) ?? '';
$btn = get_field('section_plus_size_btn', $page) ?? [];

// Преимущества
$features = get_field('section_plus_size_features', $page) ?? [];

// Медиа
$image = get_field('section_plus_size_image', $page) ?? null;
$image_mobile = get_field('section_plus_size_image_mobile', $page) ?? null;

if ($title || $descr || ($image && !empty($image['ID']))):
	?>
	<section class="section section-plus-size" id="section-plus-size">
		<div class="container section-plus-size__container">
			<div class="section-plus-size__content">
				<?php get_template_part('partials/section-header', null, [
					'label' => $label,
					'title' => $title,
					'descr' => $descr
				]); ?>

				<?php if (!empty($btn) && !empty($btn['url'])): ?>
					<a class="section-plus-size__btn btn" href="<?php echo esc_url($btn['url']); ?>" <?php if (!empty($btn['target'])): ?>target="
				<?php echo esc_attr($btn['target']); ?>" <?php endif; ?>>
						<?php echo esc_html($btn['title']); ?>
					</a>
				<?php endif; ?>

				<?php if (!empty($features)): ?>
					<div class="section-plus-size__features">
						<?php foreach ($features as $index => $feature):
							$feature_text = $feature['text'] ?? '';
							if (!$feature_text)
								continue;
							?>
							<div class="section-plus-size__feature">
								<span class="section-plus-size__feature-text">
									<?php echo esc_html($feature_text); ?>
								</span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="section-plus-size__images">
				<?php if ($image && !empty($image['ID'])): ?>
					<div class="section-plus-size__image-big image-box">
						<?php echo wp_get_attachment_image(
							$image['ID'],
							'full',
							false,
							[
								'class' => 'img',
								'alt' => $image['alt'] ?: esc_html($title),
								'loading' => 'eager'
							]
						); ?>
					</div>
				<?php endif; ?>

				<?php if ($image_mobile && !empty($image_mobile['ID'])): ?>
					<div class="section-plus-size__image-small image-box">
						<?php echo wp_get_attachment_image(
							$image_mobile['ID'],
							'full',
							false,
							[
								'class' => 'img',
								'alt' => $image_mobile['alt'] ?: esc_html($title),
								'loading' => 'eager'
							]
						); ?>
					</div>
				<?php elseif ($image && !empty($image['ID'])): ?>
					<div class="section-plus-size__image-box image-box visible-mobile">
						<?php echo wp_get_attachment_image(
							$image['ID'],
							'full',
							false,
							[
								'class' => 'img',
								'alt' => $image['alt'] ?: esc_html($title),
								'loading' => 'eager'
							]
						); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>