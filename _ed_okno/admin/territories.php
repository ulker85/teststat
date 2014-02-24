<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO territories'
						.' (id, name)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['ter_id'])).'"'
						.', "'.addslashes(stripslashes($_POST['ter_name'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE territories SET'
								.' id="'.addslashes(stripslashes($_POST["id_$v"])).'"'
								.', name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM territories'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
									
	mysql_query('REPAIR TABLE territories');
	mysql_query('OPTIMIZE TABLE territories');
}

//== подготовка массива со списком пользователей
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM territories'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}

$limit = getFormValue('limit', 50, 'territories');
$limitstart = getFormValue('limitstart', 0, 'territories');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM territories'
				.$whereStr
				." LIMIT $limitstart, $limit";
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