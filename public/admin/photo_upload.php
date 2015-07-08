<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}

$message = "";
if(isset($_POST['submit'])) {

    $photo = new Photograph();
    $photo->attach_file($_FILES['file_upload']);
    // $photo->save();
    echo $photo->filename;
    $photo->caption = $_POST['caption'];
    if($photo->save()) {
        $message = "Success";
    } else {
        $message = join("<br>", $photo->errors);
    }
}



?>


<?php  include_layout_template('admin_header.php'); ?>


<?php
echo output_message($message);
?>
<form action="photo_upload.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Photograph::$max_photo_size; ?>">
    <input type="file" name="file_upload" value="upload_file">
    <p>Cation: <input type="text" name="caption" value="" placeholder="Caption"></p>
    <input type="submit" name="submit" value="Upload">
</form>
<?php include_layout_template('admin_footer.php'); ?>