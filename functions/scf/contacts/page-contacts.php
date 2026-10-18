<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group([
		'key' => 'group_page_contacts',
		'title' => 'Страница "Контакты"',
		'fields' => [
			[
				'key' => 'field_contacts_page_message',
				'label' => 'Инструкция',
				'type' => 'message',
				'message' => '<b>Настройки страницы контактов</b><br/>Основные контактные данные (адрес, телефон, email, соцсети, карта) заполняются в разделе <strong>"Контактные данные"</strong> в левом меню админки. Здесь можно настроить заголовки и блок помощи.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			],
			[
				'key' => 'field_contacts_page_title',
				'label' => 'Заголовок страницы',
				'name' => 'contacts_page_title',
				'type' => 'text',
				'default_value' => 'Магазин STOCK HOLM',
			],
			[
				'key' => 'field_contacts_map_title',
				'label' => 'Заголовок карты',
				'name' => 'contacts_map_title',
				'type' => 'text',
				'default_value' => 'Как нас найти',
				'placeholder' => 'Как нас найти',
			],
			[
				'key' => 'field_contacts_tab_help',
				'label' => 'Блок помощи',
				'name' => '',
				'type' => 'tab',
				'placement' => 'top',
				'endpoint' => 0,
			],
			[
				'key' => 'field_contacts_help_title',
				'label' => 'Заголовок',
				'name' => 'contacts_help_title',
				'type' => 'text',
				'default_value' => 'Нужна помощь с выбором?',
			],
			[
				'key' => 'field_contacts_help_descr',
				'label' => 'Описание',
				'name' => 'contacts_help_descr',
				'type' => 'wysiwyg',
			],
			[
				'key' => 'field_contacts_help_button',
				'label' => 'Кнопка',
				'name' => 'contacts_help_button',
				'type' => 'link',
			],
			[
				'key' => 'field_contacts_help_instagram',
				'label' => 'Ссылка на Instagram',
				'name' => 'contacts_help_instagram',
				'type' => 'link',
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