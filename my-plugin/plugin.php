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

            echo the_title();
        }
    }

    $return_html .= ob_get_clean();

    wp_reset_postdata();

    return $return_html;

}

add_shortcode("latestCollections", "mp_latest_collection");

add_filter("wp_is_application_passwords_available", "__return_true"); //lägga till test användare

