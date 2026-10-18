<?php
$class = $args['class'] ?? '';
$page = get_queried_object();

// Получаем данные из ACF полей
$title = get_field('gallery_title', $page) ?? '';
$gallery_images = get_field('gallery_images', $page) ?? [];
?>

<?php if (!empty($gallery_images) || $title): ?>
	<section class="section section-contacts-gallery <?php echo esc_attr($class); ?>" id="section-contacts-gallery">
		<div class="container section-contacts-gallery__container section-gap">

			<?php if ($title): ?>
				<h2 class="section-contacts-gallery__title title-2 descr">
					<?php echo wp_kses_post($title); ?>
				</h2>
			<?php endif; ?>

			<?php if (!empty($gallery_images)): ?>
				<div class="swiper section-swiper section-contacts-gallery__swiper" data-swiper='{
						  "slidesPerView": 1,
						  "spaceBetween": 20,
						  "rewind": true,
						  "breakpoints": {
								"768": {
									 "slidesPerView": 2
								},
								"1024": {
									 "slidesPerView": 3
								}
						  }
					  }'>

					<div class="swiper-wrapper section-swiper__wrapper">
						<?php foreach ($gallery_images as $image):
							if (!is_array($image) || empty($image['ID']))
								continue;
							?>
							<div class="swiper-slide section-contacts-gallery__slide">
								<a class="section-contacts-gallery__image-box image-box" href="<?= esc_url($image['url']); ?>"
									target="_blank" data-fancybox>
									<?php echo wp_get_attachment_image(
										$image['ID'],
										'full',
										false,
										array(
											'class' => 'img',
											'alt' => $image['alt'] ?: '',
											'loading' => 'lazy'
										)
									); ?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Буллеты (пагинация) -->
					<div class="section-contacts-gallery__pagination swiper-pagination"
						data-swiper-pagination=".section-contacts-gallery__swiper"></div>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>