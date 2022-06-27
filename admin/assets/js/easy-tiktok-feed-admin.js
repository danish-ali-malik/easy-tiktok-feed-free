jQuery(document).ready(function($) {

    jQuery('.etf-tabs-holder .etf-tabs-buttons-holder .etf-tab-button').click(function() {

        jQuery('.etf-tabs-holder .etf-tabs-buttons-holder .etf-tab-button').removeClass('active');

        jQuery(this).addClass('active');

        var current_id = jQuery(this).attr('id');

        jQuery('.etf-tabs-holder .etf-tab-content').slideUp('slow');

        jQuery('.etf-tabs-holder .etf-tab-content#'+current_id).slideDown('slow');

    });


    window.onclick = function(event) {

    	let opened_modal_id = document.getElementsByClassName("etf-modal open");

    	if( opened_modal_id.length ){

    		opened_modal_id = document.getElementsByClassName("etf-modal open")[0].id;
    	}

    	const modal = document.getElementById(opened_modal_id);

    	if (event.target == modal) {

    		modal.style.display = "none";
    		
    		modal.classList.remove("open");
    	}
    }

    /*
	* Show notification
	*/	
    function etfShowNotification(text, delay = 4000){

    	if(!text){

    		text = etf.copied;
    	}

		jQuery(".etf-notification-holder").html(' ').html(text).css('opacity', 1).animate({bottom: '0'});

		setTimeout(function(){ jQuery(".etf-notification-holder").animate({bottom: '-=100%'}) }, delay);
    }

    /*
	* Show/Hide modal
	*/	
    function etfModal(id){

    	const opened_modal = document.getElementsByClassName("etf-modal open");

    	if( opened_modal.length ){

    		opened_modal.style.display = "none";
    	}

		const modal = document.getElementById(id);

		modal.style.display = "block";

		modal.classList.add("open");

    }

    /*
	* Copy anything to clipboard
	*/	
	jQuery( ".etf-copy-to-clipboard" ).click(function() {	

		var copied = new ClipboardJS('.etf-copy-to-clipboard');

		copied.on('success', function(e) {

			etfShowNotification(etf.copied);

		});

	  copied.on('error', function(e) {

	  	etfShowNotification(etf.error);

	  });


	 });

	/*
	* Shortcode Generator
	*/	
	jQuery( ".etf-generate-shortcode" ).click(function(event) {	

		event.preventDefault();

		const selectedType = jQuery("input[type=radio][name=etf-type]:checked").val();

		let hashtag = '';

		let username = '';

		if( selectedType === 'hashtag' ){

			hashtag = jQuery( '#etf-hashtag' ).val();	

			if( hashtag ){

				hashtag = 'hashtag="'+hashtag+'" ';

		   	}

		}else{

			username = jQuery( '#etf-username' ).val();	

			if( username ){

				username = 'username="'+username+'" ';

		   	}

		} 

		let layout = jQuery( '#etf-selected-layout option:selected' ).val();

	   	if( layout ){

			layout = 'layout="'+layout+'" ';

	   	}  	

	   	let videosPerPage = jQuery( '#etf-videos-per-page' ).val();

	   	if( videosPerPage ){

			videosPerPage = 'videos_per_page="'+videosPerPage+'" ';

	   	}

	   	let WrapperClass = jQuery( '#etf-wrapper-class' ).val();

	   	if( WrapperClass ){

			WrapperClass = 'wrapper_class="'+WrapperClass+'"';

	   	}

	   	const shortcode = '[easy-tiktok-feed '+username+''+layout+''+hashtag+''+videosPerPage+''+WrapperClass+']';

	   	jQuery('.etf-generated-shortcode-holder .etf-copy-to-clipboard').attr('data-clipboard-text', shortcode);

	   	jQuery('.etf-generated-shortcode-holder .etf-shortcode-holder').html(' ').html(shortcode);

	   	jQuery('.etf-generated-shortcode-holder').slideDown('slow');

	});

	/*
	* Show and hide username or hashtag field
	*/	
	jQuery('input[type=radio][name=etf-type]').on('change', function() {

		const value = jQuery(this).val();

		if( value === 'hashtag_free' ){

			etfModal('etf-premium-hashtag-modal'); return;
		}

		if( value === 'hashtag' ){

			jQuery('.etf-username-field').slideUp();

			jQuery('.etf-hashtag-field').slideDown('slow');

		}else{

			jQuery('.etf-username-field').slideDown('slow');

			jQuery('.etf-hashtag-field').slideUp();
		}
	});

	/*
	* Delete cache
	*/
	jQuery(document).on("click", ".etf-delete-transient", function($) {

		const transient_id = jQuery(this).data('transient');

		const collection_class = jQuery(this).data('type');
		
		const data = { action : 'etf_delete_transient',
					transient_id : transient_id,
					nonce : etf.nonce
		}	
		

		jQuery.ajax({
			url : etf.ajax_url,
			type : 'post',
			data : data,
			dataType: 'json',
			success : function( response ) {

					if(response.success){

						jQuery('.etf-cache-holer .etf-cache-item.'+response.data['1']).slideUp();

							jQuery('.etf-cache-holer .etf-cache-item.'+response.data['1']).remove();

						var slug = '.etf-cache-holer .'+collection_class+' .etf-cache-item';
						
						if(jQuery(slug).length == 0){
					
						jQuery('.etf-cache-holer .'+collection_class).slideUp('slow');
						}

						etfShowNotification(response.data['0']);		
					}
					else{
						etfShowNotification(response.data);
					}
					
				}

		});
	});

	/*
	* Show updgrade popup if premium layout selected
	*/
	jQuery(document).on("change", ".etf-selected-layout", function(event) {

	    const valueSelected = this.value;

	    if(valueSelected === 'free-masonry' || valueSelected === 'free-carousel' || valueSelected === 'free-half_width' || valueSelected === 'free-full_width'){
			
			etfModal('etf-premium-layout-modal');
			return;
		}
	
	});


});    