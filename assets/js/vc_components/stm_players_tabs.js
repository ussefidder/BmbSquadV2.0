(function ($) {
	$(document).ready(function () {
		$('.player-carousel').owlCarousel({
			items: 4,
			autoplay: false,
			slideBy: 1,
			nav:true,
			loop: true,
			margin: 30,
			responsive:{
				0:{
					items:1
				},
				767:{
					items:2
				},
				900:{
					items:3
				},
				1200:{
					items:4
				}
			}
		});
		$('.stm-players-tabs .slider-navs .prev').on('click', function (e) {
			e.preventDefault();
			var count = $('.stm-players-tabs').attr('data-count');
			$('.player-carousel .owl-prev').trigger('click');
			setTimeout(function () {
				var currentSlide = $('.player-info-wrap.active').attr('data-slide');
				if(currentSlide == 1){
					$('.owl-item.active .player-slide-thumb-' + count).trigger('click');
				}
				else {
					$('.owl-item.active .player-slide-thumb-' + (parseInt(currentSlide) - 1)).trigger('click');
				}
			}, 50)

		});
		$('.stm-players-tabs .slider-navs .next').on('click', function (e) {
			e.preventDefault();
			var count = $('.stm-players-tabs').attr('data-count');
			$('.player-carousel .owl-next').trigger('click');
			setTimeout(function () {
				var currentSlide = $('.player-info-wrap.active').attr('data-slide');

				if(currentSlide == $('.player-info-wrap').length){
					$('.owl-item.active .player-slide-thumb-1').trigger('click');
				}
				else {
					$('.owl-item.active .player-slide-thumb-' + (parseInt(currentSlide) + 1)).trigger('click');
				}
			}, 50)

		});
		$('.player-carousel__item a').on('click', function (e) {
			e.preventDefault();
			$('.player-carousel__item a').removeClass('active');
			$(this).addClass('active');
			var activeSlide = $(this).attr('href');
			$('.player-info-wrap').removeClass('active');
			$('.' + activeSlide).addClass('active');
		});
	});
})(jQuery);