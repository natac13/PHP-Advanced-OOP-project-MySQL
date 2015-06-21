<?php

/**
 * A class to help work with sessions
 * In my case, this primarily to manage logging users in and out
 * Keep in mind when working with sessions that it is henerally inadvisable to
 * store DB-related objects in the session. This is because these object can
 * become stale, meaning that the user object in the database changed in
 * relation to the session's user. And two, these object can be very large in
 * size so it would burn out memory
 */
class Session {

    private $logged_in = false;
    public $user_id;

    function __construct() {
        session_start();
        $this->check_login();
    }

/**
 * @return boolean Which is the variable $logged_in
 */
    public function is_logged_in() {
        return $this->logged_in;
    }

/**
 * Will check to she if there is a user then it will set both $this->user_id
 * and $_SESSION['user_id'] to the passed in $user->id value, since it is an
 * object
 * @param  object $user A user class object from the datebase that will return
 * a user object or false
 */
    public function login($user) {
        // database will find user based on username/password
        if($user) { // can be done b/c of how I go about finding users from db
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->logged_in = true;
        }
    }

    public function log_out() {
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = false;
    }

/**
 * Will check to see if the session's user_id is set and if so then it will
 * set a flag to true to show that that user is logged in with that particular
 * id
 * @return [type] [description]
 */
    private function check_login() {
        if(isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }
}

$session = new Session();
?>