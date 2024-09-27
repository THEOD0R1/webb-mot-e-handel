<?php
/*
Template Name: Home page front
*/
get_header();

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    do_action("mp_valid_nonce", "mp_add_product_to_cart");
}
?>
<?php
if (have_posts()):

    while (have_posts()):

        the_post();
        ?>
        <section>
            <h1 class="home_title">
                <?php the_title(); ?>
            </h1>

            <?php do_action("mp_get_products", 4) ?>

        </section>

        <?php


    endwhile;

endif;


get_footer();


