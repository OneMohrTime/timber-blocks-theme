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
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'load_editor_assets' ) );
        add_action( 'init', array( $this, 'disable_comments' ) );
        add_action( 'admin_menu', array( $this, 'remove_comment_menu' ) );
        add_action( 'admin_init', array( $this, 'redirect_comment_pages' ) );
        add_action( 'wp_before_admin_bar_render', array( $this, 'remove_comment_admin_bar_link' ) );

        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_filter( 'timber/twig/environment/options', [ $this, 'update_twig_environment_options' ] );
        add_filter( 'allowed_block_types_all', array( $this, 'restrict_blocks' ), 10, 2 );
        add_filter( 'comments_open', '__return_false', 20, 2 );
        add_filter( 'pings_open', '__return_false', 20, 2 );
        add_filter( 'comment_status_links', '__return_empty_array' );
        add_filter( 'comments_array', '__return_empty_array', 10, 2 );

        parent::__construct();
    }

    /**
     * Restrict the editor to a set of approved section and content blocks.
     *
     * @param bool|array $allowed
     * @param WP_Block_Editor_Context $editor_context
     * @return array
     */
    public function restrict_blocks( $allowed, $editor_context ) {
        return [
            'theme/hero',
            'theme/split-content',
            'theme/feature-grid',
            'theme/cta-banner',
            // 'core/heading',
            // 'core/paragraph',
            // 'core/button',
            // 'core/image',
            // 'core/list',
            // 'core/quote',
        ];
    }

    // /**
    //  * This is where you can register custom post types.
    //  */
    // public function register_post_types() {
    // }

    // /**
    //  * This is where you can register custom taxonomies.
    //  */
    // public function register_taxonomies() {
    // }

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
                'timber-blocks-theme-main', get_template_directory_uri() . '/assets/css/app.css', [], filemtime( $theme_css ), 'all'
            );
        }

        if ( file_exists( $theme_js ) ) {
            wp_enqueue_script(
                'timber-blocks-theme-main', get_template_directory_uri() . '/assets/js/app.js', [], filemtime( $theme_js ), true
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
                'timber-blocks-theme-editor', get_template_directory_uri() . '/assets/css/app.css', [], filemtime( $editor_css )
            );
        }
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
     * Remove all commenting and discussion functionality from the theme.
     */
    public function disable_comments() {
        remove_post_type_support( 'post', 'comments' );
        remove_post_type_support( 'page', 'comments' );
        remove_post_type_support( 'post', 'trackbacks' );
        remove_post_type_support( 'page', 'trackbacks' );
    }

    public function remove_comment_menu() {
        remove_menu_page( 'edit-comments.php' );
    }

    public function redirect_comment_pages() {
        global $pagenow;

        if ( $pagenow === 'edit-comments.php' ) {
            wp_safe_redirect( admin_url() );
            exit;
        }
    }

    public function remove_comment_admin_bar_link() {
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu( 'comments' );
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
