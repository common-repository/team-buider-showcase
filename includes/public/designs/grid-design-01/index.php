<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require PLWL_TEAM_INCLUDES_PATH . 'public/designs/'.$design.'/style.php';
?>

<div class="team-section-<?php echo esc_attr($PostId); ?>">
    <div class="row text-center">
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
		  <div class="box-team">
		    <div class="team-data-img">
		        <div class="team-img">
		            <img src="<?php echo esc_url($url[0]); ?>" alt="team" class="img-responsive" >
		            <div class="first-color overlay center-block">
		            	<?php if($enableSocial)	{ ?>
		                <ul class="team-social white-bg">
		                	<?php if($enableFacebook && $image['fburl'] != "") { ?> 
		                    	<li><a href="<?php echo esc_url($image['fburl']); ?>" class="facebook-bg-hvr" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-facebook-f"></i></a></li>
		                	<?php } ?>
		                    <?php if($enableTwitter && $image['twturl'] != "") { ?> 
		                    	<li><a href="<?php echo esc_url($image['twturl']); ?>" class="twitter-bg-hvr" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-twitter"></i></a></li>
		                	<?php } ?>
		                    <?php if($enableLinkedin && $image['lnkdnurl'] != "") { ?> 
		                    	<li><a href="<?php echo esc_url($image['lnkdnurl']); ?>" class="twitter-bg-hvr" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-linkedin"></i></a></li>
		                	<?php } ?>
		                    <?php if($enableInstagram && $image['instanurl'] != "") { ?> 
		                    	<li><a href="<?php echo esc_url($image['instanurl']); ?>" class="twitter-bg-hvr" target="<?php if($image['target']==1) {?>_blank<?php } ?>"><i class="fa-brands fa-instagram"></i></a></li>
		                	<?php } ?>
		                    <?php if($enableEmail && $image['emailurl'] != "") { ?> 
		                    	<li><a href="mailto:<?php echo esc_url($image['emailurl']); ?>" class="google-bg-hvr"><i class="fa-solid fa-envelope"></i></a></li>
		                	<?php } ?>
		                </ul>
		            	<?php } ?>
		            </div>
		        </div>
		        <div class="team-content">
		        	<?php if(!$hideTitle) {?>
		            	<h3 class="color-black font-weight-normal mt-4 text-capitalize"><?php echo esc_html($image['title']); ?></h3>
		            <?php } ?>
		            <?php if(!$hideDesignation) {?>
			            <p class="color-light-grey font-weight-light team-designation text-capitalize">
			                <?php echo esc_html($image['alt']); ?>
			            </p>
			        <?php } ?>
			        <?php if(!$hideDescription) {?>
		            	<p class="team-description"><?php echo esc_html($image['description']); ?></p>
		            <?php } ?>
		        </div>
		    </div>
		  </div>
		</div>
		<?php endforeach; ?>  
	</div>
</div>