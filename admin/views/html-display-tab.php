<?php

/**
* Admin View: Tab - Display
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div id="etf-use" class="etf-tab-content <?php 
echo  ( $active_tab == 'etf-display' ? 'etf-tab-active' : '' ) ;
?>">
 <h2><?php 
esc_html_e( "How to use?", 'easy-tiktok-feed' );
?></h2>
 <p><?php 
esc_html_e( "Copy and paste the following shortcode in any page, post or text widget to display the GoPro Tiktok Feed", 'easy-tiktok-feed' );
?></p>

 <div class="etf-shortcode-wrap">
  <div class="etf-shortcode-holder">[easy-tiktok-feed username="gopro" layout="grid"]</div>
  <div class="icon etf-icon-clipboard etf-tooltip etf-copy-to-clipboard" data-clipboard-text='[easy-tiktok-feed username="gopro" layout="grid"]'>
    <span class="etf-tooltiptext"><?php 
esc_html_e( "Copy", 'easy-tiktok-feed' );
?></span>
  </div>

</div>  

<div class="etf-shortcode-generator-wrap">
 <h2><?php 
esc_html_e( "Want to customize?", 'easy-tiktok-feed' );
?></h2>
 <p><?php 
esc_html_e( "Use the following shortcode generator to further customize the shortcode.", 'easy-tiktok-feed' );
?></p>

 <div class="etf-shortcode-generator-holder">

  <div class="etf-input-field-wrapper etf-col-12">

    <label class="etf-main-label"><?php 
esc_html_e( "Username or Hashtag?", 'easy-tiktok-feed' );
?></label>
    <div class="etf-radio-wrap"> 
     
      <div class="etf-radio-holder">
        <input checked type="radio" id="etf-username-field" name="etf-type" value="username">
        <label for="etf-username-field"><?php 
esc_html_e( "Username", 'easy-tiktok-feed' );
?></label>
      </div>  

      <?php 
?>

         <div class="etf-radio-holder">
          <input type="radio" id="etf-free-hashtag-field" name="etf-type" value="hashtag_free">
          <label for="etf-free-hashtag-field"><?php 
esc_html_e( "Hashtag", 'easy-tiktok-feed' );
?></label>
        </div> 

          <!-- Hashtag Premium Modal -->
        <div id="etf-premium-hashtag-modal" class="etf-modal">
            
            <div class="etf-modal-content">
              
              <div class="etf-lock-icon"><span class="dashicons dashicons-lock"></span></div>

              <h5><?php 
esc_html_e( "Premium Feature", 'easy-tiktok-feed' );
?></h5>

               <p><?php 
esc_html_e( "We're sorry, Hashtag feature is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'easy-tiktok-feed' );
?> </p>

               <hr />

               <a href="<?php 
echo  esc_url( etf_fs()->get_upgrade_url() ) ;
?>" class="etf-upgrade-btn"><?php 
esc_html_e( "Upgrade to pro", 'easy-tiktok-feed' );
?></a>
        
            </div>

        </div>

      <?php 
?>  

    </div>
   </div> 
  <div class="etf-input-field-wrapper etf-col-12 etf-username-field">
    <label for="etf-username" class="inp">
      <input type="text" id="etf-username" name="etf-username" value="gopro" placeholder="&nbsp;">
      <span class="label"><?php 
esc_html_e( "Enter Tiktok username", 'easy-tiktok-feed' );
?></span>
      <span class="focus-bg"></span>
    </label>
  </div>

  <?php 
?>  

    <div class="etf-input-field-wrapper etf-select-field-wrapper">

      <label for="etf-selected-layout" class=""><?php 
esc_html_e( "Layout", 'easy-tiktok-feed' );
?></label>
      
      <select id="etf-selected-layout" class="etf-selected-layout" name="etf-selected-layout" required>

       <option value="grid"><?php 
esc_html_e( "Grid", 'easy-tiktok-feed' );
?></option>
       
       
       <?php 
?>

      <option value="free-masonry"><?php 
esc_html_e( "Masonry", 'easy-tiktok-feed' );
?></option>
      <option value="free-carousel"><?php 
esc_html_e( "Carousel", 'easy-tiktok-feed' );
?></option>
      <option value="free-half_width"><?php 
esc_html_e( "Half Width", 'easy-tiktok-feed' );
?></option>
      <option value="free-full_width"><?php 
esc_html_e( "Full Width", 'easy-tiktok-feed' );
?></option>
    <?php 
?>
    
  </select> 

    <!-- Premium Modal -->
    <div id="etf-premium-layout-modal" class="etf-modal">
        
        <div class="etf-modal-content">
          
          <div class="etf-lock-icon"><span class="dashicons dashicons-lock"></span></div>

          <h5><?php 
esc_html_e( "Premium Feature", 'easy-tiktok-feed' );
?></h5>

           <p><?php 
esc_html_e( "We're sorry, selected layout is not included in your plan. Please upgrade to premium version to unlock this and all other cool features.", 'easy-tiktok-feed' );
?> </p>

           <hr />

           <a href="<?php 
echo  esc_url( etf_fs()->get_upgrade_url() ) ;
?>" class="etf-upgrade-btn"><?php 
esc_html_e( "Upgrade to pro", 'easy-tiktok-feed' );
?></a>
    
        </div>

    </div>

  </div>

  <div class="etf-input-field-wrapper etf-col-12 padding-none">
    <label for="etf-videos-per-page" class="inp">
      <input type="number" id="etf-videos-per-page" value="9" name="etf-videos-per-page" placeholder="&nbsp;">
      <span class="label"><?php 
esc_html_e( "Number of videos to display", 'easy-tiktok-feed' );
?></span>
      <span class="focus-bg"></span>
    </label>
  </div>

   <div class="etf-input-field-wrapper etf-col-12">
    <label for="etf-wrapper-class" class="inp">
      <input type="text" id="etf-wrapper-class" name="etf-wrapper-class" placeholder="&nbsp;">
      <span class="label"><?php 
esc_html_e( "Custom wraper class (optional)", 'easy-tiktok-feed' );
?></span>
      <span class="focus-bg"></span>
    </label>
  </div>

  <div class="etf-shortcode-footer-holder">
    <button class="etf-generate-shortcode"><?php 
esc_html_e( "Generate", 'easy-tiktok-feed' );
?></button>

    <div class="etf-shortcode-wrap etf-generated-shortcode-holder">
       <div class="etf-shortcode-holder">[easy-tiktok-feed username="gopro" layout="grid"]</div>
      <div class="icon etf-icon-clipboard etf-tooltip etf-copy-to-clipboard" data-clipboard-text='[easy-tiktok-feed username="gopro"]'>
        <span class="etf-tooltiptext"><?php 
esc_html_e( "Copy", 'easy-tiktok-feed' );
?></span>
      </div>
    </div>  

  </div>  

</div>                  
</div> 
</div>