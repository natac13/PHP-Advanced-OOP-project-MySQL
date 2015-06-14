<?php

require_once("../includes/database.php");
require_once("../includes/user.php");

$record = User::find_user_by_id(1);

echo "var full name NOW!: " . $user->full_name();
echo "<br><hr><br>";
echo "Found User with class: " . $record["username"];
echo "<br><hr><br>";
// $user_set = User::find_all();
// while($user = $user_set->fetch_assoc()) {
//     echo "User: ". $user['username'] . "<br>";
//     echo "First and last name: ". $user['first_name'] . " " .
//         $user['last_name'];
// }
// echo "<br><hr><br>";

?>