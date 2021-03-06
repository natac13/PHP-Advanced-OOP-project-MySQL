<?php
require_once('database_object.php');
require_once('logger.php');
/**
 * A class to help work with sessions
 * In my case, this primarily to manage logging users in and out
 * Keep in mind when working with sessions that it is generally inadvisable to
 * store DB-related objects in the session. This is because these object can
 * become stale, meaning that the user object in the database changed in
 * relation to the session's user. And two, these object can be very large in
 * size so it would burn out memory
 *
 * @param  bool  $logged_in  True when a user is logged in and false otherwise
 * @param  int   $user_id    Set to the id of the user from the database
 * @param  string  $message  Set to any message I want to transfer from page
 * to page
 * @param  object  $user     This is one of my User objects that get returned
 * by the find methods in the DatabaseObject class.
 */
class Session {

    private $logged_in = false;
    public  $user_id;
    public  $message;
    private $user;

    function __construct() {
        session_start();
        $this->check_message();
        $this->check_if_login();
        // if($this->logged_in == true) {
        //     $this->user = User::find_by_id($this->user_id);
        // }
    }

/**
 * @return boolean Which is the variable $logged_in
 */
    public function is_logged_in() {
        return $this->logged_in;
    }

/**
 * Will check to see if there is a user then it will set both $this->user_id
 * and $_SESSION['user_id'] to the passed in $user->id value, since it is an
 * object
 * Setting $this->user_id to the $user->id I found was useless since when I
 * went to do the same with $user->username I found that on different web
 * pages I could not retrieve the stored username unless I got it from the
 * $_SESSION super global. If I look down at check_if_login() it shows that on
 * EVERY admin page at the top it set the class user_id to that of the
 * $_SESSION user_id. Therefore having:
 * $this->user_id = $_SESSION['user_id']= $user->id; was redundant!
 *
 * Only had to care about setting the user_id session variable since that is
 * how every admin page checks with check_if_login().
 *
 * Can use Logger class from a different file without require_once on this
 * page since it is not this page that calls the method. When the method is
 * called the page has to have the 'log.php' file loaded.
 * @param  object $user A user class object from the datebase that will return
 * a user object or false
 */
    public function login($user) {
        // database will find user based on username/password
        if($user) { // can be done b/c of how I go about finding users from db
            $_SESSION['user_id'] = $user->id;
            $this->logged_in = true;
            Logger::InputEntry($user->username, "Login", time());

        }
    }

    public function log_out() {
        Logger::InputEntry($this->user->username, "Logout", time());
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = false;
    }


/**
 * I could break this into two function called set message and get message but
 * this is a really cool concept to grasp
 * @param  string $msg A string that will be a message to set the
 * actual $_SESSION['message'] variable to
 * @return string      This is the instance's $message attribute that is
 * returned.
 */
    public function message($msg="") {
        // this will be true if passed an argument
        // false if no $msg passed therefore return
        if(!empty($msg)) {
            $_SESSION['message'] = $msg;
            // I think I should set the instance's attribute of $message here
            // as well.... or not since if one was already set and displayed
            // on the screen it would get changed as soon as the new message
            // is set.
        } else {
            return $this->message;
        }
    }

/**
 * Will check to see if the session's user_id is set and if so then it will
 * set a flag to true to show that that user is logged in with that particular
 * id
 * This method is very important since, without it any reference to the class
 * variables when not on the login page
 *
 * Now when this method is run, which happens as a __constuct and therefore at
 * the start of every file, it will use the DatabaseObject to find a user from
 * the database and then return that User object to a private Session class
 * variable so that I can use it to log the username or full name, anything the
 * User class object offers.
 *
 * This is final what I wanted because I did not want the username to be in
 * the public realm.
 * @return [type] [description]
 */
    private function check_if_login() {
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->user = User::find_by_id($this->user_id);
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }

    private function check_message() {
        if(isset($_SESSION['message'])) {
            // add to attributes and erase from the session variable
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = "";
        }
    }


    public function get_username() {
        // return $_SESSION['username'];
        return $this->user->username;
        // return $this->username;
    }
}

$session = new Session();
// will be empty string if not set yet, because of check_message().
$message = $session->message();
?>