<?php
add_action('init', function () {
	register_post_type('looks', [
		'label' => 'Готовые образы',
		'labels' => [
			'name' => 'Готовые образы',
			'singular_name' => 'Образ',
			'add_new' => 'Добавить образ',
			'add_new_item' => 'Добавить новый образ',
			'edit_item' => 'Редактировать образ',
			'new_item' => 'Новый образ',
			'view_item' => 'Посмотреть образ',
			'search_items' => 'Найти образ',
			'not_found' => 'Образы не найдены',
			'not_found_in_trash' => 'В корзине образов не найдено',
			'menu_name' => 'Готовые образы',
		],
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'menu_position' => 15,
		'menu_icon' => 'dashicons-camera',
		'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
		'has_archive' => 'looks',
		'rewrite' => ['slug' => 'looks'],
	]);
});