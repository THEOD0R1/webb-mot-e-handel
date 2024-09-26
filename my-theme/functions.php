<?php
function mt_register_collection_post_type()
{
	$args = [
		"label" => "Collection",
		"public" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"has_archive" => true,
		"show_in_rest" => true,
		"rewrite" => ["slug" => "complete-collections"],

	];

	register_post_type("collection", $args);//same name as the php file ex. single-collection.php
}
add_action("init", "mt_register_collection_post_type");


add_action("form_on_page_template", "mt_output_form", 10);

function mt_output_form()
{

	$products = wc_get_products([]);

	?>
	<form class="add_collection_form" method="POST">
		<input type="hidden" name="post_select_product" value="product_form">
		<section class="add_collection_information_container">

			<div class="input_container">
				<label for="post_form_title">Title</label>
				<input type="text" name="post_form_title" required id="post_form_title" placeholder="Title">
			</div>

			<?php
			if (!is_user_logged_in() || true) { //Use || true or && false only in testing or 
				?>
				<div class="input_container">
					<label for="post_form_email">Email</label>
					<input type="text" name="post_form_email" required id="post_form_email" placeholder="Email">
				</div>

				<div class="input_container">
					<label for="post_form_name">Name</label>
					<input type="text" name="post_form_name" required id="post_form_name" placeholder="Full name">
				</div>

				<?php
			}
			?>
		</section>

		<span class="collection__product__card__title">
			Select cars for your collection
		</span>
		<ul class="collection__product__card">
			<?php
			foreach ($products as $product) {
				$id = $product->get_id();
				$title = $product->name;
				$image = wp_get_attachment_url($product->get_image_id());
				?>
				<li>

					<label id="<?php echo $id . 'create_collection_car_label'; ?>" class="create_collection_car_label"
						for="<?php echo $id . 'create_collection_car_checkbox' ?>"
						onclick="toggleProduct('<?php echo $id . 'create_collection_car_label' ?>','<?php echo $id . 'create_collection_car_checkbox' ?>')">

						<p>
							<?php echo esc_html($title); ?>
						</p>
						<img src="<?= $image ?>" alt="<?= $title ?>" class="car_collection_image">
					</label>
					<input class="create_collection_car_checkbox" type="checkbox" name="product_id[]" value="<?php echo $id ?>"
						id="<?php echo $id . 'create_collection_car_checkbox' ?>">
				</li>
				<?php
			}
			?>
		</ul>
		<label for="post_form_content">Collection description</label>

		<textarea name="post_form_content" id="post_form_content"></textarea>

		<?php do_action("mp_nonce_form") ?>

		<input class="add_collection_submit_button" type="submit" value="Add Collection">

		<?php

		?>
	</form>
	<?php
}



function wpb_custom_new_menu()
{
	register_nav_menu('main-nav', __('Main menu'));
}
add_action('init', 'wpb_custom_new_menu');

add_action("save_new_collection", "save_collection");

function save_collection($userId)
{
	if ('POST' == $_SERVER['REQUEST_METHOD']) {
		if (!isset($_POST['post_select_product'])) {
			return;
		}
		$post = array(
			'post_title' => wp_strip_all_tags($_POST['post_form_title']),
			"post_content" => wp_strip_all_tags($_POST["post_form_content"]),
			'post_type' => 'collection',
			'post_status' => 'publish',
			"post_author" => $userId[0]
		);
		$meta_id = wp_insert_post($post);

		$products = array_map("intval", $_POST["product_id"]);

		update_post_meta(
			$meta_id,
			"secretMessage",
			$products
		);
		?>

		<a href="<?php echo get_permalink($meta_id) ?>">
			Show your collection
		</a>
		<?php


	}


}

function my_theme_enqueue_styles()
{
	wp_enqueue_style('my_text_font_style', "https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap");

	wp_enqueue_style('my_theme_style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function mt_collection_filter($query)
{
	if ($query->is_main_query()) {
		if (isset($_GET["start_date"]) && $_GET["start_date"]) {
			$date_query = [
				[
					"after" => $_GET["start_date"],
					"inclusive" => true
				]
			];

			$query->set("date_query", $date_query);
		}
	}
}

add_action("pre_get_posts", "mt_collection_filter");

function get_coupon_after_purchase()
{
	if (!is_user_logged_in()) {
		return;
	}
	$user_id = get_current_user_id();

	$couponCode = uniqid($user_id);

	$coupon = new WC_Coupon($couponCode);

	$user = new WP_User($user_id);

	$user_email = $user->user_email;

	$coupon->set_discount_type('percent');

	$coupon->set_amount(10);

	$coupon->set_usage_limit(1);

	$coupon->set_email_restrictions(
		array($user_email)
	);

	$coupon->save();

	echo "<p class='purchased_coupon_code'>" . $couponCode . "</p>";

}
add_action("get_coupon_after_purchase", "get_coupon_after_purchase");



function mt_theme_setup()
{
	load_theme_textdomain('mt', get_template_directory());
}
add_action('after_setup_theme', 'mt_theme_setup');

function add_custom_script()
{

	wp_enqueue_script('my-custom-script', get_template_directory_uri() . '/js/toggleClasses.js', array(), true);
	// wp_enqueue_script_module('my-custom-script', get_theme_file_uri('/js/main.js'));


}

add_action('wp_enqueue_scripts', 'add_custom_script');


add_action('woocommerce_checkout_create_order_line_item', 'mt_transfer_cart_item_meta_to_order', 10, 4);

function mt_transfer_cart_item_meta_to_order($item, $cart_item_key, $values, $order)
{
	if (isset($values['collection_id'])) {
		$item->add_meta_data('collection_id', $values['collection_id'], true);
	}
}


add_action('woocommerce_thankyou', 'my_custom_collection_check_on_order', 10, 1);

function my_custom_collection_check_on_order($order_id)
{


	$order = wc_get_order($order_id);

	$collection_id = $order->get_meta('collection_id');

	foreach ($order->get_items() as $item) {
		$collection_id = $item->get_meta('collection_id');
		if ($collection_id) {

			$collection_info = get_post($collection_id);
			$user_data = get_user_by("id", $collection_info->post_author);
			var_dump($user_data->user_email);
		}
	}
}
