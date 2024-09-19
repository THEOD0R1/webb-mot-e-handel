<?php

get_header();


// var_dump(get_queried_object());

if (is_tax() || is_category()) {
	$term = get_queried_object();

	echo '<h1>' . esc_html($term->name) . '</h1>';
}
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
