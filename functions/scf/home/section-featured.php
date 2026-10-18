<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_featured',
		'title' => 'Секция "Новинки"',
		'fields' => [
			[
				'key' => 'field_featured_message',
				'label' => 'Инструкция',
				'type' => 'message',
				'message' => '<b>Секция новинок</b><br/>Показывает последние продукты WooCommerce. Чтобы добавить товар в секцию, установите метку "Избранное" (звездочка) для товара.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_featured_title',
				'label' => 'Заголовок',
				'name' => 'featured_title',
				'type' => 'text',
			],
			[
				'key' => 'field_featured_descr',
				'label' => 'Описание',
				'name' => 'featured_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_featured_view_all_link',
				'label' => 'Ссылка "Смотреть все"',
				'name' => 'featured_view_all_link',
				'type' => 'link',
			],
			[
				'key' => 'field_featured_products_count',
				'label' => 'Количество продуктов',
				'name' => 'featured_products_count',
				'type' => 'number',
				'default_value' => 4,
				'instructions' => 'Показывает количество последних продуктов. Чтобы показывать все продукты, установите значение -1.',
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