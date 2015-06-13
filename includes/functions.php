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
}
?>