<?php
add_action(
	'wp_enqueue_scripts',
	function () {
		$theme = wp_get_theme();
		$handle = $theme->get_stylesheet(); // slug темы
	
		wp_enqueue_script(
			$handle . '-main',
			get_template_directory_uri() . '/assets/main.js',
			[],
			theme_asset_version('/assets/main.js'),
			true
		);
	}
);