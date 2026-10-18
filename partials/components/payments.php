<?php
$class = $args['class'] ?? '';
$payments = get_field('contacts_payment', 'option') ?? '';

if (!empty($payments)): ?>
	<div class="payments <?= esc_attr($class); ?> descr">
		<?php echo wp_kses_post($payments) ?>
	</div>
<?php endif; ?>