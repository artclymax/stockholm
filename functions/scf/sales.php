<?php
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array(
		'key' => 'group_page_sales',
		'title' => 'Страница "Товары со скидкой"',
		'fields' => array(
			array(
				'key' => 'field_sales_posts_per_page',
				'label' => 'Товаров на странице',
				'name' => 'sales_posts_per_page',
				'type' => 'number',
				'default_value' => 8,
				'min' => 1,
				'step' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'page-sales.php',
				),
			),
		),
	));
}
?>