<?php
get_header();

?>
<section class="collection_main_container">

    <form method="GET" class="filter_collection_form">
        <div class="filter_collection_container">
            <label for="start_date">Start date</label>
            <input type="date" name="start_date" id="start_date">
        </div>
        <div class="filter_collection_container">
            <label for="end_date">End date</label>
            <input type="date" name="end_date" id="end_date">

        </div>

        <input type="submit" value="Add filter">

    </form>
    <div class="car_collections_container">
        <?php

        if (have_posts()):

            while (have_posts()):
                the_post();
                ?>
                <a class="car_collection_container" href="<?php echo get_permalink(get_the_ID()) ?>">
                    <div>
                        <?php
                        the_title();
                        ?>
                    </div>
                </a>
                <?php
            endwhile;

        endif;
        ?>
    </div>
</section>

<?php
get_footer();
