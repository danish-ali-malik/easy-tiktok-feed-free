jQuery( document ).ready(function($) {

      

	  equalheight = function(container) {

        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            jQueryel,
            topPosition = 0;
        jQuery(container).each(function() {

            jQueryel = jQuery(this);
            jQuery(jQueryel).height('auto')
            topPostion = jQueryel.position().top;

            if (currentRowStart != topPostion) {
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = jQueryel.height();
                rowDivs.push(jQueryel);
            } else {
                rowDivs.push(jQueryel);
                currentTallest = (currentTallest < jQueryel.height()) ? (jQueryel.height()) : (currentTallest);
            }
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }

	jQuery(window).resize(function() {
        equalheight('.e-outer .e-inner');
    });

    jQuery(window).resize(function(){
		    jQuery('.etf-grid-box').each(function() {
		    });
	}).resize();

    /*
	* Popup init
	*/
	function etf_feed_popup(){

        jQuery('.etf_feed_fancy_popup').fancybox({
                infobar: false,
                toolbar: true,
                baseClass: 'etf_feed_popup_container',
                showCloseButton: false,
                autoDimensions: true,
                autoScale : true,
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "thumbs",
                    "close"
                ],
                image: {
                    preload: true
                },
                animationEffect: "zoom-in-out",
                transitionEffect: "zoom-in-out",
                slideShow: {
                    autoStart: false,
                    speed: 3000
                },
                touch: true, // Allow to drag content vertically
                hash: false,
                iframe: {
                    autoDimensions: true,
                }
        });
    }

    etf_feed_popup();


    jQuery(".etf_feeds_carousel").each(function(index) {

            jQuery(this).on('initialized.owl.carousel resized.owl.carousel', function (event) {
                
            let item_width = event.relatedTarget._widths[0];

            let item_half = parseInt(item_width / 2);

            jQuery( this ).find( '.etf_feed_fancy_popup' ).css('height', item_width + 'px');

            jQuery( this ).children('.owl-nav').children('button').css('top', item_half + 'px');

            }).owlCarousel({
              loop: true,
              autoplay: true,
              margin: 0,
              responsiveClass: true,
              nav : true,
              responsive: {
                0: {
                    items: 3,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 3,
                }
              },
              navText: ["<i class='icon etf-icon-angle-left'></i>", "<i class='icon etf-icon-angle-right'></i>"]
        });

    });

    $(".etf-share").click(function(){
          $(this).next().slideToggle();
    });

});	