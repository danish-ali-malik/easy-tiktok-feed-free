<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//======================================================================
// Frontend of Tiktokt Feed
//======================================================================

if ( !class_exists( 'Easy_Tiktok_Feed_Frontend' ) ) {
    class Easy_Tiktok_Feed_Frontend
    {
        public  $etf_tiktok_url = 'https://www.tiktok.com' ;
        private  $etf_tiktok_api_url = 'https://www.tiktok.com/node' ;
        private  $config = array() ;
        protected  $etf_buffer_size = 256 * 1024 ;
        protected  $etf_headers = array() ;
        protected  $etf_headers_sent = false ;
        protected  $etf_webid_v2 = null ;
        private  $defaults = array(
            "user-agent"     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36',
            "proxy-host"     => false,
            "proxy-port"     => false,
            "proxy-username" => false,
            "proxy-password" => false,
            "cache-timeout"  => 3600,
        ) ;
        function __construct()
        {
            $this->init_config();
            add_action( 'wp_enqueue_scripts', [ $this, 'etf_enqueue_scripts' ] );
            add_shortcode( 'easy-tiktok-feed', [ $this, 'easy_tiktok_feed_render_shortcode' ] );
            add_action( 'wp_ajax_etf-video-stream', [ $this, 'etf_video_stream' ] );
            add_action( 'wp_ajax_nopriv_etf-video-stream', [ $this, 'etf_video_stream' ] );
            add_action( 'wp_ajax_etf_load_feed_popup', [ $this, 'etf_load_feed_popup' ] );
            add_action( 'wp_ajax_nopriv_etf_load_feed_popup', [ $this, 'etf_load_feed_popup' ] );
        }
        
        public function init_config()
        {
            $this->config = array_merge( [
                'cookie_file' => get_temp_dir() . 'tiktok.txt',
            ], $this->defaults );
        }
        
        /*
         * Enqueue scripts and styles for frontend
         */
        public function etf_enqueue_scripts()
        {
            wp_enqueue_style( 'easy-tiktok-feed-fonts', ETF_URL . '/admin/assets/css/easy-tiktok-feed-fonts.css' );
            wp_enqueue_style( 'easy-tiktok-feed-frontend', ETF_URL . 'frontend/assets/css/easy-tiktok-feed-frontend.css' );
            wp_enqueue_style( 'jquery.fancybox.min', ETF_URL . 'frontend/assets/css/jquery.fancybox.min.css' );
            wp_enqueue_script( 'jquery.fancybox.min', ETF_URL . 'frontend/assets/js/jquery.fancybox.min.js', [ 'jquery' ] );
            wp_enqueue_script(
                'easy-tiktok-feed-frontend',
                ETF_URL . 'frontend/assets/js/easy-tiktok-feed-frontend.js',
                [ 'jquery' ],
                null,
                false
            );
            wp_localize_script( 'easy-tiktok-feed-frontend', 'etf', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'etf-ajax-nonce' ),
            ) );
        }
        
        /*
         * Render shortcode and display feed
         */
        public function easy_tiktok_feed_render_shortcode( $atts )
        {
            extract( shortcode_atts( array(
                'wrapper_class'   => null,
                'layout'          => 'grid',
                'username'        => 'gopro',
                'hashtag'         => '',
                'videos_per_page' => 9,
                'caption_words'   => 150,
            ), $atts, 'easy-tiktok-feed' ) );
            $cache_duration = 60 * 60;
            $etf_cache_seconds = $cache_duration * 1;
            $etf_feed_slug = $username;
            
            if ( isset( $hashtag ) && !empty($hashtag) ) {
                $etf_feed_slug = $hashtag;
                $etf_show_hashtag = true;
            } else {
                $etf_show_hashtag = false;
            }
            
            ob_start();
            include 'views/feed.php';
            $returner = ob_get_contents();
            ob_end_clean();
            return $returner;
        }
        
        /*
         * Get bio of user
         */
        public function etf_get_user_bio( $etf_username, $etf_cache_seconds = 3600 )
        {
            if ( !$etf_username ) {
                return;
            }
            $etf_trasneint_key = 'etf_tiktok_bio-' . str_replace( ' ', '', $etf_username );
            $etf_response = get_transient( $etf_trasneint_key );
            
            if ( !$etf_response && empty($etf_response) ) {
                $etf_remote_url = "{$this->etf_tiktok_api_url}/share/user/@{$etf_username}";
                $etf_response = $this->etf_get_remote_data( $etf_remote_url );
                if ( isset( $etf_response->userInfo->user->id ) ) {
                    set_transient( $etf_trasneint_key, $etf_response, $etf_cache_seconds );
                }
            }
            
            if ( !isset( $etf_response->userInfo->user->id ) ) {
                return false;
            }
            $etf_user_bio = array(
                'id'                 => $etf_response->userInfo->user->id,
                'permalink'          => "{$this->etf_tiktok_url}/@{$etf_username}",
                'bio'                => $etf_response->userInfo->user->signature,
                'full_name'          => $etf_response->userInfo->user->nickname,
                'username'           => $etf_response->userInfo->user->uniqueId,
                'profile_pic_url'    => $etf_response->userInfo->user->avatarThumb,
                'profile_pic_url_hd' => $etf_response->userInfo->user->avatarLarger,
                'following_count'    => $etf_response->userInfo->stats->followingCount,
                'fan_count'          => $etf_response->userInfo->stats->followerCount,
                'likes_count'        => $etf_response->userInfo->stats->heartCount,
                'video_count'        => $etf_response->userInfo->stats->videoCount,
                'verified'           => $etf_response->userInfo->user->verified,
            );
            return $etf_user_bio;
        }
        
        /*
         * Get remote data from tiktok api
         */
        private function etf_get_remote_data( $etf_url = null, $etf_args = array() )
        {
            $ch = curl_init();
            $options = [
                CURLOPT_URL            => $etf_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT      => $this->config['user-agent'],
                CURLOPT_ENCODING       => "utf-8",
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HTTPHEADER     => [ 'Referer: https://www.tiktok.com/foryou?lang=en' ],
                CURLOPT_COOKIEJAR      => $this->config['cookie_file'],
            ];
            if ( file_exists( $this->config['cookie_file'] ) ) {
                curl_setopt( $ch, CURLOPT_COOKIEFILE, $this->config['cookie_file'] );
            }
            curl_setopt_array( $ch, $options );
            if ( defined( 'CURLOPT_IPRESOLVE' ) && defined( 'CURL_IPRESOLVE_V4' ) ) {
                curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            }
            
            if ( $this->config['proxy-host'] && $this->config['proxy-port'] ) {
                curl_setopt( $ch, CURLOPT_PROXY, $this->config['proxy-host'] . ":" . $this->config['proxy-port'] );
                if ( $this->config['proxy-username'] && $this->config['proxy-password'] ) {
                    curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $this->config['proxy-username'] . ":" . $this->config['proxy-password'] );
                }
            }
            
            $data = curl_exec( $ch );
            curl_close( $ch );
            return json_decode( $data );
        }
        
        /*
         * Get remote data from tiktok api
         */
        private function etf_get_remote_response( $json = null )
        {
            if ( !($etf_response = json_decode( wp_remote_retrieve_body( $json ), true )) || 200 !== wp_remote_retrieve_response_code( $json ) ) {
                
                if ( is_wp_error( $json ) ) {
                    $etf_response = array(
                        'error'   => 1,
                        'message' => $json->get_error_message(),
                    );
                } else {
                    $etf_response = array(
                        'error'   => 1,
                        'message' => esc_html__( 'Something went wrong, please try again', 'easy-tiktok-feed' ),
                    );
                }
            
            }
            return $etf_response;
        }
        
        /*
         * Get username feed
         */
        public function etf_get_feeds( $etf_username = null, $etf_feed_per_page = 9, $etf_cache_seconds = 3600 )
        {
            $etf_get_user_bio = $this->etf_get_user_bio( $etf_username, $etf_cache_seconds );
            if ( !isset( $etf_get_user_bio['id'] ) ) {
                return false;
            }
            $etf_trasneint_key = 'etf_tiktok_posts-' . str_replace( ' ', '', $etf_username ) . '-' . $etf_feed_per_page;
            $etf_response = get_transient( $etf_trasneint_key );
            
            if ( !$etf_response && empty($etf_response) ) {
                $etf_remote_url = add_query_arg( array(
                    'id'        => intval( $etf_get_user_bio['id'] ),
                    'count'     => intval( $etf_feed_per_page ),
                    'minCursor' => 0,
                    'maxCursor' => 0,
                    'type'      => 1,
                ), "{$this->etf_tiktok_api_url}/video/feed" );
                $etf_response = $this->etf_get_remote_data( $etf_remote_url );
                if ( !isset( $etf_response->body ) ) {
                    return;
                }
                set_transient( $etf_trasneint_key, $etf_response, $etf_cache_seconds );
            }
            
            return $etf_response->body;
        }
        
        /*
         * Clean unrelated data and return only required
         */
        public function etf_setup_feed_data( $et_feeds_raw )
        {
            $etf_feeds = array();
            $i = 1;
            if ( is_array( $et_feeds_raw ) && !empty($et_feeds_raw) ) {
                foreach ( $et_feeds_raw as $etf_single_feed ) {
                    preg_match_all( '/(?<!\\S)#([0-9a-zA-Z]+)/', $etf_single_feed->itemInfos->text, $etf_hashtags );
                    $url_encode = base64_encode( $etf_single_feed->itemInfos->video->urls[0] );
                    $url_ajax = admin_url( "admin-ajax.php?action=etf-video-stream&url={$url_encode}&user_name={$etf_single_feed->authorInfos->uniqueId}&video_id={$etf_single_feed->itemInfos->id}" );
                    $etf_feeds[] = array(
                        'number'         => $i,
                        'id'             => $etf_single_feed->itemInfos->id,
                        'description'    => etf_hastags_to_link( $etf_single_feed->itemInfos->text, $this->etf_tiktok_url ),
                        'hashtags'       => ( isset( $etf_hashtags[1] ) ? $etf_hashtags[1] : '' ),
                        'permalink'      => "{$this->etf_tiktok_url}/@{$etf_single_feed->authorInfos->uniqueId}/video/{$etf_single_feed->itemInfos->id}",
                        'created_time'   => etf_time_ago( $etf_single_feed->itemInfos->createTime ),
                        'covers'         => array(
                        'default' => $etf_single_feed->itemInfos->covers[0],
                        'origin'  => $etf_single_feed->itemInfos->coversOrigin[0],
                        'dynamic' => $etf_single_feed->itemInfos->coversDynamic[0],
                        'video'   => $url_ajax,
                    ),
                        'total_shares'   => $etf_single_feed->itemInfos->shareCount,
                        'total_comments' => $etf_single_feed->itemInfos->commentCount,
                        'digg_count'     => $etf_single_feed->itemInfos->shareCount,
                        'total_play'     => $etf_single_feed->itemInfos->playCount,
                        'width'          => $etf_single_feed->itemInfos->video->videoMeta->width,
                        'height'         => $etf_single_feed->itemInfos->video->videoMeta->height,
                        'author'         => array(
                        'id'           => $etf_single_feed->authorInfos->userId,
                        'username'     => $etf_single_feed->authorInfos->uniqueId,
                        'full_name'    => $etf_single_feed->authorInfos->nickName,
                        'tagline'      => $etf_single_feed->authorInfos->signature,
                        'verified'     => $etf_single_feed->authorInfos->verified,
                        'image'        => array(
                        'small'  => $etf_single_feed->authorInfos->covers[0],
                        'medium' => $etf_single_feed->authorInfos->coversMedium[0],
                        'larger' => $etf_single_feed->authorInfos->coversLarger[0],
                    ),
                        'profile_link' => "{$this->etf_tiktok_url}/@{$etf_single_feed->authorInfos->uniqueId}",
                    ),
                    );
                    $i++;
                }
            }
            return $etf_feeds;
        }
        
        /*
         * Load popup HTML
         */
        function etf_load_feed_popup()
        {
            $id = intval( $_GET['id'] );
            $feed_slug = sanitize_text_field( $_GET['feed_slug'] );
            $videos_per_page = intval( $_GET['videos_per_page'] );
            $etf_show_hashtag = intval( $_GET['etf_show_hashtag'] );
            $etf_feeds = $this->etf_get_feeds( sanitize_text_field( $feed_slug ), intval( $videos_per_page ) );
            
            if ( isset( $etf_feeds->itemListData ) && !empty($etf_feeds->itemListData) ) {
                $etf_feeds = $this->etf_setup_feed_data( $etf_feeds->itemListData );
                $etf_feed_key = array_search( $id, array_column( $etf_feeds, 'number' ) );
                if ( isset( $etf_feed_key ) ) {
                    $etf_single_feed = $etf_feeds[$etf_feed_key];
                }
            }
            
            /*
             * Load feed popup template if avaiable in active theme
             * Feed popup template can be overriden by "{your-theme}/easy-tiktok-feed/frontend/views/html-feed-popup.php"
             */
            
            if ( $etf_popup_templateurl = locate_template( array( 'easy-tiktok-feed/frontend/views/html-feed-popup.php' ) ) ) {
                $etf_popup_templateurl = $etf_popup_templateurl;
            } else {
                $etf_popup_templateurl = ETF_DIR . '/frontend/views/html-feed-popup.php';
            }
            
            require $etf_popup_templateurl;
            die;
        }
        
        public function etf_video_stream()
        {
            $this->init_config();
            $streamer = new Easy_Tiktok_Feed_Stream( $this->config );
            if ( !isset( $_GET['url'] ) || !isset( $_GET['user_name'] ) || !isset( $_GET['video_id'] ) ) {
                wp_die( 'Required parameters not found' );
            }
            $user_name = sanitize_key( $_GET['user_name'] );
            $video_id = sanitize_key( $_GET['video_id'] );
            $protocols = array( 'http://', 'http://www.', 'www.' );
            $home_url = str_replace( $protocols, '', home_url() );
            $http_referer = wp_get_referer();
            $url = base64_decode( esc_url_raw( $_GET['url'] ) );
            if ( strpos( $http_referer, $home_url ) === false ) {
                wp_die( 'Required parameters not found' );
            }
            
            if ( !$streamer->stream( $url ) ) {
                $url = $this->getVideoByUser( $user_name, $video_id );
                $streamer->stream( $url );
            }
        
        }
        
        function getVideoByUser( $user_name = '', $video_id = '' )
        {
            $url = "{$this->etf_tiktok_api_url}/share/video/@{$user_name}/{$video_id}";
            $data = $this->etf_get_remote_data( $url );
            return $data->itemInfo->itemStruct->video->playAddr;
        }
    
    }
    $Easy_Tiktok_Feed_Frontend = new Easy_Tiktok_Feed_Frontend();
}
