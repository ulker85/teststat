<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Редактировать"
if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE types_report SET'
								.' name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Добавить"
} else if ($action == 'add') {
	$query_str = 'INSERT INTO types_report'
						.' (name)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['name'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM types_report'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE types_report');
	mysql_query('OPTIMIZE TABLE types_report');
}

//== подготовка массива со списком периодов форм
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM types_report'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/types.php');
require_once('_stop.php');
?>