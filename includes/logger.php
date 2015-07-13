<?php
require_once('initialize.php');
/**
 * This class deal with everything about writing and reading the log file for
 * logins
 */
class Logger {
    private static $LogFile = LOG_FILE;
    private static $Content="";

/**
 * To write to the log file the login times of the user. If the file does not
 * already exist append will take care of that for me
 * @param  string $user       This is the username variable in the login.php
 * script
 * @param  string $action     This is what action the user is taking, ie login
 * or logout.
 * @param  Unix timestamp $time_stamp
 */
    public static function InputEntry($user="", $action="", $time_stamp) {

        if($file_handle = fopen(self::$LogFile, 'ab')) {
            if(!is_writable(self::$LogFile)) {
                echo "ERROR: Change file permission." . self::$LogFile;
            } else {
                $time_string = strftime("%Y-%m-%d %H:%M:%S", $time_stamp);
                $message = "{$action}: {$user} @ {$time_string}\n";
                // $message = $time_string . " | {$action}: {$user}\n";
                fwrite($file_handle, $message);
            }
        }
        fclose($file_handle);

    }

/**
 * First does a check to see if file exist and is readable. If so it will open
 * the file then line by line append it to the self::$Content var and at the
 * end it will echo out the logs. Therefore no need to echo this function as
 * it does not return anything.
 */
    public static function ReadLog() {
        if(!file_exists(self::$LogFile) || !is_readable(self::$LogFile)) {
            echo "ERROR: Log file does not exist or is unreadable.";
        }
        if($file_handle = fopen(self::$LogFile, 'rb')) {
            self::$Content .= "<ul class=\"log-list\">";
            while(!feof($file_handle)) {
                $entry = fgets($file_handle);
                if(trim($entry) != "") { // removes EXTRA newline More: note 4
                    self::$Content .= "<li>" . $entry . "</li>";
                }

            }
        }
        self::$Content .= "</ul>";
        fclose($file_handle);
        echo (self::$Content);
    }

/**
 * Will clear the log file and write time and which user id called this method
 * @param int $user_id To show who cleared the file.
 */
    public static function Clear($user_id) {
        if(file_exists(self::$LogFile)) {
            file_put_contents(self::$LogFile, '');
            $clear_time = strftime("%Y/%m/%d %H:%M:%S", time());
            file_put_contents(self::$LogFile,
                "Log file cleared @ {$clear_time}, User ID: {$user_id}\n");
        }
    }
}
?>