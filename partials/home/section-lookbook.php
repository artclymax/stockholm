<?php
$page = $args['page'] ?? get_queried_object();

$badge = get_field('look_badge', $page) ?? 'Подборка стилиста';
$title = get_field('look_title', $page) ?? 'Готовый образ — без долгих поисков';
$descr = get_field('look_descr', $page) ?? 'Мы подобрали одежду и аксессуары, которые гармонично сочетаются между собой. Выбирайте весь комплект или только понравившиеся вещи.';
$look_image_id = get_field('look_image', $page);
$product_ids = get_field('look_products', $page);
$all_link = get_field('look_all_link', $page);

if (empty($all_link)) {
	$all_link = [
		'url' => wc_get_page_permalink('shop'),
		'title' => 'Все готовые образы',
	];
}

wp_enqueue_script('wc-add-to-cart-variation');
?>

<?php if (!empty($product_ids)): ?>
	<section class="section section-look" id="section-look">
		<div class="container section-look__container section-gap">
			<div class="section-look__layout">
				<?php get_template_part('partials/section-header', null, [
					'label' => $badge,
					'title' => $title,
					'descr' => $descr
				]); ?>

				<!-- Левая колонка: Главный образ -->
				<div class="section-look__preview">
					<div class="section-look__image-wrap image-box">
						<?php if ($look_image_id): ?>
							<?php echo wp_get_attachment_image($look_image_id, 'full', false, ['class' => 'section-look__image img']); ?>
						<?php else: ?>
							<?php echo wc_placeholder_img('large'); ?>
						<?php endif; ?>
					</div>
				</div>

				<!-- Правая колонка -->
				<div class="section-look__content woocommerce">
					<!-- Список товаров в образе -->
					<div class="section-look__items">
						<?php
						$index = 1;
						foreach ($product_ids as $pid):
							$prod = wc_get_product($pid);
							if (!$prod)
								continue;
							$is_var = $prod->is_type('variable');
							$item_number = sprintf('%02d', $index);
							$is_first = ($index === 1);
							?>
							<div
								class="look-item <?php echo $is_var ? 'look-item--variable' : 'look-item--simple'; ?> <?php echo $is_first ? 'is-open' : ''; ?>"
								data-product-id="<?php echo esc_attr($pid); ?>" data-is-variable="<?php echo $is_var ? '1' : '0'; ?>"
								id="look-item-<?php echo esc_attr($pid); ?>">

								<div class="look-item__top">
									<a class="look-item__thumb image-box" href="<?php echo esc_url($prod->get_permalink()); ?>">
										<?php
										// 1. Проверяем наличие специального фото для карточки лука
										$look_card_img_id = get_field('look_card_image', $pid);

										if ($look_card_img_id) {
											echo wp_get_attachment_image(
												$look_card_img_id['ID'],
												'medium',
												false,
												array(
													'class' => 'img',
													'alt' => esc_attr($prod->get_name()),
													'loading' => 'lazy',
												)
											);
										} else {
											// 2. Фолбэк на стандартную миниатюру WooCommerce
											if (has_post_thumbnail($pid)) {
												echo get_the_post_thumbnail(
													$pid,
													'thumbnail',
													array(
														'class' => 'img',
														'alt' => esc_attr($prod->get_name()),
														'loading' => 'lazy',
													)
												);
											} else {
												echo wc_placeholder_img('thumbnail');
											}
										}
										?>
									</a>

									<div class="look-item__wrapper">
										<div class="look-item__header">
											<span class="look-item__number">
												<?php echo esc_html($item_number); ?>
											</span>

											<div class="look-item__info">
												<h4 class="look-item__name title-5">
													<a href="<?php echo esc_url($prod->get_permalink()); ?>">
														<?php echo esc_html($prod->get_name()); ?>
													</a>
												</h4>
												<div class="look-item__price">
													<?php echo $prod->get_price_html(); ?>
												</div>
											</div>

											<div class="look-item__actions">
												<?php if ($is_var): ?>
													<button type="button" class="look-item__toggle">
														<span class="look-item__toggle-label">Выбрать размер</span>
														<span class="look-item__toggle-icon"></span>
													</button>
												<?php else: ?>
													<!-- Прямая кнопка добавления в корзину для простого товара без ссылки "выберите параметры" -->
													<div class="look-item__simple-action">
														<a href="<?php echo esc_url($prod->add_to_cart_url()); ?>" data-quantity="1"
															class="button product_type_simple add_to_cart_button ajax_add_to_cart"
															data-product_id="<?php echo esc_attr($prod->get_id()); ?>"
															data-product_sku="<?php echo esc_attr($prod->get_sku()); ?>"
															aria-label="<?php echo esc_attr($prod->add_to_cart_description()); ?>"
															rel="nofollow">
															В корзину
														</a>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<!-- Форма вариаций WooCommerce -->
										<?php if ($is_var): ?>
											<div class="look-item__dropdown">
												<?php
												global $product;
												$product = $prod;
												woocommerce_variable_add_to_cart();
												?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php
							$index++;
						endforeach;
						wp_reset_postdata();
						?>
					</div>

					<div class="section-look__footer">
						<button type="button" class="btn btn-primary js-look-buy-all">
							Добавить образ в корзину
						</button>
						<?php if (!empty($all_link)): ?>
							<a href="<?php echo esc_url($all_link['url']); ?>" class="btn btn-outline btn--stroke">
								<?php echo esc_html($all_link['title']); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>

		<!-- Модальное окно для выбора невыбранных размеров -->
		<div class="look-modal" id="look-missing-modal">
			<div class="look-modal__overlay"></div>
			<div class="look-modal__dialog">
				<button type="button" class="look-modal__close" aria-label="Закрыть">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M18.75 6.81984L17.1802 5.25L12 10.4302L6.81984 5.25L5.25 6.81984L10.4302 12L5.25 17.1802L6.81984 18.75L12 13.5698L17.1802 18.75L18.75 17.1802L13.5698 12L18.75 6.81984Z"
							fill="currentColor" />
					</svg>
				</button>
				<h3 class="look-modal__title title-3">Выберите параметры комплекта</h3>
				<p class="look-modal__subtitle">Пожалуйста, выберите размер для указанных позиций:</p>
				<div class="look-modal__list"></div>
				<div class="look-modal__actions">
					<button type="button" class="btn btn-primary js-modal-confirm-bundle">Добавить все товары в
						корзину</button>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
<script>
	jQuery(function ($) {
		var $section = $('#section-look');
		if (!$section.length) return;

		/* ==========================================================================
			1. Преобразование select вариаций в плашки (с защитой от дублирования)
			========================================================================== */
		function initSwatches($scope) {
			$scope.find('.variations_form').each(function () {
				var $form = $(this);

				// Флаг защиты от повторной инициализации
				if ($form.data('swatches-initialized')) {
					return;
				}
				$form.data('swatches-initialized', true);

				$form.find('select').each(function () {
					var $select = $(this);
					var selectId = $select.attr('id');
					var label = '';

					if (selectId) {
						label = $('label[for="' + selectId + '"]').first().text().trim();
					}
					if (!label) {
						label = $select.closest('tr').find('th.label label').first().text().trim();
					}
					if (!label) {
						var attrName = $select.attr('name') || '';
						label = attrName.replace('attribute_', '').replace('pa_', '').replace(/-/g, ' ');
						label = label.charAt(0).toUpperCase() + label.slice(1);
					}

					var $swatches = $('<div class="product-card__attribute-group"></div>');
					var $labelEl = $('<div class="product-card__attribute-label text-caption">' + label + '</div>');
					var $buttonsContainer = $('<div class="product-card__size-swatches"></div>');

					$swatches.append($labelEl).append($buttonsContainer);

					$select.find('option').each(function () {
						var val = $(this).val();
						var text = $(this).text().trim();

						if (val) {
							var $btn = $('<button type="button" class="product-card__size-btn"></button>')
								.text(text)
								.attr('data-value', val);
							$buttonsContainer.append($btn);
						}
					});

					var $addToCartWrap = $form.find('.woocommerce-variation-add-to-cart');
					if ($addToCartWrap.length) {
						$addToCartWrap.before($swatches);
					} else {
						$form.find('button[type="submit"]').before($swatches);
					}

					// Скрываем нативный селект и сторонние селекты
					$select.hide();
					$select.closest('tr').find('th.label').hide();
					$select.next('.custom-select').hide();

					// Клик по размеру
					$buttonsContainer.on('click', '.product-card__size-btn:not(.disabled)', function (e) {
						e.preventDefault();
						var $this = $(this);
						var val = $this.data('value');

						$buttonsContainer.find('.product-card__size-btn').removeClass('active');
						$this.addClass('active');

						$select.val(val).trigger('change');
					});

					// Синхронизация остатков вариаций
					$form.on('woocommerce_update_variation_values', function () {
						$buttonsContainer.find('.product-card__size-btn').each(function () {
							var val = $(this).data('value');
							var $opt = $select.find('option[value="' + val + '"]');

							if ($opt.length === 0 || $opt.prop('disabled')) {
								$(this).addClass('disabled').prop('disabled', true);
							} else {
								$(this).removeClass('disabled').prop('disabled', false);
							}
						});
					});

					$form.trigger('woocommerce_update_variation_values');
				});
			});
		}

		initSwatches($section);

		/* ==========================================================================
			2. Переключение аккордеона
			========================================================================== */
		$section.on('click', '.look-item__toggle', function (e) {
			e.preventDefault();
			var $item = $(this).closest('.look-item');
			$item.toggleClass('is-open');
		});

		/* ==========================================================================
			3. Сбор и проверка невыбранных вариаций
			========================================================================== */
		function getMissingVariableItems() {
			var missing = [];
			$section.find('.section-look__items .look-item--variable').each(function () {
				var $item = $(this);
				var $form = $item.find('.variations_form');
				var variationId = parseInt($form.find('input.variation_id').val(), 10);

				// Если variation_id не определен или равен 0
				if (!variationId || variationId <= 0) {
					missing.push($item);
				}
			});
			return missing;
		}

		/* ==========================================================================
			4. Пакетная отправка товаров в корзину за 1 AJAX запрос
			========================================================================== */
		function sendAllToCart($btn) {
			var itemsData = [];

			// 1. Простые товары
			$section.find('.section-look__items .look-item--simple').each(function () {
				var pid = $(this).data('product-id');
				if (pid) {
					itemsData.push({
						product_id: parseInt(pid, 10),
						quantity: 1
					});
				}
			});

			// 2. Вариативные товары
			$section.find('.section-look__items .look-item--variable').each(function () {
				var $item = $(this);
				var $form = $item.find('.variations_form');
				var pid = parseInt($item.data('product-id'), 10);
				var variationId = parseInt($form.find('input.variation_id').val(), 10);

				var attributes = {};
				$form.find('select[name^="attribute_"]').each(function () {
					var attrName = $(this).attr('name');
					var attrVal = $(this).val();
					if (attrVal) {
						attributes[attrName] = attrVal;
					}
				});

				if (pid && variationId) {
					itemsData.push({
						product_id: pid,
						variation_id: variationId,
						quantity: 1,
						attributes: attributes
					});
				}
			});

			if (!itemsData.length) {
				alert('Нет товаров для добавления');
				return;
			}

			$btn.addClass('loading').prop('disabled', true);

			// Отправляем один запрос к нашему PHP-обработчику
			var ajaxUrl = (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.ajax_url)
				? wc_add_to_cart_params.ajax_url
				: '/wp-admin/admin-ajax.php';

			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: {
					action: 'add_bundle_to_cart',
					items: itemsData
				},
				success: function (response) {
					$btn.removeClass('loading').prop('disabled', false);

					// Если вернулись обновленные фрагменты корзины (шапка, мини-корзина)
					if (response && response.fragments) {
						$.each(response.fragments, function (key, value) {
							$(key).replaceWith(value);
						});
					}

					$(document.body).trigger('wc_fragment_refresh');
					$(document.body).trigger('added_to_cart');

					// Закрываем модалку если была открыта
					$('#look-missing-modal').removeClass('active');
					$('body').removeClass('drawer-open');

					var originalText = $btn.text();
					$btn.text('Образ в корзине!');
					setTimeout(function () {
						$btn.text(originalText);
					}, 3000);
				},
				error: function () {
					$btn.removeClass('loading').prop('disabled', false);
					alert('Произошла ошибка при добавлении в корзину. Попробуйте еще раз.');
				}
			});
		}
		/* ==========================================================================
			5. Клик по кнопке "Добавить образ в корзину"
			========================================================================== */
		$section.on('click', '.js-look-buy-all', function (e) {
			e.preventDefault();
			var missing = getMissingVariableItems();

			if (missing.length > 0) {
				var $modal = $('#look-missing-modal');
				var $list = $modal.find('.look-modal__list').empty();

				missing.forEach(function ($origItem) {
					var pId = $origItem.data('product-id');
					var title = $origItem.find('.look-item__name').text().trim();
					var price = $origItem.find('.look-item__price').html();
					var imgSrc = $origItem.find('.look-item__thumb img').attr('src');

					// Создаем облегченную карточку выбора для модалки
					var $mItem = $(
						'<div class="look-modal-item" data-product-id="' + pId + '">' +
						'<div class="look-modal-item__head">' +
						'<img src="' + imgSrc + '" class="look-modal-item__img" alt="">' +
						'<div>' +
						'<div class="look-modal-item__title">' + title + '</div>' +
						'<div class="look-modal-item__price">' + price + '</div>' +
						'</div>' +
						'</div>' +
						'<div class="look-modal-item__sizes"></div>' +
						'</div>'
					);

					var $sizesWrap = $mItem.find('.look-modal-item__sizes');

					// Копируем доступные размеры из оригинальной формы
					$origItem.find('.product-card__size-btn').each(function () {
						var val = $(this).data('value');
						var txt = $(this).text();
						var isDis = $(this).hasClass('disabled');
						var isAct = $(this).hasClass('active');

						var $b = $('<button type="button" class="product-card__size-btn"></button>')
							.text(txt)
							.attr('data-value', val);

						if (isDis) $b.addClass('disabled').prop('disabled', true);
						if (isAct) $b.addClass('active');

						$sizesWrap.append($b);
					});

					$list.append($mItem);
				});

				$modal.addClass('active');
				$('body').addClass('drawer-open');
			} else {
				sendAllToCart($(this));
			}
		});

		/* ==========================================================================
			6. Выбор размера внутри модального окна и добавление
			========================================================================== */
		$('#look-missing-modal').on('click', '.product-card__size-btn:not(.disabled)', function (e) {
			e.preventDefault();
			var $btn = $(this);
			var val = $btn.data('value');
			var $item = $btn.closest('.look-modal-item');
			var pId = $item.data('product-id');

			$item.find('.product-card__size-btn').removeClass('active');
			$btn.addClass('active');

			// Синхронизируем выбор в основной форме на странице
			var $orig = $('#look-item-' + pId);
			$orig.find('.product-card__size-btn[data-value="' + val + '"]').trigger('click');
		});

		$('#look-missing-modal').on('click', '.js-modal-confirm-bundle', function (e) {
			e.preventDefault();
			var $btn = $(this);
			var stillMissing = getMissingVariableItems();

			if (stillMissing.length > 0) {
				alert('Пожалуйста, укажите размер для всех товаров.');
				return;
			}

			sendAllToCart($btn);
		});

		$('#look-missing-modal').on('click', '.look-modal__close, .look-modal__overlay', function () {
			$('#look-missing-modal').removeClass('active');
			$('body').removeClass('drawer-open');
		});
	});
</script>