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
 * @return Will return an other general find function that returns an array of
 * User objects that have been created by $this->instantiate() method.
 * Therefore this will be an array of every User in the database as a fully
 * built object of $this class!
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
 * class merely to call find_by_id(). Just using the double colon
 * notation (::) I can call this method without instantiating the class
 * @param  integer $id Will target as specific user in the database
 * @return First there is a check to see if the array is not empty, then if
 * that is true then using the ternary operator I call array_shfit() which
 * removes the first element in the array and returns that new User object.
 * If false meaning the $sql failed to find a match in the database then I
 * will return false so that I can use this llike a boolean to just say
 * if(find_by_id){}
 */
    public static function find_by_id($id=0) {
        global $db;
        $sql= "SELECT * FROM users WHERE id={$id}";
        $result_array = self::find_by_sql($sql); // array...of one User
        return !empty($result_array) ? array_shift($result_array) : false;
    }

/**
 * This is for general mysql request that do not involve the user id.
 * Have used global $db since that is the alias to the mysqli object for
 * database access.
 * will create an empty array to accommodate a multiple object returning
 * search. This is because the $sql could be anything from one User to all.
 * @param  string $sql The string command for mysql
 * @return By looping through the returned $rows this function will build an
 * array that will have User objects as values, which will get built by the
 * self::instantiate() method.
 */
    public static function find_by_sql($sql="") {
        global $db;
        $result_set = $db->query($sql);
        $object_array = array();
        // $row is the same as $record which is a 'thing' from the database
        // such as a user to create
        while($row = $result_set->fetch_assoc()) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array; // ...of Users
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
 * At the end this will return a newly created object of the users from the
 * database since it will get the $record fro the database past into it as the
 * parameter and then will check if the columns match the variables in the
 * object, when the do it will start assigning the $record $values to the
 * object's variables.
 * $record "$value" that goes with this "$attribute" get assigned to
 * $object->$attribute
 * Long description: "../extra/notes.txt".
 * @param  object $record mysqli_result_object from the already made User
 * instance
 * @return object         This is a mysqli_object that is the same as the
 * class this method is in.
 */
    private static function instantiate($record) {
        // Simple long form approach which I explain more in note.txt
        $object = new self;
        // $object->id = $record['id'];
        // $object->username = $record['username'];
        // $object->password = $record['password'];
        // $object->first_name = $record['first_name'];
        // $object->last_name = $record['last_name'];

        foreach($record as $attribute=>$value) {
            if($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

/**
 * This will return true or false by checking if $this object has the
 * attributes(variable)
 * get_object_vars returns all the objects variable in an associative array
 * that as the 'keys' as names and 'values' as what is stored in the variable
 * itself, which in this case I do not care about. I am simple checking if the
 * column names that are return vis the $record from the database match a
 * variable in the object.
 * @param  string  $attribute this is the 'key' from the $record which
 * represent the column names from the database
 * @return boolean
 */
    private function has_attribute($attribute) {
        $object_vars = get_object_vars($this);
        return array_key_exists($attribute, $object_vars);
    }
}
?>