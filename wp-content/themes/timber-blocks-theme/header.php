<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="l-header">
    <div class="l-wrap l-header__inner">
        <a class="l-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php bloginfo( 'name' ); ?>
        </a>

        <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav class="l-header__nav" aria-label="Primary navigation">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'l-header__menu',
                ]);
                ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
