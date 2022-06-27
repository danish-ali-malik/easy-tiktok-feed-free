<?php

/*
Plugin Name: Easy TikTok Feed
Plugin URI: https://wordpress.org/plugins/easy-tiktok-feed
Description: Display your awesome tiktok feed responsive gallery on your website
Author: Danish Ali Malik
Version: 1.1.1
Text Domain: easy-tiktok-feed
Author URI: https://maltathemes.com/danish-ali-malik
*/

if ( function_exists( 'etf_fs' ) ) {
    etf_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'etf_fs' ) ) {
        // Create a helper function for easy SDK access.
        function etf_fs()
        {
            global  $etf_fs ;
            
            if ( !isset( $etf_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $etf_fs = fs_dynamic_init( array(
                    'id'             => '6903',
                    'slug'           => 'easy-tiktok-feed',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_bbb87941aa5ba3502525da59e3e33',
                    'is_premium'     => false,
                    'premium_suffix' => 'Premium',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug' => 'easy-tiktok-feed',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $etf_fs;
        }
        
        // Init Freemius.
        etf_fs();
        // Signal that SDK was initiated.
        do_action( 'etf_fs_loaded' );
    }

}

/*
* Easy Tiktok Feed Class
*/

if ( !class_exists( 'Easy_Tiktok_Feed' ) ) {
    class Easy_Tiktok_Feed
    {
        public  $version = '1.1.1' ;
        function __construct()
        {
            add_action( 'init', array( $this, 'etf_constants' ) );
            add_action( 'init', array( $this, 'etf_includes' ) );
            register_activation_hook( __FILE__, array( $this, 'etf_activate' ) );
            add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 10 );
        }
        
        /*
         * Define required plugin constants
         */
        public function etf_constants()
        {
            if ( !defined( 'ETF_VERSION' ) ) {
                define( 'ETF_VERSION', $this->version );
            }
            if ( !defined( 'ETF_DIR' ) ) {
                define( 'ETF_DIR', plugin_dir_path( __FILE__ ) );
            }
            if ( !defined( 'ETF_URL' ) ) {
                define( 'ETF_URL', plugin_dir_url( __FILE__ ) );
            }
            if ( !defined( 'ETF_FILE' ) ) {
                define( 'ETF_FILE', __FILE__ );
            }
        }
        
        /*
         * Include all plugin files
         */
        public function etf_includes()
        {
            /*
             * Custom functions to use across plugin.
             */
            include ETF_DIR . 'includes/easy-tiktok-feed-helper-functions.php';
            if ( !class_exists( 'Easy_Tiktok_Feed_Admin' ) ) {
                include ETF_DIR . 'admin/class-easy-tiktok-feed-admin.php';
            }
            if ( !class_exists( 'Easy_Tiktok_Feed_Stream' ) ) {
                include ETF_DIR . 'frontend/includes/class-easy-tiktok-feed-stream.php';
            }
            if ( !class_exists( 'Easy_Tiktok_Feed_Frontend' ) ) {
                include ETF_DIR . 'frontend/class-easy-tiktok-feed-frontend.php';
            }
        }
        
        /*
         * Add required entries to db on plugin activation
         */
        public function etf_activate()
        {
            $etf_settings = get_option( 'etf_settings', false );
            $etf_settings['version'] = sanitize_text_field( $this->version );
            $etf_settings['installed_date'] = sanitize_text_field( date( 'Y-m-d h:i:s' ) );
            update_option( 'etf_settings', $etf_settings );
        }
        
        /**
         * Load Easy Tiktok Feed text domain.
         *
         * @since 1.1.0
         *
         * @return void
         * @access public
         */
        public function load_textdomain()
        {
            load_plugin_textdomain( 'easy-tiktok-feed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
    
    }
    new Easy_Tiktok_Feed();
}
