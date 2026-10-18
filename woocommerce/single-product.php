<?php
/**
 * Верхнеуровневый шаблон страницы товара WooCommerce
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<?php while (have_posts()): ?>
	<?php the_post(); ?>

	<?php
	// Безопасно получаем объект товара
	global $product;
	if (!is_a($product, 'WC_Product')) {
		$product = wc_get_product(get_the_ID());
	}

	if (empty($product)) {
		continue;
	}

	$product_id = $product->get_id();
	$is_variable = $product->is_type('variable');

	// Галерея и главное фото
	$attachment_ids = $product->get_gallery_image_ids();
	$main_image_id = $product->get_image_id();

	$gallery_images = [];
	if ($main_image_id) {
		$gallery_images[] = $main_image_id;
	}
	if (!empty($attachment_ids)) {
		$gallery_images = array_unique(array_merge($gallery_images, $attachment_ids));
	}

	// Категория
	$category_list = wc_get_product_category_list($product_id, '•');

	// ACF-поля
	$composition_care = get_field('prod_composition_care', $product_id);
	$shipping_payment = get_field('prod_shipping_payment', $product_id);
	$returns_exchange = get_field('prod_returns_exchange', $product_id);

	wp_enqueue_script('wc-add-to-cart-variation');
	?>

	<section
		class="section section-product-single <?= (!$product->is_in_stock()) ? 'section-product-single--out-of-stock' : ''; ?>"
		id="section-product-<?php echo esc_attr($product_id); ?>">
		<div class="container section-product-single__container">
			<div class="section-product-single__breadcrumbs page-breadcrumbs">
				<?php if (function_exists('bcn_display')) {
					bcn_display();
				} ?>
			</div>
			<div
				class="product-single woocommerce <?php echo $is_variable ? 'product-card--variable' : 'product-card--simple'; ?>"
				id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

				<!-- Левая колонка: Галерея-слайдер или Одиночный Thumb -->
				<div class="product-single__media">
					<?php if (count($gallery_images) > 1): ?>
						<!-- main-swiper -->
						<div class="product-single__main-swiper">

							<div class="swiper product-swiper-main">
								<div class="swiper-wrapper product-swiper-main__wrapper">
									<?php foreach ($gallery_images as $image_id): ?>
										<div class="swiper-slide product-swiper-main-slide">
											<a class="product-swiper-main-slide-image image-box"
												href="<?= wp_get_attachment_image_url($image_id, 'full') ?>" data-fancybox>
												<?php echo wp_get_attachment_image($image_id, 'full', false, [
													'class' => 'product-single__image img',
													'loading' => 'lazy',
													'alt' => get_the_title(),
												]); ?>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="product-swiper-main__counter visible-mobile"></div>
							</div>
							<div class="swiper-pagination product-swiper-main__pagination visible-mobile"></div>
						</div>


						<!-- Слайдер миниатюр (на десктопе) -->
						<div class="swiper product-swiper-thumb hidden-mobile">
							<div class="swiper-wrapper product-swiper-thumb__wrapper">
								<?php foreach ($gallery_images as $image_id): ?>
									<div class="swiper-slide product-swiper-thumb-slide">
										<div class="product-swiper-thumb-slide-image image-box">
											<?php echo wp_get_attachment_image($image_id, 'full', false, [
												'class' => 'product-single__image img',
												'loading' => 'lazy',
												'alt' => get_the_title(),
											]); ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

					<?php elseif (!empty($gallery_images)): ?>
						<a class="product-swiper-main-slide-image product-swiper-main-slide-image--full image-box"
							href="<?= wp_get_attachment_image_url($gallery_images[0], 'full') ?>" data-fancybox>
							<?php echo wp_get_attachment_image($gallery_images[0], 'full', false, [
								'class' => 'product-single__image img',
								'loading' => 'eager',
								'alt' => get_the_title(),
							]); ?>
						</a>
					<?php else: ?>
						<div class="product-swiper-main-slide-image image-box">
							<?php echo wc_placeholder_img('full'); ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- Правая колонка: Информация, вариации, спойлеры -->
				<div class="product-single__summary">

					<div class="product-single__header">
						<?php if ($category_list): ?>
							<div class="product-single__category">
								<?php echo $category_list; ?>
							</div>
						<?php endif; ?>

						<h1 class="product-single__title title-2">
							<?php the_title(); ?>
						</h1>

						<div class="product-single__price">
							<?php echo $product->get_price_html(); ?>
						</div>
					</div>

					<!-- Форма добавления в корзину -->
					<div class="product-single__cart-form">
						<?php if ($is_variable): ?>
							<?php woocommerce_variable_add_to_cart(); ?>
						<?php else: ?>
							<?php woocommerce_simple_add_to_cart(); ?>
						<?php endif; ?>
					</div>

					<!-- Аккордеон с описанием и ACF вкладками -->
					<?php if (get_the_content() || !empty($composition_care) || !empty($shipping_payment) || !empty($returns_exchange)): ?>

						<ul class="product-single__spoilers product-spoilers" data-spoilers>

							<?php if (get_the_content()): ?>
								<li class="product-spoiler spoiler-active" data-spoiler>
									<button type="button" class="product-spoiler__btn" data-spoiler-title>
										<h3 class="product-spoiler__title">Описание</h3>
										<span class="product-spoiler__icon"></span>
									</button>
									<div class="product-spoiler__content" data-spoiler-content>
										<div class="product-spoiler__descr descr">
											<?php the_content(); ?>
										</div>
									</div>
								</li>
							<?php endif; ?>

							<?php if (!empty($composition_care)): ?>
								<li class="product-spoiler" data-spoiler>
									<button type="button" class="product-spoiler__btn" data-spoiler-title>
										<h3 class="product-spoiler__title">Состав и уход</h3>
										<span class="product-spoiler__icon"></span>
									</button>
									<div class="product-spoiler__content" data-spoiler-content>
										<div class="product-spoiler__descr descr">
											<?php echo wp_kses_post($composition_care); ?>
										</div>
									</div>
								</li>
							<?php endif; ?>

							<?php if (!empty($shipping_payment)): ?>
								<li class="product-spoiler" data-spoiler>
									<button type="button" class="product-spoiler__btn" data-spoiler-title>
										<h3 class="product-spoiler__title">Доставка и оплата</h3>
										<span class="product-spoiler__icon"></span>
									</button>
									<div class="product-spoiler__content" data-spoiler-content>
										<div class="product-spoiler__descr descr">
											<?php echo wp_kses_post($shipping_payment); ?>
										</div>
									</div>
								</li>
							<?php endif; ?>

							<?php if (!empty($returns_exchange)): ?>
								<li class="product-spoiler" data-spoiler>
									<button type="button" class="product-spoiler__btn" data-spoiler-title>
										<h3 class="product-spoiler__title">Возврат и обмен</h3>
										<span class="product-spoiler__icon"></span>
									</button>
									<div class="product-spoiler__content" data-spoiler-content>
										<div class="product-spoiler__descr descr">
											<?php echo wp_kses_post($returns_exchange); ?>
										</div>
									</div>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>

				</div>

			</div>

		</div>
	</section>
	<!-- Модальное окно: Товар добавлен в корзину -->
	<div class="cart-added-modal" id="cart-added-modal" aria-hidden="true">
		<div class="cart-added-modal__overlay js-cart-modal-close"></div>
		<div class="cart-added-modal__dialog">
			<div class="cart-added-modal__arrow"></div>

			<div class="cart-added-modal__header">
				<h3 class="cart-added-modal__title">Товар добавлен в корзину</h3>
				<button type="button" class="cart-added-modal__close js-cart-modal-close" aria-label="Закрыть">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
					</svg>
				</button>
			</div>

			<div class="cart-added-modal__body">
				<div class="cart-added-modal__thumb">
					<img src="" alt="" class="js-cart-modal-img">
				</div>

				<div class="cart-added-modal__info">
					<h4 class="cart-added-modal__name js-cart-modal-title"></h4>
					<div class="cart-added-modal__attributes js-cart-modal-attrs"></div>
				</div>

				<div class="cart-added-modal__price js-cart-modal-price"></div>
			</div>

			<div class="cart-added-modal__footer">
				<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn cart-added-modal__btn-cart">
					Перейти в корзину
				</a>
				<button type="button" class="btn cart-added-modal__btn-continue js-cart-modal-close">
					Продолжить покупки
				</button>
			</div>
		</div>
	</div>
<?php endwhile; ?>
<?php get_footer('shop'); ?>