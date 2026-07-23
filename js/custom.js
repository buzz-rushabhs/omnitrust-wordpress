function init_intl_input(){
	const input = document.querySelector("#phone");

	if (input) {
		const iti = window.intlTelInput(input, {
			initialCountry: "us",
			preferredCountries: ["in", "us", "gb", "ca", "au"],
			utilsScript:
			"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/utils.js",
		});
		// Display full formatted number
		input.addEventListener("input", () => {
			const number = iti.getNumber();
			document.querySelector("#output").textContent = number
			? `Full Number: ${number}`
			: "";
		});

		jQuery('#phone').css('padding-left','42px')
	}

	jQuery('.interests-select').multipleSelect({
    	placeholder: 'Select'
	})

	jQuery('[name="first-name"]').trigger('focus');
}

jQuery(document).ready(function($) {

	init_intl_input()

	let wppopupData = {};

	$('.whitepaper-open-popup').on('click', function(){
        popupData = {
	        title: $(this).attr('title'),
	        pdf: $(this).data('url'),
	        img: $(this).data('img')
	    };
        elementorProFrontend.modules.popup.showPopup( { id: 5083 } ); // replace with your popup ID
    });

    $(document).on('wpcf7init', function () {
	    $('[name="white-paper"]').val(popupData.title);
	    $('[name="white-paper-url"]').val(popupData.pdf);
	    $('.whitepaper-image img').attr('src',popupData.img);
	});

	let clickedPostId = null;
	let prod_sheet_data = {};

	jQuery(document).on('click', '.open-datasheet-popup-loop', function () {
	    clickedPostId = jQuery(this).data('post-id');

	    jQuery.ajax({
            url: js_config.ajax_url,
            type: 'POST',
            data: {
                action: 'load_related_shortcode',
                post_id: clickedPostId
            },
            success: function (response) {
            	prod_sheet_data = {
			        html: response,
			    };
                elementorProFrontend.modules.popup.showPopup( { id: 5176 } );
            }
        });
	});

	$(document).on('elementor/popup/show', function(event, id, instance) {
		if (typeof wpcf7 !== 'undefined' && typeof wpcf7.init === 'function') {
		      jQuery('.wpcf7 > form').each(function(){
		          wpcf7.init(this);
		          //jQuery('.wpcf7-response-output').hide()
		          jQuery('.sbmt-grp .wpcf7-spinner').remove()
		      });
		  }
    	if (id === 5083) {
    		$('#elementor-popup-modal-5083 .whitepaper-image img').attr('src',popupData.img).removeAttr('srcset');
    		$('[name="white-paper"]').val(popupData.title);
    		$('.whitepaper-title p').text(popupData.title)
	    	$('[name="white-paper-url"]').val(popupData.pdf);
	    	$('.hide-btn').hide()
    	}

    	if (id === 5112) {
    		const $popup = $('#elementor-popup-modal-5112');
		    init_intl_input();
    	}

    	if (id === 5176 && clickedPostId) {

	        jQuery('.popup-shortcode-wrapper').html(prod_sheet_data.html);
	    }
	});

	document.addEventListener('wpcf7submit', function (event) {
	    event.preventDefault();
	});

	jQuery(document).on('elementor/popup/hide', function (event, id) {
	    if (id === 5083) {
	        popupData = {};
	    }
	});

	let timer;

	$('#ajax-search').on('keyup', function () {
		clearTimeout(timer);
		let keyword = $(this).val();
		timer = setTimeout(function () {
			data = {
				'action' : 'elementor_ajax_search',
				'keyword' : keyword,
			}
			jQuery.ajax({
		        url: js_config.ajax_url,   // For WordPress AJAX
		        type: 'POST',
		        data: data,
		        beforeSend: function() {
		            jQuery('.events-loop .col-md-9').html('<div class="no-event h-100 d-flex justify-content-center align-items-center"><h4>Loading....</h4></div>');
		        },
		        success: function(response) {
		        	jQuery('.elementor-loop-container').html(response);
		        },
		        error: function(xhr, status, error) {
		            console.error('AJAX Error:', error);
		            jQuery('.events-loop .col-md-9').html('<div class="no-event h-100 d-flex justify-content-center align-items-center"><h4>Something went wrong. Please try again.</h4></div>');
		        }
		    });
		}, 400);
	});


	// jQuery(document).on('click', '.open-popup-at-click', function (e) {
	//     if(!$(this).hasClass('active-down')){
	//     	$('.soln-download').show();	
	//     	$(this).addClass('active-down');
	//     }
	//     else{
	//     	$('.soln-download').hide();
	//     	$(this).removeClass('active-down');
	//     }
	// });

	// jQuery(document).on('click', '.close-down', function (e) {
	//     	$('.soln-download').hide();
	//     	$('.open-popup-at-click').removeClass('active-down');
	// });

	$(document).on('click','.view-inds',function(){
		const list = $(this).parents('.expand-holder').find('ul')
		list.parents('.inds-menu').toggleClass('expanded');

		$(this).find('div').text(list.parents('.inds-menu').hasClass('expanded')
		? 'View less'
		: 'View more');

	})


	var heightBox = $('.built-compliance .row > div').outerHeight() * 2;
	$('.built-compliance .row').css({'height':heightBox,'overflow' : 'hidden','transition' : '0.3s ease-in-out all'})

	if($('.built-compliance .row').outerHeight() <= heightBox){
		$('.show-all').hide();
	}

	$('.show-all').on('click',function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$(this).find('.elementor-button-text').text('Show Less');
			$('.built-compliance .row').css({'height':'auto'})	
		}
		else{
			$(this).find('.elementor-button-text').text('Show All');
			$('.built-compliance .row').css({'height':heightBox})
		}
		
	})

	$(window).on('scroll', function () {
        // Check if any element has the class
        if ( $('.elementor-sticky--active').length ) {
        	//$('.fixed-top > div').addClass('p-0');
        	$('.fixed-top > div > div').css('background-color','#0000001A');
        	$('.fixed-top').css('background-color','#f2f2f2');
        }
        else{
        	//$('.fixed-top > div').removeClass('p-0');
        	$('.fixed-top > div > div').removeAttr('style');
        	$('.fixed-top').css('background-color','transparent');
        }
    });

	/* Secondary Menu Click Action */
    $('a[href^="#"]').on('click', function(e){
        e.preventDefault();

        $('a[href^="#"]').removeClass('active')
        $(this).addClass('active');
        var target = $(this.hash);
        if (!target.length) return;

        var headerHeight = $('.elementor-sticky--active').outerHeight() || 0;

        $('html, body').animate({
            scrollTop: target.offset().top - 152
        }, 200);
    });
    /* Secondary Menu Click Action */

    /* Secondary Menu Check Active */
    var headerHeight = 152;
    if($('body').hasClass('single-solution') && !$('#trust-solutions').length){
		$('.secondary-menu a[href="#trust-solutions"]').parent().hide();
	}
	if($('body').hasClass('single-platforms') && !$('#related-resources').length){
		$('.secondary-menu a[href="#related-resources"]').parent().hide();
	}

    $(window).on('scroll', function () {
        var scrollPos = $(document).scrollTop() + headerHeight + 10; // +10 buffer

        $('.scroll-check').each(function () {
        	
            var id = $(this).attr('id');
            var top = $(this).offset().top;
            var bottom = top + $(this).outerHeight();

            var first_sec = $('.scroll-check:first').offset().top;
            var last_sec = $('.scroll-check:last').offset().top + $('.scroll-check:last-of-type').outerHeight() - 10;

            if(scrollPos > last_sec){
            	$('.secondary-menu').removeAttr('style')
            }
           

            if (scrollPos >= top && scrollPos < bottom) {
                $('a[href="#' + id + '"]').addClass('active');
            } else {
                $('a[href="#' + id + '"]').removeClass('active');
            }
        });
    });

    $(window).on("scroll", function () {
	    let scrollPos = $(window).scrollTop();
	    let headerHeight = $(".fixed-top").outerHeight();

	    $(".page-content > div > div, footer > div, header > div:last-of-type").each(function () {
	        let sectionTop = $(this).offset().top - headerHeight;
	        let sectionBottom = sectionTop + $(this).outerHeight();

	        if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
	            $(this).addClass("active");
	        }
	    });
	});
    /* Secondary Menu Check Active */

	// var style = $('.rel-soln .elementor-loop-container style').clone();
	// $('.rel-soln .elementor-loop-container').parent().append(style);
	// $('.rel-soln .elementor-loop-container style').remove()
	// $('.rel-soln .elementor-loop-container').removeClass('d-flex').removeClass('overflow-hidden').slick({
	// 	slidesToShow: 3,
	// 	arrows: true,
	// 	prevArrow: $('.prev'),
	// 	nextArrow: $('.next'),
	// 	responsive: [
	// 	{
	// 		breakpoint: 768,
	// 		settings: {
	// 			slidesToShow: 1,
	// 			centerPadding: '40px'
	// 		}
	// 	}
	// 	]
	// })

    $(document).on('click', '.close-menu', function() {
        $('.dialog-close-button').trigger('click')
    });

    $(document).on('click', '.accordion-menu .menu-item-has-children > a', function(e) {
        e.preventDefault();
        let parent = $(this).parent();

        // Close other submenus at the same level
        parent.siblings('.menu-item-has-children').removeClass('active')
            .find('> .elementor-nav-menu--dropdown').slideUp(300);

        // Toggle current submenu
        parent.toggleClass('active');
        parent.find('> .elementor-nav-menu--dropdown').stop(true, true).slideToggle(300);
    });


//     jQuery(document).on('elementor/popup/show', (event, popupId, popupDocument) => {
//         if (popupId === 245) {

//             setTimeout(function() {
//                 jQuery('.elementor-nav-menu li a.has-submenu span').html('<img src="/wp-content/uploads/2025/10/expand-plus.svg" alt="">');
//             }, 500)

//         }
//     });
});

var ww = jQuery(window).width();
if(ww > 1600){
	spv = 1.68;
	cslides = true;
}
else if(ww > 1200){
	spv = 1.35;
	cslides = true;
}
else{
	spv = 1.2
	cslides = false;
}

var swiper = new Swiper(".mySwiper", {
    slidesPerView: spv,
    spaceBetween: 20,
    centeredSlides: cslides,
    arrows: false,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});

const navItems = document.querySelectorAll('.nav-item');

// On nav click → go to slide
navItems.forEach((item) => {
    item.addEventListener('click', () => {
        const slideIndex = item.getAttribute('data-slide');
        swiper.slideToLoop(parseInt(slideIndex)); // go to slide
    });
});

// Update nav active state when slide changes
swiper.on('slideChange', () => {
    navItems.forEach(i => i.classList.remove('active'));
    const realIndex = swiper.realIndex;
    navItems[realIndex].classList.add('active');
});


// if(ww < 768){
// 	var container = document.querySelector('.mobile-featured-solutions');

// // Add swiper structure dynamically
// container.classList.add('swiper-container');

// // Wrap children in swiper-wrapper
// var wrapper = document.createElement('div');
// wrapper.classList.add('swiper-wrapper');

// while (container.firstChild) {
//   container.firstChild.classList.add('swiper-slide');
//   wrapper.appendChild(container.firstChild);
// }

// container.appendChild(wrapper);

// var swiperSoln = new Swiper(".mobile-featured-solutions", {
//   slidesPerView: spv,
//   spaceBetween: 20,
//   centeredSlides: cslides,
// });
// }

jQuery(document).ready(function($) {
	// Optimized Slick initializer for both sliders (copy-paste)
	(function ($) {
		$(function () {
			var defaultOptions = {
				slide: '[data-elementor-type="loop-item"]', // only treat real loop items as slides
				slidesToShow: 3,
				slidesToScroll: 1,
				infinite: false,
				arrows: true,
				centerMode: false,
				responsive: [
					{ breakpoint: 1024, settings: { slidesToShow: 1, slidesToScroll: 1 } },
				],
			};

			var sliders = [
				{ wrapper: ".io-solution-slider", overrides: {} },
				{ wrapper: ".io-case-studies-slider", overrides: {} },
			];

			sliders.forEach(function (entry) {
				var wrapper = entry.wrapper;
				var overrides = entry.overrides || {};

				$(wrapper).each(function () {
					var $wrapper = $(this);
					var $container = $wrapper.find(".elementor-loop-container").first();

					if (!$container.length) {
						return;
					}

					if ($container.hasClass("slick-initialized")) {
						try {
							$container.slick("unslick");
						} catch (e) {}
					}

					var $prev = $wrapper.find(".prev-slide");
					var $next = $wrapper.find(".next-slide");

					var opts = $.extend(true, {}, defaultOptions, overrides, {
						prevArrow: $prev.length ? $prev : '<button class="slick-prev">Prev</button>',
						nextArrow: $next.length ? $next : '<button class="slick-next">Next</button>',
					});

					if ($container.children('[data-elementor-type="loop-item"]').length) {
						$container.slick(opts);
					}
				});
			});
		});
	})(jQuery);


	// Initialize Industry Slider
	$('.io-gallery-slider').each(function () {
		var $slider = $(this);
		var $slides = $slider.children().clone();
		$slider.append($slides);
		$slider.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: true,
			arrows: false,
			autoplay: true,
			autoplaySpeed: 0,
			speed: 12000,
			cssEase: 'linear',
			pauseOnHover: true,
			pauseOnFocus: false,
			variableWidth: true
		});

		/* New full-screen dark hamburger menu: build a Platform/Resources/Our Industries/
		   Our Solutions/Company two-column layout out of the existing menu links, and hide
		   the site's original popup menu markup via CSS - requested 2026-07-22 */
		function build_custom_mobile_menu() {
			var $popup = $('.elementor-location-popup.elementor-245');
			if (!$popup.length) {
				return false; // popup not in the DOM yet - caller should retry
			}

			// If a menu was already built successfully (has real items), don't rebuild.
			var $existing = $popup.find('.custom-mobile-menu');
			if ($existing.length && $existing.find('.cmm-item').length) {
				return true;
			}

			// Bail out (and let the caller retry later) unless the source markup this
			// function reads from is actually present - this is what was racing before:
			// a fixed 50ms delay would sometimes fire before the popup's own content
			// finished rendering, producing a menu with no links that then never retried.
			var $platformLiCheck = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Our Platform (TLM)';
			}).first().closest('li');
			var $resourcesLiCheck = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Resources';
			}).first().closest('li');
			var $companyLiCheck = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Company';
			}).first().closest('li');
			var industriesReady = $popup.find('.elementor-element-d1ff02b ul').first().find('> li > a').length > 0;
			var solutionsReady = $popup.find('.elementor-element-22ef50b ul').first().find('> li > a').length > 0;

			if (!$platformLiCheck.length || !$resourcesLiCheck.length || !$companyLiCheck.length || !industriesReady || !solutionsReady) {
				return false; // source markup not ready yet - caller should retry
			}

			// If a previous attempt left behind an empty shell, clear it before rebuilding.
			if ($existing.length) {
				$existing.remove();
			}

			// Pull real links straight out of the existing (hidden) menu markup so nothing is invented.
			var sections = [];

			function linksFrom($ul) {
				var out = [];
				$ul.find('> li > a').each(function() {
					out.push({ text: $(this).text().trim(), href: $(this).attr('href') });
				});
				return out;
			}

			// Platform
			var $platformLi = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Our Platform (TLM)';
			}).first().closest('li');
			sections.push({
				key: 'platform',
				label: 'Platform',
				heading: 'Platform — Trust Lifecycle Management',
				links: [{ text: 'Trust Lifecycle Management', href: $platformLi.children('a').attr('href') }]
					.concat(linksFrom($platformLi.children('ul')))
					// These two don't have live pages on the site yet - placeholder link until real pages exist (requested explicitly 2026-07-22)
					.concat([
						{ text: 'AI Lifecycle Management (ALM)', href: '#' },
						{ text: 'Certify: Cyber Risk & Regulatory Assessment', href: '#' }
					])
			});

			// Resources
			var $resourcesLi = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Resources';
			}).first().closest('li');
			sections.push({
				key: 'resources',
				label: 'Resources',
				heading: 'Resources',
				links: linksFrom($resourcesLi.children('ul'))
			});

			// Our Industries (already its own widget)
			sections.push({
				key: 'industries',
				label: 'Our Industries',
				heading: 'Our Industries',
				links: linksFrom($popup.find('.elementor-element-d1ff02b ul').first())
			});

			// Our Solutions (already its own widget)
			sections.push({
				key: 'solutions',
				label: 'Our Solutions',
				heading: 'Our Solutions',
				links: linksFrom($popup.find('.elementor-element-22ef50b ul').first())
			});

			// Company
			var $companyLi = $popup.find('.elementor-element-2d6cc4f a.elementor-item').filter(function() {
				return $(this).text().trim() === 'Company';
			}).first().closest('li');
			sections.push({
				key: 'company',
				label: 'Company',
				heading: 'Company',
				links: linksFrom($companyLi.children('ul'))
			});

			var $left = $('<div class="custom-mobile-menu-left"></div>');
			var $right = $('<div class="custom-mobile-menu-right"></div>');

			function renderRight(section) {
				var $heading = $('<div class="cmm-right-heading"></div>').text(section.heading);
				var $ul = $('<ul></ul>');
				section.links.forEach(function(link) {
					if (!link.href || !link.text) { return; }
					$ul.append($('<li></li>').append($('<a></a>').attr('href', link.href).text(link.text)));
				});
				$right.empty().append($heading).append($ul);
			}

			sections.forEach(function(section, i) {
				var $item = $('<div class="cmm-item"></div>')
					.attr('data-key', section.key)
					.append($('<span></span>').text(section.label))
					.append($('<span class="cmm-chevron">&#8250;</span>'));
				if (i === 0) { $item.addClass('active'); }
				$item.on('click', function() {
					$left.find('.cmm-item').removeClass('active');
					$item.addClass('active');
					renderRight(section);
				});
				$left.append($item);
			});

			renderRight(sections[0]);

			var $menu = $('<div class="custom-mobile-menu"></div>').append($left).append($right);
			$popup.find('.elementor-element-2949b31').append($menu);
			return true;
		}

		// Retry/poll instead of a single fixed delay: keep trying until the popup's
		// source menu markup is confirmed present and the menu actually builds, since
		// under real network/server latency a single 50ms check could fire too early
		// and leave the panel permanently empty.
		function try_build_custom_mobile_menu(attemptsLeft) {
			if (typeof attemptsLeft === 'undefined') { attemptsLeft = 60; } // ~60 * 75ms = 4.5s max
			var built = build_custom_mobile_menu();
			if (!built && attemptsLeft > 0) {
				setTimeout(function() {
					try_build_custom_mobile_menu(attemptsLeft - 1);
				}, 75);
			}
		}

		$(document).on('click', '#menu-icon', function() {
			try_build_custom_mobile_menu();
		});
		// Also try eagerly in case the popup markup is already present in the DOM
		try_build_custom_mobile_menu();

		// Build as soon as Elementor itself says this popup is ready, instead of relying
		// only on the fixed-duration retry loop above. On a fresh page load the popup's
		// own content can still be rendering when the click retry loop times out, so this
		// event-driven hook is the primary trigger; the click-based retry loop above stays
		// in place as a fallback in case this event fires before the source menu markup is ready.
		$(document).on('elementor/popup/show', function(event, id) {
			if (id === 245) {
				try_build_custom_mobile_menu();
			}
		});
	});
});

