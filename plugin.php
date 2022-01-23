<?php
/**
Plugin Name: Search Widget Post Types for Elementor
Description: Adds an option to make Elementor's search widget only search for a specific post type such as WooCommerce products or custom post types.
Text Domain: search-widget-post-types-for-elementor
Domain Path: /lang
Version: 1.0.3
*/


add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'search-widget-post-types-for-elementor', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
} );

add_action( 'elementor/element/search-form/search_content/after_section_end', function($element, $args = '') {
    $element->start_controls_section(
        'swptfe',
        [
            'label' => __( 'Search Options', 'search-widget-post-types-for-elementor' )
        ]
    );

    $public_post_types = get_post_types( [
        'public' => true,
    ], 'objects' );

    $mapped_post_types = [
        'default' => __( 'All Post Types', 'search-widget-post-types-for-elementor' )
    ];

    foreach ( $public_post_types as $post_type ) {
        $mapped_post_types[$post_type->name] = $post_type->label;
    }

    $element->add_control(
        'swptfe_post_type',
        [
        'type' => \Elementor\Controls_Manager::SELECT,
        'label' => __( 'Post Type', 'search-widget-post-types-for-elementor' ),
        'options' => $mapped_post_types,
        'default' => 'default',
        ]
    );

    $element->end_controls_section();
});

add_action( 'elementor_pro/search_form/after_input', function ( $element ) {
    $settings = $element->get_settings();

    if ( 'default' === $settings['swptfe_post_type'] ) {
        return;
    }

    echo '<input type="hidden" name="post_type" value="' . esc_attr( $settings['swptfe_post_type'] ) . '" />';
}, 10, 1 );
