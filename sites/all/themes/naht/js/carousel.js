(function ($) {
Drupal.behaviors.carouselReviews = {
	attach: function (context, settings) {
		$('.owl-carousel').owlCarousel({
			autoplay: true,
            center: true,
            loop: true,
            items: 1,
            margin: 30,
            stagePadding: 0,
            nav: false,
            navText: ['<span class="ion-ios-arrow-back">', '<span class="ion-ios-arrow-forward">'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 2
                }
            }
		});
	}
};
})(jQuery);