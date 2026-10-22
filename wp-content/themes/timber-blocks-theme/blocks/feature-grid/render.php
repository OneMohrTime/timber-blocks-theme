<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Timber\Timber' ) ) {
    echo '<section class="c-block"><div class="c-block__inner"><h2>' . esc_html( $attributes['title'] ?? 'Features' ) . '</h2></div></section>';
    return;
}

$context = [
    'eyebrow' => $attributes['eyebrow'] ?? 'Features',
    'title' => $attributes['title'] ?? 'Features',
    'text' => $attributes['text'] ?? '',
];

Timber\Timber::render( trailingslashit( get_template_directory() ) . 'blocks/feature-grid/views/feature-grid.twig', $context );
