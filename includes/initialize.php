<?php

// Define core paths
// Define them ass absolute paths to make sure that require_once works as
// expected
// DIRECTORY_SEPARATOR is a php pre-defined constant, all the rest I am
// creating myself.

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null :
    define('SITE_ROOT', DS.'var'.DS.'www'.DS.'sandbox'.DS.'photo_gallery');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// config file to be loaded first
require_once(LIB_PATH.DS.'config.php');

// function next since everything needs them.
require_once(LIB_PATH.DS.'functions.php');

// load in the core objects that are used
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'log_object.php');

// load database related objects, or the ones that use the database
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'photograph.php');

?>