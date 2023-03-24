// Site Configurating
$("#title").text('aByte');

if (document.cookie.split(';').filter(item) {
	return item.trim().startsWith('aByte=');
}
	.length) {
	var creds = document.cookie.replace(/(?:(?:^|.*;\s*)aByte\s*\=\s*([^;]*).*$)|^.*$/, "$1");
	$.get("controller.php?validate=creds", function(data){
		if (data.success) {
			$("#auth").text('Dashboard');
			$("#auth").attr('href', 'dash.php');
		} else {
			// Login Form
			$(document).ready(function() {
				$('.popup-with-form').magnificPopup({
					type: 'inline',
					preloader: true,
					focus: '#login',

					callbacks: {
						beforeOpen: function() {
							if($(window).width() < 700) {
								this.st.focus = false;
							} else {
								this.st.focus = '#login';
							}
						}
					}
				});
			});
		}
	});
} else {
	// Login Form
	$(document).ready(function() {
		$('.popup-with-form').magnificPopup({
			type: 'inline',
			preloader: true,
			focus: '#login',
			callbacks: {
				beforeOpen: function() {
					if($(window).width() < 700) {
						this.st.focus = false;
					} else {
						this.st.focus = '#login';
					}
				}
			}
		});
	});
}

(function ($) {
	"use strict";

	$(window).on('scroll', function () {
		var scroll = $(window).scrollTop();
		if (scroll < 400) {
			$("#sticky-header").removeClass("sticky");
			$('#back-top').fadeIn(500);
		} else {
			$("#sticky-header").addClass("sticky");
			$('#back-top').fadeIn(500);
		}
	});

	$(document).ready(function(){

		// Initialize Isotope
		var $grid = $('.grid').isotope({
			itemSelector: '.grid-item',
			percentPosition: true,
			masonry: {
				columnWidth: 1
			}
		});

		// Initialize WOWjs
		new WOW().init();

		// Counter
		$('.counter').counterUp({
			delay: 10,
			time: 10000
		});


		// scrollIt for smoth scroll
		$.scrollIt({
			upKey: 38,						 // key code to navigate to the next section
			downKey: 40,					 // key code to navigate to the previous section
			easing: 'linear',			// the easing function for animation
			scrollTime: 600,			 // how long (in ms) the animation takes
			activeClass: 'active', // class given to the active nav element
			onPageChange: null,		// function(pageIndex) that is called when page is changed
			topOffset: 0					 // offste (in px) for fixed top navigation
		});

		// scrollup bottom to top
		$.scrollUp({
			scrollName: 'scrollUp', // Element ID
			topDistance: '4500', // Distance from top before showing element (px)
			topSpeed: 300, // Speed back to top (ms)
			animation: 'fade', // Fade, slide, none
			animationInSpeed: 200, // Animation in speed (ms)
			animationOutSpeed: 200, // Animation out speed (ms)
			scrollText: '<i class="fa fa-angle-double-up"></i>', // Text for element
			activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
		});
	});

	// Registration Form
	$(document).ready(function() {
		$('.dont-hav-acc').magnificPopup({
			type: 'inline',
			preloader: false,
			focus: '#name',

			// When elemened is focused, some mobile browsers in some cases zoom in
			// It looks not nice, so we disable it:
			callbacks: {
				beforeOpen: function() {
					if($(window).width() < 700) {
						this.st.focus = false;
					} else {
						this.st.focus = '#name';
					}
				}
			}
		});
	});

	// Search Toggle
	$("#search_input_box").hide();
	$("#search").on("click", function() {
		$("#search_input_box").slideToggle();
		$("#search_input").focus();
	});

	$("#close_search").on("click", function() {
		$('#search_input_box').slideUp(500);
	});
	
	$("#search_input_box").hide();
	$("#search_1").on("click", function() {
		$("#search_input_box").slideToggle();
		$("#search_input").focus();
	});
})(jQuery);	

$(document).ready(function() {
	$("#registerButton").click(function (event) {
		event.preventDefault();
		var form = $('#register')[0];
		var data = new FormData(form);
		$("#registerButton").prop("disabled", true);
 
		$.ajax({
			type: "POST",
			enctype: 'multipart/form-data',
			url: "controller.php",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 800000,
			success: function (data) {
				$("#output").text(data);
				$("#registerButton").prop("disabled", false);
				window.location.replace('dash.php');
			},
			error: function (e) {
				$("#output").text(e.responseText);
				$("#registerButton").prop("disabled", false);
			}
		});
	});

	$("#loginButton").click(function (event) {
		event.preventDefault();
		var form = $('#login')[0];
		var data = new FormData(form);
		$("#loginButton").prop("disabled", true);
 
		$.ajax({
			type: "POST",
			enctype: 'multipart/form-data',
			url: "controller.php",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			timeout: 800000,
			success: function (data) {
				if (data.error) {
					Swal.fire('Login?', 'We think your Credentials not match with our Database', 'error');
				} else {
					$("#auth").text('Dashboard');
					$("#auth").attr('href', 'dash.php');

				}
				$("#loginButton").prop("disabled", false);
				window.location.replace('dash.php');
			},
			error: function (e) {
				Swal.fire('Login?', 'Check your Connection?', 'warning');
				$("#logginButton").prop("disabled", false);
			}
		});
	});
});