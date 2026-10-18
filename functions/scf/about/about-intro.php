<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_about_intro',
		'title' => 'Секция "О магазине" - Введение',
		'fields' => [
			[
				'key' => 'field_about_intro_title',
				'label' => 'Заголовок',
				'name' => 'about_intro_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
			],
			[
				'key' => 'field_about_intro_descr',
				'label' => 'Описание',
				'name' => 'about_intro_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_about_intro_image',
				'label' => 'Изображение',
				'name' => 'about_intro_image',
				'type' => 'image',
			],
		],
		'location' => [
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-about.php',
				],
			],
		],
	]);
}