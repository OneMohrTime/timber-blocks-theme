<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Timber\Timber' ) ) {
    echo '<section class="c-block"><div class="c-block__inner"><h2>' . esc_html( $attributes['title'] ?? 'Split content' ) . '</h2></div></section>';
    return;
}

$context = [
    'eyebrow' => $attributes['eyebrow'] ?? 'Why us',
    'title' => $attributes['title'] ?? 'Split content',
    'text' => $attributes['text'] ?? '',
    'media' => ! empty( $attributes['mediaId'] ) ? wp_get_attachment_image( (int) $attributes['mediaId'], 'large' ) : '',
    'alignment' => $attributes['alignment'] ?? 'left',
];

Timber\Timber::render( trailingslashit( get_template_directory() ) . 'blocks/split-content/views/split-content.twig', $context );
