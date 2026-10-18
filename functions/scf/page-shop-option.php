<?php
add_action('acf/init', function () {
	// Регистрируем страницу настроек
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title' => 'Настройки магазина',
			'menu_title' => 'Настройки магазина',
			'menu_slug' => 'opt-shop',
			'position' => 21,
			'icon_url' => 'dashicons-store',
		]);
	}

	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_shop_sort_titles',
		'title' => 'Заголовки сортировки',
		'fields' => [
			[
				'key' => 'field_shop_sort_message',
				'label' => 'Инструкция',
				'type' => 'message',
				'message' => '<b>Заголовки для разных вариантов сортировки</b><br/>Добавьте запись для каждого варианта сортировки. Если какой-то вариант не указан здесь — будет использовано стандартное название категории или страницы.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_shop_sort_titles',
				'label' => 'Список заголовков',
				'name' => 'shop_sort_titles',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Добавить вариант сортировки',
				'sub_fields' => [
					[
						'key' => 'field_sort_key',
						'label' => 'Ключ сортировки',
						'name' => 'sort_key',
						'type' => 'select',
						'choices' => [
							'date' => 'По новизне (date)',
							'price' => 'По возрастанию цены (price)',
							'price-desc' => 'По убыванию цены (price-desc)',
							'rating' => 'По рейтингу (rating)',
							'popularity' => 'По популярности (popularity)',
						],
						'default_value' => 'date',
						'allow_null' => 0,
						'multiple' => 0,
						'instructions' => 'Выберите, к какому типу сортировки относится этот заголовок',
					],
					[
						'key' => 'field_sort_title',
						'label' => 'Заголовок',
						'name' => 'sort_title',
						'type' => 'text',
						'placeholder' => 'Новинки',
						'instructions' => 'Отображается как H1 на странице',
					],
					[
						'key' => 'field_sort_descr',
						'label' => 'Описание',
						'name' => 'sort_descr',
						'type' => 'textarea',
						'rows' => 2,
						'new_lines' => 'wpautop',
						'placeholder' => 'Новые модели одежды, обуви и аксессуаров в коллекции STOCKHOLM.',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'opt-shop',
				],
			],
		],
	]);
});