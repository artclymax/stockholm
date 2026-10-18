<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_product_look_card',
		'title' => 'Настройки для секции "Готовый образ"',
		'fields' => [
			[
				'key' => 'field_look_card_image',
				'label' => 'Фото для карточки образа',
				'name' => 'look_card_image',
				'type' => 'image',
				'instructions' => 'Если не загружено, будет выведена миниатюра товара.',
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				],
			],
		],
	]);
}
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_product_details',
		'title' => 'Дополнительные вкладки товара',
		'fields' => [
			[
				'key' => 'field_prod_composition_care',
				'label' => 'Состав и уход',
				'name' => 'prod_composition_care',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_prod_shipping_payment',
				'label' => 'Доставка и оплата',
				'name' => 'prod_shipping_payment',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_prod_returns_exchange',
				'label' => 'Возврат и обмен',
				'name' => 'prod_returns_exchange',
				'type' => 'wysiwyg',
			],
		],
		'location' => [
			[
				[
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
				],
			],
		],
	]);
}