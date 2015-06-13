<?php

require_once("../includes/database.php");
// the echo seems to have to go first in the ternary operator way.
echo isset($db) ? "true" : "false";
echo "<br>";
echo "is this working";
?>