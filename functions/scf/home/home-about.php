<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_home_about',
		'title' => 'Секция "О магазине"',
		'fields' => [
			[
				'key' => 'field_home_about_tab_content',
				'label' => 'Контент',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_home_about_label',
				'label' => 'Надзаголовок',
				'name' => 'home_about_label',
				'type' => 'text',
			],
			[
				'key' => 'field_home_about_title',
				'label' => 'Заголовок',
				'name' => 'home_about_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
				'instructions' => 'Основной заголовок',
			],
			[
				'key' => 'field_home_about_descr',
				'label' => 'Описание',
				'name' => 'home_about_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_home_about_btn',
				'label' => 'Кнопка',
				'name' => 'home_about_btn',
				'type' => 'link',
			],
			[
				'key' => 'field_home_about_tab_media',
				'label' => 'Медиа',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_home_about_image',
				'label' => 'Изображение',
				'name' => 'home_about_image',
				'type' => 'image',
			],
		],
		'location' => [
			[
				[
					'param' => 'post_template',
					'operator' => '==',
					'value' => 'front-page.php',
				],
			],
		],
	]);
}