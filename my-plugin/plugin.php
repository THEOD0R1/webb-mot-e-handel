<?php
/**
 * Plugin Name: test-plugin
 **/

// add_filter("the_content", function ($content) {

//     if (is_user_logged_in()) {
//         return $content;
//     }

//     return null;
// });


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
    $return_html = "<h2>Latest Collection</h2>";


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

function mp_nonce($hookName, ...$arg)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $action = $_POST["action"];
        // $action = "jfkl";
        $id = $_POST["id"];
        $date = $_POST["date"];
        $nonce = $_POST["nonce"];

        $valid_nonce = wp_create_nonce($action . "|" . $id . "|" . $date);

        if ($valid_nonce === $nonce) {

            $current_time = time();

            $plus_in_10_minutes = $current_time - (10 * 60);

            $plus10 = date("Y-m-d h:i:s", $plus_in_10_minutes);

            if ($plus10 < $date) {


                $arg ? do_action($hookName, $arg) : do_action($hookName);

                echo "success";

            } else {

                echo "Too late";
            }
        } else {
            echo "Wrong nonce"; // only in test
        }

    }

}

add_action("mp_valid_nonce", "mp_nonce", 10, 10);



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


function mp_add_cart_icon()
{
    ?> <svg fill="#3333ff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 495.525 495.525" xml:space="preserve" stroke="#3333ff">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
            <g>
                <g>
                    <path
                        d="M281.799,288.831h-44.908l-3.347,39.076h67.941C292.111,317.489,285.549,301.858,281.799,288.831z">
                    </path>
                    <path
                        d="M311.873,177.148c16.598-16.598,37.352-27.144,59.914-31.447c7.113-7.123,14.229-15.785,21.344-20.995h34.113 c26.053,0,26.053-39.077,0-39.077h-41.48c-4.287,0-7.826,0.61-10.656,2.485c-1.658,0.899-3.281,1.816-4.83,3.363 c-17.646,17.668-35.291,35.43-52.938,53.098c-1.936,1.936-3.305,3.577-4.24,6.182h-64.853l-3.637,44.287h52.853 C301.609,187.23,306.402,182.62,311.873,177.148z">
                    </path>
                    <polygon points="130.073,275.806 224.897,275.806 230.47,208.072 124.503,208.072 "></polygon>
                    <polygon points="119.793,150.759 123.43,195.046 231.543,195.046 235.18,150.759 "></polygon>
                    <path d="M289.967,208.072h-46.43l-5.573,67.734h41.208C275.952,252.36,279.567,228.913,289.967,208.072z">
                    </path>
                    <polygon points="111.435,208.072 14.964,208.072 32.661,275.806 117.006,275.806 "></polygon>
                    <polygon points="106.725,150.759 0,150.759 11.552,195.046 110.359,195.046 "></polygon>
                    <polygon points="134.493,327.909 220.477,327.909 223.824,288.831 131.146,288.831 "></polygon>
                    <polygon points="36.074,288.831 46.708,327.909 121.426,327.909 118.078,288.831 "></polygon>
                    <circle cx="86.171" cy="375.458" r="34.438"></circle>
                    <circle cx="268.799" cy="375.458" r="34.438"></circle>
                    <path
                        d="M393.734,156.934c-56.129,0-101.793,45.664-101.793,101.792c0,56.129,45.664,101.792,101.793,101.792 s101.791-45.663,101.791-101.792C495.525,202.598,449.863,156.934,393.734,156.934z M453.297,278.411h-41.684v39.076h-36.473 v-39.076h-20.842h-20.84v-2.605v-36.472h41.682v-39.077h36.473v39.077h41.684V278.411L453.297,278.411z">
                    </path>
                </g>
            </g>
        </g>
    </svg> <?php
}
add_action("mp_add_cart_icon", "mp_add_cart_icon");

function mp_filter_icon()
{
    ?> <svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#101010" stroke="#101010">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048">
        </g>
        <g id="SVGRepo_iconCarrier">
            <title>Filter</title>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Filter">
                    <rect id="Rectangle" fill-rule="nonzero" x="0" y="0" width="24" height="24"> </rect>
                    <line x1="4" y1="5" x2="16" y2="5" id="Path" stroke="#202020" stroke-width="2" stroke-linecap="round">
                    </line>
                    <line x1="4" y1="12" x2="10" y2="12" id="Path" stroke="#202020" stroke-width="2" stroke-linecap="round">
                    </line>
                    <line x1="14" y1="12" x2="20" y2="12" id="Path" stroke="#202020" stroke-width="2"
                        stroke-linecap="round"> </line>
                    <line x1="8" y1="19" x2="20" y2="19" id="Path" stroke="#202020" stroke-width="2" stroke-linecap="round">
                    </line>
                    <circle id="Oval" stroke="#202020" stroke-width="2" stroke-linecap="round" cx="18" cy="5" r="2">
                    </circle>
                    <circle id="Oval" stroke="#202020" stroke-width="2" stroke-linecap="round" cx="12" cy="12" r="2">
                    </circle>
                    <circle id="Oval" stroke="#202020" stroke-width="2" stroke-linecap="round" cx="6" cy="19" r="2">
                    </circle>
                </g>
            </g>
        </g>
    </svg>
    <?php
}
add_action("mp_filter_icon", "mp_filter_icon");
