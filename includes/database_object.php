<?php

require_once(LIB_PATH.DS.'database.php');

class DatabaseObject {

    protected static $table_name;

//////////////////////////////////////////////////////////////////////////////
//////////////////                                   /////////////////////////
//////////////////      Common Database Methods      /////////////////////////
//////////////////                                   /////////////////////////
//////////////////////////////////////////////////////////////////////////////


/**
 * Finds all rows in the database with all the column data available.
 * This is a static function so that I do not need to have an instance of the
 * class merely to call find_all(), I can just now use the :: notation to call
 * the function. ex. User::find_all() INSTEAD OF $user = new User();
 * $user->find_all();
 *
 * @return Will return an other general find function that returns an array of
 * User objects that have been created by $this->instantiate() method.
 *
 * Therefore this will be an array of every User in the database as a fully
 * built object of $this class!
 */
    public static function find_all() {
        $sql = "SELECT * FROM " . static::$table_name;
        return static::find_by_sql($sql);
    }

/**
 * Finds a row in the database by the given id value. Since I am only
 * finding one row I simple return the associative array to that row, with
 * the columns as keys and the database value as the value of the assoc array.
 * Have used global $db since that is the alias to the mysqli object for
 * database access.
 *
 * This is a static function so that I do not need to have an instance of the
 * class merely to call find_by_id(). Just using the double colon
 * notation (::) I can call this method without instantiating the class
 *
 * @param  integer $id Will target as specific row in the database
 * @return object, like User, which gets made by these find functions by will
 * build a object based off which child class called it. This is done using
 * late static binding. static::{method} instead of self::{method}
 *
 * First there is a check to see if the array is not empty, then if that is
 * true then using the ternary operator I call array_shfit() which removes the
 * first element in the array and returns that new object. If false meaning
 * the $sql failed to find a match in the database then I will return false so
 * that I can use this like a boolean to just say if(find_by_id){}
 */
    public static function find_by_id($id=0) {
        global $db;
        $sql= "SELECT * FROM " . static::$table_name . " WHERE id=" .
            $db->escape_string($id) . " LIMIT 1";
        $result_array = static::find_by_sql($sql); // ie. array...of one User
        return !empty($result_array) ? array_shift($result_array) : false;
    }

/**
 * This is for general mysql request that do not involve the id.
 * Have used global $db since that is the alias to the mysqli object for
 * database access.
 * will create an empty array to accommodate a multiple object returning
 * search. This is because the $sql could be anything from one row to all.
 *
 * @param  string $sql The string command for mysql
 * @return By looping through the returned $rows this function will build an
 * array that will have objects as values, which is built by the
 * self::instantiate() method. Therefore this returns a class object NOT a
 * mysqli::result() like a normal query() would. This finds the record form
 * database then build object.
 */
    public static function find_by_sql($sql="") {
        global $db;
        $result_set = $db->query($sql);
        confirm_query($result_set, $sql);
        $object_array = array();
        // $row is the same as $record which is a 'thing' from the database
        // such as a user to create
        while($row = $result_set->fetch_assoc()) {
            $object_array[] = static::instantiate($row);
        }
        return $object_array; // ...of Users/photos of rows from database
    }

/**
 * This method will make an object of itself and then assign the attribute
 * from the given $record(and for me that is an mysqli_result_object)
 *
 * At the end this will return a newly created object of the table(users) from
 * the database since it will get the $record fro the database past into it as
 * the parameter and then will check if the columns match the variables in the
 * object, when the do it will start assigning the $record $values to the
 * object's variables.
 * $record "$value" that goes with this "$attribute" get assigned to
 * $object->$attribute
 * Long description: "../extra/notes.txt". 1-3
 * @param  object $record mysqli_result_object from the already made instance
 * the record is the same as calling it a row from the database.
 * @return object         This is a Object that is of the same class that
 * calls it.
 */
    private static function instantiate($record) {
        $object = new static;
        foreach($record as $attribute=>$value) {
            if($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

/**
 * $result will be be a normal mysql_result with the difference being that it
 * is one thing(record) and that is the count. Therefore I still need to
 * fetch_assoc() to get back the array of one record, and then use array_shift
 * to retrieve that record
 * @return  First element from the array which is a int.
 */
    public static function count_all() {
        global $db;
        $sql  = "SELECT COUNT(*) FROM ";
        $sql .= static::$table_name;
        $result = $db->query($sql);
        confirm_query($result, $sql);
        $record = $result->fetch_assoc();
        return array_shift($record);
    }

/**
 * This will return true or false by checking if the calling object has the
 * attribute(variable) which is passed in as a parameter
 *
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
        $object_vars = $this->attributes();
        return array_key_exists($attribute, $object_vars);
    }

/**
 * returns an associative array of the class' attributes and there values.
 * @return assoc key, value pairing of class variables
 */
    protected function attributes() {
        // return get_object_vars($this);
        $attributes = array();
        foreach(static::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

/**
 * Cleans the values of the class variables which get submitted to the
 * database and therefore need to be escaped.
 * @return assoc With the instance's variables/attribute as keys with
 * real_escape_string() run on the values.
 */
    protected function sanitized_attributes() {
        global $db;
        $cleaned_values = array();
        foreach($this->attributes() as $key => $value) {
            $cleaned_values[$key] = $db->escape_string($value);
        }
        return $cleaned_values;
    }


/**
 * General create method that get escaped versions of the attribute values
 * and query this all to mysql
 * By changing the table name value to the static variable of the class
 * calling it will make this and the other CRUD functions more abstract and
 * therefore reusable.
 * @return bool true of success and false on fail
 */
    protected function create() {
        global $db;

        $attributes = $this->sanitized_attributes();
        $sql  = "INSERT INTO ".static::$table_name." (";
        // join on , and space.
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('"; // open first single to values
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        $result = $db->query($sql);
        confirm_query($result, $sql);
        if($result) {
            $this->id = $db->insert_id;
            return true;
        } else {
            return false;
        }
    }


/**
 * Finding the attributes and the values I build a array, which the items are
 * strings that will be ready for mysql. key='value', ...,
 *
 * Before calling this function I need to change one of the object's
 * attributes so that something gets changed in the database.
 *
 * If this change is to the password of the User class then I will run
 * update_password() first so that the hashed password is changed.
 * @return bool true on success
 */
    protected function update() {
        global $db;

        $attributes = $this->sanitized_attributes();
        $attribute_strings = array();
        foreach($attributes as $key => $value) {
            $attribute_strings[] = "{$key}='{$value}'";
        }
        $sql  = "UPDATE ".static::$table_name." SET ";
        $sql .= join(", ", $attribute_strings);
        $sql .= " WHERE id=". $db->escape_string($this->id);
        $result = $db->query($sql);
        confirm_query($result, $sql);
        return ($db->affected_rows == 1) ? true : false;
    }

/**
 * A new record will not have a id yet. Since when I create I pass in as
 * params everything but the id.
 * @return method If there is an id set then call update. If no id set then
 * call create.
 */
    public function save() {
        return isset($this->id) ? $this->update() : $this->create();
    }


    public function delete() {
        global $db;

        $sql =  "DELETE FROM ". static::$table_name;
        $sql .= " WHERE id=" . $db->escape_string($this->id);
        $sql .= " LIMIT 1";
        $result = $db->query($sql);
        confirm_query($result, $sql);
        return ($db->affected_rows == 1) ? true : false;
    }


/**
 * Takes in the none user friendly formatted time string from my database and
 * will convert to a nicer looking display
 * @return string Time created.
 */
    public function datetime_to_text() {
        if(isset($this->created)) {
            $datetime = $this->created;
            $unix_datetime = strtotime($datetime);
            return strftime("%B %d, %Y at %I:%M %p", $unix_datetime);
        } else {
            return "";
        }
    }

}

?>