<?php
/**
* Admin View: Page - Easy Tiktokt Feed
*/
if ( ! defined( 'ABSPATH' ) ) {
exit;
}

if( isset( $_GET['tab'] ) ) {
    $active_tab = esc_html( $_GET['tab'] );
}else{
	$active_tab = 'etf-display';
} 

?>
<div class="etf-admin-wrap slideLeft">
	<div class="etf-admin-container">

		<div class="etf-tabs-holder">

			<?php do_action('etf_before_admin_tab'); ?>

			<div class="etf-tabs-buttons-holder">
				<nav class="etf-nav-tab-wrapper wp-clearfix">
					<a href="<?php echo esc_url( admin_url('admin.php?page=easy-tiktok-feed&tab=etf-display') ); ?>" class="etf-nav-tab <?php echo $active_tab == 'etf-display' ? 'etf-nav-tab-active' : ''; ?>"><?php esc_html_e("Use (Display)", 'easy-tiktok-feed');?></a>

					<a href="<?php echo esc_url( admin_url('admin.php?page=easy-tiktok-feed&tab=etf-cache') ); ?>" class="etf-nav-tab <?php echo $active_tab == 'etf-cache' ? 'etf-nav-tab-active' : ''; ?>"><?php esc_html_e("Clear Cache", 'easy-tiktok-feed');?></a>
					
					<?php do_action('etf_admin_tab'); ?>

				</nav>
			</div>

			<?php include_once ETF_DIR . 'admin/views/html-display-tab.php'; ?>

			<?php include_once ETF_DIR . 'admin/views/html-cache-tab.php'; ?>

			<?php do_action('etf_admin_tab_content'); ?>

		</div>	
	</div>	
 <div class="etf-notification-holder">
 	<?php esc_html_e('Copied', 'floating-links');?>
 </div>	
</div>
