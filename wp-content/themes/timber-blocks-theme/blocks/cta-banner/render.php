<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Timber\Timber' ) ) {
    echo '<section class="c-block"><div class="c-block__inner c-cta-banner"><h2>' . esc_html( $attributes['title'] ?? 'Ready to start?' ) . '</h2><p>' . esc_html( $attributes['text'] ?? '' ) . '</p></div></section>';
    return;
}

$context = [
    'title' => $attributes['title'] ?? 'Ready to start?',
    'text' => $attributes['text'] ?? '',
    'buttonLabel' => $attributes['buttonLabel'] ?? 'Book a call',
    'buttonUrl' => $attributes['buttonUrl'] ?? '#',
];

Timber\Timber::render( trailingslashit( get_template_directory() ) . 'blocks/cta-banner/views/cta-banner.twig', $context );
