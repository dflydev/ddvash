
        </div><!-- #content -->
        </div><!-- #content-container -->

        <?php if ( get_option('ddvash_layout_sidebar_location') != 'none' ): ?>
        <div id="sidebar-container">
        <div id="sidebar">
            <?php get_sidebar(); ?>
        </div><!-- #sidebar -->
        </div><!-- #sidebar-container -->
        <?php endif; ?>

    </div><!-- .content-section -->
    </div><!-- .content-section-container -->

    <div id="footer-container" role="contentinfo">
    <div id="footer">
        <div id="colophon">
            <div id="site-info">
                <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                    <?php bloginfo( 'name' ); ?>
                </a>
                <br />
                    
                    Copyright 2010 - <a href="http://dflydev.com/">Dragonfly Development, Inc.</a> - All rights Reserved
            </div><!-- #site-info -->
        </div><!-- #colophon -->
    </div><!-- #footer -->
    </div><!-- #footer-container -->
    
</div><!-- #shell -->
</div><!-- #shell-container -->

<?php wp_footer(); ?>
</body>
</html>