<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_look',
		'title' => 'Секция "Готовый образ" (Look)',
		'fields' => [
			[
				'key' => 'field_look_message',
				'label' => 'Инструкция',
				'type' => 'message',
				'message' => '<b>Секция образа со стилистом</b><br/>Позволяет загрузить общее фото образа (lookbook) и привязать до 4-х конкретных товаров WooCommerce, которые входят в этот комплект.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_look_badge',
				'label' => 'Подзаголовок / Бейдж',
				'name' => 'look_badge',
				'type' => 'text',
				'default_value' => 'Подборка стилиста',
			],
			[
				'key' => 'field_look_title',
				'label' => 'Заголовок',
				'name' => 'look_title',
				'type' => 'text',
				'default_value' => 'Готовый образ — без долгих поисков',
			],
			[
				'key' => 'field_look_descr',
				'label' => 'Описание',
				'name' => 'look_descr',
				'type' => 'textarea',
				'default_value' => 'Мы подобрали одежду и аксессуары, которые гармонично сочетаются между собой. Выбирайте весь комплект или только понравившиеся вещи.',
				'rows' => 3,
			],
			[
				'key' => 'field_look_image',
				'label' => 'Основное фото образа',
				'name' => 'look_image',
				'type' => 'image',
				'return_format' => 'id',
				'required' => 1,
			],
			[
				'key' => 'field_look_products',
				'label' => 'Товары комплекта',
				'name' => 'look_products',
				'type' => 'relationship',
				'post_type' => ['product'],
				'filters' => ['search'],
				'max' => 4,
				'return_format' => 'id',
				'instructions' => 'Выберите товары входящие в образ (рекомендуется до 4 позиций)',
			],
			[
				'key' => 'field_look_all_link',
				'label' => 'Ссылка "Все готовые образы"',
				'name' => 'look_all_link',
				'type' => 'link',
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