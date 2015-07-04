<?php

require_once('../../includes/initialize.php');


/**
 * Just a testing file. NO IMPORTANCE to public.
 */
?>

<?php
// $new_user = new User("stephie", "yellow", "steph", "paslaw");
// $new_user->create();
// echo $new_user->id;


// $updat_user = User::find_by_id(22);
// // $updat_user->last_name = "paslawski";
// $updat_user->update_password('test');
// $updat_user->save();
// echo $updat_user->full_name();
// echo "<hr>";

// $new_user = new User("attritest", "check", "adam", "Groge");
// $new_user->save();
// echo $new_user->id;
// echo "<br>";
// echo $new_user->full_name();

// $x = User::find_by_id(9);
// $x->password = "testing";
// $x->save();

// $x = User::find_by_id(21);
// echo ($x->delete('test')) ? $x->first_name . " was deleted" : 'nop';


if(isset($_POST['submit'])) {
    // process the form!!!
    // for demo I am just outputting some info about the file that would be
    // uploaded
    echo "<pre>";
    print_r($_FILES['file_upload']);
    echo "</pre>";
    echo "<hr>";

    $photo = new Photograph();
    $photo->attach_file($_FILES['file_upload']);
    // $photo->save();
    echo $photo->filename;
}

if(!empty($photo->errors)) {
    foreach($photo->errors as $error) {
        echo $error;
    }
}
?>


<form action="test.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    <input type="file" name="file_upload" value="upload_file">
    <input type="submit" name="submit" value="Upload">
</form>
<?php include_layout_template('admin_footer.php'); ?>