/* global window, document */

(function ($) {
	if (!$) return;

	function notifyError(message, title) {
		if (window.toastr && typeof window.toastr.error === 'function') {
			if (typeof window.toastr.clear === 'function') {
				window.toastr.clear();
			}
			window.toastr.error(message, title || 'Lỗi');
			return;
		}

		window.alert((title ? title + ': ' : '') + message.replace(/<br\s*\/?\s*>/gi, '\n'));
	}

	function isValidEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(String(email || '').trim());
	}

	function parseQuantity(raw) {
		const num = parseInt(String(raw || '').replace(/[^0-9]/g, ''), 10);
		return Number.isFinite(num) && num > 0 ? num : 1;
	}

	$(document).ready(function () {

		function showWishlistModal(productId) {
			const modalId = 'liton_wishlist_modal-' + productId;
			const el = document.getElementById(modalId);
			if (!el) return;

			if (window.bootstrap && window.bootstrap.Modal) {
				window.bootstrap.Modal.getOrCreateInstance(el).show();
				return;
			}

			if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
				window.jQuery(el).modal('show');
			}
		}

		function showAddToCartModal(productId) {
			const modalId = 'add_to_cart_modal-' + productId;
			const el = document.getElementById(modalId);
			if (!el) return;

			// Bootstrap 5 (no jQuery) support
			if (window.bootstrap && window.bootstrap.Modal) {
				window.bootstrap.Modal.getOrCreateInstance(el).show();
				return;
			}

			// Fallback for setups that still expose jQuery modal()
			if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
				window.jQuery(el).modal('show');
			}
		}

		function hideQuickViewModal(productId) {
			const modalId = 'quick_view_modal-' + productId;
			const el = document.getElementById(modalId);
			if (!el) return;

			// Bootstrap 5 (no jQuery) support
			if (window.bootstrap && window.bootstrap.Modal) {
				window.bootstrap.Modal.getOrCreateInstance(el).hide();
				return;
			}

			// Fallback for setups that still expose jQuery modal()
			if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.modal === 'function') {
				window.jQuery(el).modal('hide');
			}
		}

		$(document).on('click', '.js-add-to-cart', function (e) {
			e.preventDefault();

			const $btn = $(this);
			const url = $btn.data('url') || '/cart/add';
			const productId = parseInt($btn.data('product-id'), 10);
			if (!Number.isFinite(productId) || productId <= 0) {
				notifyError('Không xác định được sản phẩm để thêm vào giỏ hàng.', 'Lỗi');
				return;
			}

			// Try to detect quantity from nearby UI (product detail / quick view)
			let qty = 1;
			const $qtyInput = $btn.closest('ul, .ltn__product-details-menu-2, .modal-product-info, .product-info')
				.find('input.cart-plus-minus-box')
				.first();
			if ($qtyInput.length) {
				qty = parseQuantity($qtyInput.val());
			}

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$btn.attr('aria-disabled', 'true');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: productId,
					quantity: qty
				},
				success: function (response) {
					if (response && response.success) {
						if (window.toastr && typeof window.toastr.success === 'function') {
							window.toastr.success(response.message || 'Đã thêm vào giỏ hàng.');
						}
						if (typeof response.cart_count !== 'undefined') {
							$('#cart_count').text(response.cart_count);
						} else {
							const currentCount = parseQuantity($('#cart_count').text());
							$('#cart_count').text(currentCount + qty);
						}
						hideQuickViewModal(productId);
						showAddToCartModal(productId);

						// If mini-cart is currently open, refresh its content.
						const $miniCartMenu = $('#ltn__utilize-cart-menu, #ltn___utilize-cart-menu');
						if ($miniCartMenu.length && $miniCartMenu.hasClass('ltn__utilize-open')) {
							$('.mini-cart-icon').trigger('click');
						}
						return;
					}
					notifyError((response && response.message) ? response.message : 'Thêm vào giỏ hàng thất bại.', 'Lỗi');
				},
				error: function (xhr) {
					let message = 'Thêm vào giỏ hàng thất bại.';
					if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					notifyError(message, 'Lỗi');
				},
				complete: function () {
					$btn.removeAttr('aria-disabled');
				}
			});
		});

		// Add to cart button used in product grids (home/products list)
		$(document).on('click', '.add-to-cart-btn', function (e) {
			e.preventDefault();

			const $btn = $(this);
			const url = $btn.data('url') || '/cart/add';
			const productId = parseInt($btn.data('product-id') || $btn.data('id'), 10);
			if (!Number.isFinite(productId) || productId <= 0) {
				notifyError('Không xác định được sản phẩm để thêm vào giỏ hàng.', 'Lỗi');
				return;
			}

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$btn.attr('aria-disabled', 'true');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: productId,
					quantity: 1
				},
				success: function (response) {
					if (response && response.success) {
						if (window.toastr && typeof window.toastr.success === 'function') {
							window.toastr.success(response.message || 'Đã thêm vào giỏ hàng.');
						}
						if (typeof response.cart_count !== 'undefined') {
							$('#cart_count').text(response.cart_count);
						} else {
							const currentCount = parseQuantity($('#cart_count').text());
							$('#cart_count').text(currentCount + 1);
						}
						showAddToCartModal(productId);

						const $miniCartMenu = $('#ltn__utilize-cart-menu, #ltn___utilize-cart-menu');
						if ($miniCartMenu.length && $miniCartMenu.hasClass('ltn__utilize-open')) {
							$('.mini-cart-icon').trigger('click');
						}
						return;
					}
					notifyError((response && response.message) ? response.message : 'Thêm vào giỏ hàng thất bại.', 'Lỗi');
				},
				error: function (xhr) {
					let message = 'Thêm vào giỏ hàng thất bại.';
					if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					notifyError(message, 'Lỗi');
				},
				complete: function () {
					$btn.removeAttr('aria-disabled');
				}
			});
		});

		// Add to wishlist
		$(document).on('click', '.add-to-wishlist', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			const $btn = $(this);
			const url = $btn.data('url') || '/wishlist/add';
			const productId = parseInt($btn.data('product-id') || $btn.data('id'), 10);
			if (!Number.isFinite(productId) || productId <= 0) {
				notifyError('Không xác định được sản phẩm để thêm vào yêu thích.', 'Lỗi');
				return;
			}

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$btn.attr('aria-disabled', 'true');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: productId
				},
				success: function (response) {
					if (response && response.status) {
						showWishlistModal(productId);
						return;
					}
					notifyError((response && response.message) ? response.message : 'Thêm vào yêu thích thất bại.', 'Lỗi');
				},
				error: function (xhr) {
					if (xhr && xhr.status === 401) {
						window.location.href = '/login';
						return;
					}
					let message = 'Thêm vào yêu thích thất bại.';
					if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					notifyError(message, 'Lỗi');
				},
				complete: function () {
					$btn.removeAttr('aria-disabled');
				}
			});
		});

		// Validate register form
		$('#register-form').submit(function (e) {
			const firstname = $('input[name="firstname"]').val() || '';
			const lastname = $('input[name="lastname"]').val() || '';
			const fullName = (firstname + ' ' + lastname).trim();
			const email = $('input[name="email"]').val() || '';
			const password = $('input[name="password"]').val() || '';
			const confirmPassword = $('input[name="password_confirmation"]').val() || '';
			const checkbox1 = $('input[name="checkbox1"]').is(':checked');
			const checkbox2 = $('input[name="checkbox2"]').is(':checked');
			let errorMessage = '';

			if (fullName.length < 3) {
				errorMessage += 'Họ và tên phải có ít nhất 3 ký tự. <br>';
			}

			if (!isValidEmail(email)) {
				errorMessage += 'Email không hợp lệ. <br>';
			}

			if (password.length < 6) {
				errorMessage += 'Mật khẩu phải có ít nhất 6 ký tự. <br>';
			}

			if (password !== confirmPassword) {
				errorMessage += 'Mật khẩu nhập lại không khớp. <br>';
			}

			if (!checkbox1 || !checkbox2) {
				errorMessage += 'Bạn phải đồng ý với các điều khoản trước khi tạo tài khoản. <br>';
			}

			if (errorMessage !== '') {
				notifyError(errorMessage, 'Lỗi');
				e.preventDefault();
			}
		});

		// Validate login form
		$('#login-form').submit(function (e) {
			if (window.toastr && typeof window.toastr.clear === 'function') {
				window.toastr.clear();
			}
			const email = $('input[name="email"]').val() || '';
			const password = $('input[name="password"]').val() || '';
			let errorMessage = '';

			if (!isValidEmail(email)) {
				errorMessage += 'Email không hợp lệ. <br>';
			}

			if (password.length < 6) {
				errorMessage += 'Mật khẩu phải có ít nhất 6 ký tự. <br>';
			}

			if (errorMessage !== '') {
				notifyError(errorMessage, 'Lỗi');
				e.preventDefault();
			}
		});

		// Validate reset password form
		$('#reset-password-form').submit(function (e) {
			if (window.toastr && typeof window.toastr.clear === 'function') {
				window.toastr.clear();
			}
			const email = $('input[name="email"]').val() || '';
			const password = $('input[name="password"]').val() || '';
			const confirmPassword = $('input[name="password_confirmation"]').val() || '';
			let errorMessage = '';

			if (!isValidEmail(email)) {
				errorMessage += 'Email không hợp lệ. <br>';
			}

			if (password.length < 6) {
				errorMessage += 'Mật khẩu phải có ít nhất 6 ký tự. <br>';
			}
			if (password !== confirmPassword) {
				errorMessage += 'Mật khẩu nhập lại không khớp. <br>';
			}

			if (errorMessage !== '') {
				notifyError(errorMessage, 'Lỗi');
				e.preventDefault();
			}
		});

		//***PAGE ACCOUNT
		$('.profile-pic').click(function () {
			$("#avatar").click();
		});

		$("#avatar").change(function () {
			let input = this;
			if (input.files && input.files[0]) {
				let reader = new FileReader();
				reader.onload = function (e) {
					$('#preview-image').attr('src', e.target.result);
				};
				reader.readAsDataURL(input.files[0]);
			}
		});

		$('#update-account').submit(function (e) {
			// By default, allow normal form submission so the button always works.
			// Enable AJAX update only when explicitly requested via: data-ajax="1"
			if ($(this).data('ajax') !== 1 && $(this).data('ajax') !== '1') {
				return true;
			}

			e.preventDefault();

			let formData = new FormData(this);
			let urlUpdate = $(this).attr('action');
			let $submitBtn = $(this).find('button[type="submit"]');
			let originalBtnText = $submitBtn.text();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			});
			$.ajax({
				url: urlUpdate,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function () {
					$submitBtn.text('Đang cập nhật...').attr('disabled', true);
				},
				success: function (response) {
					if (response.success) {
						toastr.success(response.message);
						if (response.avatar) {
							$('.profile-pic').attr('src', response.avatar);
						}
					} else {
						toastr.error(response.message || 'Cập nhật thất bại. Vui lòng thử lại.');
					}
				},
				error: function (xhr) {
					// handled in complete
				},
				complete: function () {
					$submitBtn.text(originalBtnText).attr('disabled', false);
				}
			});
		});

		// Change password form
		$('#change-password-form').submit(function (e) {
			if (window.toastr && typeof window.toastr.clear === 'function') {
				window.toastr.clear();
			}
			let current_password = $(this).find('input[name="current_password"]').val().trim();
			const new_password = $(this).find('input[name="new_password"]').val().trim();
			const confirm_new_password = $(this).find('input[name="confirm_new_password"]').val().trim();
			let errorMessage = '';

			if (current_password.length < 6) {
				errorMessage += 'Mật khẩu hiện tại phải có ít nhất 6 ký tự. <br>';
			}
			if (new_password.length < 6) {
				errorMessage += 'Mật khẩu mới phải có ít nhất 6 ký tự. <br>';
			}
			if (new_password !== confirm_new_password) {
				errorMessage += 'Mật khẩu nhập lại không khớp. <br>';
			}

			if (errorMessage !== '') {
				notifyError(errorMessage, 'Lỗi');
				e.preventDefault();
				return;
			}

			e.preventDefault();
			let $submitBtn = $(this).find('button[type="submit"]');
			let originalBtnText = $submitBtn.text();

			let formData = $(this).serialize();
			let urlUpdate = $(this).attr('action');

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			});

			$.ajax({
				url: urlUpdate,
				type: 'POST',
				data: formData,
				beforeSend: function () {
					$submitBtn.text('Đang cập nhật...').attr('disabled', true);
				},
				success: function (response) {
					if (response.success) {
						toastr.success(response.message);
						$('#change-password-form')[0].reset();
					} else {
						toastr.error(response.message || 'Cập nhật thất bại. Vui lòng thử lại.');
					}
				},
				error: function (xhr) {
					// handled in complete
				},
				complete: function () {
					$submitBtn.text(originalBtnText).attr('disabled', false);
				}
			});
		});

		// Add address form (account page)
		$('#addAddressForm').submit(function (e) {
			let isValid = true;
			$('.error-message').remove();

			let fullName = ($(this).find('input[name="full_name"]').val() || '').trim();
			let phone = ($(this).find('input[name="phone_number"]').val() || '').trim();

			if (fullName.length < 3) {
				isValid = false;
				$(this).find('input[name="full_name"]').after(
					'<p class="error-message text-danger">Họ và tên phải có ít nhất 3 ký tự.</p>'
				);
			}

			let phoneRegex = /^\d{10,15}$/;
			if (!phoneRegex.test(phone)) {
				isValid = false;
				$(this).find('input[name="phone_number"]').after(
					'<p class="error-message text-danger">Số điện thoại không hợp lệ.</p>'
				);
			}

			if (!isValid) {
				e.preventDefault();
			}
		});

		// Products page filters
		let currentPage = 1;
		$(document).on('click', '.pagination a', function (e) {
			e.preventDefault();
			let url = $(this).attr('href');
			let page = url.split('page=')[1];
			currentPage = parseInt(page);
			fetchProducts();
		});

		function fetchProducts() {
			let category_id = $('.category-filter.active').data('id') || null;
			let sort_by = $('#short-by').val() || 'default';

			let minPrice = null;
			let maxPrice = null;
			if ($.fn.slider && $('.slider-range').length) {
				minPrice = $('.slider-range').slider('values', 0);
				maxPrice = $('.slider-range').slider('values', 1);
			}

			$.ajax({
				url: '/products/filter?page=' + currentPage,
				type: 'GET',
				dataType: 'json',
				data: {
					category_id: category_id,
					min_price: minPrice,
					max_price: maxPrice,
					sort_by: sort_by
				},
				beforeSend: function () {
					$('#loading-spinner').css('display', 'flex');
					$('#loading_product_grid').hide();
				},
				success: function (response) {
					if (response && response.success) {
						$('#loading_product_grid').html(response.html || '');
						$('#loading_product_grid').show();
						$('#cart_count').text(response.cart_count);
						if (typeof response.pagination !== 'undefined') {
							$('.ltn__pagination').html(response.pagination || '');
						}
						return;
					}

					notifyError(
						(response && response.message) ? response.message : 'Không thể tải danh sách sản phẩm.',
						'Lỗi'
					);
				},
				complete: function () {
					$('#loading-spinner').hide();
					$('#liton_product_grid').show();
				},
				error: function (xhr) {
					let message = 'Đã có lỗi xảy ra khi tải sản phẩm.';
					if (xhr && xhr.responseJSON) {
						message = xhr.responseJSON.message || message;
					}
					notifyError(message, 'Lỗi');
				}
			});
		}

		$(document).on('click', '.category-filter', function (e) {
			e.preventDefault();
			$('.category-filter').removeClass('active');
			$(this).addClass('active');
			currentPage = 1;
			fetchProducts();
		});

		$('#short-by').change(function () {
			currentPage = 1;
			fetchProducts();
		});

		if ($.fn.slider && $('.slider-range').length) {
			$('.slider-range').slider({
				range: true,
				min: 0,
				max: 300000,
				values: [0, 300000],
				slide: function (event, ui) {
					$('.amount').val(ui.values[0] + ' - ' + ui.values[1] + ' VNĐ');
				},
				change: function () {
					currentPage = 1;
					fetchProducts();
				}
			});
			$('.amount').val(
				$('.slider-range').slider('values', 0) + ' - ' + $('.slider-range').slider('values', 1) + ' VNĐ'
			);
		}
	}); // end document.ready

	function getMiniCartElements() {
		// Theme markup uses 2 underscores; keep fallback for older markup.
		let $menu = $('#ltn__utilize-cart-menu');
		if (!$menu.length) {
			$menu = $('#ltn___utilize-cart-menu');
		}
		let $inner = $menu.find('.ltn__utilize-menu-inner').first();
		if (!$inner.length && $menu.hasClass('ltn__utilize-menu-inner')) {
			$inner = $menu;
		}
		const $overlay = $('.ltn__utilize-overlay');
		return { $menu, $inner, $overlay };
	}

	// Use delegated handler so it works on every page/layout.
	$(document).on('click', '.mini-cart-icon, .mini-cart-icon a, a.ltn__utilize-toggle[href="#ltn__utilize-cart-menu"], a.ltn__utilize-toggle[href="#ltn___utilize-cart-menu"]', function () {
		const els = getMiniCartElements();
		if (!els.$menu.length) return;

		$.ajax({
			url: '/mini-cart',
			type: 'GET',
			dataType: 'json',
			success: function (response) {
				const ok = !!(response && (response.status || response.success));
				if (!ok) {
					notifyError('Không thể tải giỏ hàng', 'Lỗi');
					return;
				}

				if (typeof response.html === 'string') {
					els.$inner.html(response.html);
				}
				if (typeof response.cart_count !== 'undefined') {
					$('#cart_count').text(response.cart_count);
				}

				// Ensure open state even if theme toggle isn't active.
				els.$menu.addClass('ltn__utilize-open');
				if (els.$overlay.length) {
					els.$overlay.show();
				}
			},
			error: function () {
				notifyError('Không thể tải giỏ hàng', 'Lỗi');
			}
		});
	});

	$(document).on('click', '.ltn__utilize-close', function () {
		const els = getMiniCartElements();
		els.$menu.removeClass('ltn__utilize-open');
		els.$overlay.hide();
	});

	// Remove product from cart
	$(document).on('click', '.mini-cart-item-delete', function () {
		let productId = $(this).data('id');
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			url: '/cart/remove',
			type: 'POST',
			dataType: 'json',
			data: { product_id: productId },
			success: function (response) {
				if (response.status) {
					$('#cart_count').text(response.cart_count);
					$('.mini-cart-icon').click();
				}
			}
		});
	});

	// PAGE DETAIL PRODUCT
	if (window.location.pathname !== '/cart') {
		$(document).on("click", ".qtybutton", function () {
			var $button = $(this);
			var $input = $button.siblings("input");
			var oldValue = parseQuantity($input.val());
			var rawMax = parseInt($input.data('max'), 10);
			var maxStock = (Number.isFinite(rawMax) && rawMax > 0) ? rawMax : Infinity;

			var newValue = oldValue;
			if ($button.hasClass("inc")) {
				newValue = Math.min(oldValue + 1, maxStock);
			} else {
				newValue = Math.max(oldValue - 1, 1);
			}
			$input.val(newValue);
		});

		$(document).on('input change', 'input.cart-plus-minus-box', function () {
			var $input = $(this);
			var value = parseQuantity($input.val());
			var rawMax = parseInt($input.data('max'), 10);
			var maxStock = (Number.isFinite(rawMax) && rawMax > 0) ? rawMax : Infinity;
			var clamped = Math.max(1, value);
			if (maxStock !== Infinity) {
				clamped = Math.min(clamped, maxStock);
			}
			$input.val(clamped);
		});
	} else {
		$(document).on("click", ".qtybutton", function () {
			var $button = $(this);
			var $input = $button.siblings("input");
			var oldValue = parseQuantity($input.val());
			var rawMax = parseInt($input.data('max'), 10);
			var maxStock = (Number.isFinite(rawMax) && rawMax > 0) ? rawMax : Infinity;
			var productId = $input.data('id');
			let newValue = oldValue;

			if ($button.hasClass("inc") && oldValue < maxStock) {
				newValue = oldValue + 1;
			} else if ($button.hasClass("dec") && oldValue > 1) {
				newValue = oldValue - 1;
			}
			if (newValue !== oldValue) {
				updateCart(productId, newValue, $input);
			}
		});

		// ADD to cart (product detail button)
		$(document).on('click', '.add-to-cart', function (e) {
			e.preventDefault();

			const $btn = $(this);
			const url = $btn.data('url') || '/cart/add';
			const productId = parseInt($btn.data('id') || $btn.data('product-id'), 10);
			if (!Number.isFinite(productId) || productId <= 0) {
				notifyError('Không xác định được sản phẩm để thêm vào giỏ hàng.', 'Lỗi');
				return;
			}

			let qty = 1;
			const $qtyInput = $btn.closest('ul, .ltn__product-details-menu-2, .modal-product-info, .product-info, li')
				.find('input.cart-plus-minus-box')
				.first();
			if ($qtyInput.length) {
				qty = parseQuantity($qtyInput.val());
			}

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$btn.attr('aria-disabled', 'true');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: productId,
					quantity: qty,
				},
				success: function (response) {
					if (response && response.success) {
						if (typeof response.cart_count !== 'undefined') {
							$('#cart_count').text(response.cart_count);
						} else {
							const currentCount = parseQuantity($('#cart_count').text());
							$('#cart_count').text(currentCount + qty);
						}
						return;
					}
					notifyError((response && response.message) ? response.message : 'Thêm vào giỏ hàng thất bại.', 'Lỗi');
				},
				error: function (xhr) {
					let message = 'Thêm vào giỏ hàng thất bại.';
					if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					notifyError(message, 'Lỗi');
				},
				complete: function () {
					$btn.removeAttr('aria-disabled');
				}
			});
		});

		// PAGE CART
		function updateCart(productId, newValue, $input) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				url: '/cart/update',
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: productId,
					quantity: newValue // FIX: was "quantity: quantity" (undefined variable)
				},
				success: function (response) {
					if (!response) return;
					if (typeof response.quantity !== 'undefined') {
						$input.val(response.quantity);
					}
					if (typeof response.item_subtotal !== 'undefined') {
						$input.closest('tr').find('.cart-product-subtotal').text(response.item_subtotal);
					}
					if (typeof response.cart_total !== 'undefined') {
						$('.cart-total').text(response.cart_total);
					}
					if (typeof response.cart_grand_total !== 'undefined') {
						$('.cart-grand-total').text(response.cart_grand_total);
					}
					if (typeof response.cart_count !== 'undefined') {
						$('#cart_count').text(response.cart_count);
					}
				},
				error: function (xhr) {
					let message = 'Cập nhật giỏ hàng thất bại';
					if (xhr && xhr.responseJSON) {
						message = xhr.responseJSON.error || xhr.responseJSON.message || message;
					}
					alert(message);
				}
			});
		}
	}

	// PAGE CART: remove item (button "x")
	$(document).on('click', '.remove-from-cart', function (e) {
		e.preventDefault();

		const productId = $(this).data('id');
		const $row = $(this).closest('tr');

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			url: '/cart/remove-cart',
			type: 'POST',
			dataType: 'json',
			data: {
				product_id: productId,
			},
			success: function (response) {
				if (!response) return;
				if (!response.success) {
					const msg = response.error || response.message || 'Xóa sản phẩm khỏi giỏ hàng thất bại.';
					alert(msg);
					return;
				}

				$row.remove();
				if (typeof response.cart_total !== 'undefined') {
					$('.cart-total').text(response.cart_total);
				}
				if (typeof response.cart_grand_total !== 'undefined') {
					$('.cart-grand-total').text(response.cart_grand_total);
				}
				if (typeof response.cart_count !== 'undefined') {
					$('#cart_count').text(response.cart_count);
				}

				// If cart becomes empty, reload to show empty state.
				if ($('.shoping-cart-table tbody tr').length === 0) {
					location.reload();
				}
			},
			error: function (xhr) {
				let message = 'Xóa sản phẩm khỏi giỏ hàng thất bại';
				if (xhr && xhr.responseJSON) {
					message = xhr.responseJSON.error || xhr.responseJSON.message || message;
				}
				alert(message);
			}
		});
	});

	// Checkout: auto fill address info when selecting a saved address
	$(document).on('change', '#list_address', function () {
		const addressId = $(this).val();
		if (!addressId) return;

		$.ajax({
			url: '/check/get-address',
			type: 'GET',
			dataType: 'json',
			data: {
				address_id: addressId
			},
			success: function (response) {
				const ok = !!(response && (response.success || response.status));
				if (!ok) return;

				const data = response.data || {};
				$('input[name="ltn__name"]').val(data.full_name || '');
				$('input[name="ltn__phone"]').val(data.phone_number || '');
				$('input[name="ltn__address"]').val(data.address || '');
				$('input[name="ltn__city"]').val(data.city || '');
				$('input[name="ltn__id"]').val(data.id || '');
			},
			error: function () {
				notifyError('Không thể lấy địa chỉ', 'Lỗi');
			}
		});
	});
	// Handle Rating Product
	if(window.location.pathname.includes('/products/')) {
		let seletedRating = 0;
		$(".star-rating").hover(function(){
			let hoverValue = $(this).data('rating');
			highlightStars(hoverValue);
		},function(){
			highlightStars(seletedRating);
		});
		$(".star-rating").click(function(e){
			e.preventDefault();
			seletedRating = $(this).data('rating');
			$("#rating-value").val(seletedRating);
			highlightStars(seletedRating);
		});

	function highlightStars(value)
	{
		$(".star-rating").each(function(){
			let starValue = $(this).data('rating');
			let starIcon = $(this).find('i');
			if(starValue <= value){
				starIcon.removeClass('far').addClass('fas');
			}else{
				starIcon.removeClass('fas').addClass('far');
			}
		});
	}

	//Handle submit rating with AJAX
	$("#review-form").submit(function(e){
		e.preventDefault();

		let productId = $(this).data('product-id');
		let reviewUrl = $(this).data('url') || "/review";
		let rating = parseInt($("#rating-value").val(), 10) || 0;
		let comment = $("#review-content").val();

		let messageEl = $("#review-message");
		if(messageEl.length){
			messageEl.html('');
		}

		if(rating === 0){
			if(messageEl.length){
				messageEl.html('<div class="alert alert-warning">Vui lòng chọn số sao trước khi gửi đánh giá.</div>');
			}else{
				alert('Vui lòng chọn số sao trước khi gửi đánh giá.');
			}
			return;
		}

		$.ajax({
			url: reviewUrl,
			type: "POST",
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				product_id: productId,
				rating: rating,
				comment: comment
			},
			success: function(response){
				if(messageEl.length){
					messageEl.html('<div class="alert alert-success">' + (response.message || 'Đã gửi đánh giá.') + '</div>');
				}
				$("#review-content").val("");
				$("#rating-value").val("0");
				seletedRating = 0;
				highlightStars(0);
				if (typeof toastr !== 'undefined') {
					toastr.success(response.message || 'Đã gửi đánh giá.');
				}
				loadReviews(productId);
			},
			error: function(xhr){
				let msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error))
					? (xhr.responseJSON.message || xhr.responseJSON.error)
					: 'Gửi đánh giá thất bại. Vui lòng thử lại.';

				if(messageEl.length){
					messageEl.html('<div class="alert alert-danger">' + msg + '</div>');
				}else{
					alert(msg);
				}
			}
		});
	});
	function loadReviews(productId) {
    $.ajax({
        url: "/review/" + productId,
        type: "GET",
        success: function (response) {
            // Ghi đè HTML mới nhận được vào thẻ div chứa danh sách đánh giá
            $('.it_comment_list').html(response);
        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });
	}
	}
		// Handle Page Contact
		$("#contact-form").on(function(e){
			let name = $('input[name="name"]').val();
			let email = $('input[name="email"]').val();
			let phone = $('input[name="phone"]').val();
			let message = $('textarea[name="message"]').val();
			let errorMessage = "";

			if(name.length <3)
			{
				errorMessage += "Họ và Tên phải có ít nhất 3 ký tự. <br>";
			}

			if(phone.length < 10 || phone.length > 10){
				errorMessage += "Số điện thoại phải có 10 chữ số. <br>";
			}

			let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if(!emailRegex.test(email)){
				errorMessage += "Email không hợp lệ. <br>";
			}

			if(errorMessage !== ""){
				toastr.error(errorMessage, 'Lỗi');
				e.preventDefault();
				
			}
		});

			// Handle wishlist 
			$(document).ready(function () {
    // Khi click vào nút trái tim
    $(document).on('click', '.add-to-wishlist', function (e) {
        e.preventDefault();

        // Lấy ID sản phẩm từ data-id
        let productId = $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/wishlist/add", // Đường dẫn tương ứng với route POST đã tạo
            method: "POST",
            data: {
                product_id: productId
            },
            success: function (response) {
                if (response.status === true) {
                    // Thông báo Toastr thành công
                    toastr.success('Đã thêm vào danh sách yêu thích thành công.');
                    
                    // Show Model (Popup) báo thành công. Lưu ý id model cần match với id sản phẩm
                    $('#wishlist_modal_' + productId).modal('show'); 
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    // Xử lý nếu người dùng chưa đăng nhập
                    toastr.error('Vui lòng đăng nhập để thực hiện chức năng này.');
                } else {
                    console.log(xhr.responseText);
                }
            }
        });
    });
});

})(window.jQuery);