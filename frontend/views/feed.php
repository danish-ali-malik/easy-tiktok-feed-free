<?php

/** 
 * Represents the view for the public-facing feed of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 */
?>

<div class="etf_feed_wraper <?php 
echo  esc_attr( $wrapper_class ) ;
?>" id="etf_feed_<?php 
echo  esc_attr( $username ) ;
?>">

	<?php 
$etf_bio_data = $this->etf_get_user_bio( $username, $etf_cache_seconds );
/*
 * Load header template if avaiable in active theme
 * Header template can be overriden by "{your-theme}/easy-tiktok-feed/frontend/views/html-feed-header.php"
 */

if ( $etf_header_templateurl = locate_template( array( 'easy-tiktok-feed/frontend/views/html-feed-header.php' ) ) ) {
    $etf_header_templateurl = $etf_header_templateurl;
} else {
    $etf_header_templateurl = ETF_DIR . '/frontend/views/html-feed-header.php';
}

include_once $etf_header_templateurl;
/*
 * Get Feed and load selected template.
 * Feed Template can be overriden by "{your-theme}/easy-tiktok-feed/frontend/templates/template-{$layout}.php"
 */
$etf_feeds = $this->etf_get_feeds( sanitize_text_field( $username ), intval( $videos_per_page ), $etf_cache_seconds );

if ( isset( $etf_feeds->itemListData ) && !empty($etf_feeds->itemListData) ) {
    $carousel_class = '';
    ?>

	<div class="etf_feeds_holder etf_feeds_<?php 
    echo  esc_attr( $layout ) ;
    ?> <?php 
    echo  esc_attr( $carousel_class ) ;
    ?>" data-template="<?php 
    echo  esc_attr( $layout ) ;
    ?>">

		<?php 
    switch ( $layout ) {
        case 'masonry':
            ?>

				<div class="etf-masonry-skin">
	               <div class="etf-masonry etf-coulmn-count-3">

	           <?php 
            break;
        case 'grid':
            ?>

	           	<div class="etf-grid-skin">
					<div class="etf-row e-outer">

				<?php 
            break;
    }
    if ( etf_fs()->is_free_plan() && !etf_fs()->is_trial() ) {
        $layout = 'grid';
    }
    $etf_trasneint_key = 'etf-feed-' . str_replace( ' ', '', $username );
    $etf_feeds = $this->etf_setup_feed_data( $etf_feeds->itemListData );
    foreach ( $etf_feeds as $etf_single_feed ) {
        $etf_thumbnail = '';
        
        if ( isset( $etf_single_feed['covers']['dynamic'] ) ) {
            $etf_thumbnail = $etf_single_feed['covers']['dynamic'];
        } else {
            $etf_thumbnail = $etf_single_feed['covers']['default'];
        }
        
        $etf_thumbnail = apply_filters( 'etf_thumbnail_url', $etf_thumbnail, $etf_single_feed );
        
        if ( $etf_templateurl = locate_template( array( 'easy-tiktok-feed/templates/template-' . $layout . '.php' ) ) ) {
            $etf_templateurl = $etf_templateurl;
        } else {
            $etf_templateurl = ETF_DIR . 'frontend/views/templates/template-' . $layout . '.php';
        }
        
        require $etf_templateurl;
    }
    if ( $layout == 'masonry' || $layout == 'grid' ) {
        ?>
            </div> 
        </div>
     <?php 
    }
    ?>

	</div>	

	<?php 
    /*
     * Load footer template if avaiable in active theme
     * Header template can be overriden by "{your-theme}/easy-tiktok-feed/frontend/views/html-feed-footer.php"
     */
    
    if ( $etf_footer_templateurl = locate_template( array( 'easy-tiktok-feed/frontend/views/html-feed-footer.php' ) ) ) {
        $etf_footer_templateurl = $etf_footer_templateurl;
    } else {
        $etf_footer_templateurl = ETF_DIR . '/frontend/views/html-feed-footer.php';
    }
    
    include_once $etf_footer_templateurl;
} else {
    ?>
        <div class="etf-error-msg"><i class="icon etf-icon-tik-tok"></i><span><?php 
    esc_html_e( 'Unable to get feed' );
    ?></span> </div>
    <?php 
}

?>

</div>	