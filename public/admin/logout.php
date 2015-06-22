<?php
require_once('../../includes/initialize.php');
// Logger::InputEntry($_SESSION['username'], "Logout", time());
$session->log_out();
redirect_to('login.php');
?>