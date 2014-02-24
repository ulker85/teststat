<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO periods_report'
						.' (name, id_year)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['name'])).'"'
						.', "'.addslashes(stripslashes($_POST['year'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE periods_report SET'
								.' name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
								.', id_year="'.addslashes(stripslashes($_POST["year_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM periods_report'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE periods_report');
	mysql_query('OPTIMIZE TABLE periods_report');
}

//== подготовка массива со списком периодов форм
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM periods_report'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}
$limit = getFormValue('limit', 50, 'periods_report');
$limitstart = getFormValue('limitstart', 0, 'periods_report');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM periods_report'
				.$whereStr
				." LIMIT $limitstart, $limit";
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('year' => getListIdYears($row['id_year'], ''));
	}
	@mysql_free_result($resId);
}

//подготовка строки со списком годов
$year = getListIdYears(0, '');

require_once('templates/periods_report.php');
require_once('_stop.php');
?>