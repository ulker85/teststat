<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO users'
						.' (login, pass, location, id_type_report, id_department, session_name)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['login'])).'"'
						.', "'.addslashes(stripslashes(md5($_POST['pass']))).'"'
						.', "'.addslashes(stripslashes($_POST['location'])).'"'
						.', "'.addslashes(stripslashes($_POST['type'])).'"'
						.', "'.addslashes(stripslashes($_POST['department'])).'"'
						.', "'.addslashes(stripslashes($_POST['session'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE users SET'
								.' login="'.addslashes(stripslashes($_POST["login_$v"])).'"'
								.', pass="'.addslashes(stripslashes(md5($_POST["pass_$v"]))).'"'
								.', location="'.addslashes(stripslashes($_POST["location_$v"])).'"'
								.', id_type_report="'.addslashes(stripslashes($_POST["type_$v"])).'"'
								.', id_department="'.addslashes(stripslashes($_POST["dep_$v"])).'"'
								.', session_name="'.addslashes(stripslashes($_POST["session_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM users'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE users');
	mysql_query('OPTIMIZE TABLE users');
}

//== подготовка массива со списком пользователей
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM users'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('types_report' => getListTypes($row['id_type_report'], '(відсутній)'),
											'department' => getListDepartmentsFull($row['id_department'], '(відсутній)'));
	}	
	
	@mysql_free_result($resId);
}

//подготовка строки со списком периодов, отделов
$types_report = getListTypes(0, '(відсутній)');
$departments = getListDepartmentsFull(0, '(відсутній)');

require_once('templates/index.php');
require_once('_stop.php');
?>