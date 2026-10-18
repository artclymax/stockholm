<?php
$page = get_queried_object();

// Поля со страницы контактов
$contacts_title = get_field('contacts_page_title', $page) ?? 'Магазин STOCK HOLM';
$map_title = get_field('contacts_map_title', $page) ?? 'Как нас найти';
$help_title = get_field('contacts_help_title', $page) ?? 'Нужна помощь с выбором?';
$help_descr = get_field('contacts_help_descr', $page) ?? '';
$help_button = get_field('contacts_help_button', $page);
$help_instagram = get_field('contacts_help_instagram', $page);

// Поля из Options Page (Контактные данные)
$address = get_field('contacts_address', 'option') ?? '';
$work_time = get_field('contacts_time', 'option') ?? '';
$phones = get_field('contacts_phones', 'option') ?? [];
$emails = get_field('contacts_emails', 'option') ?? [];
$socials = get_field('contacts_socials', 'option') ?? [];
$map = get_field('contacts_map', 'option') ?? '';
?>

<section class="section section-contacts" id="section-contacts">
	<div class="container section-contacts__container">

		<!-- Верхняя часть: контакты + карта -->
		<div class="section-contacts__top">

			<!-- Левая колонка: контактная информация -->
			<div class="section-contacts__info">
				<?php if ($contacts_title): ?>
					<h1 class="section-contacts__title title-2">
						<?php echo esc_html($contacts_title); ?>
					</h1>
				<?php endif; ?>

				<div class="section-contacts__details">
					<?php if ($address): ?>
						<div class="contacts-detail">
							<span class="contacts-detail__label">Адрес</span>
							<div class="contacts-detail__value descr">
								<?php echo wp_kses_post($address); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($work_time): ?>
						<div class="contacts-detail">
							<span class="contacts-detail__label">Режим работы</span>
							<div class="contacts-detail__value descr">
								<?php echo wp_kses_post($work_time); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($phones)): ?>
						<div class="contacts-detail">
							<span class="contacts-detail__label">Телефон</span>
							<div class="contacts-detail__value descr">
								<?php foreach ($phones as $index => $phone_item):
									$phone = $phone_item['phone'] ?? '';
									if (!$phone)
										continue;
									$phone_clean = preg_replace('/[^0-9+]/', '', $phone);
									?>
									<a class="contacts-detail__link" href="tel:<?php echo esc_attr($phone_clean); ?>">
										<?php echo esc_html($phone); ?>
									</a>
									
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($emails)): ?>
						<div class="contacts-detail">
							<span class="contacts-detail__label">E-mail</span>
							<div class="contacts-detail__value descr">
								<?php foreach ($emails as $index => $email_item):
									$email = $email_item['email'] ?? '';
									if (!$email)
										continue;
									?>
									<a class="contacts-detail__link" href="mailto:<?php echo esc_attr($email); ?>">
										<?php echo esc_html($email); ?>
									</a>
									<?php if ($index < count($emails) - 1): ?>, <?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<?php if (!empty($socials)): ?>
					<div class="section-contacts__socials">
						<h2 class="section-contacts__socials-title title-2">
							Мы в социальных сетях
						</h2>
						<div class="section-contacts__socials-list">
							<?php foreach ($socials as $social):
								$social_link = $social['link'] ?? [];
								if (empty($social_link['url']))
									continue;
								?>
								<a class="section-contacts__social-link btn-link" href="<?php echo esc_url($social_link['url']); ?>"
									target="_blank" rel="noopener noreferrer">
									<?php echo esc_html($social_link['title']); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Правая колонка: карта -->
			<div class="section-contacts__map-column">
				<?php if ($map_title): ?>
					<h2 class="section-contacts__map-title title-2">
						<?php echo esc_html($map_title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($map): ?>
					<div class="section-contacts__map-box">
						<?php echo wp_kses($map, [
							'iframe' => [
								'src' => true,
								'width' => true,
								'height' => true,
								'style' => true,
								'allowfullscreen' => true,
								'loading' => true,
							],
						]) ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Нижняя часть: помощь с выбором -->
		<?php if ($help_title || $help_descr || $help_button || $help_instagram): ?>
			<div class="section-contacts__help">
				<div class="section-contacts__help-container container">

					<?php if ($help_title || $help_descr): ?>
						<div class="section-contacts__help-text">
							<?php if ($help_title): ?>
								<h2 class="section-contacts__help-title title-3 descr">
									<?php echo wp_kses_post($help_title); ?>
								</h2>
							<?php endif; ?>

							<?php if ($help_descr): ?>
								<div class="section-contacts__help-descr descr">
									<?php echo wp_kses_post($help_descr); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="section-contacts__help-actions">
						<?php if ($help_button && !empty($help_button['url'])): ?>
							<a class="section-contacts__help-btn btn" href="<?php echo esc_url($help_button['url']); ?>"
								<?php if (!empty($help_button['target'])): ?>target="<?php echo esc_attr($help_button['target']); ?>"
								<?php endif; ?>>
								<?php echo esc_html($help_button['title']); ?>
							</a>
						<?php endif; ?>

						<?php if ($help_instagram && !empty($help_instagram['url'])): ?>
							<a class="section-contacts__help-instagram btn-link" href="<?php echo esc_url($help_instagram['url']); ?>"
								target="_blank" rel="noopener noreferrer">
								<?php echo esc_html($help_instagram['title']); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
