<?php


get_header();

// wp_kses() eller esc_attr()
?>

<?php
if (have_posts()):

	while (have_posts()):
		the_post();


		$query = new WP_Query(
			array(
				'post_parent' => get_the_ID(),
				'post_type' => 'page',
				'orderby' => 'menu_order',
				'order' => 'ASC'
			)
		);

		while ($query->have_posts()) {
			$query->the_post();

			?><a href="<?php echo (get_permalink(get_the_ID())); ?>">
				<?php
				the_title();
				?>
			</a>
			<?php
		}

		wp_reset_postdata();


		the_title();
		the_content();


	endwhile;

endif;
?>

<?php
get_footer();
