<?php

require_once('../../includes/initialize.php');


/**
 * Just a testing file. NO IMPORTANCE to public.
 */
?>

<?php
// $new_user = new User("stephie", "yellow", "steph", "paslaw");
// $new_user->create();
// echo $new_user->id;


// $updat_user = User::find_by_id(3);
// $updat_user->last_name = "paslawski";
// $updat_user->save();
// echo $updat_user->full_name();
echo "<hr>";

$new_user = new User("Boomer", "yellow", "stephaine", "campbell");
$new_user->save();
echo $new_user->id;
echo "<br>";
echo $new_user->full_name();

// $x = User::find_by_id(9);
// $x->password = "testing";
// $x->save();

$x = User::find_by_id(16);
echo ($x->delete('yellow')) ? 'yep' : 'nop';
echo $x->first_name . " was deleted";

?>

<?php include_layout_template('admin_footer.php'); ?>