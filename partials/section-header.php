<?php
$class = $args['class'] ?? '';
$label = $args['label'] ?? '';
$title = $args['title'] ?? '';
$descr = $args['descr'] ?? '';

if (!empty($label) || !empty($title) || !empty($descr)): ?>
	<div class="section-header <?= esc_attr($class) ?>">
		<?php if ($label): ?>
			<div class="section-header__label label">
				<?php echo esc_html($label); ?>
			</div>
		<?php endif; ?>

		<?php if ($title): ?>
			<h2 class="section-header__title title-2 descr">
				<?php echo wp_kses_post($title); ?>
			</h2>
		<?php endif; ?>

		<?php if ($descr): ?>
			<div class="section-header__descr descr">
				<?php echo wp_kses_post($descr); ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>