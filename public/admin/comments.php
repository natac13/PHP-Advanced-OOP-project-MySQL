<?php



require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }

if(empty($_GET['photo_id'])) {
    $session->message("No photo ID was provided, therefore cannot find.");
    redirect_to("list_photo.php");
}

$photo = Photograph::find_by_id($_GET['photo_id']);

if(!$photo) {
    $session->message("Photo object cannot be found in database.");
    redirect_to("index.php");
}


$comments = $photo->comments();
?>

<?php include_layout_template("admin_header.php"); ?>
&laquo;<a href="index.php">Admin Home</a>
<br>
&laquo;<a href="list_photo.php">List Photos</a>
<br>
<!-- Photo -->

<h2><?php echo $photo->caption; ?></h2>


<figure id="figure">
    <img id="small-pic" src="<?php echo "../" . $photo->image_path(); ?>"
        alt="<?php echo $photo->filename; ?>">
    <figcaption id="small-pic-caption">
        Filename: <?php echo $photo->filename; ?>
    </figcaption>
</figure>

<!-- List Comments -->

<div id="comments">
<?php foreach($comments as $comment) {  ?>
<div class="comment">

    <div class="author">
        <?php echo htmlentities($comment->author); ?> wrote:
    </div>

    <div class="body">
        <?php echo nl2br(strip_tags($comment->body, '<strong><em><p>'));?>
    </div>

    <div class="meta-data">
        <?php echo $comment->datetime_to_text(); ?>
    </div>

    <div class="delete">
        <a href="delete_comment.php?comment_id=<?php echo $comment->id; ?>"
            onclick="return check()"><button type="button">Delete Comment
            </button></a>
    </div>

</div>
<?php } ?>
<?php if(empty($comments)) { echo "No comments yet.";} ?>
</div>






<?php include_layout_template("admin_footer.php"); ?>