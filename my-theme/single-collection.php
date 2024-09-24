<?php

if (is_user_logged_in()) {

    $user = wp_get_current_user();

    $roles = (array) $user->roles;

    if (in_array("administrator", $roles)) {

    }

}

if (current_user_can("administrator")) {

}
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
        WC()->cart->add_to_cart($product_id, 1, 0, [], ["collection_id" => get_the_ID()]);


    }
    var_dump($_POST);

}
function display_cart_item_data()
{
    // Get all cart items
    $cart_items = WC()->cart->get_cart();

    // Loop through the cart items
    foreach ($cart_items as $cart_item_key => $cart_item) {
        // Get the product ID
        $product_id = $cart_item['product_id'];

        // Get the quantity
        $quantity = $cart_item['quantity'];

        // Get the cart item data
        $cart_item_data = $cart_item['data'];

        // Custom cart item data you added
        $custom_data = isset($cart_item['collection_id']) ? $cart_item['collection_id'] : '';

        // Display the product ID, quantity, and custom data
        echo 'Product ID: ' . esc_html($product_id) . '<br>';
        echo 'Quantity: ' . esc_html($quantity) . '<br>';
        echo 'Collection ID: ' . esc_html($custom_data) . '<br>';
        echo '<hr>';
    }
}
display_cart_item_data();
get_footer();
