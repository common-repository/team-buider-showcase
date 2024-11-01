(function ($) {
	'use strict';

	$(document).ready(function () {

		// Copy shortcode functionality
		$('.copy-plwl-shortcode').click(function (e) {
			e.preventDefault();
			var gallery_shortcode = $(this).parent().find('input');
			gallery_shortcode.focus();
			gallery_shortcode.select();
			document.execCommand('copy');
			$(this).next('span').text('Shortcode copied');
			$('.copy-plwl-shortcode').not($(this)).parent().find('span').text('');
		});

		// Dismiss notice
		$('body').on('click', '#plwl-lightbox-upgrade .notice-dismiss', function (e) {
			e.preventDefault();
			var notice = $(this).parent();

			var data = {
				action: 'plwl_lbu_notice',
				nonce: teamBuilderShowcaseHelper._wpnonce
			};

			$.post(teamBuilderShowcaseHelper.ajax_url, data, function (response) {
				// Redirect to plugins page
				notice.remove();
			});
		});
	});

	const plwlOpenModal = (e) => {
		e.preventDefault();
		const upsell = e.data.upsell;
		$.get(
			teamBuilderShowcaseHelper.ajax_url, {
				action: 'plwl_modal-' + upsell + '_upgrade'
			},
			(html) => {
				$('body').addClass('modal-open');
				$('body').append(html);

				$(document).one('click', '.plwl-modal__overlay.'+ upsell, {
					upsell
				}, plwlCloseModal);
				$(document).one('click', '.plwl-modal__dismiss.'+ upsell, {
					upsell
				}, plwlCloseModal);
			}
		);
	};

	const plwlCloseModal = (e) => {
		const upsell = e.data.upsell;
		$('.plwl-modal__overlay.'+ upsell).remove();
		$('body').removeClass('modal-open');
	};

	$('body').on(
		'click',
		'#adminmenu #menu-posts-plwl-team-builder-showcase ul li a[href="edit.php?post_type=plwl-team&page=#plwl-albums"]', {
			upsell: 'albums'
		},
		plwlOpenModal
	);

	 $('body').on(
		'click',
		'#adminmenu #menu-posts-plwl-team-builder-showcase ul li a[href="edit.php?post_type=plwl-team&page=#gallery-defaults"]',
		{upsell:'gallery-defaults'},
		plwlOpenModal
	);

	$('body').on(
		'click',
		'#adminmenu #menu-posts-plwl-team-builder-showcase ul li a[href="edit.php?post_type=plwl-team&page=#albums-defaults"]',
		{upsell:'albums-defaults'},
		plwlOpenModal
	);

})(jQuery);