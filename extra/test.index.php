<?php
// the echo seems to have to go first in the ternary operator way.
echo isset($db) ? "true" : "false";
echo "<br>";
echo $db->real_escape_string("It's working?<br/>");
echo $db->escape_string("I want to see if it's working again? <br>");
// escape_string is an alias of real_escape_string

$id = 1;
$user = 'Natac';
$pass = 'test';
$first_name = "Sean";
$last_name = "Campbell";

$sql =  "INSERT INTO users (";
$sql .= "id, username, password, first_name, last_name";
$sql .= ") VALUES (";
$sql .= "{$id}, '{$user}', '{$pass}', '{$first_name}', '{$last_name}'";
$sql .= ")";
// $result = $db->query($sql);
// confirm_query($result, $sql);
// echo "id: " . $db->affected_rows;
// echo "<br><hr><br>";
// echo $last_query;

$query = "SELECT * FROM users WHERE id = 1 LIMIT 1";
$res = $db->query($query);
confirm_query($res, $query);
$found_user = $res->fetch_assoc();
echo "Found User: " . $found_user["username"];
echo "<br><hr><br>";
echo "The last query test: " . $last_query;
echo "<br><hr><br>";
?>