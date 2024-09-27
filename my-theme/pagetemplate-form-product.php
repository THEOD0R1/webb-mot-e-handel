<?php
/*
 * Template Name:   Create car collection
 */

get_header();
?>

<?php
if (have_posts()):
    while (have_posts()):
        the_post();

    endwhile;

endif;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["post_select_product"]) && isset($_POST["product_id"])) {

        if (!is_user_logged_in() && isset($_POST["post_form_email"]) && isset($_POST["post_form_name"])) {
            $email = $_POST["post_form_email"];
            $name = $_POST["post_form_name"];

            $new_User_ID = register_new_user($name, $email);


            if (!is_wp_error($new_User_ID)) {

                do_action("mp_valid_nonce", "save_new_collection", $new_User_ID);

                echo "user created";

            } else {

                echo $new_User_ID->get_error_message();

            }

        } else {

            do_action("mp_valid_nonce", "save_new_collection", get_current_user_id());
        }
    }
}

?>
<?php do_action("form_on_page_template") ?>

<?php get_footer();


