<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_section_plus_size',
		'title' => 'Секция "Коллекция больших размеров"',
		'fields' => [
			[
				'key' => 'field_section_plus_size_tab_content',
				'label' => 'Контент',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_section_plus_size_label',
				'label' => 'Надзаголовок',
				'name' => 'section_plus_size_label',
				'type' => 'text',
			],
			[
				'key' => 'field_section_plus_size_title',
				'label' => 'Заголовок',
				'name' => 'section_plus_size_title',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
				'instructions' => 'Основной заголовок',
			],
			[
				'key' => 'field_section_plus_size_descr',
				'label' => 'Описание',
				'name' => 'section_plus_size_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_section_plus_size_btn',
				'label' => 'Кнопка',
				'name' => 'section_plus_size_btn',
				'type' => 'link',
			],
			[
				'key' => 'field_section_plus_size_tab_features',
				'label' => 'Преимущества',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_section_plus_size_features',
				'label' => 'Список преимуществ',
				'name' => 'section_plus_size_features',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Добавить преимущество',
				'sub_fields' => [
					[
						'key' => 'field_section_plus_size_feature_text',
						'label' => 'Текст',
						'name' => 'text',
						'type' => 'text',
					],
				],
			],
			[
				'key' => 'field_section_plus_size_tab_media',
				'label' => 'Медиа',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_section_plus_size_image',
				'label' => 'Изображение большое',
				'name' => 'section_plus_size_image',
				'type' => 'image',
			],
			[
				'key' => 'field_section_plus_size_image_mobile',
				'label' => 'Изображение маленькое',
				'name' => 'section_plus_size_image_mobile',
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