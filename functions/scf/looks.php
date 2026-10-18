<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_page_looks',
		'title' => 'Образов на странице',
		'fields' => [
			[
				'key' => 'field_page_looks_posts_per_page',
				'label' => 'Образов на странице',
				'name' => 'looks_posts_per_page',
				'type' => 'number',
				'default_value' => 4,
				'min' => 1,
				'step' => 1,
				'instructions' => 'Сколько карточек загружается при первом открытии и при каждом нажатии "Показать ещё"',
			],
		],
		'location' => [
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-looks.php',
				],
			],
		],
	]);
}

if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_single_look',
		'title' => 'Товары в образе',
		'fields' => [
			[
				'key' => 'field_single_look_products',
				'label' => 'Список товаров',
				'name' => 'look_products',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Добавить товар',
				'sub_fields' => [
					[
						'key' => 'field_look_product',
						'label' => 'Товар',
						'name' => 'product',
						'type' => 'post_object',
						'post_type' => ['product'],
						'return_format' => 'id',
						'instructions' => 'Выберите товар из каталога WooCommerce',
					],
					[
						'key' => 'field_look_card_image',
						'label' => 'Фото товара для карточки',
						'name' => 'look_card_image',
						'type' => 'image',
						'instructions' => 'Опционально. Если не загружено — будет использовано изображение товара из WooCommerce',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'looks',
				],
			],
		],
	]);
}