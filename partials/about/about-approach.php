<?php

$class = $args['class'] ?? '';
$page = get_queried_object();

$title = get_field('approach_title', $page) ?? 'Наш подход';
$items = get_field('approach_items', $page) ?? [];
?>

<?php if (!empty($items)): ?>
	<section class="section section-about-approach <?php echo esc_attr($class); ?>" id="section-about-approach">
		<div class="container section-about-approach__container section-gap">

			<?php if ($title): ?>
				<h2 class="section-about-approach__title title-2">
					<?php echo esc_html($title); ?>
				</h2>
			<?php endif; ?>

			<?php if (!empty($items)): ?>
				<div class="section-about-approach__grid">
					<?php foreach ($items as $index => $item):
						$number = ($index + 1) > 9 ? ($index + 1) : '0' . ($index + 1);
						$item_title = $item['approach_item_title'] ?? '';
						$item_descr = $item['approach_item_descr'] ?? '';
						?>
						<div class="section-about-approach__item approach-item">
							<div class="approach-item__number text-display">
								<?php echo esc_html($number); ?>
							</div>

							<?php if ($item_title): ?>
								<h3 class="approach-item__title title-3">
									<?php echo esc_html($item_title); ?>
								</h3>
							<?php endif; ?>

							<?php if ($item_descr): ?>
								<div class="approach-item__descr descr">
									<?php echo wp_kses_post($item_descr); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>