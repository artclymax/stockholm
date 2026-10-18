<?php
$title = get_field('page_404_title', 'option') ?? 'Страница не найдена';
$descr = get_field('page_404_descr', 'option') ?? 'Возможно, страница была удалена, перемещена или адрес указан неверно.';
$btn = get_field('page_404_btn', 'option') ?? [url => home_url('/'), title => 'На главную'];
$btn_second = get_field('page_404_btn_second', 'option') ?? [];
$image = get_field('page_404_image', 'option') ?? null;

if (empty($btn['url'])) {
	$btn = [
		'url' => home_url('/'),
		'title' => 'На главную',
	];
}

$has_image = ($image && is_array($image) && !empty($image['ID']));
?>
<section class="page-404 section" id="page-404">
	<div class="container page-404__container section-gap">
		<div class="page-404__content">
			<div class="page-404__number title-1">404</div>
			<?php if ($title): ?>
				<h1 class="page-404__title title-2"><?php echo esc_html($title); ?></h1>
			<?php endif; ?>

			<?php if ($descr): ?>
				<div class="page-404__descr descr"><?php echo wp_kses_post($descr); ?></div>
			<?php endif; ?>

			<?php if (!empty($btn) || !empty($btn_second)): ?>

				<div class="page-404__btn-box">
					<?php if (!empty($btn) && !empty($btn['url'])): ?>
						<a class="page-404__btn btn" href="<?php echo esc_url($btn['url']); ?>" <?php if (!empty($btn['target'])): ?> target="<?php echo esc_attr($btn['target']); ?>" <?php endif; ?>><?php echo esc_html($btn['title']); ?></a>
					<?php endif; ?>
					<?php if (!empty($btn_second) && !empty($btn_second['url'])): ?>
						<a class="page-404__link btn-link" href="<?php echo esc_url($btn_second['url']); ?>" <?php if (!empty($btn_second['target'])): ?> target="<?php echo esc_attr($btn_second['target']); ?>" <?php endif; ?>><?php echo esc_html($btn_second['title']); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>
	</div>
	<?php if ($has_image): ?>
		<!--<div class="page-404__image image-box">
			<?php if ($image && is_array($image) && !empty($image['ID'])): ?>
				<?php echo wp_get_attachment_image(
					$image['ID'],
					'full',
					false,
					[
						'class' => 'img',
						'alt' => $image['alt'] ?: $title,
						'loading' => 'eager',
					]
				); ?>
			<?php endif; ?>
		</div>-->
	<?php endif; ?>
</section>