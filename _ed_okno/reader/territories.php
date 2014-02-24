<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//подготовка массива со списком отделов
$query_str = 'SELECT *'
				.' FROM territories';
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/territories.php');
require_once('_stop.php');
?>