<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO periods'
						.' (name, number, name_rod, name_misc)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['name'])).'"'
						.', '.addslashes(stripslashes($_POST['number']))
						.', "'.addslashes(stripslashes($_POST['nameRod'])).'"'
						.', "'.addslashes(stripslashes($_POST['nameMisc'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE periods'
							.' SET name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
								.', number='.addslashes(stripslashes($_POST["number_$v"]))
								.', name_rod="'.addslashes(stripslashes($_POST["nameRod_$v"])).'"'
								.', name_misc="'.addslashes(stripslashes($_POST["nameMisc_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}

//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM periods'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE periods');
	mysql_query('OPTIMIZE TABLE periods');
}

//== подготовка массива со списком пользователей
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM periods'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();

	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/periods.php');
require_once('_stop.php');
?>