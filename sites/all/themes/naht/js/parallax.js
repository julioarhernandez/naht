(function ($) {
Drupal.behaviors.parallaxMeStellar = {
	attach: function (context, settings) {
	$(window).stellar({
		horizontalScrolling: false,
		responsive: true
	});
	}
};
})(jQuery);