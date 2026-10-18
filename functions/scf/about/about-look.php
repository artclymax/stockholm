<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_about_look',
		'title' => 'Секция "Собираем образ целиком"',
		'fields' => [
			[
				'key' => 'field_about_look_title',
				'label' => 'Заголовок',
				'name' => 'look_title',
				'type' => 'text',
				'placeholder' => 'Собираем образ целиком',
			],
			[
				'key' => 'field_about_look_descr',
				'label' => 'Описание',
				'name' => 'look_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_about_look_button',
				'label' => 'Кнопка',
				'name' => 'look_button',
				'type' => 'link',
			],
			[
				'key' => 'field_about_look_image',
				'label' => 'Изображение',
				'name' => 'look_image',
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