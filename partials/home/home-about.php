<?php
$page = $args['page'] ?? get_queried_object();

// Контент
$label = get_field('home_about_label', $page) ?? '';
$title = get_field('home_about_title', $page) ?? '';
$descr = get_field('home_about_descr', $page) ?? '';
$btn = get_field('home_about_btn', $page) ?? [];

// Медиа
$image = get_field('home_about_image', $page) ?? null;

if ($title || $descr || ($image && !empty($image['ID']))):
	?>
	<section class="section home-about" id="home-about">
		<div class="container home-about__container">
			<div class="home-about__content">
				<?php get_template_part('partials/section-header', null, [
					'label' => $label,
					'title' => $title,
					'descr' => $descr
				]); ?>

				<?php if (!empty($btn) && !empty($btn['url'])): ?>
					<a class="home-about__btn btn" href="<?php echo esc_url($btn['url']); ?>" <?php if (!empty($btn['target'])): ?>target="
				<?php echo esc_attr($btn['target']); ?>" <?php endif; ?>>
						<?php echo esc_html($btn['title']); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ($image && !empty($image['ID'])): ?>
				<div class="home-about__image-box image-box">
					<?php echo wp_get_attachment_image(
						$image['ID'],
						'full',
						false,
						[
							'class' => 'img',
							'alt' => $image['alt'] ?: esc_html($title),
							'loading' => 'lazy'
						]
					); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>