<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_hero_page',
		'title' => 'Первый экран',
		'fields' => [
			[
				'key' => 'field_hero_page_title',
				'label' => 'Заголовок',
				'name' => 'hero_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
			],
			[
				'key' => 'field_hero_page_descr',
				'label' => 'Описание',
				'name' => 'hero_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_hero_image',
				'label' => 'Изображение (не обязательно)',
				'name' => 'hero_image',
				'type' => 'image',
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				],
				[
					'param' => 'post_template',
					'operator' => '!=',
					'value' => 'front-page.php',
				],
			],
		],
	]);
}