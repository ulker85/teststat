<?php
require_once('../lib/func.php');
require_once('../lib/settings.php');
require_once('../lib/_debug.php');

$ERROR_MSG = '';

session_name('stat');
session_start();
if (!isset($_SESSION['edrpou'])) {
	header('Location: login.php');
	exit;
}

connectDB ();
?>