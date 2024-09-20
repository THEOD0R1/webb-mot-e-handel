<?php
/*
 * Template Name:   Create car collection
 */

get_header();
?>

<?php

echo __("This is a form page:", "mt"); //loco translate test
if (have_posts()):
    while (have_posts()):

        the_post();

    endwhile;

endif;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["test_discount_code"])) {
        do_action("get_coupon_after_purchase");
    }

    if (isset($_POST["post_select_product"]) && isset($_POST["product_id"])) {

        do_action("save_new_collection");

        do_action("mp_valid_nonce", );


        if (!is_user_logged_in() && isset($_POST["post_form_email"]) && isset($_POST["post_form_name"])) {
            $email = $_POST["post_form_email"];
            $name = $_POST["post_form_name"];
            register_new_user($name, $email);
            echo "user created in";
        }

    }

}

?>
<?php do_action("form_on_page_template") ?>


<form method="post">
    <input type="submit" name="test_discount_code" value="test code">
</form>

<?php get_footer();


