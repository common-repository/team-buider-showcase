<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

require PLWL_TEAM_INCLUDES_PATH . 'public/designs/'.$design.'/style.php';
?>

<div class="team-section-<?php echo esc_attr($PostId); ?>">
    <div class="row">
	<?php
    foreach ( $images as $image ): ?>
    <?php 
        
        $image_object = get_post( $image['id'] );

        if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
            continue;
        }

        /*--image cropping--*/
        $id=$image['id'];
      
        $url = wp_get_attachment_image_src($id, 'full', true);
    
       ?> 
       <div class="col-md-<?php echo esc_attr($column); ?> col-sm-6 col-12">
           <div class="our-team">
                <div class="pic">
                    <img src="<?php echo esc_url($url[0]); ?>" alt="">

                    <?php if(!$hideDescription || $enableSocial) {?>
                        <div class="social_media_team">
                        <?php if(!$hideDescription) {?>
                            <p class="team-description">
                                <?php echo esc_html($image['description']); ?>
                            </p>
                        <?php } ?>
                        <?php if($enableSocial) { ?>
                        <ul class="social-link">
                            <?php if($enableFacebook && $image['fburl'] != "") { ?> 
                                <li><a href="<?php echo esc_url($image['fburl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <?php } ?>
                            <?php if($enableTwitter && $image['twturl'] != "") { ?>
                                <li><a href="<?php echo esc_url($image['twturl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-twitter"></i></a></li>
                            <?php } ?>
                            <?php if($enableLinkedin && $image['lnkdnurl'] != "") { ?> 
                                <li><a href="<?php echo esc_url($image['lnkdnurl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-linkedin"></i></a></li>
                            <?php } ?>
                            <?php if($enableInstagram && $image['instanurl'] != "") { ?>
                                <li><a href="<?php echo esc_url($image['instanurl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-instagram"></i></a></li>
                            <?php } ?>
                            <?php if($enableEmail && $image['emailurl'] != "") { ?>
                                <li><a href="mailto:<?php echo esc_url($image['emailurl']); ?>"><i class="fa-solid fa-envelope"></i></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <?php if(!$hideTitle || !$hideDesignation) {?>
                    <div class="team-content">
                        <?php if(!$hideTitle) {?>
                            <h3 class="post-title"><?php echo esc_html($image['title']); ?></h3>
                        <?php } ?>
                        <?php if(!$hideDesignation) {?>
                            <span class="team-designation"><?php echo esc_html($image['alt']); ?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
       </div>
       <?php endforeach; ?>  
    </div>
</div>