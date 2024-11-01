<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<style>
.team-section-<?php echo esc_attr($PostId); ?> a, 
.team-section-<?php echo esc_attr($PostId); ?>a:hover{
    text-decoration: none;
}

.team-section-<?php echo esc_attr($PostId); ?> ul{
    list-style: none;
}

.team-section-<?php echo esc_attr($PostId); ?> img{
    width: 100%;
    height: auto;
}

.team-section-<?php echo esc_attr($PostId); ?>{
  position: relative;
}

.team-section-<?php echo esc_attr($PostId); ?> .card-wrapper {
    padding: 25px;
    border: 3px dotted rgba(43, 43, 43, 0.8);
    margin-bottom: 24px;
}

.team-section-<?php echo esc_attr($PostId); ?> .card-wrapper .card {
    border: none;
    background-color: <?php echo esc_attr($teamBgColor); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .list-inline-item:not(:last-child) {
    margin-right: 0.5rem;
}
.team-section-<?php echo esc_attr($PostId); ?> .list-inline-item {
    display: inline-block;
}

.team-section-<?php echo esc_attr($PostId); ?> .card-body h3{
    color: <?php echo esc_attr($titleColor); ?>;
    font-size: <?php echo esc_attr($titleFontSize); ?>px;
    margin: 8px 5px 5px !important;
}

.team-section-<?php echo esc_attr($PostId); ?> .card-body p.team-designation{
    color: <?php echo esc_attr($designationColor); ?>;
    font-size: <?php echo esc_attr($designationFontSize); ?>px;
    margin-bottom: 8px;
}

.team-section-<?php echo esc_attr($PostId); ?> .card-body p.team-description{
    color: <?php echo esc_attr($captionColor); ?>;
    font-size: <?php echo esc_attr($captionFontSize); ?>px;
}

@media only screen and (max-width: 480px){
    .team-section-<?php echo esc_attr($PostId); ?> .card-body h3{
        font-size: <?php echo esc_attr($mobileTitleFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .card-body p.team-designation{
        font-size: <?php echo esc_attr($mobileDesignationFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .card-body p.team-description{
        font-size: <?php echo esc_attr($mobileCaptionFontSize); ?>px;
    }
}

.team-section-<?php echo esc_attr($PostId); ?> .team-ul{
    margin:0;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-ul li a {
    width: 38px;
    height: 38px;
    text-align: center;
    line-height: 38px;
    border-radius: 50px;
    font-size: <?php echo esc_attr($socialIconSize); ?>px;
    color: <?php echo esc_attr($socialIconColor); ?>;
    background: <?php echo esc_attr($socialIconBgColor); ?>;
    border: 1px solid transparent;
    -webkit-transition: background-color .2s ease-in-out;
    -o-transition: background-color .2s ease-in-out;
    transition: background-color .2s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-ul li a:hover {
    color: <?php echo esc_attr($socialIconHColor); ?>;
    background: <?php echo esc_attr($socialIconBgHColor); ?>;
}

/*Settings*/
.team-section-<?php echo esc_attr($PostId); ?>{
    font-family: <?php echo esc_attr($fontFamily); ?>;
}

<?php echo esc_attr($customCSS); ?>
</style>