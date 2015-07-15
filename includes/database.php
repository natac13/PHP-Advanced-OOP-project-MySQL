<?php
require_once(LIB_PATH.DS."config.php");

// below is the OOP way to do this.
// I do not need to select the database afterwards as it is the 4th param
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ")");
}
$db =& $mysqli; // a reference to use in the main scripts


/**
 * This is in the class that was made that I choose not to since I was using
 * the pre-made mysqli class
 * Anytime I use the query method from the class I need to call this after!!
 * But not when using delete, since I ask if affected rows where changed.
 * @param  obj/bool $result    Is a object when using SELECT, SHOW, DESCRIBE
 * and EXPLAIN for query, the rest are bools
*/
function confirm_query($result, $query) {
    global $db;
    // global $last_query;
    // I am making a global version of the last_query, available to all scopes
    // $last_query =& $query;
    if(!$result) {
        $output =  "Database query failed: " . $db->error . "<br>";
        $output .= "Last SQL query: " . $query;
        die($output);
    }
}



































/**
* This is an attempt at overriding some methods in the normal mysqli class
* This is s I can keep up with the addition of methods in the class
*/
// class MySQLDatabase extends mysqli {

//     public $last_query;

//     function __construct($server, $user, $pass, $database) {
//         parent::__construct($server, $user, $pass, $database);
//     }
// /**
//  * To be used instead of the normal query() method from the parent class
//  * @param  string $sql Statement for mySQL
//  * @return object      For select,
//  */
//     public function query_mod($sql) {
//         $this->last_query = $sql;
//         $result = $this->query($sql);
//         $this->confirm_query($result);
//         return $result;
//     }

//     private function confirm_query($result) {

//         if(!$result) {
//             $output =  "Database query failed: " . $this->error . "<br>";
//             $output .= "Last SQL query: " . $this->last_query;
//             die($output);
//         }
//     }
// }

/**
 * This class is very similar to the mysqli OOP class that is already pre-made
 * however this is using the procedural mysql functions to create an object
 * I will NOT be using the old way and if I get fuck up oh well.
 * I am going to try and branch on my own and use the documentation to help
 * me with the mysqli OOP way
 */
// class MySQLDatabase {

//     private $connection;

//     function __construct() {
//         $this->open_connection();
//     }

//     public function open_connection() {
//         $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS,
//             DB_NAME);
//         if(!$this->connection) {
//             die("Database connection failed: " . mysql_error());
//         }
//     }

//     public function close_connection() {
//         if(isset($this->connection)) {
//             mysql_close($this->connection);
//             unset($this->connection);
//         }
//     }

//     // more in video part 8
// }
// $database = new MySQLDatabase();

/**
 * When I need to use the functions below I just use
 * $var = "string that needs to be preped"
 * $var = $db->real_escape_string($var)
 * that will be the same as mysql_prep()
 */
// function mysql_prep($string) {
//     global $db_connection;

//     $escape_string = mysqli_real_escape_string($db_connection, $string);
//     return $escape_string;
// }

// function confirm_query($result_set) {
//     if (!$result_set) {
//         die("Database query failed.");
//     }
// }
?>