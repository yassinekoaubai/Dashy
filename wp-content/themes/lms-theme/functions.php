<?php 

function wpdocs_lms_scripts() {
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/css/fontawesome.css');
    wp_enqueue_style( 'templatemo-seo-dream', get_template_directory_uri() . '/assets/css/templatemo-seo-dream.css');
    wp_enqueue_style( 'animated', get_template_directory_uri() . '/assets/css/animated.css');
    wp_enqueue_style( 'owl', get_template_directory_uri() . '/assets/css/owl.css');

    wp_enqueue_script('jquery');

    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/vendor/bootstrap/js/bootstrap.bundle.min.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'owl', get_template_directory_uri() . '/assets/js/owl-carousel.js', array(), '1.0.0', true );
    wp_enqueue_script( 'animation', get_template_directory_uri() . '/assets/js/animation.js', array(), '1.0.0', true );
    wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.js', array(), '1.0.0', true );
    wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'wpdocs_lms_scripts' );
?>
