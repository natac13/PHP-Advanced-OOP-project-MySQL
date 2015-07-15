<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}

$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

$per_page = 5;

$count = Photograph::count_all();

$pagination = new Pagination($page, $per_page, $count);
$sql = $pagination->build_sql("photographs");
$photos = Photograph::find_by_sql($sql);
?>

<?php  include_layout_template('admin_header.php'); ?>
&laquo;<a href="index.php">Admin Home</a>
<br>
<br>
<h2>Photographs</h2>

<?php
// Note 11
// session.php runs first and sets the variable $message to
// anything that was in the $_SESSION['message'] variable. Thats why I see no
// assignment of $message
echo output_message($message);
?>

<table class="bordered">
<thead>
<tr>
    <th>Image</th>
    <th>Filename</th>
    <th>Caption</th>
    <th>Size</th>
    <th>Type</th>
    <th>Created</th>
    <th>Comments</th>
    <th>&nbsp;</th>
</tr>
</thead>

<tbody>
<?php foreach($photos as $photo) { ?>
<tr>
<td>
    <img src="../<?php echo $photo->image_path(); ?>" alt=
        "<?php if(isset($photo->filename)) { echo $photo->filename; } ?>"
        width="100">
</td>
<td>
    <?php echo $photo->filename; ?>
</td>
<td>
    <?php echo $photo->caption; ?>
</td>
<td>
    <?php echo $photo->size_as_text(); ?>
</td>
<td>
    <?php echo $photo->type; ?>
</td>
<td>
    <?php echo $photo->datetime_to_text(); ?>
</td>
<td>
    <a href="comments.php?photo_id=<?php echo $photo->id; ?>"
        title="View comments"><?php echo count($photo->comments()); ?></a>
</td>
<td>
    <a href="delete_photo.php?id=<?php echo $photo->id; ?>"
        title="Click to delete the photo.">Delete Photo</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>

<div class="pagination">
    <?php
        if($pagination->total_pages() > 1) {
            if($pagination->has_previous_page()) {
                echo "<a href=\"list_photo.php?page=";
                echo $pagination->pervious_page();
                echo "\"><button>&laquo; Previous</button> </a> ";
            }

            for($i = 1; $i <= $pagination->total_pages(); $i++) {
                if($i == $page) {
                    echo "<span class=\"selected\">{$i}</span>";
                } else {
                    echo " <a href=\"list_photo.php?page={$i}\">{$i}</a> ";
                }
            }
            if($pagination->has_next_page()) {
                echo "<a href=\"list_photo.php?page=";
                echo $pagination->next_page();
                echo "\"> <button>Next &raquo;</button></a>";
            }
        }
    ?>
</div>
<br>
<a href="photo_upload.php">Upload a new photo to the database.</a>

<?php include_layout_template('admin_footer.php'); ?>