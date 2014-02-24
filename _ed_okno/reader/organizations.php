<?php
require_once('_start.php');

$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : '';
$filtr_s = isset($_POST['filtr_s']) ? $_POST['filtr_s'] : 2;
$filtr_t = isset($_POST['filtr_t']) ? $_POST['filtr_t'] : '';

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//подготовка массива со списком организаций
// Готовим условия отбора
$where = array();

if ($filtr_o!=0) $where[] = 'edrpou='.$filtr_o;
if ($filtr_s!=2) $where[] = 'fiz_person='.$filtr_s;
if ($filtr_t!=0) $where[] = 'ter='.$filtr_t;

$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

// ----------Кол-во записей----------
$total = 0;

$str_query = 'SELECT count(*)'
				.' FROM organizations'
				.$whereStr;
$resId = mysql_query($str_query);
if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}

$limit = getFormValue('limit', 50, 'organizations');
$limitstart = getFormValue('limitstart', 0, 'organizations');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------

// Выборка по условиям
$str_query = 'SELECT *'
				.' FROM organizations'
				.$whereStr
				.' ORDER BY edrpou'
				." LIMIT $limitstart, $limit";
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

//подготовка строки со списком форм + Все формы
$allSubject = getListSubject($filtr_s);
$allTerritories = getDblListOneTerritories($filtr_t);

require_once('templates/organizations.php');
require_once('_stop.php');
?>