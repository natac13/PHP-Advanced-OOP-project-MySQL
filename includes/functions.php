<?php
/**
 * Using strtodate does not have an output for the date with leading zeros
 * removed, therefore this function does that for me
 * @param  string $marked_string This is the date string that will have "*"
 * marks in front of the months and days identifiers. Then using str_replace
 * it removes any leading zeros or if none match will remove the "*" at the
 * end.
 * @return date_string                A formated date string with leading
 * zeros on the months and days removed.
 */
function strip_zeros_from_date($marked_string="") {
    // first remove the marked zeros
    $no_zeros = str_replace("*0", "", $marked_string);
    // then any remaining marks, this is for when they do not match with zeros
    $cleaned_string = str_replace("*", "", $no_zeros);
    return $cleaned_string;
}

/**
 * This will change the header to the webpage so that the user will be
 * redirected
 * @param  string $location relative path to the new page
 */
function redirect_to($location=null) {
    if($location != null) {
        header("Location: {$location}");
        exit;
    }
}

/**
 * Given an string will display with a <p> tag with the class of message to
 * be styled in a css file. Before this was used to display error messages or
 * success of executions.
 * @param  string $message Either errors or a success message
 * @return string          Wrapped in <p> tags and class of message
 */
function output_message($message="") {
    if(!empty($message)) {
        return "<p class=\"message\">{$message}</p>";
    } else {
        return "";
    }
}

/**
 * If for some reason I can't figure out yet, if I forget to require_once()
 * for the user.php file and I am trying to call that class in the file.
 * In stead of a error I can make this 'default' __autoload() function that
 * will take as a parameter $class_name which is what the file is trying to
 * call but can't find.
 * Note: I can be as complex as I want; like looking through more directories
 * with 'elseif' before failing.
 * @param  string $class_name  This is the object that the file is trying to
 * call but cannot find.
 */
function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path)) {
        require_once($path);
    } else {
        die("The file {$class_name}.php could not be found. Please fix!");
    }
}


/**
 * This will generate the correct template that is needed based off the
 * parameter that is passed into it
 * @param  string $template Name to one of the layout files. ie header, footer
 */
function include_layout_template($template="") {
    include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

?>