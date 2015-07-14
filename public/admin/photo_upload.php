<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}


if(isset($_POST['submit'])) {

    $photo = new Photograph();
    $photo->attach_file($_FILES['file_upload']);
    echo $photo->filename;
    $photo->caption = $_POST['caption'];
    if($photo->save()) {
        // Note 12.
        $session->message("Photograph Upload successfully.");
        redirect_to("list_photo.php");
    } else {
        $message = join("<br>", $photo->errors);
    }
}



?>


<?php  include_layout_template('admin_header.php'); ?>
&laquo;<a href="index.php">Admin Home</a>
<br>
<br>

<?php
// Note 11
echo output_message($message);
?>
<form action="photo_upload.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
<fieldset>
    <legend>Upload Photo</legend>
        <input type="hidden" name="MAX_FILE_SIZE"
            value="<?php echo Photograph::$max_photo_size; ?>">

        <label for="file_upload">File:</label>
        <input type="file" name="file_upload" value="upload_file"
            id="file_upload" required>

        <br>

        <label for="caption">Caption:</label>
        <input type="text" name="caption" value="" placeholder="Caption"
            id="caption" title="A description of the photo." required>

        <input type="submit" class="submit" name="submit" value="Upload">
</fieldset>

</form>
<?php include_layout_template('admin_footer.php'); ?>