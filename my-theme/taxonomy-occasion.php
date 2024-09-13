<?php

get_header(); ?>

<?php
if (is_tax()) {
    $term = get_queried_object();

    echo "<h1>" . esc_html($term->name) . "</h1>";

}

if (have_posts()):

    while (have_posts()):
        the_post();
        the_title();

        ?>
        <a href="<?php echo get_permalink($post) ?>"> <?php echo $post->post_name ?></a>
        <?php
        ?>

        <?php
    endwhile;

endif;
?>

<?php
get_footer();