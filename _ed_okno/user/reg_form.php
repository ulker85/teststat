<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

require_once('templates/reg_form.php');
require_once('_stop.php');
?>