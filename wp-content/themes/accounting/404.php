<?php get_header(); ?>
<section class="container" id="site-content">
<?php
	if(anps_get_option($anps_page_data, 'error_page') != '' && anps_get_option($anps_page_data, 'error_page') != 0) {
		query_posts('post_type=page&p=' . anps_get_option($anps_page_data, 'error_page'));

        while(have_posts()) { the_post();
            the_content();
        }

        wp_reset_query();
	} else {
		?>
		<div class="error-page-content">
			<h1 class="error-page-title"><?php _e('It seems that something went wrong!', 'accounting'); ?></h1>
			<h2 class="error-page-subtitle"><?php _e('This page does not exist.', 'accounting'); ?></h2>
		</div>
		<?php
	}
?>
</section>
<?php get_footer(); ?>
