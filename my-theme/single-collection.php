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



if ($_SERVER["REQUEST_METHOD"] == "POST" && false) { //&& false can be used in testing

    $action = $_POST["action"];
    $id = $_POST["id"];
    $date = $_POST["date"];
    $nonce = $_POST["nonce"];

    $valid_nonce = wp_create_nonce($action . "|" . $id . "|" . $date);

    if ($valid_nonce === $nonce) {

        $stale_nonce = $_POST["stale_nonce"];
        $saved_stale_nonce = wp_create_nonce(get_the_content());

        $current_date = date("Y-m-d h:i:s");
        $current_time = time();


        $plus_in_10_minutes = $current_time - (10 * 60);
        $plus10 = date("Y-m-d h:i:s", $plus_in_10_minutes);

        var_dump($current_time, $plus10, $date);

        if ($plus10 < $date) {

            echo "Correct";

            if ($action == "edit") {
                wp_update_post([
                    "ID" => $id,
                    "post_content" => $_POST["description"]
                ]);
            }

            if ($action == "delete") {

            }

        } else {

            echo "Too late";
        }
    }

}

$action = "edit";
$post_id = 1;
$date = date("Y-m-d h:i:s");
$nonce = wp_create_nonce($action . "|" . $post_id . "|" . $date);
$wp_rest_nonce = wp_create_nonce("wp_rest");
$stale_nonce = wp_create_nonce(get_the_content());
?>

<form method="POST">
    <input type="hidden" name="action" value="<?php echo $action ?>">
    <input type="hidden" name="id" value="<?php echo $post_id ?>">
    <input type="hidden" name="date" value="<?php echo $date ?>">
    <input type="hidden" name="nonce" value="<?php echo $nonce ?>">
    <input type="hidden" name="stale_nonce" value="<?php echo $stale_nonce ?>">

    <textarea name="description">

    </textarea>
    <input type="submit">
</form>

<?php

get_footer();
