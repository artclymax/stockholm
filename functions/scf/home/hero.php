<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_hero_collection',
		'title' => 'Первый экран',
		'fields' => [
			[
				'key' => 'field_hero_tab_content',
				'label' => 'Контент',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_hero_label',
				'label' => 'Надзаголовок',
				'name' => 'hero_label',
				'type' => 'text',
			],
			[
				'key' => 'field_hero_title',
				'label' => 'Заголовок',
				'name' => 'hero_title',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_hero_descr',
				'label' => 'Описание',
				'name' => 'hero_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_hero_tab_buttons',
				'label' => 'Кнопки',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_hero_btn_women',
				'label' => 'Кнопка акцентная',
				'name' => 'hero_btn_women',
				'type' => 'link',
			],
			[
				'key' => 'field_hero_btn_men',
				'label' => 'Кнопка вторая',
				'name' => 'hero_btn_men',
				'type' => 'link',
			],
			[
				'key' => 'field_hero_link_plus_size',
				'label' => 'Ссылка',
				'name' => 'hero_link_plus_size',
				'type' => 'link',
			],
			[
				'key' => 'field_hero_tab_media',
				'label' => 'Медиа',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_hero_bg_big',
				'label' => 'Фон Desktop',
				'name' => 'hero_bg_big',
				'type' => 'image',
			],
			[
				'key' => 'field_hero_bg_small',
				'label' => 'Фон Mobile',
				'name' => 'hero_bg_small',
				'type' => 'image',
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