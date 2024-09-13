<?php


get_header();

// wp_kses() eller esc_attr()
?>

<?php
if (have_posts()):

	while (have_posts()):
		the_post();
		the_title();
		the_content();


	endwhile;

endif;
?>

<?php
get_footer();
