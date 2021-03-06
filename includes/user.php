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
 *
 * I can create a array of database column headers so that I do not need to
 * re-write the create and update functions fully. The only thing I need to
 * concern myself with per the video is checking for the hashed_password to
 * match as well to up date the hashed_password before running the update()
 * method so since it is only run in the __construct method it will not be
 * updated since the process involves finding the user to update by id then
 * modifying an attribute of that instance of the user then running the update
 * so that the changes get 'pushed' to the database.
 * Note 9
 *
 * hashed_password could not be set to private because the Static form of the
 * class was trying to access it and if this was private it can only be
 * accessed by the instance itself.
 */
    protected static $table_name = "users";
    protected static $db_fields = array('id', 'username', 'first_name',
        'last_name', 'hashed_password', 'created');

    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $created;
    protected $hashed_password;

/**
 * No reference to id because it will auto increment by mysql
 * NO reference to hashed_password because the hashing of the password will
 * occur at the end of this method and assign it to the attribute
 * $hashed_password.
 *
 * Empty by default since the instantiate method will
 * build a new object off the parent class' find methods and the returned data
 * from the database itself.
 *
 * @param string $username  To be inserted into database after assigning them
 * to the attributes of the instance.
 * @param string $password  Same as above
 * @param string $firstName Same as above
 * @param string $lastName  Same as above
 */
    public function __construct($username="", $password="", $firstName="",
                                $lastName="") {
        $this->username   = $username;
        $this->password   = $password;
        $this->first_name = $firstName;
        $this->last_name  = $lastName;
        $this->created    = strftime("%Y-%m-%d %H:%M:%S", time());
        if(!empty($this->password)) {
            $this->hashed_password = $this->password_encrypt($this->password);
        }

    }
/**
 * First checks with isset() to see if both first and last name class
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
 * Involves finding the user based off his input username finding a match in
 * the database then checking if the hashed password stored
 * will match the input password when run through the password_check() method.
 *
 * I feel I should only be storing the hashed_password in the database. So no
 * need to search for password and username since the usernames are unique.
 * Note 8.
 *
 * @param  string $username Input text from a form so need to escape it
 * @param  string $password Input text from a form so need to escape it
 * @return User Object           Which is returned by find_by_sql().
 */
    public static function authenticate($username="", $password="") {
        global $db;
        $username = $db->escape_string($username);
        $password = $db->escape_string($password);
        //$password = self::password_encrypt($password);
        //More on note.txt #7 and note #8

        $sql =  "SELECT * FROM users ";
        $sql .= "WHERE username = '{$username}' ";
        $sql .= "LIMIT 1";

        $result_array = self::find_by_sql($sql); // array...of one User object
        $user = !empty($result_array) ? array_shift($result_array) : false;
        if($user) {
            if (self::password_check($password, $user->hashed_password)) {
                // password matches
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


/**
 * This is from the basic videos which will return a hashed password
 * @param  string $password User password in plain text form
 * @return string           Hashed password to stored in the database
 */
    protected static function password_encrypt($password) {
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
 *
 * I found out why this function need to happen instead of just using
 * password_encrypt to re-hash the attempt, is because the generate salt part
 * is random based off time so it will be different unless I use the same salt
 * from when the hash first happened which is during the create part
 *
 * @param  string $password      From a form $_POST['password']
 * @param  string $existing_hash From the database itself meaning I have to
 * find a 'record'/'row' first to obtain the hashed password.
 * @return boolean                If password gets hashed with the salt from
 * the existing hashed_password and still matched then it will return true.
 */
    protected static function password_check ($password, $existing_hash) {
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

/**
 * I am checking if the instance's username and password are empty because
 * I am running this function in the __construct() which will get called when
 * instantiate() gets called which I do not want because no values are passed
 * in for the __construct() and therefore it would insert a blank user into my
 * database every time.
 *
 * If there was a successful create then the instance's id get set to whatever
 * mysql gives back since it will auto increment in the database.
 *
 * To abstract the attributes I need to first return an associative array of
 * the key value pairings.
 *
 * I have found it easier to rewrite the CRUD for the user because I am using
 * a hashed password.
 *
 * @return boolean True on success and false on fail.
 */
    protected function create() {
        global $db;
    // Don't forget the Sql syntax good habits
    // "INSERT INTO [table_name] (key, key, ...) VALUES ('value', 'value', ..)
    // single quote around the values so they are literal and then I will
    // escape all values with escape_string() to prevent sql injects"
        if(!empty($this->username) && !empty($this->password)) {
            return (parent::create()) ? true : false;
        }
        return false;
    }

    public function update_password($password) {
        $this->hashed_password = $this->password_encrypt($password);
    }



//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
/// save() method taken from parent class like these other methods would be///
/// if I was not checking the hashed password of the user class which I am ///
/// just not sure fully yet to get around and still have just the abstract ///
/// method but still able to hash the password and such.                   ///
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

/**
 * Written so that the user would need to input the password to delete
 *
 * Because of the $password checking addition I again have to override the
 * delete method and therefore have two versions of it.
 *
 * Changed so that after verifying the password through the password_check()
 * method, I simple run the parent version of delete and return true if that
 * is true. I could not simple run the parent method and get this method to
 * return true. It did still delete the record though.
 *
 * @param  string $password From a form that will be displayed when the user
 * wants to delete themselves.
 * @return boolean           True on successful delete and false otherwise.
 */
    // public function delete($password) {
    //     global $db;

    //     if($this->password_check($password, $this->hashed_password)) {
    //         return (parent::delete()) ? true : false;
    //     }
    // }
}
?>