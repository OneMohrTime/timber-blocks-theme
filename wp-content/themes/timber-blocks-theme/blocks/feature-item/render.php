<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Timber\Timber' ) ) {
    echo '<li class="c-feature-item"><h3>' . esc_html( $attributes['title'] ?? 'Feature title' ) . '</h3><p>' . esc_html( $attributes['text'] ?? '' ) . '</p></li>';
    return;
}

$context = [
    'title' => $attributes['title'] ?? 'Feature title',
    'text' => $attributes['text'] ?? '',
];

Timber\Timber::render( trailingslashit( get_template_directory() ) . 'blocks/feature-item/views/feature-item.twig', $context );
