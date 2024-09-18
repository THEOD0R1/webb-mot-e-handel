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
	<form method="POST">
		<input type="hidden" name="post_select_product" value="product_form">
		<label for="post_form_title">Title</label>
		<?php
		if (!is_user_logged_in() || true) { //Use || true or && false only in testing or 
			?>
			<input type="text" name="post_form_title" id="post_form_title" placeholder="Title">
			<label for="post_form_name">Full name</label>
			<input type="text" name="post_form_name" id="post_form_name" placeholder="Full name">
			<label for="post_form_email">Email</label>
			<?php
		}
		?>

		<input type="text" name="post_form_email" id="post_form_email" placeholder="Email">

		<section class="collection__product__card">
			<?php
			foreach ($products as $product) {
				$id = $product->get_id();
				$title = $product->name;

				?>
				<article>

					<label for="<?php echo $id ?>">
						<?php echo $title ?>
					</label>
					<input type="checkbox" name="product_id[]" value="<?php echo $id ?>" id="<?php echo $id ?>">
					<?php echo esc_html($id) ?>
				</article>

				<?php
			}

			?>
		</section>
		<label for="post_form_content">Content</label>
		<textarea name="post_form_content" id="post_form_content"></textarea>

		<input type="submit" value="Add Collection">

		<?php wp_nonce_field("new_product_collection") ?>
	</form>
	<?php
}

add_action("save_new_collection", "save_collection");
function save_collection()
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
			"post_author" => get_current_user_id()
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
	wp_enqueue_style('my_theme_style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function mt_collection_filter($query)
{
	if ($query->is_main_query()) {
		if (isset($_GET["start_date"]) && !empty($_GET["start_date"])) {
			$date_query = [
				"after" => $_GET["start_date"],
				"inclusive" => true
			];

		}
	}
}