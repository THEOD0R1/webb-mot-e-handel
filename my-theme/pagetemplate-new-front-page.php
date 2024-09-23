<?php
/*
Template Name: New front
*/
get_header();

?>

<div class="menu-line">
    <div class="centered-site"></div>
</div>
<?php
if (have_posts()):

    while (have_posts()):

        the_post();



        ?>
        <div class="centered-content">
            <h1>
                <?php the_title(); ?>
            </h1>
            <?php the_content(); ?>
        </div>
        <?php


    endwhile;

endif;


get_footer();


