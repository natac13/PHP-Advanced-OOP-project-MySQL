<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}
/**
 * Finds all the photos in the database in an array
 * @var array Of Photo objects.
 */
$photos = Photograph::find_all();
?>

<?php  include_layout_template('admin_header.php'); ?>

<h2>Photographs</h2>

<table class="bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Filename</th>
            <th>Caption</th>
            <th>Size</th>
            <th>Type</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach($photos as $photo) { ?>
        <tr>
            <td><img src="../<?php echo $photo->image_path(); ?>" alt=
                "<?php if(isset($photo->filename)) {
                    echo $photo->filename; } ?>" width="100"></td>
            <td><?php echo $photo->filename; ?></td>
            <td><?php echo $photo->caption; ?></td>
            <td><?php echo $photo->size_as_text(); ?></td>
            <td><?php echo $photo->type; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<br>
<a href="photo_upload.php">Upload a new photo to the database.</a>

<?php include_layout_template('admin_footer.php'); ?>