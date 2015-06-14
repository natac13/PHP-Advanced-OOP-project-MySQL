<?php
/**
 * Since this user class will be accessing the database it is good practice to
 * include the database.php file. However it is best to use require_once so
 * that it will only load one time per page, even if it was asked for multiple
 * times
 */
require_once("database.php");

class User {

/**
 *  Every column in the database gets an attribute so that when I fetch the
 *  associative array I can assign that to the class attributes.
 */
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;


/**
 * Finds all users in the database with all the column data available.
 * This is a static function so that I do not need to have an instance of the
 * class merely to call find_all(), I can just now use the :: notation to call
 * the function. ex. User::find_all() INSTEAD OF $user = new User();
 * $user->find_all();
 * @return general mysql request which just makes this a clearer looking
 * function. old:
 *      global $db;
 *      $sql = "SELECT * FROM users";
 *      $result_set = $db->query($sql);
 *      return $result_set;
 * @return mysqli_result object This has methods corresponding to it such as
 * fetch_assoc(). This is the likely case for me here after calling this
 * function I will fetch_assoc() and use a while loop to cycle through.
 */
    public static function find_all() {
        $sql = "SELECT * FROM users";
        return self::find_by_sql($sql);
    }

/**
 * Finds a users in the database by the given id value. Since I am only
 * finding one user I simple return the associative array to that user, with
 * the columns as keys and the database value as the value of the assoc array.
 * Have used global $db since that is the alias to the mysqli object for
 * database access.
 * This is a static function so that I do not need to have an instance of the
 * class merely to call find_user_by_id(). Just using the double colon
 * notation (::) I can call this method without instantiating the class
 * @param  integer $id Will target as specific user in the database
 * @return Assoc Array      With keys as the database columns and values for
 * values
 */
    public static function find_user_by_id($id=0) {
        global $db;
        $sql= "SELECT * FROM users WHERE id={$id}";
        $result_set = $db->query($sql);
        $found_user = $result_set->fetch_assoc();
        return $found_user;
    }

/**
 * This is for general mysql request that do not involve the user id.
 * Have used global $db since that is the alias to the mysqli object for
 * database access.
 * @param  string $sql The string command for mysql
 * @return mysqli_result object      This object has its own methods like
 * fetch_assoc(), check docs for more info.
 */
    public static function find_by_sql($sql="") {
        global $db;
        $result_set = $db->query($sql);
        return $result_set;
    }

/**
 * First checks with issset() to see if both first and last name class
 * variables have been set, then if so returns them in a string together
 * @return string Full name of the User instance.
 */
    public function full_name() {
        if(isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . " " . $this->last_name;
        } else {
            return "";
        }
    }

/**
 * This method will make an object of itself and then assign the attribute
 * from the given $record(and for me that is an mysqli_result_object)
 * @param  object $record mysqli_result_object from the already made User
 * instance
 * @return object         This is a mysqli_object that is the same as the
 * class this method is in.
 */
    private static function instantiate($record) {
        $object = new self;
        $object->id = $record['id'];
        $object->username = $record['username'];
        $object->password = $record['password'];
        $object->first_name = $record['first_name'];
        $object->last_name = $record['last_name'];
        return $object;
    }
}
?>