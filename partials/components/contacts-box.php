<?php
$class = $args['class'] ?? '';
$time = get_field('contacts_time', 'option') ?? '';
?>
<div class="contacts-box <?= esc_attr($class); ?>">
	<div class="contacts-box__wrapper">
		<?php get_template_part('partials/components/phones'); ?>
		<?php get_template_part('partials/components/address', null, ['is_address' => true, 'descr' => $time]); ?>
		<?php get_template_part('partials/components/emails'); ?>
	</div>
	<div class="contacts-box__socials">
		<h4 class="contacts-box__title">
			Напишите нам в мессенджере
		</h4>
		<?php get_template_part('partials/components/socials'); ?>
	</div>

</div>