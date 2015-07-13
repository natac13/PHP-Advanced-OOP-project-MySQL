<?php

require_once(LIB_PATH . DS . "database.php");
/**
 * @param string   $table_name Used in the parent find methods with late
 * static binding, this is the name of the mysql database table.
 *
 * @param array    $db_fields  The names of the table headings of the database
 * in mysql. Use in the attribute() methods so that the abstracted create and
 * update methods are usable when they join the queries
 *
 * @param int      $id          The comment id number.
 *
 * @param int      $photograph_id The ID number that matches the ID number for
 * the photo being commented on. This is matched with the photograph database.
 *
 * @param string $created    A format string made from a Unix Timestamp.
 *
 * @param string   $author     The name that the author of the comment gives.
 *
 * @param string   $body       The comment itself, this is a TEXT field in
 * mysql so it has no restriction in length.
 *
 * @param array    $errors     An array of errors as strings.
 */
class Comment extends DatabaseObject {

    protected static $table_name = "comments";
    protected static $db_fields = array('id', 'photograph_id', 'created',
        'author', 'body');

    public $id;
    public $photograph_id;
    public $created;
    public $author;
    public $body;
    public $errors = array();


/**
 * When given the required parameters this method will make a comment object
 * for me. This does not save it in the database yet. The create() method
 * would need to be called for that action to happen.
 *
 * @param  int $photo_id The ID to match the photo being commented on
 *
 * @param  string $author   The name of the user who wrote the comment
 *
 * @param  string $body     The comment itself
 *
 * @return object           A new Comment object will be returned, false
 * otherwise.
 */
    public static function make($photo_id, $author="", $body="") {
        if(empty($author) || empty($body)) {
            $this->errors[] = "Name or comment was not provided. Cannot \
            created.";
            return false;
        }
        if(!empty($photo_id)) {
            $comment = new Comment();
            $comment->photograph_id = $photo_id;
            $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
            $comment->author = $author;
            $comment->body = $body;
            return $comment;
        } else {
            $this->errors[] = "No photo ID provided to make comment.";
            return false;
        }
    }

/**
 * Given a ID to a photo in the database this will return an array of comment
 * objects that belong to such ID.
 *
 * @param  int $photo_id Foreign key to the photograph database
 *
 * @return array           This is an array of comment objects.
 */
    public static function find_comments_on($photo_id) {
        global $db;
        $sql =  "SELECT * FROM " . self::$table_name;
        $sql .= " WHERE photograph_id=" . $db->escape_string($photo_id);
        $sql .= " ORDER BY created ASC";
        return self::find_by_sql($sql);
    }


}
?>