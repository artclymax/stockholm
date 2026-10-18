<?php
add_action('acf/init', function () {

	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title' => 'Подвал / Футер',
			'menu_title' => 'Подвал / Футер',
			'menu_slug' => 'opt-footer',
			'position' => 22,
		]);
	}

	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_footer_settings',
		'title' => 'Настройки футера',
		'fields' => [
			[
				'key' => 'field_footer_info_message',
				'label' => 'Инструкция',
				'name' => '',
				'type' => 'message',
				'message' => '<b>Логотип и подпись под ним</b> — в <a href="' . esc_url(admin_url('admin.php?page=opt-header')) . '" target="_blank" rel="noopener">Шапке</a>.<br>' .
					'<b>Контакты</b> (телефон, email, соцсети, время работы) — в <a href="' . esc_url(admin_url('admin.php?page=opt-contacts')) . '" target="_blank" rel="noopener">Контактных данных</a>.<br>' .
					'<b>Меню</b> — в разделе <a href="' . esc_url(admin_url('nav-menus.php')) . '" target="_blank" rel="noopener">Внешний вид → Меню</a> (расположение "footer-menu").<br>' .
					'Здесь настраиваются только заголовки колонок, форма, ссылки и копирайт.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_footer_tab_top',
				'label' => 'Верхняя часть',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_footer_descr',
				'label' => 'Описание',
				'name' => 'footer_descr',
				'type' => 'textarea',
				'rows' => 2,
				'new_lines' => 'wpautop',
			],
			[
				'key' => 'field_footer_btn',
				'label' => 'Кнопка',
				'name' => 'footer_btn',
				'type' => 'link',
			],
			[
				'key' => 'field_footer_tab_bottom',
				'label' => 'Нижняя часть',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],

			[
				'key' => 'field_footer_copy',
				'label' => 'Дополнительный текст копирайта',
				'name' => 'footer_copy',
				'type' => 'text',
				'placeholder' => 'Все права защищены',
			],
			[
				'key' => 'field_footer_company',
				'label' => 'Название компании',
				'name' => 'footer_company',
				'type' => 'text',
				'instructions' => 'Отображается в копирайте. Пусто — подставится название сайта.',
			],
			[
				'key' => 'field_footer_copy',
				'label' => 'Текст копирайта',
				'name' => 'footer_copy',
				'type' => 'text',
				'instructions' => 'Текст после названия и года (например: «Все права защищены»).',
			],
			[
				'key' => 'field_footer_links',
				'label' => 'Ссылки в футере',
				'name' => 'footer_links',
				'type' => 'repeater',
				'layout' => 'table',
				'collapsed' => 'field_footer_links_link',
				'button_label' => 'Добавить ссылку',
				'sub_fields' => [
					[
						'key' => 'field_footer_links_link',
						'label' => 'Ссылка',
						'name' => 'footer_links_link',
						'type' => 'link',
						'return_format' => 'array',
					],
				],
			],
			[
				'key' => 'field_footer_legal_info',
				'label' => 'Юридическая информация',
				'name' => 'footer_legal_info',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_footer_registration_info',
				'label' => 'Регистрационная информация',
				'name' => 'footer_registration_info',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_footer_payments',
				'label' => 'Платежные системы',
				'name' => 'footer_payments',
				'type' => 'repeater',
				'layout' => 'table',
				'button_label' => 'Добавить платежную систему',
				'sub_fields' => [
					[
						'key' => 'field_payment_image',
						'label' => 'Логотип',
						'name' => 'image',
						'type' => 'image',
					],
				],
			],

		],
		'location' => [
			[
				[
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'opt-footer',
				],
			],
		],
	]);

});