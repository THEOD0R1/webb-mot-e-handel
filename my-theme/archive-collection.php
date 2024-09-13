<?php
get_header();

?>

<form method="GET">
    <input type="date" name="start_date" id="start_date">
    <input type="date" name="end_date" id="end_date">
    <select name="filter_occasion" id="filter_occasion">
        <option>Select time</option>
    </select>
    <input type="submit">

</form>
<?php
if (have_posts()):

    while (have_posts()):
        the_post();

        ?>
        <a href="<?php echo get_permalink(get_the_ID()) ?>">
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

<?php
get_footer();
