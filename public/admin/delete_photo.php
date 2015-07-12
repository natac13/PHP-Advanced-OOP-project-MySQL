<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) {
   redirect_to("login.php");
}

if(empty($_GET['id'])) {
    $session->message("No photo id was provided. Cannot delete.");
    redirect_to("index.php");
}

$photo = Photograph::find_by_id($_GET['id']);
if($photo && $photo->destroy()) {
    $session->message("The photo file: " . $photo->filename . " was deleted");
    redirect_to("list_photo.php");
} else {
    $session->message("Unable to delete photo file.");
    redirect_to("index.php");
}
?>

<?php
if(isset($db)) {
    $db->close();
}
?>