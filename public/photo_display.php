<?php

require_once("../includes/initialize.php");

if(empty($_GET['id'])) {
    $session->message("No photo ID was provided, therefore cannot find.");
    redirect_to("index.php");
}

$photo = Photograph::find_by_id($_GET['id']);

if(!$photo) {
    $session->message("Photo object cannot be found in database.");
    redirect_to("index.php");
}


/**
 * When there is a form submission of a comment this will trim the extra white
 * space of the input fields. Then I will send all the to the make() method
 * which is static and therefore will build and return a Comment object (with
 * user input as attributes however they get cleaned while going through the
 * create() method.)
 */
if(isset($_POST['submit'])) {
    $author   = trim($_POST['author']);
    $body     = trim($_POST['body']);
    $comment = Comment::make($photo->id, $author, $body);
    if($comment && $comment->save()) {
        // Success!
        //
        // If page is reload then there will be another comment which gets
        // processed into the database, if I just let the page render normal
        // Instead redirect so this does not happen
        redirect_to("photo_display.php?id={$photo->id}");
    } else {
        // I believe I don't involve the $session->message() here is because
        // that is to transfer messages. If used here the message I want to
        // display will not get set to the $message variable at the end of the
        // session.php page(the start of this script)
        $message = join("<br>", $comment->errors);
    }

} else {
    $author = "";
    $body = "";
}

$comments = $photo->comments();
?>

<?php include_layout_template("header.php"); ?>

<!-- Photo -->

<h2><?php echo $photo->caption; ?></h2>

<a href="index.php">&laquo; Back</a>
<figure>
    <img id="pic" src="<?php echo $photo->image_path(); ?>" alt="<?php echo
        $photo->filename; ?>">
    <figcaption id="pic-caption">
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
    </div>
    <?php } ?>
    <?php if(empty($comments)) { echo "No comments yet.";} ?>
</div>

<!-- Comment form -->

<?php echo output_message($message); ?>
<fieldset>
    <legend>New Comment</legend>
    <form action="photo_display.php?id=<?php echo $photo->id; ?>"
        method="POST" accept-charset="utf-8">

        <label for="author">Your name: </label>
        <input type="text" id="author" name="author" maxlength="255"
            value="<?php echo $author; ?>">

        <br>

        <label for="body">Comment about photo: </label>
        <textarea name="body" id="body" cols="40" rows="8"><?php echo $body;?>
        </textarea>

        <br>

        <input type="submit" name="submit" class="submit"
            value="Submit Comment">
    </form>
</fieldset>




<?php include_layout_template("footer.php"); ?>