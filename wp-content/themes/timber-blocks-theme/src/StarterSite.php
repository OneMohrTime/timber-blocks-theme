<?php

use Timber\Site;

/**
 * Class StarterSite
 */
class StarterSite extends Site {
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'init', array( $this, 'register_blocks' ) );
        add_filter( 'allowed_block_types_all', array( $this, 'restrict_blocks' ), 10, 2 );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'load_editor_assets' ) );

        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_filter( 'timber/twig/environment/options', [ $this, 'update_twig_environment_options' ] );

        parent::__construct();
    }

    /**
     * Limit the editor to large section blocks for a drag-and-drop experience.
     *
     * @param bool|array $allowed
     * @param WP_Block_Editor_Context $editor_context
     * @return array
     */
    public function restrict_blocks( $allowed, $editor_context ) {
        $allowed_blocks = [
            'theme/hero',
            'theme/split-content',
            'theme/feature-grid',
            'theme/cta-banner',
            'core/heading',
            'core/paragraph',
            'core/button',
            'core/image',
            'core/list',
            'core/quote',
        ];

        return $allowed_blocks;
    }

    /**
     * This is where you can register your custom block types.
     */
    public function register_blocks() {
        $block_dirs = [
            get_template_directory() . '/blocks/hero',
            get_template_directory() . '/blocks/split-content',
            get_template_directory() . '/blocks/feature-grid',
            get_template_directory() . '/blocks/feature-item',
            get_template_directory() . '/blocks/cta-banner',
        ];

        foreach ( $block_dirs as $dir ) {
            if ( file_exists( $dir . '/block.json' ) ) {
                register_block_type( $dir );
            }
        }
    }

    /**
     * This is where you load the frontend CSS & JS files.
     */
    public function load_scripts() {
        $theme_css = get_template_directory() . '/assets/css/app.css';
        $theme_js  = get_template_directory() . '/assets/js/app.js';

        if ( file_exists( $theme_css ) ) {
            wp_enqueue_style(
                'timber-blocks-theme-main',
                get_template_directory_uri() . '/assets/css/app.css',
                array(),
                filemtime( $theme_css ),
                'all'
            );
        }

        if ( file_exists( $theme_js ) ) {
            wp_enqueue_script(
                'timber-blocks-theme-main',
                get_template_directory_uri() . '/assets/js/app.js',
                array(),
                filemtime( $theme_js ),
                true
            );
        }
    }

    /**
     * Load editor assets so block previews, global editor styles, and block-specific improvements work.
     */
    public function load_editor_assets() {
        $editor_css = get_template_directory() . '/assets/css/app.css';

        if ( file_exists( $editor_css ) ) {
            wp_enqueue_style(
                'timber-blocks-theme-editor',
                get_template_directory_uri() . '/assets/css/app.css',
                array(),
                filemtime( $editor_css )
            );
        }
    }

    /**
     * This is where you can register custom post types.
     */
    public function register_post_types() {

    }

    /**
     * This is where you can register custom taxonomies.
     */
    public function register_taxonomies() {

    }

    /**
     * This is where you add some context.
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context( $context ) {
        $context['foo']   = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        $context['menu']  = Timber::get_menu();
        $context['site']  = $this;

        return $context;
    }

    public function theme_supports() {
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
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

        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );
    }

    /**
     * This would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo( $text ) {
        $text .= ' bar!';
        return $text;
    }

    /**
     * This is where you can add your own functions to twig.
     *
     * @param Twig\Environment $twig get extension.
     */
    public function add_to_twig( $twig ) {
        $twig->addFilter( new Twig\TwigFilter( 'myfoo', [ $this, 'myfoo' ] ) );

        return $twig;
    }

    /**
     * Updates Twig environment options.
     *
     * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
     *
     * \@param array $options An array of environment options.
     *
     * @return array
     */
    function update_twig_environment_options( $options ) {
        // $options['autoescape'] = true;

        return $options;
    }
}
