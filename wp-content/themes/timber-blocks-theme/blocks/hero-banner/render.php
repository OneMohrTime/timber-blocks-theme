<?php
/**
 * The template for displaying the Hero Banner block
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

// Variables
$image = get_field('image');
$image_id = is_array($image) ? absint($image['ID'] ?? 0) : absint($image);
$image_src = $image_id ? wp_get_attachment_image_src($image_id, 'large') : false;

// Block data
$context['ID'] = $post_id;
$context['block'] = $block;
$context['content'] = $content;
// $context['attributes'] = $attributes;
$context['fields'] = get_fields();

// Block fields
$context['layout'] = get_field('layout');
$context['blockAlign'] = get_field('block_alignment');
$context['textAlign'] = get_field('text_alignment');
$context['eyebrow'] = get_field('eyebrow');
$context['headline'] = get_field('headline');
$context['text'] = get_field('text');
$context['image'] = $image_src ? [
    'src' => $image_src[0],
    'width' => $image_src[1],
    'height' => $image_src[2],
    'srcset' => wp_get_attachment_image_srcset($image_id, 'medium') ?: '',
    'sizes' => wp_get_attachment_image_sizes($image_id, 'medium') ?: '',
    'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
] : null;
$context['video'] = get_field('video');
$context['buttons'] = get_field('buttons');

// Render block
Timber::render( get_template_directory() . '/blocks/hero-banner/template.twig', $context );
