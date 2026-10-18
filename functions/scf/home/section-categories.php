<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_categories',
		'title' => 'Секция "Категории товаров"',
		'fields' => [
			[
				'key' => 'field_section_categories_tab_content',
				'label' => 'Контент',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_section_categories_title',
				'label' => 'Заголовок',
				'name' => 'categories_title',
				'type' => 'text',
				'placeholder' => 'Всё для вашего гардероба',
			],
			[
				'key' => 'field_section_categories_descr',
				'label' => 'Описание',
				'name' => 'categories_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_section_categories_tab_items',
				'label' => 'Категории',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_section_categories_list',
				'label' => 'Список категорий',
				'name' => 'categories_list',
				'type' => 'repeater',
				'layout' => 'table',
				'button_label' => 'Добавить категорию',
				'instructions' => 'Оставьте пустым, чтобы автоматически показать последние 4 категории',
				'sub_fields' => [
					[
						'key' => 'field_category_title',
						'label' => 'Заголовок',
						'name' => 'title',
						'type' => 'text',
						'instructions' => 'Если не указывать, будет показано название категории',
					],
					[
						'key' => 'field_category_image',
						'label' => 'Картинка',
						'name' => 'image',
						'type' => 'image',
					],
					[
						'key' => 'field_category_select',
						'label' => 'Категория',
						'name' => 'category',
						'type' => 'taxonomy',
						'taxonomy' => 'product_cat',
						'field_type' => 'select',
						'return_format' => 'id',
						'allow_null' => 0,
						'instructions' => 'Выберите категорию товаров',
					],
				],
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