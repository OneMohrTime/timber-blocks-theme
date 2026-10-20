<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( class_exists( 'Timber\Timber' ) ) {
    Timber\Timber::init();
    Timber\Timber::$dirname = [ 'templates', 'views' ];
}

function timber_blocks_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/app.css' );

    register_nav_menus([
        'primary' => 'Primary Navigation',
        'footer' => 'Footer Navigation',
    ]);
}
add_action( 'after_setup_theme', 'timber_blocks_theme_setup' );

function timber_blocks_theme_enqueue_assets() {
    $css_file = get_template_directory() . '/assets/css/app.css';
    $js_file  = get_template_directory() . '/assets/js/app.js';

    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'timber-blocks-theme-main',
            get_template_directory_uri() . '/assets/css/app.css',
            [],
            filemtime( $css_file )
        );
    }

    if ( file_exists( $js_file ) ) {
        wp_enqueue_script(
            'timber-blocks-theme-main',
            get_template_directory_uri() . '/assets/js/app.js',
            [],
            filemtime( $js_file ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'timber_blocks_theme_enqueue_assets' );

function timber_blocks_register_theme_blocks() {
    $block_dirs = [
        get_template_directory() . '/blocks/hero',
        get_template_directory() . '/blocks/split-content',
        get_template_directory() . '/blocks/feature-grid',
        get_template_directory() . '/blocks/feature-item',
        get_template_directory() . '/blocks/cta-banner',
    ];

    foreach ( $block_dirs as $dir ) {
        $block_json = $dir . '/block.json';

        if ( file_exists( $block_json ) ) {
            register_block_type( $dir );
        }
    }
}
add_action( 'init', 'timber_blocks_register_theme_blocks' );
