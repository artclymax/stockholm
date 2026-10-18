<?php
$class = $args['class'] ?? '';
$socials = get_field('contacts_socials', 'option') ?? [];

if (!empty($socials)):
	?>
	<div class="socials footer__socials-list <?= esc_attr($class) ?>">
		<?php foreach ($socials as $social):
			$social_link = $social['link'] ?? [];
			if (empty($social_link['url']))
				continue;
			?>
			<a class="footer__social-link btn-link" href="<?php echo esc_url($social_link['url']); ?>" target="_blank"
				rel="noopener noreferrer">
				<?php echo esc_html($social_link['title']); ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>