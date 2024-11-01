<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<script type="text/html" id="tmpl-plwl-image">
    <div class="plwl-single-image-content {{data.orientation}}" <# if ( data.full != '' ) { #> style="background-image:url({{ data.thumbnail }})" <# } #> >
        <?php do_action( 'plwl_admin_gallery_image_start' ) ?>
        <# if ( data.thumbnail != '' ) { #>
            <img src="{{ data.thumbnail }}">
        <# } #>
        <div class="bg-actions">
            <div class="actions">
                <?php do_action( 'plwl_admin_gallery_image_before_actions' ) ?>
                <a href="#" class="plwl-edit-image" title="<?php esc_attr_e( 'Edit Image', 'team-builder-showcase' ) ?>"><span class="dashicons dashicons-edit"></span></a>
                <?php do_action( 'plwl_admin_gallery_image_after_actions' ) ?>
                <a href="#" class="plwl-delete-image" title="<?php esc_attr_e( 'Delete Image', 'team-builder-showcase' ) ?>"><span class="dashicons dashicons-trash"></span></a>
            </div>
        </div>
        <div class="segrip ui-resizable-handle ui-resizable-se"></div>
        <?php do_action( 'plwl_admin_gallery_image_end' ) ?>
    </div>
</script>

<script type="text/html" id="tmpl-plwl-image-editor">
    <div class="edit-media-header">
        <button class="left dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit previous media item', 'team-builder-showcase' ); ?></span></button>
        <button class="right dashicons"><span class="screen-reader-text"><?php esc_html_e( 'Edit next media item', 'team-builder-showcase' ); ?></span></button>
    </div>
    <div class="media-frame-title">
        <h1><?php esc_html_e( 'Edit Metadata', 'team-builder-showcase' ); ?></h1>
    </div>
    <div class="media-frame-content">
        <div class="attachment-details save-ready">
            <!-- Left -->
            <div class="attachment-media-view portrait">
                <div class="thumbnail thumbnail-image">
                    <img class="details-image" src="{{ data.full }}" draggable="false" />
                </div>
            </div>
            
            <!-- Right -->
            <div class="attachment-info">
                <!-- Settings -->
                <div class="settings">
                    <!-- Attachment ID -->
                    <input type="hidden" name="id" value="{{ data.id }}" />
                    
                    <!-- Image Title -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Member Name', 'team-builder-showcase' ); ?></span>
                        <input type="text" name="title" value="{{ data.title }}" />
                        <div class="description">
                            <?php esc_html_e( 'Image titles can take any type of HTML.', 'team-builder-showcase' ); ?>
                        </div>
                    </label>
                  
                    
                    <!-- Alt Text -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Member Designation', 'team-builder-showcase' ); ?></span>
                        <input type="text" name="alt" value="{{ data.alt }}" />
                        <div class="description">
                        </div>
                    </label>

                    <!-- Caption Text -->
                    <label class="setting">
                        <span class="name"><?php esc_html_e( 'Member Short Description', 'team-builder-showcase' ); ?></span>
                        <textarea name="description">{{ data.description }}</textarea>
                        <div class="description">
                        </div>
                    </label>


                     <div class="setting plwl-social">
                        <label class="social-icons">
                            <span class="name"><?php esc_html_e( 'Social Icons:', 'team-builder-showcase' ); ?>
                            
                            <input class="social-link" type="text" name="fburl" placeholder="Facebook URL" value="{{ data.fburl }}" />
                            <span class="dashicons dashicons-facebook-alt"></span>
                        </label>
                    </div>
                    <div class="setting plwl-social">
                        <label class="social-icons">
                            <span class="name"><?php esc_html_e( '', 'team-builder-showcase' ); ?>
                            
                            <input class="social-link" type="text" name="twturl" placeholder="Twitter URL" value="{{ data.twturl }}" />
                            <span class="dashicons dashicons-twitter"></span>
                        </label>
                    </div>
                    <div class="setting plwl-social">
                        <label class="social-icons">
                            <span class="name"><?php esc_html_e( '', 'team-builder-showcase' ); ?>
                            
                            <input class="social-link" type="text" name="lnkdnurl" placeholder="Linkedin URL" value="{{ data.lnkdnurl }}" />
                            <span class="dashicons dashicons-linkedin"></span>
                        </label>
                    </div>
                    <div class="setting plwl-social">
                        <label class="social-icons">
                            <span class="name"><?php esc_html_e( '', 'team-builder-showcase' ); ?>
                            
                            <input class="social-link" type="text" name="instanurl" placeholder="Instagram URL" value="{{ data.instanurl }}" />
                            <span class="dashicons dashicons-instagram"></span>
                        </label>
                    </div>

                    <!-- Link -->
                    <div class="setting">
                        <label style="display:flex;">
                        <span class="description">
                            <input type="checkbox" name="target" value="1"<# if ( data.target == '1' ) { #> checked <# } #> />
                            <span><?php esc_html_e( 'Open Social Icons links in a new browser window/tab.', 'team-builder-showcase' ); ?></span>
                        </span>
                        </label>
                    </div>

                    <!-- Email -->
                    <label class="setting" style="padding: 15px 0 8px 0;border-top: 1px solid #ddd;">
                        <span class="name"><?php esc_html_e( 'Email', 'team-builder-showcase' ); ?></span>
                        <input type="text" name="emailurl" value="{{ data.emailurl }}" />
                        <div class="description">
                        </div>
                    </label>


                </div>
                <!-- /.settings -->     
               
                <!-- Actions -->
                <div class="actions">
                    <a href="#" class="plwl-team-builder-showcase-meta-submit button media-button button-large button-primary media-button-insert" title="<?php esc_attr_e( 'Save Metadata', 'team-builder-showcase' ); ?>">
                        <?php esc_html_e( 'Save', 'team-builder-showcase' ); ?>
                    </a>
                    <a href="#" class="plwl-team-builder-showcase-meta-submit-close button media-button button-large media-button-insert" title="<?php esc_attr_e( 'Save & Close', 'team-builder-showcase' ); ?>">
                        <?php esc_html_e( 'Save & Close', 'team-builder-showcase' ); ?>
                    </a>

                    <!-- Save Spinner -->
                    <span class="settings-save-status">
                        <span class="spinner"></span>
                        <span class="saved"><?php esc_html_e( 'Saved.', 'team-builder-showcase' ); ?></span>
                    </span>
                </div>
                <!-- /.actions -->
            </div>
        </div>
    </div>
</script>