<?php
/**
 * Since this user class will be accessing the database it is good practice to
 * include the database.php file. However it is best to use require_once so
 * that it will only load one time per page, even if it was asked for multiple
 * times
 *
 * DatabaseObject which this class extends from has all the find functions.
 * These are static methods and they then return the corresponding object to
 * the class which called the find methods. The reaason for this line in using
 * late static bindings which is the static:: instead of self::
 * It mean whatever class called the static function the refer to that as the
 * static(self part).
 */
require_once(LIB_PATH.DS."database.php");

class User extends DatabaseObject {

/**
 *  Every column in the database gets an attribute so that when I fetch the
 *  associative array I can assign that to the class attributes. This is done
 *  by the instantiate function of this class.
 */
    protected static $table_name = "users";
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

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

    public static function authenticate($username="", $password="") {
        global $db;
        $username = $db->escape_string($username);
        $password = $db->escape_string($password);
        // $password = self::password_encrypt($password);
        // will hash the password
        // and then it can be matched with one in the database that was hashed

        $sql =  "SELECT * FROM users ";
        $sql .= "WHERE username = '{$username}' ";
        $sql .= "AND password = '{$password}' ";
        $sql .= "LIMIT 1";

        $result_array = self::find_by_sql($sql); // array...of one User
        return !empty($result_array) ? array_shift($result_array) : false;
    }

/**
 * This is from the basic videos which will return a hashed password
 * @param  string $password User password in plain text form
 * @return string           Hashed password to stored in the database
 */
    private static function password_encrypt($password) {
        $hash_format = "$2y$10$"; // tell php to use blowfish with cost of 10
        $salt_lenght = 22; // what blowfish expects to see everytime

        $salt = self::generate_salt($salt_lenght);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($password, $format_and_salt);
        return $hash;
    }

/**
 * This generates a random string for the salt to hash a password
 * mt_rand() will given a random value to be passed into uniqid() which makes
 * such I get a unique id back and with the true, adds entropy which all gets
 * passed to the md5 hashed.
 * @param  int $length the length of the salt with in this case is 22 for
 * blowfish
 * @return sting         A random sting to be given to the crypt() with the
 * password.
 */
    private static function generate_salt($length) {
        // Not 100% random or unique but good enough for salt.
        // MD5 returns 32 characters
        $unique_random_string = md5(uniqid(mt_rand(), true));

        // Valid characters for a salt are [a-z A-Z 0-9 ./]
        $base64_string = base64_encode($unique_random_string);
        // base 64 return + instead of . so i have to fix this on next line

        // But not '+' which is valid in base 64 encoding
        $modified_base64_string = str_replace('+', '.', $base64_string);

        // Truncate string to the correct length
        $salt = substr($modified_base64_string, 0, $length);

        return $salt;
    }

/**
 * Will check the given $password from a form post and compare it to one from
 * the database. Meaning I have to pass in both the attempted password and the
 * one from the database. After looking at the first project I see that, yes
 * I found the admin "record" from the database based solely on the username
 * to get the stored hashed password and then compared the two. Where in this
 * project it seem it would just be better to hashed the password coming in
 * from the post and then "WHERE" with the hashed passed word
 * @param  string $password      From a form $_POST['password']
 * @param  string $existing_hash From the database itself meaning I have to
 * find a 'record'/'row' first to obtain the hashed password.
 * @return boolean                If password gets hashed with the salt from
 * the existing hashed_password and still matched then it will return true.
 */
    private static function password_check ($password, $existing_hash) {
        // existing hash contains format and salt at start
        // able to use the entire existing hash since the function will only
        // take the first 22 character of the second argument and prefix it
        // to the hashed password. Therefore the first 22 character are the
        // same on the hashed versions and off
        // pulls the format_and_salt which is at beginning of the existing_hash
        $hash = crypt($password, $existing_hash);
        if ($hash === $existing_hash) {
            return true;
        }else {
            return false;
        }
    }
}
?>