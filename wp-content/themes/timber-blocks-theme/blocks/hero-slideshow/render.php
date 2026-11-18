<?php
/**
 * The template for displaying the Hero Slideshow block
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

// Variables
$context = Timber::context();
$slides = get_field('swiper_slides');
$normalized_slides = [];

// Block data
if (!is_array($slides)) {
    $slides = [];
}
foreach ($slides as $slide) {
    if (!is_array($slide)) {
        continue;
    }
    $image = $slide['image'] ?? null;
    $image_id = is_array($image) ? absint($image['ID'] ?? 0) : absint($image);
    $image_src = $image_id ? wp_get_attachment_image_src($image_id, 'large') : false;
    $image_srcset = $image_id ? wp_get_attachment_image_srcset($image_id, 'medium') : false;
    $image_mime_type = $image_id ? get_post_mime_type($image_id) : false;
    $slide['image'] = $image_src ? [
        'src' => $image_src[0],
        'width' => $image_src[1],
        'height' => $image_src[2],
        'srcset' => $image_srcset ?: '',
        'sizes' => wp_get_attachment_image_sizes($image_id, 'medium') ?: '',
        'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
        'webp' => $image_mime_type === 'image/webp' ? ($image_srcset ?: $image_src[0]) : null,
    ] : null;
    $normalized_slides[] = $slide;
}
$slides = $normalized_slides;

// Block fields
$context['ID'] = $post_id;
$context['block'] = $block;
$context['content'] = $content;
$context['slides'] = $slides;
$context['aspectRatio'] = get_field('aspect_ratio');
$context['textAlign'] = get_field('text_alignment');

// Render block
Timber::render(get_template_directory() . '/blocks/hero-slideshow/template.twig', $context);
