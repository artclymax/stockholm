<?php
add_action('acf/init', function () {

	if (function_exists('acf_add_options_page')) {
		acf_add_options_page(array(
			'page_title' => 'Общие секции',
			'menu_title' => 'Общие секции',
			'menu_slug' => 'opt-page',
			'position' => 15,
		));
	}

	if (!function_exists('acf_add_local_field_group')) {
		return;
	}
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group([
			'key' => 'group_popular_products',
			'title' => 'Секция "Популярные изделия"',
			'fields' => [
				[
					'key' => 'field_popular_message',
					'label' => 'Инструкция',
					'name' => '',
					'type' => 'message',
					'message' => '<b>Популярные изделия.</b> В слайдер попадают товары с меткой "Избранное" (featured). Если таких товаров нет — выводятся 8 последних добавленных товаров.',
					'new_lines' => 'wpautop',
					'esc_html' => 0,
				],
				[
					'key' => 'field_popular_tab',
					'label' => 'Популярные изделия',
					'name' => '',
					'type' => 'tab',
					'placement' => 'top',
					'endpoint' => 0,
				],

				[
					'key' => 'field_popular_title',
					'label' => 'Заголовок секции',
					'name' => 'popular_products_title',
					'type' => 'text',
					'placeholder' => 'Популярные изделия',
				],
				[
					'key' => 'field_popular_btn_text',
					'label' => 'Текст кнопки карточки',
					'name' => 'popular_products_btn_text',
					'type' => 'text',
					'placeholder' => 'Подробнее',
				],
				[
					'key' => 'field_popular_descr',
					'label' => 'Описание секции',
					'name' => 'popular_products_descr',
					'type' => 'textarea',
					'rows' => 3,
					'new_lines' => 'wpautop',
				],
				[
					'key' => 'field_popular_btn',
					'label' => 'Кнопка "Перейти в каталог"',
					'name' => 'popular_products_btn',
					'type' => 'link',
				],
			],
			'location' => [
				[
					[
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'opt-page',
					],
				],
			],
		]);
	}

	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group([
			'key' => 'group_cta_section',
			'title' => 'Секция "Не нашли нужное изделие?"',
			'fields' => [
				[
					'key' => 'field_cta_message',
					'label' => 'Инструкция',
					'name' => '',
					'type' => 'message',
					'message' => 'Если поле оставлено пустым — значение берётся из «Общие секции».',
					'new_lines' => 'wpautop',
					'esc_html' => 0,
				],
				[
					'key' => 'field_cta_tab_content',
					'label' => 'Контент',
					'name' => '',
					'type' => 'tab',
					'placement' => 'top',
					'endpoint' => 0,
				],
				[
					'key' => 'field_cta_title',
					'label' => 'Заголовок',
					'name' => 'cta_title',
					'type' => 'text',
					'placeholder' => 'Не нашли нужное изделие?',
				],
				[
					'key' => 'field_cta_descr',
					'label' => 'Описание',
					'name' => 'cta_descr',
					'type' => 'wysiwyg',
				],
				[
					'key' => 'field_cta_btn',
					'label' => 'Кнопка',
					'name' => 'cta_btn',
					'type' => 'link',
				],
				[
					'key' => 'field_cta_tab_media',
					'label' => 'Медиа',
					'name' => '',
					'type' => 'tab',
					'placement' => 'top',
					'endpoint' => 0,
				],
				[
					'key' => 'field_cta_image',
					'label' => 'Иконка',
					'name' => 'cta_image',
					'type' => 'image',
				],
			],
			'location' => [
				[
					[
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'opt-page',
					],
				],
				[
					[
						'param' => 'post_template',
						'operator' => '==',
						'value' => 'front-page.php',
					],
				],
				[
					[
						'param' => 'post_template',
						'operator' => '==',
						'value' => 'archive-projects.php',
					],
				],
				[
					[
						'param' => 'post_template',
						'operator' => '==',
						'value' => 'page-services.php',
					],
				],
			],
		]);
	}
});
?>