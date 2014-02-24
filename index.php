<?php
require_once('lib/func.php');
require_once('lib/settings.php');

$login = isset($_POST['login']) ? stripslashes(substr($_POST['login'], 0, 10)) : '';
$pass = isset($_POST['pass']) ? stripslashes(substr($_POST['pass'], 0, 10)) : '';

session_name('stat');
session_start();
session_unset();

if ($login != '' || $pass != '') {
	if (checkUser($login, $pass)) {
		session_name('stat');
		session_start();
		
		$_SESSION[$ses_name] = $id;
			
		$_SESSION['type_report'] = $type_report;
		header('Location: _ed_okno/'.$location);
	} else 
		$ERROR_MSG .= "<br />Невірна пара логін-пароль";
}

function checkUser($login, $pass) {
	global $id, $location, $ses_name, $type_report;
	$result = false;
	$id = $type_report = 0;
	$location = $ses_name = '';
	
	connectDB();
	
	$str_query = 'SELECT *'
					.' FROM users'
					.' WHERE login="'.addslashes($login).'"'
						.' AND pass="'.addslashes(md5($pass)).'"';
	
	$resId = mysql_query($str_query);	
	if ($resId) {
		$result = (mysql_num_rows($resId) != 0);
		
		while ($row = mysql_fetch_assoc($resId)) {
			$id = $row['id'];
			$location = $row['location'];
			$ses_name = $row['session_name'];
			$type_report = $row['id_type_report'];
		}
		@mysql_free_result($resId);
	}
	@mysql_close();
	return $result;
}

require_once('templates/index.php');
?>