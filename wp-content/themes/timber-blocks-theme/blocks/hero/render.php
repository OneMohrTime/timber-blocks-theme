<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Timber\Timber' ) ) {
    echo '<section class="c-block c-hero"><div class="c-block__inner"><h2>' . esc_html( $attributes['title'] ?? 'Hero' ) . '</h2></div></section>';
    return;
}

$context = [
    'eyebrow' => $attributes['eyebrow'] ?? 'Featured',
    'title' => $attributes['title'] ?? 'Hero',
    'text' => $attributes['text'] ?? '',
    'buttonLabel' => $attributes['buttonLabel'] ?? 'Learn More',
    'buttonUrl' => $attributes['buttonUrl'] ?? '#',
    'media' => ! empty( $attributes['mediaId'] ) ? wp_get_attachment_image( (int) $attributes['mediaId'], 'large' ) : '',
];

Timber\Timber::render( trailingslashit( get_template_directory() ) . 'blocks/hero/views/hero.twig', $context );
