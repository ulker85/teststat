<?php
require_once('../lib/func.php');
require_once('../lib/settings.php');
require_once('../lib/_debug.php');

$ERROR_MSG = '';

session_name('stat');
session_start();

connectDB();

//подготовка массива с данными про организацию
// Готовим условия отбора
$where = array();
$where[] = 'organizations.id='.$_SESSION['edrpou'];
$where[] = 'periods.number='.date('m');
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$str_query = 'SELECT organizations.edrpou, organizations.name, periods.name_rod'
				.' FROM organizations, periods'
				.$whereStr
				.' LIMIT 1';
$resId = mysql_query($str_query);

$listHeaders = array();
if ($resId) {	
	$listHeaders = mysql_fetch_assoc($resId);
	@mysql_free_result($resId);
} else {
	$str = 'Помилка в запиті';
	traceMsg($str.': '.$str_query);
	$ERROR_MSG .= $str.'<br />';
}

//подготовка массива с данными для таблицы
// Готовим условия отбора
$where = array();
$where[] = 'organizations.id='.$_SESSION['edrpou'];
$where[] = 'MONTH(charts.num_srok)='.date('m');
$where[] = 'YEAR(charts.num_srok)='.date('Y');
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$str_query = 'SELECT reports.date_first'
							.', charts.num_srok'
							.', periods_report.name AS period_report'
							.', forms.name_full, forms.name AS name_short'
							.', periods_form.name AS period'
				.' FROM reports'
					.' LEFT JOIN charts'
						.' ON reports.id_form=charts.id_form AND reports.id_period_report=charts.id_period_report'
					.' LEFT JOIN periods_report'
						.' ON reports.id_period_report=periods_report.id'
					.' LEFT JOIN forms'
						.' ON reports.id_form=forms.id'
					.' LEFT JOIN periods_form'
						.' ON forms.id_period_form=periods_form.id'
					.' LEFT JOIN organizations'
						.' ON reports.id_organization=organizations.id'
				.$whereStr;
$resId = mysql_query($str_query);

$listItems = array();
if ($resId) {	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('date' => ($row['date_first']<>0) ? phpDateShort($row['date_first']) : '-');
	}
	@mysql_free_result($resId);
} else {
	$ERROR_MSG .= 'Помилка в запиті<br />';
}

require_once('templates/tabel.php');
require_once('expert/_stop.php');
?>