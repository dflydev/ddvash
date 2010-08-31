<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'post', 'loop' ); ?>
<?php comments_template( '', true ); ?>
<?php endwhile; ?>
<?php get_footer(); ?>