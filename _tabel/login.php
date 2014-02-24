<?php
require_once('../lib/func.php');
require_once('../lib/settings.php');

$login = isset($_POST['login']) ? stripslashes(substr($_POST['login'], 0, 10)) : '';

if ($login != '') {
	if (checkUser($login)) {
		session_name('stat');
		session_start();
		$_SESSION['edrpou'] = $id;
		header('Location: index.php');
	} else $ERROR_MSG .= 'Підприємство з таким кодом не знайдено<br />';
}

//=========================================
//==  функция проверки наличия ЕДРПОУ в базе
//=========================================

function checkUser($login) {
	global $id;
	$result = false;
	
	connectDB();

	// Готовим условия отбора
	$where = array();
	$where[] = 'edrpou='.addslashes($login);
	$where[] = 'fiz_person<>1';
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	// Выборка по условиям
	$str_query = 'SELECT *'
					.' FROM organizations'
					.$whereStr
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	
	if ($resId) {
		$result = (mysql_num_rows($resId) != 0);
		$row = mysql_fetch_assoc($resId);
		$id = ($row !== false) ? $row['id'] : '';
		
		@mysql_free_result($resId);
	}

	return $result;
}

require_once('templates/login.php');
?>