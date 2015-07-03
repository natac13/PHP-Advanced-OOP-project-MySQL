<?php

require_once(LIB_PATH.DS."database.php");

/**
 * @param string   $table_name Used in the parent find methods with late
 * static binding
 * @param array    $db_fields  Use in the attribute() methods so that the
 * abstracted create and update methods are usable when they join the queries
 * @param int      $id         From the database as it auto_increments
 * @param string   $filename   Set to the $_FILE['name'] by calling basename()
 * to only return the name without the extension
 * @param string   $type       The mime type
 * @param int      $size       The size of the uploaded file
 * @param string   $caption    A caption which goes with the photograph
 * @param string   $temp_path   This is provide to me when I upload a file, by
 * the $_FILE['filename']['tmp_name'] which will be needed in the function
 * move_uploaded_file([from], [to]).
 * @param string   $upload_dir  Where the website stores the images to load on
 * the pages
 * @param array    $errors      This will be a list of all errors that can
 * occur during this process like the php ones listed below or the ones I will
 * create like 'does the file exist?
 * @param assoc    $upload_errors A user friendly read out of the php errors
 * which can occur during this process. The keys are the php errors and the
 * value are the user friendly strings. This gets user when checking for php
 * errors and then if one found use it as the key to retrieve the value from
 * this associative array
 *
 */
class Photograph extends DatabaseObject {


    protected static $table_name = "photographs";
    protected static $db_fields = array('id', 'filename', 'type', 'size',
        'caption');
    public $id;
    public $filename;
    public $type;
    public $size;
    public $caption;

    private $temp_path;
    protected $upload_dir = "images";
    public $errors = array();

    protected $upload_errors = array(
        UPLOAD_ERR_OK    => "No errors.",
        UPLOAD_ERR_INI_SIZE => "Larger then upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL => "Partial Upload.",
        UPLOAD_ERR_NO_FILE => "No file.",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Cannot write to the disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by extension."
        );

/**
 * This method is to take in a uploaded file, in the form of an associative
 * array. That array contains all the information that I need to create an
 * photograph object and then save it in the database.
 *
 * Will check for errors on the form parameters
 * Set the attribute to the form parameters
 * Do NOT save to database yet
 *
 * pass in $_FILE['uploaded_file'] which is represented by $file inside the
 * method
 *
 * @param  assoc $file This is from the super global $_FILE which is an array
 * itself that as all the uploaded files with keys as which are from the form
 * submission.
 * @return [type]       [description]
 */
    public function attach_file($file) {
        // Check for errors
        if(!$file || empty($file) || !is_array($file)) {
            // nothing uploaded or wrong argument used
            // append a person error message about the file not being there
            $this->errors[] = "No file was uploaded at this time.";
            return false;
        }
        elseif($file['error'] != 0) {
            // there was an php error
            // this is appending to the array of errors the value from the
            // associative array of php errors. The key is the super globals
            // errors
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        }
        else {
            // Set the attribute to the form parameters
        $this->temp = $file['tmp_name'];
        $this->filename = basename($file['name']);
        $this->type = $file['type'];
        $this->size = $file['size'];
        }
    }
}
?>