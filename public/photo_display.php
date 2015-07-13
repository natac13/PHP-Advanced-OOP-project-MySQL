<?php

require_once("../includes/initialize.php");

if(empty($_GET['id'])) {
    $session->message("No photo ID was provided, therefore cannot find.");
    redirect_to("index.php");
}

$id = $_GET['id'];
$photo = Photograph::find_by_id($id);

if(!$photo) {
    $session->message("Photo object cannot be found in database.");
    redirect_to("index.php");
}
?>

<?php include_layout_template("header.php"); ?>

<h2><?php echo $photo->caption; ?></h2>

<a href="index.php">&laquo; Back</a>
<figure>
    <img id="pic" src="<?php echo $photo->image_path(); ?>" alt="<?php echo
        $photo->filename; ?>">
    <figcaption id="pic-caption">
        Filename: <?php echo $photo->filename; ?>
    </figcaption>

</figure>




<?php include_layout_template("footer.php"); ?>