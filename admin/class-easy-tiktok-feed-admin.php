<?php
/*
* Stop execution if someone tried to get file directly.
*/ 
if ( ! defined( 'ABSPATH' ) ) exit;


								//======================================================================
													// Admin Area Of Easy Tiktok Feed
								//======================================================================

if ( ! class_exists( 'Easy_Tiktok_Feed_Admin' ) ):
class Easy_Tiktok_Feed_Admin {

	function __construct(){

	
		add_action('admin_menu', [$this,'etf_menu']);

		add_action( 'admin_enqueue_scripts', [$this, 'etf_admin_style']);
	
        add_filter( 'admin_footer_text', [$this, 'etf_admin_footer_text'] );

        add_action( 'admin_footer', [$this, 'etf_admin_footer'] );

        add_action( 'wp_ajax_etf_delete_transient', [$this, 'etf_delete_transient'] );

        add_action( 'wp_ajax_etf_create_skin', [$this, 'etf_create_skin'] );


	}

	/*
	* Add Easy Tiktok Feed menu in dashboard
	*/ 
	public function etf_menu() {

		add_menu_page('Easy Tiktok Feed', 'Easy TikTok Feed', 'administrator', 'easy-tiktok-feed', [$this,'etf_page'], ETF_URL . '/admin/assets/images/tiktok-icon.svg');

	}

	/*
	* Include Easy Tiktok Feed page view
	*/
	public function etf_page() {
	
		include_once ETF_DIR . 'admin/views/html-admin-page-easy-tiktok-feed.php';
	}

	
	/*
	* Include admin styles and scripts
	*/
	public function etf_admin_style($hook) {
	
		/*
		* Load only on Easy Tiktok Feed pages
		*/
		if( in_array( $hook, $this->etf_pages_hooks() ) ) {

	
	        wp_enqueue_style('easy-tiktok-feed-fonts', ETF_URL . '/admin/assets/css/easy-tiktok-feed-fonts.css' );

	        wp_enqueue_style('easy-tiktok-feed-admin', ETF_URL . '/admin/assets/css/easy-tiktok-feed-admin.css' );

	        wp_enqueue_script( 'clipboard' );

			wp_enqueue_script( 'easy-tiktok-feed-admin', ETF_URL . '/admin/assets/js/easy-tiktok-feed-admin.js', array( 'jquery' ) );

			wp_localize_script( 'easy-tiktok-feed-admin', 'etf', array(
	            'ajax_url'     => admin_url( 'admin-ajax.php' ),
	            'copied' => __('Copied', 'easy-tiktok-feed'),
	            'error' => __('Something went wrong!', 'easy-tiktok-feed'),
	            'nonce' => wp_create_nonce('etf-ajax-nonce')
	        ) );


		}
		
    }

    /*
    * Create new skin/layout
    */
    function etf_create_skin(){

        $form_data = $_POST['form_data'];
       
        parse_str( $form_data );

        $layout = array();
        
        $layout['layout_option'] = sanitize_text_field($etf_selected_layout);

        $skin_array = ['post_title'   => sanitize_text_field($etf_skin_title),
            'post_content' => sanitize_text_field($etf_skin_description),
            'post_type'    => 'etf_skins',
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        ];

        if(wp_verify_nonce( $_POST['nonce'], 'etf-ajax-nonce' )){

         if(current_user_can('editor') || current_user_can('administrator')){

             $skin_id = wp_insert_post( $skin_array );

            }
        }

        
        if ( isset( $skin_id ) ) {

            update_post_meta( $skin_id, 'layout',  $layout);

            $page_id = $fta_settings['plugins']['instagram']['default_page_id'];

            $page_permalink = get_permalink( $page_id );

            $customizer_url = 'customize.php';

            if ( isset( $page_permalink ) ) {

                $customizer_url = add_query_arg( array(
                    'url'              => urlencode( $page_permalink ),
                    'autofocus[panel]' => 'mif_skins_panel',
                    'mif_skin_id'      => $skin_id,
                    'mif_customize'    => 'yes',
                    'mif_account_id'    => $mif_selected_account,
                ), $customizer_url );

            }
           
            echo  wp_send_json_success( admin_url( $customizer_url ) ) ;
            wp_die();
        } else {
            echo  wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'easy-tiktok-feed' ) ) ;
            wp_die();
        }
        
        exit;
    }

	/*
	* Return slug of Easy Tiktok Feed pages. 
	*/
	public function etf_pages_hooks(){

		$hooks = array('toplevel_page_easy-tiktok-feed');

		return apply_filters('etf_pages_hooks', $hooks);

	}


	/*
	* Add like button for footer
	*/ 
	public function etf_admin_footer_text($text){

		$screen = get_current_screen();
        
        if ( in_array( $screen->id, $this->etf_pages_hooks() ) ) {

            $Easy_Tiktok_Feed = new Easy_Tiktok_Feed();

            $text = '<i><a href="' . admin_url( '?page=easy-tiktok-feed' ) . '" title="' . __( 'Visit Easy Tiktok Feed page for more info', 'wpoptin' ) . '">Easy Tiktok Feed</a> v' . $Easy_Tiktok_Feed->version . '. Please <a target="_blank" href="https://wordpress.org/support/plugin/easy-tiktok-feed/reviews/?filter=5#new-post" title="Rate the plugin">rate the plugin <span style="color: #ffb900;" class="stars">&#9733; &#9733; &#9733; &#9733; &#9733; </span></a> to help us spread the word. Thank you from the Easy Tiktok Feed team!</i><div style="margin-left:5px;top: 1px;" class="fb-like" data-href="https://www.facebook.com/easy-tiktok-feed" data-width="" data-layout="button" data-action="like" data-size="small" data-share="false"></div>';
        }
        
        return $text;
	}

	/*
	* Include Facebook SDK script to like the page
	*/ 
	public function etf_admin_footer(){

		$screen = get_current_screen();
       

        if ( in_array( $screen->id, $this->etf_pages_hooks() ) ) {

        	echo '<div id="fb-root"></div><script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v6.0&appId=1983264355330375&autoLogAppEvents=1"></script><style>#wpfooter{background-color: #fff;padding: 15px 20px;-webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.12), 0 1px 5px 0 rgba(0,0,0,.2);box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.12), 0 1px 5px 0 rgba(0,0,0,.2);}.fb_iframe_widget{float:left;}#wpfooter a{text-decoration:none;}</style>';
        }	

	}

	/*
    * Delete the cache
    */
    function etf_delete_transient(){

        $transient_id = sanitize_text_field($_POST['transient_id']);

        $replaced_value = str_replace( '_transient_', '', $transient_id );

        if(wp_verify_nonce( $_POST['nonce'], 'etf-ajax-nonce' )){
       
         if(current_user_can('editor') || current_user_can('administrator')){

            $etf_deleted_trans = delete_transient( $replaced_value );

          }
        }
        
        if ( isset( $etf_deleted_trans ) ) {

            echo  wp_send_json_success( array( __( 'Cache is successfully deleted.', 'easy-tiktok-feed' ), $transient_id ) ); wp_die();

        } else {

            echo  wp_send_json_error( __( 'Something Went Wrong! Please try again.', 'easy-tiktok-feed' ) ); wp_die();
        }
        
        exit;
    }

				
}

$Easy_Tiktok_Feed_Admin  = new Easy_Tiktok_Feed_Admin();
endif;	