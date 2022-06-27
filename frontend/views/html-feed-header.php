<?php

if ( $etf_bio_data ) {
    
    if ( isset( $etf_bio_data['profile_pic_url_hd'] ) ) {
        $profile_pic_url = $etf_bio_data['profile_pic_url_hd'];
    } else {
        
        if ( isset( $etf_bio_data['profile_pic_url'] ) ) {
            $profile_pic_url = $etf_bio_data['profile_pic_url'];
        } else {
            $profile_pic_url = '';
        }
    
    }
    
    ?>

<div class="etf_header">
		<div class="etf_header_inner_wrap">

				<?php 
    do_action( 'etf_before_feed_header', $etf_bio_data );
    ?>

					<div class="etf_header_img">
						<a href="<?php 
    echo  esc_url_raw( $etf_bio_data['permalink'] ) ;
    ?>" title="@<?php 
    echo  esc_attr( $etf_bio_data['username'] ) ;
    ?>" target="_blank">

							<?php 
    do_action( 'etf_before_feed_header_image', $etf_bio_data );
    
    if ( $profile_pic_url ) {
        ?>

								<img src="<?php 
        echo  esc_url( apply_filters( 'etf_feed_header_image', $profile_pic_url, $etf_bio_data ) ) ;
        ?>" />
							<?php 
    }
    
    ?>		

								<?php 
    if ( $etf_show_hashtag ) {
        ?>

									<span class="etf-hashtag-overlay"><i class="icon etf-icon-tik-tok"></i></span>
									
								<?php 
    }
    ?>	


							<?php 
    do_action( 'etf_after_feed_header_image', $etf_bio_data );
    ?>	
						</a>
					</div>

				
			<div class="etf_header_content">
				<div class="etf_header_meta">

					<?php 
    
    if ( isset( $etf_bio_data['full_name'] ) ) {
        ?>

						<div class="etf_header_title">

							<?php 
        do_action( 'etf_before_feed_header_title', $etf_bio_data );
        ?>

							<h4>
								<a href="<?php 
        echo  esc_url_raw( $etf_bio_data['permalink'] ) ;
        ?>" title="@<?php 
        echo  esc_attr( $etf_bio_data['username'] ) ;
        ?>" target="_blank"><?php 
        if ( $etf_show_hashtag ) {
            ?>#<?php 
        }
        echo  esc_html( $etf_bio_data['full_name'] ) ;
        ?>
								</a>
							</h4>
							
							<?php 
        do_action( 'etf_after_feed_header_title', $etf_bio_data );
        ?>

						</div>

					<?php 
    }
    
    ?>

					<?php 
    
    if ( isset( $etf_bio_data['likes_count'] ) ) {
        ?>

						<div class="etf_likes" title="<?php 
        esc_html_e( 'Likes', 'easy-tiktok-feed' );
        ?>">

							<?php 
        do_action( 'etf_before_feed_header_likes', $etf_bio_data );
        ?>

							<i class="icon etf-icon-heart"></i>

							<div class="etf_count_wrap"><?php 
        echo  etf_readable_count( apply_filters( 'etf_feed_header_likes', $etf_bio_data['likes_count'], $etf_bio_data ) ) ;
        ?></div>

							<?php 
        do_action( 'etf_after_feed_header_likes', $etf_bio_data );
        ?>
						</div>

					<?php 
    }
    
    ?>

					<?php 
    
    if ( isset( $etf_bio_data['fan_count'] ) ) {
        ?>

						<div class="etf_followers" title="<?php 
        esc_html_e( 'Followers', 'easy-tiktok-feed' );
        ?>">

							<?php 
        do_action( 'etf_before_feed_header_followers', $etf_bio_data );
        ?>

							<i class="icon etf-icon-users"></i>

							<div class="etf_count_wrap"><?php 
        echo  etf_readable_count( apply_filters( 'etf_feed_header_followers', $etf_bio_data['fan_count'], $etf_bio_data ) ) ;
        ?></div>

							<?php 
        do_action( 'etf_after_feed_header_followers', $etf_bio_data );
        ?>
						</div>

					<?php 
    }
    
    ?>


					<?php 
    
    if ( isset( $etf_bio_data['video_count'] ) ) {
        ?>

						<div class="etf_videos" title="<?php 
        esc_html_e( 'Videos', 'easy-tiktok-feed' );
        ?>">

							<?php 
        do_action( 'etf_before_feed_header_videos', $etf_bio_data );
        ?>
							<i class="icon etf-icon-video-camera"></i>
							<div class="etf_count_wrap"><?php 
        echo  etf_readable_count( apply_filters( 'etf_feed_header_videos', $etf_bio_data['video_count'], $etf_bio_data ) ) ;
        ?></div>

							<?php 
        do_action( 'etf_after_feed_header_videos', $etf_bio_data );
        ?>
						</div>

					<?php 
    }
    
    ?>

					<?php 
    
    if ( isset( $etf_bio_data['following_count'] ) ) {
        ?>

						<div class="etf_following" title="<?php 
        esc_html_e( 'Following', 'easy-tiktok-feed' );
        ?>">

							<?php 
        do_action( 'etf_before_feed_header_following', $etf_bio_data );
        ?>

							<i class="icon etf-icon-user-plus"></i>

							<div class="etf_count_wrap"><?php 
        echo  etf_readable_count( apply_filters( 'etf_feed_header_following', $etf_bio_data['following_count'], $etf_bio_data ) ) ;
        ?></div>

							<?php 
        do_action( 'etf_after_feed_header_following', $etf_bio_data );
        ?>
						</div>

					<?php 
    }
    
    ?>

					<?php 
    
    if ( $etf_show_hashtag && isset( $etf_bio_data['views_count'] ) ) {
        ?>

						<div class="etf_views" title="<?php 
        esc_html_e( 'Views', 'easy-tiktok-feed' );
        ?>">

							<?php 
        do_action( 'etf_before_feed_header_views', $etf_bio_data );
        ?>

							<i class="icon etf-icon-eye"></i>

							<div class="etf_count_wrap"><?php 
        echo  etf_readable_count( apply_filters( 'etf_feed_header_views', $etf_bio_data['views_count'], $etf_bio_data ) ) ;
        ?></div>
		
							<?php 
        do_action( 'etf_after_feed_header_views', $etf_bio_data );
        ?>
						</div>

					<?php 
    }
    
    ?>

			
				</div>
				<?php 
    
    if ( $etf_bio_data['bio'] ) {
        do_action( 'etf_before_feed_header_bio', $etf_bio_data );
        $etf_bio = esc_html( apply_filters( 'etf_feed_header_bio', $etf_bio_data['bio'], $etf_bio_data ) );
        $etf_bio = etf_hastags_to_link( $etf_bio );
        ?>
				 
					<p class="etf_bio"><?php 
        echo  $etf_bio ;
        ?></p>

				<?php 
        do_action( 'etf_after_feed_header_bio', $etf_bio_data );
    }
    
    ?>
			</div>
		</div>
	</div>

<?php 
}
