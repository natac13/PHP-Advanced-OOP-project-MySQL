<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}
/**
 * Finds all the photos in the database in an array
 * @var array Of Photo objects.
 */
$users = User::find_all();
?>

<?php  include_layout_template('admin_header.php'); ?>

<h2>Users</h2>

<?php echo output_message($message); ?>

<table class="bordered">

    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Joined</th>
            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach($users as $user) { ?>
        <tr>
            <td><?php echo $user->username; ?></td>
            <td><?php echo $user->full_name(); ?></td>
            <td><?php echo $user->datetime_to_text(); ?></td>
            <td><a href="delete_user.php?id=<?php echo $user->id; ?>"</a>
            Delete User</td>
        </tr>
    <?php } ?>
    </tbody>

</table>
<br>

<a href="new_user.php">Create a New User</a>

<?php include_layout_template('admin_footer.php'); ?>