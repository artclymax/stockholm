<?php
/**
 * Шаблон футера
 */

$page_footer = 'option';
$page_contacts = 'option';

// Данные футера
$footer_descr = get_field('footer_descr', $page_footer) ?? '';
$footer_btn = get_field('footer_btn', $page_footer) ?? [];
$footer_company = get_field('footer_company', $page_footer) ?: get_bloginfo('name');
$footer_copy = get_field('footer_copy', $page_footer) ?? '';
$footer_links = get_field('footer_links', $page_footer) ?? [];
$footer_legal_info = get_field('footer_legal_info', $page_footer) ?? '';
$footer_registration_info = get_field('footer_registration_info', $page_footer) ?? '';
$footer_payments = get_field('footer_payments', $page_footer) ?? [];

// Данные контактов
$contacts_phones = get_field('contacts_phones', $page_contacts) ?? [];
$contacts_emails = get_field('contacts_emails', $page_contacts) ?? [];
$contacts_socials = get_field('contacts_socials', $page_contacts) ?? [];
$contacts_address = get_field('contacts_address', $page_contacts) ?? '';
$contacts_time = get_field('contacts_time', $page_contacts) ?? '';
?>

<footer class="footer" id="footer">
	<div class="container footer__container">
		<div class="footer__top">
			<div class="footer__logo logo">
				<?php mx_logo(); ?>
				<?php if ($footer_descr): ?>
					<span class="footer__logo-text descr"><?php echo wp_kses_post($footer_descr); ?></span>
				<?php endif; ?>
			</div>

			<?php if (!empty($footer_btn) && !empty($footer_btn['url'])): ?>
				<a class="footer__btn btn btn--stroke-white" href="<?php echo esc_url($footer_btn['url']); ?>" <?php if (!empty($footer_btn['target'])): ?>target="<?php echo esc_attr($footer_btn['target']); ?>" <?php endif; ?>>
					<?php echo esc_html($footer_btn['title']); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="footer__middle">
			<div class="footer__middle-container">


				<?php
				$catalog_menu = wp_nav_menu([
					'theme_location' => 'catalog-menu',
					'container' => false,
					'menu_class' => 'footer-menu',
					'depth' => 1,
					'fallback_cb' => false,
					'echo' => false
				]);
				if (!empty($catalog_menu)): ?>
					<div class="footer__col footer-col">
						<h3 class="footer-col__title title-3">Каталог</h3>
						<?php echo $catalog_menu ?>
					</div>
				<?php endif; ?>
				<?php
				$collection_menu = wp_nav_menu([
					'theme_location' => 'collection-menu',
					'container' => false,
					'menu_class' => 'footer-menu',
					'depth' => 1,
					'fallback_cb' => false,
					'echo' => false
				]);
				if (!empty($collection_menu)): ?>
					<div class="footer__col footer-col">
						<h3 class="footer-col__title title-3">Коллекции</h3>
						<?php echo $collection_menu; ?>
					</div>
				<?php endif; ?>
				<?php
				$customer_menu = wp_nav_menu([
					'theme_location' => 'customer-menu',
					'container' => false,
					'menu_class' => 'footer-menu',
					'depth' => 1,
					'fallback_cb' => false,
					'echo' => false
				]);
				if (!empty($customer_menu)): ?>
					<div class="footer__col footer-col footer-col--customer">
						<h3 class="footer-col__title title-3">Покупателям</h3>
						<?php echo $customer_menu; ?>
					</div>
				<?php endif; ?>

				<div class="footer__contacts">
					<div class="footer__col footer-col footer-col--contacts">
						<h3 class="footer-col__title title-3">Связаться с нами</h3>
						<div class="footer-col__contacts-box">
							<?php if (!empty($contacts_phones)): ?>
								<div class="footer-col__contact">
									<span class="footer-col__label">Телефон</span>
									<?php foreach ($contacts_phones as $phone_item):
										$phone = $phone_item['phone'] ?? '';
										if (!$phone)
											continue;
										$phone_clean = preg_replace('/[^0-9+]/', '', $phone);
										?>
										<div class="footer-col__list">
											<a class="footer-col__link" href="tel:<?php echo esc_attr($phone_clean); ?>">
												<?php echo esc_html($phone); ?>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if (!empty($contacts_emails)): ?>
								<div class="footer-col__contact">
									<span class="footer-col__label">Email</span>
									<?php foreach ($contacts_emails as $email_item):
										$email = $email_item['email'] ?? '';
										if (!$email)
											continue;
										?>
										<div class="footer-col__list">
											<a class="footer-col__link" href="mailto:<?php echo esc_attr($email); ?>">
												<?php echo esc_html($email); ?>
											</a>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ($contacts_address): ?>
								<div class="footer-col__contact">
									<span class="footer-col__label">Адрес</span>
									<div class="footer-col__text descr"><?php echo wp_kses_post($contacts_address); ?></div>
								</div>
							<?php endif; ?>

							<?php if ($contacts_time): ?>
								<div class="footer-col__contact">
									<span class="footer-col__label">Режим работы</span>
									<div class="footer-col__text descr"><?php echo wp_kses_post($contacts_time); ?></div>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<?php if (!empty($contacts_socials)): ?>
						<div class="footer__col footer-col">
							<h3 class="footer-col__title title-3">Мы в соцсетях</h3>
							<?php get_template_part('partials/components/socials'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="footer__bottom-links">
				<?php if (!empty($footer_links)): ?>
					<?php foreach ($footer_links as $item):
						$link = $item['footer_links_link'] ?? [];
						if (empty($link['url']))
							continue;
						?>
						<a class="footer__bottom-link" href="<?php echo esc_url($link['url']); ?>" <?php if (!empty($link['target'])): ?>target="
					<?php echo esc_attr($link['target']); ?>" <?php endif; ?>>
							<?php echo esc_html($link['title']); ?>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>

				<div class="footer__copy">
					&copy;
					<?php echo esc_html($footer_company) . ', ' . date('Y'); ?>
					<?php if ($footer_copy): ?>
						.
						<?php echo esc_html($footer_copy); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="footer__bottom">
			<div class="footer__legal">
				<?php if ($footer_legal_info): ?>
					<div class="footer__legal-info descr"><?php echo $footer_legal_info; ?></div>
				<?php endif; ?>
				<?php if ($footer_registration_info): ?>
					<div class="footer__registration-info descr"><?php echo $footer_registration_info; ?></div>
				<?php endif; ?>
			</div>
			<?php if (!empty($footer_payments)): ?>
				<div class="footer__payments">
					<h4 class="footer__payments-title">Принимаем к оплате</h4>
					<div class="footer__payments-list">
						<?php foreach ($footer_payments as $payment):
							$payment_image = $payment['image'] ?? null;
							if (!$payment_image || empty($payment_image['ID']))
								continue;
							?>
							<div class="footer__payment-item">
								<?php echo wp_get_attachment_image(
									$payment_image['ID'],
									'full',
									false,
									[
										'class' => 'img',
										'alt' => $payment_image['alt'] ?: 'Способ оплаты',
										'loading' => 'lazy'
									]
								); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>