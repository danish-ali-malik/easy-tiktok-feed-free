<?php 
/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if( $etf_thumbnail && !empty( $etf_thumbnail ) ){

?>

  <div class="etf-col-lg-4 etf-col-12">
     <div class="etf-grid-wrapper etf-story-wrapper">

           <a data-fancybox="etf_feed_popup_<?php echo esc_attr( $etf_feed_slug ); ?>" class="etf_feed_fancy_popup etf_grid_box" data-type="ajax" data-src="<?php echo admin_url('admin-ajax.php')?>?action=etf_load_feed_popup&id=<?php echo esc_attr( $etf_single_feed['number'] ); ?>&videos_per_page=<?php echo esc_attr( $videos_per_page ); ?>&feed_slug=<?php echo esc_attr( $etf_feed_slug ); ?>&etf_show_hashtag=<?php echo esc_attr( $etf_show_hashtag ); ?>" href="javascript:;" target="_blank" style="background-image: url(<?php echo esc_url( $etf_thumbnail ); ?>)">   

            <div class="etf-overlay">
                <i class="icon etf-icon-plus etf-plus" aria-hidden="true"></i>

             </div>
        </a>      
  </div>              
</div>

<?php } ?>