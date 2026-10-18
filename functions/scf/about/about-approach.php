<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_about_approach',
		'title' => 'Секция "Наш подход"',
		'fields' => [
			[
				'key' => 'field_approach_title',
				'label' => 'Заголовок секции',
				'name' => 'approach_title',
				'type' => 'text',
				'default_value' => 'Наш подход',
				'placeholder' => 'Наш подход',
			],
			[
				'key' => 'field_approach_items',
				'label' => 'Пункты подхода',
				'name' => 'approach_items',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Добавить пункт',
				'sub_fields' => [
					[
						'key' => 'field_approach_item_title',
						'label' => 'Заголовок',
						'name' => 'approach_item_title',
						'type' => 'text',
						'instructions' => 'Короткий заголовок пункта',
					],
					[
						'key' => 'field_approach_item_descr',
						'label' => 'Описание',
						'name' => 'approach_item_descr',
						'type' => 'textarea',
						'rows' => 2,
						'new_lines' => 'wpautop',
					],
				],
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