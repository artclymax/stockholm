//import 'jarallax/dist/jarallax.min.css';
import "@fancyapps/ui/dist/fancybox/fancybox.css";
//import "aos/dist/aos.css";
import "./scss/style.scss";
//!
import { Swiper } from "swiper/bundle";

//import { jarallax } from "jarallax";
import { Fancybox } from "@fancyapps/ui/dist/fancybox/";
//import AOS from "AOS";

import { AutoCalc } from "./js/AutoCalc.js";
import { Menu } from "./js/Menu.js";
//import { RunningLine } from './js/RunningLine.js';
//import { BackdropList } from './js/BackdropList.js';
import { Modal } from "./js/Modal.js";
//import { cookies } from "./js/Cookies.js";
//import { AnimationCounter } from "./js/AnimationCounter.js";
//import HiddenDescr from "./js/HiddenDescr.js";
//import HiddenList from "./js/HiddenList.js";
import { imask } from "./js/imask.js";
import { fileBtn } from "./js/FileBtn.js";
//import { CustomFieldSelect } from "./js/CustomFieldSelect.js";
import { Spoiler } from "./js/Spoiler.js";
import { ScrollToTop } from "./js/ScrollToTop.js";
import { MXSwiper } from "./js/MXSwiper.js";
import { CustomSelect } from "./js/CustomSelect.js";
//import { Tabs } from './js/Tabs.js';
//import { MapScrollLock } from './js/MapScrollLock.js';
//import { cf7CustomValidation } from './js/cf7-validation.js';
//import { SelectFromAcf } from './js/SelectFromAcf.js';
//import { loadMore } from './js/loadMore.js';
//import AirDatepicker from 'air-datepicker';
//import 'air-datepicker/air-datepicker.css';
//document.querySelectorAll('input.form-input-date')?.forEach(input => {
//	new AirDatepicker(input, {
//		selectedDates: [new Date()],
//		isMobile: true,
//		autoClose: true,
//		minDate: new Date(),
//	});
//})
// time
//document.querySelectorAll('input.form-input-time')?.forEach(input => {
//	new AirDatepicker(input, {
//		isMobile: true,
//		autoClose: true,
//		timepicker: true,
//		onlyTimepicker: true
//	});
//})
//cf7CustomValidation();

// FOR CONTACTS FORM 7
//import "./js/redirect-to-thanks-page.js";

new AutoCalc();
//new CustomFieldSelect({
//	input: '.form-wrapper .cf-select',
//	lastEdit: false
//});
new Menu({
	btn: ".burger-btn", //.burger-btn-icon
	menu: ".header-menu",
	activeClass: "open-header-menu",
	closeBtns: ["a", ".cart-btn"],
});
//new Menu({
//	btn: '.filter-mobile-btn',
//	menu: '.filter',
//	activeClass: 'open-filter-menu',
//	closeBtns: ['.filter-close-btn']
//})
//new Menu({
//	btn: '.header__phone-btn', //.burger-btn-icon
//	menu: '.header-phone-box',
//	activeClass: 'open-phone-box',
//	//closeBtns: ['.menu-close-btn']
//})

//new BackdropList();

new Modal();

//jarallax(document.querySelectorAll('.jarallax'), {
//speed: 0.2,
//});

//cookies();
imask();

Fancybox.bind("[data-fancybox]", {
	placeFocusBack: false,
});

//new RunningLine();
new ScrollToTop();

new Spoiler();

//new Spoiler('[data-spoiler]', {
//	onlyOneOpen: true,
//	activeClass: 'is-open',
//});

//new Spoiler('[data-spoiler]', { media: 'max-width: 768px' });

new MXSwiper();
//new Tabs();
new CustomSelect({
	selector: "select",
	forceSend: true,
});

//new MapScrollLock();
/*
window.addEventListener('scroll', function () {
	const header = document.querySelector('.header');

	if (window.scrollY > 50) {
		header.classList.add('scrolled');
	} else {
		header.classList.remove('scrolled');
		const valueUnits = header.offsetHeight;
		document.querySelector(':root').style.setProperty(`--header-height`, valueUnits + 'px');
	}
});
*/

fileBtn(); //

//new Tabs();

//document.querySelectorAll('video.wp-video-shortcode').forEach(video => {
//	if (!video.src) {
//		const link = video.querySelector('a');
//		if (link) video.src = link.href;
//	}
//});

//document?.querySelectorAll('.wpcf7-acceptance input[type="checkbox"]')?.forEach(el => {
//	el.checked = true;
//})

//AOS.init({
//	disable: 'mobile',
//	duration: 600,
//	offset: 100
//});

//new SelectFromAcf('.custom-select-product', {
//	data: '.select-products-data'
//})

//loadMore();

//AnimationCounter();

//HiddenDescr();

//HiddenList();

document.addEventListener("DOMContentLoaded", () => {
	const catalogGrid = document.getElementById("shop-products-grid");

	/* ==========================================================================
		1. Инициализация размеров (с защитой от дублирования)
		========================================================================== */
	function initCardVariations(scope) {
		const root = scope || document;
		const forms = root.querySelectorAll(".variations_form");

		forms.forEach((form) => {
			if (form.dataset.swatchesInitialized === "true") {
				return;
			}
			form.dataset.swatchesInitialized = "true";

			// Инициализируем плагин вариаций WooCommerce для вновь созданных карточек
			if (
				window.jQuery &&
				typeof window.jQuery.fn.wc_variation_form === "function"
			) {
				window.jQuery(form).wc_variation_form();
			}

			const selects = form.querySelectorAll("select");

			selects.forEach((select) => {
				const selectId = select.id;
				let label = "";

				if (selectId) {
					const labelElem = document.querySelector(`label[for="${selectId}"]`);
					if (labelElem) label = labelElem.textContent.trim();
				}

				if (!label) {
					const rowLabel = select
						.closest("tr")
						?.querySelector("th.label label");
					if (rowLabel) label = rowLabel.textContent.trim();
				}

				if (!label) {
					const attrName = select.name || "";
					label = attrName
						.replace("attribute_", "")
						.replace("pa_", "")
						.replace(/-/g, " ");
					label = label.charAt(0).toUpperCase() + label.slice(1);
				}

				// Создаем разметку плашек
				const swatchesGroup = document.createElement("div");
				swatchesGroup.className = "product-card__attribute-group";

				const labelElem = document.createElement("div");
				labelElem.className = "product-card__attribute-label text-status";
				labelElem.textContent = label;

				const buttonsContainer = document.createElement("div");
				buttonsContainer.className = "product-card__size-swatches";

				swatchesGroup.appendChild(labelElem);
				swatchesGroup.appendChild(buttonsContainer);

				// Заполняем кнопками размеров
				Array.from(select.options).forEach((opt) => {
					const val = opt.value;
					const text = opt.textContent.trim();

					if (val) {
						const btn = document.createElement("button");
						btn.type = "button";
						btn.className = "product-card__size-btn";
						btn.textContent = text;
						btn.dataset.value = val;
						buttonsContainer.appendChild(btn);
					}
				});

				// Вставляем перед кнопкой добавления в корзину
				const addToCartWrap = form.querySelector(
					".woocommerce-variation-add-to-cart",
				);
				if (addToCartWrap) {
					addToCartWrap.parentNode.insertBefore(swatchesGroup, addToCartWrap);
				} else {
					const submitBtn = form.querySelector('button[type="submit"]');
					if (submitBtn) {
						submitBtn.parentNode.insertBefore(swatchesGroup, submitBtn);
					}
				}

				// Скрываем оригинальный селект и лишние элементы
				select.style.display = "none";
				const thLabel = select.closest("tr")?.querySelector("th.label");
				if (thLabel) thLabel.style.display = "none";

				const customSelect = select.nextElementSibling;
				if (customSelect && customSelect.classList.contains("custom-select")) {
					customSelect.style.display = "none";
				}

				// Клик по размеру
				buttonsContainer.addEventListener("click", (e) => {
					const btn = e.target.closest(".product-card__size-btn");
					if (!btn || btn.classList.contains("disabled")) return;

					e.preventDefault();

					buttonsContainer
						.querySelectorAll(".product-card__size-btn")
						.forEach((b) => b.classList.remove("active"));
					btn.classList.add("active");

					select.value = btn.dataset.value;

					// Диспатчим нативное и jQuery событие change для WooCommerce
					select.dispatchEvent(new Event("change", { bubbles: true }));
					if (window.jQuery) {
						window.jQuery(select).trigger("change");
					}
				});

				// Обновление состояния доступности размеров от WooCommerce
				const updateSwatchesAvailability = () => {
					buttonsContainer
						.querySelectorAll(".product-card__size-btn")
						.forEach((btn) => {
							const val = btn.dataset.value;
							const opt = select.querySelector(`option[value="${val}"]`);

							if (!opt || opt.disabled) {
								btn.classList.add("disabled");
								btn.disabled = true;
							} else {
								btn.classList.remove("disabled");
								btn.disabled = false;
							}
						});
				};

				if (window.jQuery) {
					window
						.jQuery(form)
						.on(
							"woocommerce_update_variation_values",
							updateSwatchesAvailability,
						);
				}
				form.addEventListener(
					"woocommerce_update_variation_values",
					updateSwatchesAvailability,
				);

				updateSwatchesAvailability();
			});

			// Закрытие мобильного ящика при успешном добавлении
			if (window.jQuery) {
				window.jQuery(form).on("added_to_cart", closeMobileDrawer);
			}
			form.addEventListener("added_to_cart", closeMobileDrawer);
		});
	}

	// Первичная инициализация на странице
	initCardVariations(document.body);

	/* ==========================================================================
		2. Мобильный drawer
		========================================================================== */
	function openMobileDrawer(productId) {
		const drawer = document.getElementById("mobile-drawer-" + productId);
		if (drawer) {
			drawer.classList.add("active");
			document.body.classList.add("drawer-open");
		}
	}

	function closeMobileDrawer() {
		document
			.querySelectorAll(".product-card__mobile-drawer")
			.forEach((drawer) => {
				drawer.classList.remove("active");
			});
		document.body.classList.remove("drawer-open");
	}

	// Делегирование событий клика
	document.addEventListener("click", (e) => {
		// Клик по мобильному оверлею карточки
		const trigger = e.target.closest(".product-card__mobile-trigger");
		if (trigger && window.matchMedia("(max-width: 768px)").matches) {
			e.preventDefault();
			e.stopPropagation();
			openMobileDrawer(trigger.dataset.productId);
			return;
		}

		// Клик по подложке или ручке шторки
		if (
			e.target.closest(".product-card__drawer-overlay") ||
			e.target.closest(".product-card__drawer-handle")
		) {
			closeMobileDrawer();
			return;
		}

		// Клик по простой кнопке в шторке
		if (
			e.target.closest(
				".product-card__drawer-simple .single_add_to_cart_button",
			)
		) {
			setTimeout(closeMobileDrawer, 500);
			return;
		}

		// Клик по кнопке "Показать ещё" (Load More)
		const loadMoreBtn = e.target.closest(".js-shop-load-more");
		if (loadMoreBtn) {
			e.preventDefault();
			handleLoadMore(loadMoreBtn);
		}
	});

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape") closeMobileDrawer();
	});

	/* ==========================================================================
			 3. Кнопка "Показать ещё" (AJAX Fetch Load More)
			 ========================================================================== */
	async function handleLoadMore(btn) {
		const categoryId = btn.dataset.category || 0;

		// Берем актуальное значение сортировки
		const orderbySelect = document.querySelector(
			".woocommerce-ordering select.orderby",
		);
		const orderby = orderbySelect
			? orderbySelect.value
			: btn.dataset.orderby || "";

		// Собираем ID всех уже отображенных товаров в каталоге
		const existingCards = catalogGrid
			? catalogGrid.querySelectorAll(".product-card")
			: [];
		const excludeIds = Array.from(existingCards)
			.map((card) => card.dataset.productId)
			.filter(Boolean);

		const originalText = btn.innerHTML;
		btn.innerHTML = `
            <span class="btn-spinner"></span>
            <span>Пожалуйста, подождите...</span>
        `;
		btn.classList.add("loading");
		btn.disabled = true;

		if (catalogGrid) {
			catalogGrid.classList.add("is-loading");
		}

		const ajaxUrl =
			typeof wc_add_to_cart_params !== "undefined" &&
			wc_add_to_cart_params.ajax_url
				? wc_add_to_cart_params.ajax_url
				: "/wp-admin/admin-ajax.php";

		const formData = new FormData();
		formData.append("action", "load_more_products");
		formData.append("category", categoryId);
		formData.append("orderby", orderby);

		// Передаем массив исключений
		excludeIds.forEach((id) => {
			formData.append("exclude_ids[]", id);
		});

		try {
			const response = await fetch(ajaxUrl, {
				method: "POST",
				body: formData,
			});

			const res = await response.json();

			btn.innerHTML = originalText;
			btn.classList.remove("loading");
			btn.disabled = false;
			if (catalogGrid) {
				catalogGrid.classList.remove("is-loading");
			}

			if (res.success && res.data.html) {
				const tempDiv = document.createElement("div");
				tempDiv.innerHTML = res.data.html;

				const newElements = Array.from(tempDiv.children);

				if (catalogGrid) {
					newElements.forEach((el) => {
						el.classList.add("fade-in-up");
						catalogGrid.appendChild(el);
					});
				}

				// Инициализируем вариации в новых карточках
				newElements.forEach((el) => initCardVariations(el));

				// Если товаров больше нет — удаляем кнопку
				if (!res.data.has_more) {
					btn.parentElement?.remove();
				}
			} else {
				btn.parentElement?.remove();
			}
		} catch (error) {
			btn.innerHTML = originalText;
			btn.classList.remove("loading");
			btn.disabled = false;
			if (catalogGrid) {
				catalogGrid.classList.remove("is-loading");
			}
		}
	}
});

document.addEventListener("DOMContentLoaded", () => {
	const mainSliderEl = document.querySelector(".product-swiper-main");
	const thumbSliderEl = document.querySelector(".product-swiper-thumb");
	const counterEl = document.querySelector(".product-swiper-main__counter");

	if (!mainSliderEl) return;

	// 1. Слайдер миниатюр (вертикальный)
	let thumbSwiper = null;
	if (thumbSliderEl) {
		thumbSwiper = new Swiper(thumbSliderEl, {
			direction: "vertical",
			spaceBetween: 10,
			slidesPerView: 4,
			freeMode: true,
			watchSlidesProgress: true,
			// На мобильных при необходимости переключаем в горизонталь
			breakpoints: {
				0: {
					direction: "horizontal",
					slidesPerView: 4,
				},
				769: {
					direction: "vertical",
					slidesPerView: 4,
				},
			},
		});
	}

	// Вспомогательная функция счётчика (1 / 4)
	const updateCounter = (swiper) => {
		if (!counterEl) return;
		const current = swiper.realIndex + 1;
		const total = swiper.slides.length;
		counterEl.innerHTML = `<span class="counter-current">${current}</span> / <span class="counter-total">${total}</span>`;
	};

	// 2. Главный слайдер (вертикальный)
	const mainSwiper = new Swiper(mainSliderEl, {
		spaceBetween: 20,
		slidesPerView: 1,
		rewind: true,
		thumbs: {
			swiper: thumbSwiper,
		},
		pagination: {
			el: ".product-swiper-main__pagination",
			type: "bullets",
			clickable: true,
		},
		on: {
			init: function () {
				updateCounter(this);
			},
			slideChange: function () {
				updateCounter(this);
			},
		},
	});
});

document.addEventListener("DOMContentLoaded", () => {
	const singleProduct = document.querySelector(".product-single");
	const modal = document.getElementById("cart-added-modal");

	if (!singleProduct || !modal) return;

	const modalTitle = modal.querySelector(".js-cart-modal-title");
	const modalPrice = modal.querySelector(".js-cart-modal-price");
	const modalImg = modal.querySelector(".js-cart-modal-img");
	const modalAttrs = modal.querySelector(".js-cart-modal-attrs");

	function fillAndOpenModal() {
		const titleText =
			singleProduct
				.querySelector(".product-single__title")
				?.textContent.trim() || "";
		const priceHtml =
			singleProduct.querySelector(".product-single__price")?.innerHTML || "";

		const mainImg =
			singleProduct.querySelector(".product-swiper-main-slide img") ||
			singleProduct.querySelector(".product-single__media img");
		const mainImgSrc = mainImg ? mainImg.getAttribute("src") : "";

		const selectedAttrs = [];

		singleProduct
			.querySelectorAll('form.cart select[name^="attribute_"]')
			.forEach((select) => {
				const opt = select.options[select.selectedIndex];
				if (opt && opt.value) {
					selectedAttrs.push(opt.textContent.trim());
				}
			});

		if (!selectedAttrs.length) {
			singleProduct
				.querySelectorAll(".product-card__size-btn.active")
				.forEach((btn) => {
					selectedAttrs.push(btn.textContent.trim());
				});
		}

		if (modalTitle) modalTitle.textContent = titleText;
		if (modalPrice) modalPrice.innerHTML = priceHtml;
		if (modalImg && mainImgSrc) modalImg.src = mainImgSrc;
		if (modalAttrs) modalAttrs.textContent = selectedAttrs.join(" • ");

		modal.classList.add("is-active");
		modal.setAttribute("aria-hidden", "false");
	}

	function closeModal() {
		modal.classList.remove("is-active");
		modal.setAttribute("aria-hidden", "true");
	}

	modal.querySelectorAll(".js-cart-modal-close").forEach((btn) => {
		btn.addEventListener("click", (e) => {
			e.preventDefault();
			closeModal();
		});
	});

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && modal.classList.contains("is-active")) {
			closeModal();
		}
	});

	const submitBtn = singleProduct.querySelector(".single_add_to_cart_button");

	if (submitBtn) {
		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (
					mutation.attributeName === "class" &&
					submitBtn.classList.contains("added")
				) {
					fillAndOpenModal();
				}
			});
		});

		observer.observe(submitBtn, { attributes: true });
	}

	document.body.addEventListener("added_to_cart", fillAndOpenModal);

	if (window.jQuery) {
		window.jQuery(document.body).on("added_to_cart", fillAndOpenModal);
	}
});

document.addEventListener("DOMContentLoaded", () => {
	let updateTimeout = null;

	// Вспомогательная функция отправки нативной кнопки обновления корзины
	function triggerCartUpdate() {
		clearTimeout(updateTimeout);
		updateTimeout = setTimeout(() => {
			const updateBtn = document.querySelector('button[name="update_cart"]');
			if (updateBtn) {
				updateBtn.disabled = false;
				updateBtn.removeAttribute("aria-disabled");

				if (window.jQuery) {
					window.jQuery(updateBtn).trigger("click");
				} else {
					updateBtn.click();
				}
			}
		}, 500); // 500 мс паузы после последнего действия пользователя
	}

	// 1. Клики по кнопкам + и -
	document.addEventListener("click", (e) => {
		const btn = e.target.closest(".qty-control__btn");
		if (!btn) return;

		e.preventDefault();

		const control = btn.closest(".qty-control");
		if (!control) return;

		const input = control.querySelector("input.qty");
		if (!input) return;

		let currentVal = parseInt(input.value, 10);
		if (isNaN(currentVal)) currentVal = 1;

		const step = parseInt(input.step, 10) || 1;
		const min = parseInt(input.min, 10) || 1;
		const max = parseInt(input.max, 10) || 9999;

		if (btn.classList.contains("qty-control__btn--plus")) {
			if (currentVal + step <= max) {
				input.value = currentVal + step;
			}
		} else if (btn.classList.contains("qty-control__btn--minus")) {
			if (currentVal - step >= min) {
				input.value = currentVal - step;
			}
		}

		input.dispatchEvent(new Event("change", { bubbles: true }));
		triggerCartUpdate();
	});

	// 2. Ручной ввод цифр с клавиатуры в input.qty
	document.addEventListener("input", (e) => {
		const input = e.target.closest(".qty-control input.qty");
		if (!input) return;

		const val = parseInt(input.value, 10);
		const min = parseInt(input.min, 10) || 1;
		const max = parseInt(input.max, 10) || 9999;

		// Если пользователь очистил поле или ввел 0, ждем ввода нормального числа
		if (isNaN(val) || val < min) {
			return;
		}

		// Если введено число больше максимума — сбрасываем до максимума
		if (val > max) {
			input.value = max;
		}

		input.dispatchEvent(new Event("change", { bubbles: true }));
		triggerCartUpdate();
	});

	// 3. Если поле осталось пустым при потере фокуса (blur), возвращаем 1
	document.addEventListener("focusout", (e) => {
		const input = e.target.closest(".qty-control input.qty");
		if (!input) return;

		let val = parseInt(input.value, 10);
		const min = parseInt(input.min, 10) || 1;

		if (isNaN(val) || val < min) {
			input.value = min;
			input.dispatchEvent(new Event("change", { bubbles: true }));
			triggerCartUpdate();
		}
	});

	// 4. Защита от перезагрузки страницы по нажатию Enter в инпуте количества
	document.addEventListener("keydown", (e) => {
		if (e.key === "Enter" && e.target.closest(".qty-control input.qty")) {
			e.preventDefault();
			e.target.blur(); // убираем фокус, вызывая сохранение
		}
	});
});

// !===============
