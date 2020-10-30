<?php get_header();?>
<?php if ( is_front_page() ) : ?>
	<?php echo get_template_part(TEMPLATES_PARTS, 'front-page');?>
<?php else: ?>
	<?php echo get_template_part(TEMPLATES_PARTS, 'none');?>
<?php //endwhile; ?>
<?php endif; ?>
<?php get_footer();?>