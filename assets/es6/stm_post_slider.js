(function($) {
	$(document).ready(() =>  {
		stmPostSlider();
	});
	const stmPostSlider = () => {

		$('.stm-post__slider__nav li a').on('click', function (e) {
			e.preventDefault();
			let activeSlide = $(this).attr('href');
			$('.stm-slide').removeClass('active');
			$(activeSlide).addClass('active');
			$('.stm-post__slider__nav li').removeClass('active');
			$(this).parent().addClass('active');
		});

	}
})(jQuery);
