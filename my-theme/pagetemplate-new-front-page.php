<?php
/*
Template Name: New front
*/
get_header();
?>

<?php
if (have_posts()):

    while (have_posts()):

        the_post();

        the_title();

        the_content();

    endwhile;

endif;


get_footer();


