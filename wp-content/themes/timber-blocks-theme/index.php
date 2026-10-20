<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( class_exists( 'Timber\Timber' ) ) {
    $context = Timber\Timber::context();
    $context['posts'] = Timber\Timber::get_posts();

    Timber\Timber::render( 'index.twig', $context );
    return;
}

get_header();
?>
<main class="l-main">
    <div class="l-wrap">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    <div><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer();
