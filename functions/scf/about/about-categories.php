<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_about_categories',
		'title' => 'Секция "Все необходимое для гардероба"',
		'fields' => [
			[
				'key' => 'field_categories_message',
				'label' => 'Инструкция',
				'type' => 'message',
				'message' => '<b>Секция с категориями товаров</b><br/>Добавьте ссылки на категории. Можно выбрать существующую категорию товаров WooCommerce или ввести кастомную ссылку. Поле "Текст ссылки" позволяет переопределить отображаемое название.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_categories_title',
				'label' => 'Заголовок',
				'name' => 'categories_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
			],
			[
				'key' => 'field_categories_list',
				'label' => 'Список категорий',
				'name' => 'categories_list',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Добавить категорию',
				'sub_fields' => [
					[
						'key' => 'field_category_term',
						'label' => 'Категория товара',
						'name' => 'category_term',
						'type' => 'taxonomy',
						'taxonomy' => 'product_cat',
						'field_type' => 'select',
						'return_format' => 'id',
						'instructions' => 'Выберите категорию из списка товаров WooCommerce',
						'allow_null' => 1,
					],
					[
						'key' => 'field_custom_link',
						'label' => 'Или кастомная ссылка',
						'name' => 'custom_link',
						'type' => 'link',
						'instructions' => 'Если не выбрана категория выше, можно ввести ссылку вручную',
					],
					[
						'key' => 'field_link_title',
						'label' => 'Текст ссылки',
						'name' => 'link_title',
						'type' => 'text',
						'instructions' => 'Оставьте пустым, чтобы использовать название категории или кастомной ссылки автоматически',
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