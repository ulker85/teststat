<?php
require_once('_start.php');

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : date('m');
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : date('Y');

//построение перекрестной таблицы
// Готовим условия отбора
$where = array();	
$where[] = 'MONTH(num_srok)='.$filtr_p;
$where[] = 'YEAR(num_srok)='.$filtr_y;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );	

// Выборка по условиям
$str_query = 'SELECT c.num_srok, c.id_form'
						.', f.name AS f_name, pf.name AS f_period, pr.name AS period'
				.' FROM charts AS c'
					.' LEFT JOIN forms AS f'
						.' ON c.id_form=f.id'
					.' LEFT JOIN periods_form AS pf'
						.' ON f.id_period_form=pf.id'
					.' LEFT JOIN periods_report AS pr'
						.' ON c.id_period_report=pr.id'
				.$whereStr
				.' ORDER BY c.num_srok, f.name';

$resId = mysql_query($str_query);
if ($resId) {
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		if (!isset($listItems[$row['num_srok']])) $listItems[$row['num_srok']] = array();
		$listItems[$row['num_srok']][$row['id_form']] = array(
			'form' => $row['f_name'].' ('.$row['f_period'].')',
			'period' => $row['period']
			);
	}
	@mysql_free_result($resId);
	
} else {
	$ERROR_MSG .= 'Помилка в запиті<br />';
}

//подготовка строки со списком месяцев + Все месяца
$allPeriods = getListMonths($filtr_p, 0);
$allYears = getListYears($filtr_y, '');

require_once('templates/chart.php');
require_once('_stop.php');
?>