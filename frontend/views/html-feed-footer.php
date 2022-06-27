<?php 

/*
* Stop execution if someone tried to get file directly.
*/
if ( ! defined( 'ABSPATH' ) ) exit;

do_action('etf_before_feed_footer', $etf_feeds);
?>


<div class="etf_load_more_btns_wrap">
	<div class="etf_feed_btns_holder">

		<div class="etf-follow-btn-wrap">
			<a href="<?php echo esc_url_raw( $etf_bio_data['permalink'] ); ?>" title="@<?php echo esc_attr( $etf_bio_data['username'] ); ?>" target="_blank" class="etf-follow-btn" target="_blank"><i class="icon etf-icon-tik-tok"></i><?php esc_html_e( 'Follow on Tiktok', 'easy-tiktok-feed' ); ?>
			</a>
		</div>	
		
	</div>
</div>

<?php do_action('etf_after_feed_footer', $etf_feeds); ?>