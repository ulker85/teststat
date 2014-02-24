<?php
require_once('_start.php');

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : 0;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//подготовка массива со списком форм
// Готовим условия отбора
$where = array();
if ($filtr_p!=0) $where[] = 'id_period_form='.$filtr_p;
if ($filtr_o!=0) $where[] = 'id_department='.$filtr_o;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM forms'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}
$limit = getFormValue('limit', 50, 'forms');
$limitstart = getFormValue('limitstart', 0, 'forms');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$query_str = 'SELECT f.*, pf.name AS period, d.name AS department'
				.' FROM forms AS f'
					.' LEFT JOIN periods_form AS pf'
						.' ON f.id_period_form=pf.id'
					.' LEFT JOIN departments AS d'
						.' ON f.id_department=d.id'
				.$whereStr
				.' ORDER BY f.name'
				." LIMIT $limitstart, $limit";
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}	
	@mysql_free_result($resId);
}

//формирование ссылок для бланков и инструкций
//для вывода респондентам
function makeLink ($strLink, $comment) {
	$prepStr = '';
	$masStrLink = explode(';', $strLink);
	foreach ($masStrLink as $value) {
		$value = trim($value);
		$p = explode('(', $value);
		if ($p[1] <> '') $p[1] = ' '.substr($p[1], 0, strlen($p[1])-1);
		if ($prepStr <> '') $prepStr .= '<br />';
		$prepStr .= '<a href="'.$p[0].'" target="_blank">'.$comment.$p[1].'</a>';
	}
	return $prepStr;
}

//подготовка строки со списком периодов+ Все периоды, отделов + Все отделы
$allPeriod = getListPeriodsForm($filtr_p);
$allOtdel = getListDepartments($filtr_o);

require_once('templates/forms.php');
require_once('_stop.php');
?>