<?php
/**
* Admin View: Tab - Clear Cache
*/
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $wpdb;

$etf_transient_sql = "SELECT `option_name` AS `name`, `option_value` AS `value` FROM  {$wpdb->options} WHERE `option_name` LIKE '%transient_%'            ORDER BY `option_name`";

$etf_transient_results = $wpdb->get_results( $etf_transient_sql );

$etf_transient_feed = array();

$etf_transient_bio = array();

if ( $etf_transient_results ) {
    foreach ( $etf_transient_results as $etf_transient ) {
        
        if ( strpos( $etf_transient->name, 'etf_tiktok' ) !== false && strpos( $etf_transient->name, 'posts' ) !== false && strpos( $etf_transient->name, 'timeout' ) == false ) {
            $etf_transient_feed[$etf_transient->name] = $etf_transient->value;
        }

        
        if ( strpos( $etf_transient->name, 'etf_tiktok' ) !== false && strpos( $etf_transient->name, 'bio' ) !== false && strpos( $etf_transient->name, 'timeout' ) == false ) {
            $etf_transient_bio[$etf_transient->name] = $etf_transient->value;
        }
        
    }
}  



?>

<div id="etf-cache" class="etf-tab-content <?php echo $active_tab == 'etf-cache' ? 'etf-tab-active' : ''; ?>">

<div class="etf-skins-info-holder">

  <h2><?php esc_html_e("Cached Account(s) and Hashtag feed", 'easy-tiktok-feed');?></h2>
   <p><?php esc_html_e("Following is the cached data from Tiktok API. Delete the cache to refresh your feed manually", 'easy-tiktok-feed');?></p>
</div>   



<div class="etf-cache-holer">

  <?php  if ( $etf_transient_bio ) {   ?>

         <ul class="etf-tiktok-bio-wrap etf-cache-wrap">
                <li class="header"><h2><?php esc_html_e("Bio", 'easy-tiktok-feed');?></h2></li>

                <?php foreach ( $etf_transient_bio as $key => $value ) {
  
                    $pieces = explode( '-', $key );

                    $name = array_pop( $pieces );

                    ?>

                    <li class="etf-cache-item <?php echo $key; ?>">
                        <div><?php echo $name; ?>
                        <a href="javascript:void(0);" data-type="etf-tiktok-bio-wrap" data-transient="<?php echo $key; ?>" class="etf-delete-transient"><span class="dashicons dashicons-trash"></span></a>
                    </div>
                </li>
            <?php } ?>

        </ul>

  <?php } ?> 

  <?php  if ( $etf_transient_feed ) {   ?>

       <ul class="etf-tiktok-bio-wrap etf-cache-wrap">
        <li class="header"><h2><?php esc_html_e("Feed", 'easy-tiktok-feed');?></h2></li>

        <?php foreach ( $etf_transient_feed as $key => $value ) {

          $pieces = explode( '-', $key );

          $number = array_pop( $pieces );

          $name = $pieces['1'];

          ?>

          <li class="etf-cache-item <?php echo $key; ?>">
            <div><?php echo $name; ?>
            <a href="javascript:void(0);" data-type="etf-tiktok-bio-wrap" data-transient="<?php echo $key; ?>" class="etf-delete-transient"><span class="dashicons dashicons-trash"></span></a>
          </div>
        </li>
      <?php } ?>

    </ul>

  <?php } 

  if (  empty($etf_transient_feed) && empty($etf_transient_bio) ) { ?>

    <p class="etf-no-cache"><?php esc_html_e("Whoops! nothing cached at the moment.", 'easy-tiktok-feed');?></p>

<?php } ?>  

</div> 

</div>