<?php
add_action('acf/init', function () {

	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title' => 'Шапка',
			'menu_title' => 'Шапка',
			'menu_slug' => 'opt-header',
			'position' => 21,
		]);
	}

	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_header_settings',
		'title' => 'Поля шапки',
		'fields' => [
			[
				'key' => 'field_header_info_message',
				'label' => 'Инструкция',
				'name' => '',
				'type' => 'message',
				'message' => '<b>Логотип</b> и <b>меню</b> настраиваются в настройках темы: ' .
					'<a href="' . esc_url(admin_url('admin.php?page=verda-settings&tab=general')) . '" target="_blank" rel="noopener">Логотип</a> · ' .
					'<a href="' . esc_url(admin_url('admin.php?page=verda-settings&tab=menus')) . '" target="_blank" rel="noopener">Меню</a>.<br>' .
					'<b>Телефон, время работы и соцсети</b> берутся из <a href="' . esc_url(admin_url('admin.php?page=opt-contacts')) . '" target="_blank" rel="noopener">Контактных данных</a>.<br>',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_header_delivery',
				'label' => 'Подпись в шапке',
				'name' => 'header_delivery',
				'type' => 'text',
				'instructions' => 'Например: "Доставка по всей Беларуси"',
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'opt-header',
				],
			],
		],
	]);

});