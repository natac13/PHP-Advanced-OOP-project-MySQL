<?php

require_once("../includes/initialize.php");

$photos = Photograph::find_all();
?>

<?php include_layout_template("header.php"); ?>

<h2>Photographs</h2>
<?php output_message($message); ?>
<ul id="photos">
    <?php foreach($photos as $photo) { ?>
    <li>
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
    </li>
    <?php } ?>
</ul>




<?php include_layout_template("footer.php"); ?>