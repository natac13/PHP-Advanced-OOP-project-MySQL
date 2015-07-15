<?php

require_once("../includes/initialize.php");

/**
 * First I need to get the page number and in the event it is empty, like on
 * first getting to the site.
 * @var int
 */
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

$per_page = 5;

$count = Photograph::count_all();


// $photos = Photograph::find_all();

$pagination = new Pagination($page, $per_page, $count);
$sql = $pagination->build_sql("photographs");
$photos = Photograph::find_by_sql($sql);
?>

<?php include_layout_template("header.php"); ?>

<h2>Photographs</h2>
<?php echo output_message($message); ?>
<div>
    <?php foreach($photos as $photo) { ?>
    <div id="photo">
        <figure>
            <a href="photo_display.php?id=<?php echo $photo->id; ?>">
                <img src="<?php echo $photo->image_path(); ?>"alt="<?php echo
                    $photo->filename;?>" width="200" height="200">
            </a>
            <figcaption>
                Fig.<?php echo $photo->id; ?> -
                <?php echo isset($photo->caption) ? $photo->caption : ""; ?>
            </figcaption>
        </figure>
    </div>
    <?php } ?>
</div>

<div class="pagination">
    <?php
        if($pagination->total_pages() > 1) {
            if($pagination->has_previous_page()) {
                echo "<a href=\"index.php?page=";
                echo $pagination->pervious_page();
                echo "\"><button>&laquo; Previous</button> </a> ";
            }

            for($i = 1; $i <= $pagination->total_pages(); $i++) {
                if($i == $page) {
                    echo "<span class=\"selected\">{$i}</span>";
                } else {
                    echo " <a href=\"index.php?page={$i}\">{$i}</a> ";
                }
            }
            if($pagination->has_next_page()) {
                echo "<a href=\"index.php?page=";
                echo $pagination->next_page();
                echo "\"> <button>Next &raquo;</button></a>";
            }
        }
    ?>
</div>




<?php include_layout_template("footer.php"); ?>