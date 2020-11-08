<?php
/**
Plugin Name: Elementor Search Widget Post Types
Description: Adds an option to make Elementor's search widget only search for a specific post type such as WooCommerce products or custom post types.
Text Domain: elementor-search-widget-post-types
Domain Path: /lang
Version: 1.0.0
*/


add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'elementor-search-widget-post-types', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
} );

add_action( 'elementor/element/search-form/search_content/after_section_end', function($element, $args = '') {
    $element->start_controls_section(
        'search-extended',
        [
            'label' => __( 'Search Options', 'elementor-search-widget-post-types' )
        ]
    );

    $public_post_types = get_post_types( [
        'public' => true,
    ], 'objects' );

    $mapped_post_types = [
        'default' => __( 'All Post Types', 'elementor-search-widget-post-types' )
    ];

    foreach ( $public_post_types as $post_type ) {
        $mapped_post_types[$post_type->name] = $post_type->label;
    }

    $element->add_control(
        'search_extended_post_type',
        [
        'type' => \Elementor\Controls_Manager::SELECT,
        'label' => __( 'Post Type', 'elementor-search-widget-post-types' ),
        'options' => $mapped_post_types,
        'default' => 'default',
        ]
    );

    $element->end_controls_section();
});

add_action( 'elementor_pro/search_form/after_input', function ( $element ) {
    $settings = $element->get_settings();

    if ( 'default' === $settings['search_extended_post_type'] ) {
        return;
    }

    echo '<input type="hidden" name="post_type" value="' . esc_attr( $settings['search_extended_post_type'] ) . '" />';
}, 10, 1 );