<?php
/**
 * The template for displaying the Hero block
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

// Variables
$image = get_field( 'image' );

// Block data
$context['ID'] = $post_id;
$context['block'] = $block;
$context['content'] = $content;
// $context['attributes'] = $attributes;
$context['fields'] = get_fields();

// Block fields
$context['layout'] = get_field('layout');
$context['eyebrow'] = get_field('eyebrow');
$context['headline'] = get_field('headline');
$context['text'] = get_field('text');

// Render block
Timber::render( get_template_directory() . '/blocks/hero/views/hero.twig', $context );
