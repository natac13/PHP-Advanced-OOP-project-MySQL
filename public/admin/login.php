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
    $message = "";
}
?>

<?php  include_layout_template('admin_header.php'); ?>
        <h2>Staff Login</h2>
        <?php
        echo output_message($message);
        ?>
        <form action="login.php" method="post" accept-charset="utf-8">
            <table>
                <tr>
                    <td>Username:</td>
                    <td>
                        <input type="text" name="username" maxlength="30"
                        value="<?php echo htmlentities($username); ?>">
                    </td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <input type="password" name="password" maxlength="30"
                        value="<?php echo htmlentities($password); ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="submit" value="Login">
                    </td>
                </tr>
            </table>
        </form>
<?php include_layout_template('admin_footer.php'); ?>