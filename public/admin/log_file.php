<?php

require_once('../../includes/initialize.php');

if(!$session->is_logged_in()) {
    redirect_to('login.php');
}
// Only true since I put that in the query part of URL
if(isset($_POST['submit'])) {
    if($_GET['clear'] == 'true') {
        Logger::Clear($session->user_id);
        redirect_to('log_file.php');
    }
}


?>

<?php  include_layout_template('admin_header.php'); ?>

        <h2>Log File</h2>
        <!-- <p><a href="log_file.php?clear=true">Clear Log File</a></p> -->
        <form action="log_file.php?clear=true" method="post" accept-charset="utf-8">
            <input type="submit" name="submit" value="Clear Log File">
        </form>
        <?php Logger::ReadLog() ?>


<?php include_layout_template('admin_footer.php'); ?>