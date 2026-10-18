<?php

$page = $args['page'] ?? get_queried_object();

// Контент
$label = get_field('hero_label', $page) ?? '';
$title = get_field('hero_title', $page) ?? '';
$descr = get_field('hero_descr', $page) ?? '';

// Кнопки
$btn_women = get_field('hero_btn_women', $page) ?? [];
$btn_men = get_field('hero_btn_men', $page) ?? [];
$link_plus_size = get_field('hero_link_plus_size', $page) ?? [];

$bg_big = get_field('hero_bg_big', $page) ?? null;
$bg_small = get_field('hero_bg_small', $page) ?? null;

if ($title || $descr || ($bg_big && !empty($bg_big['ID'])) || ($bg_small && !empty($bg_small['ID']))):
	?>
	<section class="section hero" id="hero">
		<div class="hero__container container">
			<div class="hero__content">
				<?php if ($label): ?>
					<div class="hero__label label">
						<?php echo esc_html($label); ?>
					</div>
				<?php endif; ?>

				<?php if ($title): ?>
					<h1 class="hero__title descr title-2">
						<?php echo wp_kses_post($title); ?>
					</h1>
				<?php endif; ?>

				<?php if ($descr): ?>
					<div class="hero__descr descr">
						<?php echo wp_kses_post($descr); ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($btn_women) || !empty($btn_men)): ?>
					<div class="hero__buttons">
						<?php if (!empty($btn_women) && !empty($btn_women['url'])): ?>
							<a class="btn" href="<?php echo esc_url($btn_women['url']); ?>" <?php if (!empty($btn_women['target'])): ?>target="
					<?php echo esc_attr($btn_women['target']); ?>" <?php endif; ?>>
								<?php echo esc_html($btn_women['title']); ?>
							</a>
						<?php endif; ?>
						<?php if (!empty($btn_men) && !empty($btn_men['url'])): ?>
							<a class="btn btn--stroke" href="<?php echo esc_url($btn_men['url']); ?>" <?php if (!empty($btn_men['target'])): ?>target="
					<?php echo esc_attr($btn_men['target']); ?>" <?php endif; ?>>
								<?php echo esc_html($btn_men['title']); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($link_plus_size) && !empty($link_plus_size['url'])): ?>
					<div class="hero__link-box">
						<a class="hero__link btn-link" href="<?php echo esc_url($link_plus_size['url']); ?>" <?php if (!empty($link_plus_size['target'])): ?>target="<?php echo esc_attr($link_plus_size['target']); ?>" <?php endif; ?>>
							<?php echo esc_html($link_plus_size['title']); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if (($bg_big && !empty($bg_big['ID'])) || ($bg_small && !empty($bg_small['ID']))): ?>
			<div class="hero__bg-box image-box">
				<?php if ($bg_big && !empty($bg_big['ID'])): ?>
					<?php echo wp_get_attachment_image(
						$bg_big['ID'],
						'full',
						false,
						[
							'class' => 'hero__bg-big' . (!empty($bg_small['ID']) ? ' hidden-mobile' : ''),
							'alt' => $bg_big['alt'] ?: '',
							'loading' => 'eager'
						]
					); ?>
				<?php endif; ?>
				<?php if ($bg_small && !empty($bg_small['ID'])): ?>
					<?php echo wp_get_attachment_image(
						$bg_small['ID'],
						'full',
						false,
						[
							'class' => 'hero__bg-small' . (!empty($bg_big['ID']) ? ' visible-mobile' : ''),
							'alt' => $bg_small['alt'] ?: '',
							'loading' => 'eager'
						]
					); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>