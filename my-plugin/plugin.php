<?php
/**
 * Plugin Name: test-plugin
 **/

function mp_test($messageCount)
{
    ?>
    <div><?= $messageCount ?></div>
    <?php
}


add_action("before_form_on_page_template", "mp_test", 20);
// add_action("before_form_on_page_template", "\MYClass::test"); vg


add_action("before_form_on_page_template", function () {
    ?>anonym
    <?php
}, 10);

// add_action("before_form_on_page_template", [$object, "test"]);

// function mt_init()
// {
//     remove_action("form_on_page_template", "mt_output_form", 10);
// }

// add_action("init", "mt_init"); //körs när alla plugin och temman är redo 




function mp_reverse($messages, $post_id)
{
    var_dump($messages);
    var_dump($post_id);

    return array_reverse($messages);

}

add_filter("secret_messages", "mp_secret_messages", 10, 2);

add_filter("the_content", function ($content) {

    if (is_user_logged_in()) {
        return $content;
    }

    return null;
});


function create_occasion_taxonomy()
{
    register_taxonomy(
        "occasion",
        "collection",
        array(
            "label" => "Occasions",
            "show_ui" => true,
            "show_admin_column" => true,
            "rewrite" => ["slug" => "occasion"],
            "hierarchical" => true,
            "show_in_rest" => true
        )
    );



    register_taxonomy(
        "attribute",
        "collection",
        array(
            "label" => "Attribute",
            "show_ui" => true,
            "show_admin_column" => true,
            "rewrite" => ["slug" => "attribute"],
            "hierarchical" => false,
            "show_in_rest" => true
        )
    );

}
add_action("init", "create_occasion_taxonomy");

function mp_get_latest_collection()
{
    $args = [
        "post_type" => "collection",
        "orderby" => "date",
        "order" => "DESC",
        "posts_per_page" => 3
    ];

    $args = apply_filters("mp_get_latest_collection_query_args", $args);

    $query = new WP_Query($args);

    return $query;
}

add_filter("mp_get_latest_collection", "mp_get_latest_collection");


function mp_latest_collection($atts)
{
    $return_html = "<h2>Senaste kollektioner</h2>";


    add_filter("mp_get_latest_collection_query_args", function ($args) {
        $args["posts_per_page"] = 4;

        return $args;
    });


    $query = apply_filters("mp_get_latest_collection", null);

    if ($query) {

        ob_start();

        while ($query->have_posts()) {
            $query->the_post();

            echo esc_html(the_title());
        }
    }

    $return_html .= ob_get_clean();

    wp_reset_postdata();

    return $return_html;

}

add_shortcode("latestCollections", "mp_latest_collection");

add_filter("wp_is_application_passwords_available", "__return_true"); //lägga till test användare

function mp_nonce($accept_action)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $action = $_POST["action"];
        // $action = "jfkl";
        $id = $_POST["id"];
        $date = $_POST["date"];
        $nonce = $_POST["nonce"];

        $valid_nonce = wp_create_nonce($action . "|" . $id . "|" . $date);

        if ($valid_nonce === $nonce) {

            // $stale_nonce = $_POST["stale_nonce"];
            // $saved_stale_nonce = wp_create_nonce(get_the_content());

            // $current_date = date("Y-m-d h:i:s");
            $current_time = time();


            $plus_in_10_minutes = $current_time - (10 * 60);
            $plus10 = date("Y-m-d h:i:s", $plus_in_10_minutes);

            // var_dump($current_time, $plus10, $date);

            if ($plus10 < $date) {

                $accept_action;

            } else {

                echo "Too late";
            }
        } else {
            echo "Wrong nonce"; // only in test
        }

    }



}

add_action("mp_valid_nonce", "mp_nonce");


function mp_nonce_form()
{

    $action = "new_collection_save";
    $post_id = get_the_ID();
    $date = date("Y-m-d h:i:s");
    $nonce = wp_create_nonce($action . "|" . $post_id . "|" . $date);
    $stale_nonce = wp_create_nonce(get_the_content());
    ?>

    <input type="hidden" name="action" value="<?php echo $action ?>">
    <input type="hidden" name="id" value="<?php echo $post_id ?>">
    <input type="hidden" name="date" value="<?php echo $date ?>">
    <input type="hidden" name="nonce" value="<?php echo $nonce ?>">
    <input type="hidden" name="stale_nonce" value="<?php echo $stale_nonce ?>">

    <?php

}
add_action("mp_nonce_form", "mp_nonce_form");
