<?php

if (is_user_logged_in()) {

    $user = wp_get_current_user();

    $roles = (array) $user->roles;

    if (in_array("administrator", $roles)) {

    }

}

if (current_user_can("administrator")) {

}
$message = "";
get_header();

?>

<div>
    <h2>
        <a href="<?php echo home_url("/complete-collections"); ?>">Complete collections</a>
    </h2>
</div>

<?php

?>
<?php
if (have_posts()):

    while (have_posts()):
        the_post();

        $products_ids = get_post_meta(get_the_ID(), "secretMessage", true);
        ?>
        <section class="collection_title_container">
            <h3>
                <?= the_title(); ?>
            </h3>
            <p><?php echo $message ?></p>
            <form method="POST" class="add_cart_icon_form">
                <label for="mp_add_cart_icon" class="add_cart_icon">
                    <?php do_action("mp_add_cart_icon"); ?>
                </label>
                <input type="submit" id="mp_add_cart_icon" class="add_cart_icon_button" />
            </form>

        </section>

        <?php
        ?>
        <div class="cars_collection_container">
            <?php
            if (is_array($products_ids)) {
                foreach ($products_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    $image_id = $product->get_image_id();
                    $image_url = wp_get_attachment_image_url($image_id, 'full');


                    ?>
                    <div class="collection__product__container">
                        <a class="collection__product__container__name" href="<?php echo get_permalink($product->get_id()) ?>">
                            <h4 class="car_title">
                                <?php echo $product->get_name() ?>
                            </h4>
                        </a>
                        <div class="car_collection_image_container">
                            <img class="car_collection_image" src="<?= $image_url ?>" alt="<?= $product->get_name() ?> image ">
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        the_content();
    endwhile;

endif;
?>


<?php


$taxonomies = [
    "attribute" => ["label" => "Attribute"],
    "occasion" => ["label" => "This collection match good with:"]
];

foreach ($taxonomies as $taxonomy => $data) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);

    if ($terms) {
        foreach ($terms as $term) {
            echo "<a href=" . get_term_link($term) . ">" . $term->name . "</a>";
        }
    }
}
?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $products_ids = get_post_meta(get_the_ID(), "secretMessage", true);

    foreach ($products_ids as $product_id) {
        WC()->cart->add_to_cart($product_id, 1, 0, [], ["collection" => get_the_ID()]);
    }
    $message = "Collection added";
}
get_footer();
