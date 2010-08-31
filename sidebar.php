<div id="primary-sidebar" class="widget-area" role="complementary">
<ul><?php dynamic_sidebar( 'primary-widget-area' ); ?></ul>
</div><!-- #primary-sidebar .widget-area -->

<?php if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>
<div id="secondary-sidebar" class="widget-area" role="complementary">
<ul><?php dynamic_sidebar( 'secondary-widget-area' ); ?></ul>
</div><!-- #secondary-sidebar .widget-area -->
<?php endif; ?>