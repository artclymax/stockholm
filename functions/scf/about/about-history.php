<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_about_history',
		'title' => 'Секция "История основателя"',
		'fields' => [
			[
				'key' => 'field_history_label',
				'label' => 'Надзаголовок (лейбл)',
				'name' => 'history_label',
				'type' => 'text',
				'instructions' => 'Текст над заголовком',
			],
			[
				'key' => 'field_history_title',
				'label' => 'Заголовок',
				'name' => 'history_title',
				'type' => 'text',
			],
			[
				'key' => 'field_history_descr',
				'label' => 'Описание',
				'name' => 'history_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_history_quote',
				'label' => 'Цитата',
				'name' => 'history_quote',
				'type' => 'wysiwyg',
				'instructions' => 'Выделенная цитата (отображается крупным шрифтом)',
			],
			[
				'key' => 'field_history_founder_name',
				'label' => 'Имя основателя',
				'name' => 'history_founder_name',
				'type' => 'text',
				'instructions' => 'Подпись под цитатой',
			],
			[
				'key' => 'field_history_image',
				'label' => 'Фото основателя',
				'name' => 'history_image',
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