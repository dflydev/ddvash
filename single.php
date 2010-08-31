<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

<div id="nav-above" class="navigation">
    <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ddvash' ) . '</span> %title' ); ?></div>
    <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ddvash' ) . '</span>' ); ?></div>
</div><!-- #nav-above -->

<?php get_template_part( 'post', 'loop' ); ?>

<div id="nav-below" class="navigation">
    <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ddvash' ) . '</span> %title' ); ?></div>
    <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ddvash' ) . '</span>' ); ?></div>
</div><!-- #nav-below -->

<?php comments_template( '', true ); ?>

<?php endwhile; ?>
<?php get_footer(); ?>