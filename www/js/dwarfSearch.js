(function ($) {
	var scrollTo = function (link) {
		link = $(link);
		$('body').animate({
			'scrollTop': link.offset().top - 200
		}, 10, 'swing', function () {
			$('.screenplay-line-link').removeClass('list-group-item-info');
			link.addClass('list-group-item-info');
		});
	};

	$(document).on('click', '.screenplay-line-link', function () {
		scrollTo(this);
	});

	setTimeout(function () {
		if (location.hash && location.hash.match(/^#screenplay-line-/)) {
			scrollTo($(location.hash));
		}
	}, 10);
})(jQuery);
