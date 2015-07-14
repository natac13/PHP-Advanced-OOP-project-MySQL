<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) {
   redirect_to("login.php");
}

if(empty($_GET['id'])) {
    $session->message("No user id was provided. Cannot delete.");
    redirect_to("list_users.php");
}

?>
<?php
$user = User::find_by_id($_GET['id']);

if($user && $user->delete()) {
    $session->message("The user: " . $user->username . " was deleted");
    redirect_to("list_users.php");
} else {
    $session->message("Unable to delete user, either could not find in "
        "database, or the delete sql is wrong.");
    redirect_to("index.php");
}
?>

<?php
if(isset($db)) {
    $db->close();
}
?>