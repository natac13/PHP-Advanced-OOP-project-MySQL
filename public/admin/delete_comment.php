<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) {
   redirect_to("login.php");
}

if(empty($_GET['comment_id'])) {
    $session->message("No comment ID was provided. Cannot delete.");
    redirect_to("list_photo.php");
}

?>
<?php

$comment = Comment::find_by_id($_GET['comment_id']);

if($comment && $comment->delete()) {
    $session->message("The comment by: " . $comment->author . " was deleted,
        thanks");
    redirect_to("list_photo.php");
} else {
    $session->message("Unable to delete comment, either could not find comment
        in database, or the delete sql is wrong.");
    redirect_to("list_photo.php");
}
?>

<?php
if(isset($db)) {
    $db->close();
}
?>