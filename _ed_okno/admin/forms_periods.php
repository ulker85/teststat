<?php
require_once('_start.php');

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : 0;
$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO forms_periods'
						.' (id_form, id_period_report, id_period)'
					.' VALUES ('
						.addslashes(stripslashes($_POST['form']))
						.', '.addslashes(stripslashes($_POST['period_r']))
						.', '.addslashes(stripslashes($_POST['period']))
						.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE forms_periods SET'
								.' id_form='.addslashes(stripslashes($_POST["form_$v"]))
								.', id_period_report='.addslashes(stripslashes($_POST["period_r_$v"]))
								.', id_period='.addslashes(stripslashes($_POST["period_$v"]))
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM forms_periods'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE forms_periods');
	mysql_query('OPTIMIZE TABLE forms_periods');
}

//подготовка массива со списком форм
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM forms_periods'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}
$limit = getFormValue('limit', 50, 'forms_periods');
$limitstart = getFormValue('limitstart', 0, 'forms_periods');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM forms_periods'
				.$whereStr
				." LIMIT $limitstart, $limit";
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('form' => getDoubleListTwoForms($row['id_form'], ''),
											 'period_r' => getDblListTwoPeriodsR($row['id_period_report'], ''),
											 'period' => getListPeriods($row['id_period'], ''));
	}	
	@mysql_free_result($resId);
}

//подготовка строки со списком форм и периодов
$form = getDoubleListTwoForms(0, '');
$period_r = getDblListTwoPeriodsR(0, '');
$period = getListPeriods(0, '');

require_once('templates/forms_periods.php');
require_once('_stop.php');
?>