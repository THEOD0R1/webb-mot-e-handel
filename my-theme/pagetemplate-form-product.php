<?php
/*
 * Template Name:   product-form
 */

get_header();

if (have_posts()):
    while (have_posts()):

        the_post();

    endwhile;

endif;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["post_select_product"]) && isset($_POST["product_id"])) {

        var_dump($_POST["product_id"]);

        do_action("save_new_collection");
    }

}

?>
<?php do_action("form_on_page_template") ?>

<?php get_footer();


