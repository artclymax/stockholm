<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_contacts_gallery',
		'title' => 'Секция "Галерея магазина"',
		'fields' => [
			[
				'key' => 'field_gallery_title',
				'label' => 'Заголовок',
				'name' => 'gallery_title',
				'type' => 'text',
				'default_value' => 'Ждем вас в STOCK HOLM',
			],
			[
				'key' => 'field_gallery_images',
				'label' => 'Изображения',
				'name' => 'gallery_images',
				'type' => 'gallery',
			],
		],
		'location' => [
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-contacts.php',
				],
			],
		],
	]);
}