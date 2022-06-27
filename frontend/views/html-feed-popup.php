<?php

/*
* Stop execution if someone tried to get file directly.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// echo "<pre>"; print_r($etf_single_feed); exit();
?>

<div class="etf-popup etf-post-detail" id="etf-popup-<?php 
echo  esc_attr( $etf_single_feed['number'] ) ;
?>">
    <div class="etf-d-columns-wrapper <?php 
if ( !etf_fs()->is_free_plan() ) {
    ?>etf-popup-pro-wrap <?php 
}
?>">
        <div class="etf-image">

          <video width="100%"  controls>
                  <source src="<?php 
echo  esc_url( $etf_single_feed['covers']['video'] ) ;
?>" type="video/mp4">
                   <?php 
esc_html_e( 'Your browser does not support the video tag', 'easy-facebook-likebox' );
?>
          </video>

        </div>

        <?php 
?>
    </div>
</div>