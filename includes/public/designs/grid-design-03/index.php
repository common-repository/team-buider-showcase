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
           	<div class="card-wrapper">
	            <div id="card-2" class="card card-rotating text-center">
	                <div class="face front">
	                    <!-- Avatar -->
	                    <div class="avat-image-team">
	                        <img src="<?php echo esc_url($url[0]); ?>" alt="First sample avatar image">
	                    </div>
	                </div>
	                <div class="face back">
	                    <!-- Content -->
	                    <div class="card-body p-2">
	                    	<?php if(!$hideTitle) {?>
		                        <h3 class="mt-4 mb-4"><?php echo esc_html($image['title']); ?></h3>
		                    <?php } ?>
		            		<?php if(!$hideDesignation) {?>
	                        	<p class="team-designation"><?php echo esc_html($image['alt']); ?></p>
	                        <?php } ?>
			        		<?php if(!$hideDescription) {?>
	                        	<p class="team-description"><?php echo esc_html($image['description']); ?></p>
	                        <?php } ?>
	                        
	                        <!-- Social Icons -->
	                        <?php if($enableSocial) { ?>
	                        <ul class="list-inline list-unstyled team-ul">
	                            <?php if($enableFacebook && $image['fburl'] != "") { ?>
		                            <li class="list-inline-item">
		                            	<a class="p-2 fa-lg tw-ic" href="<?php echo esc_url($image['fburl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>">
		                            		<i class="fa-brands fa-facebook-f"></i>
		                            	</a>
		                            </li>
	                            <?php } ?>
                            	<?php if($enableTwitter && $image['twturl'] != "") { ?>
		                            <li class="list-inline-item">
		                            	<a class="p-2 fa-lg tw-ic" href="<?php echo esc_url($image['twturl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>">
		                            		<i class="fa-brands fa-twitter"></i>
		                        		</a>
		                        	</li>
	                            <?php } ?>
                            	<?php if($enableLinkedin && $image['lnkdnurl'] != "") { ?> 
		                            <li class="list-inline-item">
		                            	<a class="p-2 fa-lg tw-ic" href="<?php echo esc_url($image['lnkdnurl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>">
		                            		<i class="fa-brands fa-linkedin"></i>
		                            	</a>
		                            </li>
	                            <?php } ?>
                            	<?php if($enableInstagram && $image['instanurl'] != "") { ?>
		                            <li class="list-inline-item">
		                            	<a class="p-2 fa-lg tw-ic" href="<?php echo esc_url($image['instanurl']); ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>">
		                            		<i class="fa-brands fa-instagram"></i>
		                            	</a>
		                            </li>
	                            <?php } ?>
                            	<?php if($enableEmail && $image['emailurl'] != "") { ?>
		                            <li class="list-inline-item">
		                            	<a class="p-2 fa-lg tw-ic" href="mailto:<?php echo esc_url($image['emailurl']); ?>">
		                            		<i class="fa-solid fa-envelope"></i>
		                            	</a>
		                            </li>
		                        <?php } ?>
	                        </ul>
	                        <?php } ?>
	                	</div>
	            	</div>
	        	</div>
       		</div>
		</div>
		<?php endforeach; ?>  
	</div>
</div>