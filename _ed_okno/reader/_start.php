<?php
require_once('../../lib/func.php');
require_once('../../lib/settings.php');
require_once('../../lib/_debug.php');

$ERROR_MSG = '';

session_name('stat');
session_start();
if (!isset($_SESSION[$_ses_reader]) && !isset($_SESSION[$_ses_admin]) && !isset($_SESSION[$_ses_user]) && !isset($_SESSION[$_ses_expert])) {
	header('Location: ../../'.$_loginPHP);
	exit;
}

connectDB();

?>