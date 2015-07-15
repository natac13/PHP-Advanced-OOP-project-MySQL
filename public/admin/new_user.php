<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}


if(isset($_POST['submit'])) {
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);

    $new_user = new User($username, $password, $first_name, $last_name);

    if($new_user->save()) {
        $session->message($new_user->username . " was created! Thanks.");
        redirect_to("list_users.php");
    } else {
        $message = "Something went wrong with saving the user to database.";
    }
}
?>


<?php include_layout_template('admin_header.php'); ?>
&laquo;<a href="index.php">Admin Home</a>
<br>
<br>
<h2>Create New User</h2>

<?php echo output_message($message); ?>
<fieldset>
    <legend>User Info:</legend>
    <form action="new_user.php" method="POST" id="user-form"
            accept-charset="utf-8">
        <label for="username">Username: </label>
        <input type="text" id="username" name="username" require>

        <br>

        <label for="password">Password: </label>
        <input type="password" name="password"  id="password" require>

        <br>

        <label for="first-name">Your first name:</label>
        <input type="text" name="first_name" id="first-name" require>

        <br>

        <label for="last-name">Your last name: </label>
        <input type="text" name="last_name" id="last-name" require>

        <input type="submit" name="submit" class="submit">
    </form>
</fieldset>
<?php include_layout_template('admin_footer.php'); ?>