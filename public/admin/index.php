<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}

/**
 * Using the $session->id which is the same as $_SESSION['user_id'] to call
 * static method find_by_id() which will return an User Object when used with
 * the User class.
 * @var int which i s ultimately from the database
 */
$user = User::find_by_id($session->user_id);
?>

<?php  include_layout_template('admin_header.php'); ?>

<p>Welcome: <?php echo $user->full_name(); ?></p>
<h2>Menu</h2>

<ul>
    <li><a href="log_file.php">View Log File</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

<!-- <p><a href="log_file.php">View Log File</a></p>
<p><a href="logout.php">Logout</a></p> -->

<?php include_layout_template('admin_footer.php'); ?>