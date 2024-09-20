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
    <a href="<?php echo home_url("/complete-collections"); ?>">Complete collections</a>
</div>

<?php

?>
<?php
if (have_posts()):

    while (have_posts()):
        the_post();
        the_title();
        the_content();
    endwhile;

endif;

$products_ids = get_post_meta(get_the_ID(), "secretMessage", true);

if (is_array($products_ids)) {
    foreach ($products_ids as $product_id) {
        $product = wc_get_product($product_id);


        ?>
        <div>
            <a href="<?php echo get_permalink($product->get_id()) ?>">
                <?php echo $product->get_name() ?>
            </a>
        </div>
        <?php
    }
}
?>
<form method="POST">
    <input type="submit" value="Add to cart">
</form>

<?php

// $taxonomies = get_object_taxonomies("collection");

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
        var_dump($product_id);
        WC()->cart->add_to_cart($product_id, 1, 0, [], ["collection" => get_the_ID()]);
    }
}
get_footer();
