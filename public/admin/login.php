<?php

require_once('../../includes/initialize.php');

if($session->is_logged_in()) {
    redirect_to('index.php');
}

// Remember to give my form submit button or 'tag' a name='submit'
if(isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check the database to see if the username and password exist
    $found_user = User::authenticate($username, $password);

    if ($found_user) {
        // where I put the log function
        $timestamp = time();
        // Logger::InputEntry($found_user->username, "Login", $timestamp);
        // Now the $session method login() takes care of the data entry.
        $session->login($found_user);
        redirect_to('index.php');
    } else {
        // Username and password combo not found in database
        $message = "Username/Password combination not found.";
    }
} else { // Form not submitted
    $username = "";
    $password = "";
    // $message = "";
}
?>

<?php  include_layout_template('admin_header.php'); ?>
<h2>Staff Login</h2>
<?php
echo output_message($message);
?>
<form id="login" action="login.php" method="POST" accept-charset="utf-8">
<fieldset>
<legend>User Login</legend>
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" pattern="\w{4,30}"
                value="<?php echo htmlentities($username); ?>" required
                title="Enter username 4-30 characters.">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" pattern="\w{2,30}"
                value="<?php echo htmlentities($password); ?>" required
                title="Enter Password 2-30 characters.">
    <input type="submit" class="submit" name="submit" value="Login">
</fieldset>
</form>



<?php include_layout_template('admin_footer.php'); ?>