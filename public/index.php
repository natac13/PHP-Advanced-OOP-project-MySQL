<?php

require_once('../includes/initialize.php');

$user = User::find_by_id(1);

echo "var full name NOW!: " . $user->full_name();
echo "<br><hr><br>";

$users = User::find_all();
foreach ($users as $user) {
    echo "User: " . $user->username . "<br>";
    echo "Name: " . $user->full_name() . "<br><br>";
}

?>

<?php include_layout_template('header.php'); ?>
<?php include_layout_template('footer.php'); ?>