<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO years'
						.' (name, name_short)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['name'])).'"'
						.', "'.addslashes(stripslashes($_POST['short'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE years'
							.' SET name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
								.', name_short="'.addslashes(stripslashes($_POST["short_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}

//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM years'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE years');
	mysql_query('OPTIMIZE TABLE years');
}

//== подготовка массива со списком пользователей
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM years'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();

	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/years.php');
require_once('_stop.php');
?>