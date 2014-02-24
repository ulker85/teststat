<?php
require_once('_start.php');

$filtr_d_s = isset($_POST['filtr_d_s']) ? $_POST['filtr_d_s'] : '';      //date start
$filtr_d_e = isset($_POST['filtr_d_e']) ? $_POST['filtr_d_e'] : '';     //date end
$filtr_g = isset($_POST['filtr_g']) ? $_POST['filtr_g'] : 2;               //given
$filtr_inT = isset($_POST['filtr_inT']) ? $_POST['filtr_inT'] : 2;        //in time
$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : 0;                 //forms
$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;               //period reports
$filtr_s = isset($_POST['filtr_s']) ? $_POST['filtr_s'] : 2;                //not in sukup
$filtr_t = isset($_POST['filtr_t']) ? $_POST['filtr_t'] : 0;                //type
$filtr_r = isset($_POST['filtr_r']) ? $_POST['filtr_r'] : 0;                 //territories
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : 1;                 //years

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

if ($action) {
	//== подготовка массива со списком организаций, форм, отделов...
	// Готовим условия отбора
	$where = array();
	$where[] = 'r.id_form='.$filtr_f;
	$where[] = 'y.id='.$filtr_y;
	
	if ($filtr_d_s) {
		if (sqlDateShort($filtr_d_s)) {
			$where[] = 'r.date_first>="'.sqlDateShort($filtr_d_s).' 00:00:00"';
			$filtr_d_s = phpDateShort($filtr_d_s);
		} else
			$ERROR_MSG .= 'Введено некорректну дату. "<em>Починаючи з: '.$filtr_d_s.'</em>"';
	}
		
	if ($filtr_d_e) {
		if (sqlDateShort($filtr_d_e)) {
			$where[] = 'r.date_first<="'.sqlDateShort($filtr_d_e).' 23:59:59"';
			$filtr_d_e = phpDateShort($filtr_d_e);
			
			if (!$filtr_d_s) $where[] = 'r.date_first<>0';
		} else
			$ERROR_MSG .= 'Введено некорректну дату: "<em>Закінчуючи: '.$filtr_d_e.'</em>"';
	}
	
	if ($filtr_g == 0) {
		$where[] = 'r.date_first=0';
	} else if ($filtr_g == 1) {
		$where[] = 'r.date_first<>0';
	}
	
	if ($filtr_inT == 0) {
		$where[] = 'r.date_first>c.num_srok';
		$where[] = 'r.date_first<>0';
	} else if ($filtr_inT == 1) {
		$where[] = 'r.date_first<=c.num_srok';
		$where[] = 'r.date_first<>0';
	}
	
	if ($filtr_p != 0) $where[] = 'r.id_period_report='.$filtr_p;
	if ($filtr_s != 2) $where[] = 'r.not_in_sukup='.$filtr_s;
	if ($filtr_t != 0) $where[] = 'r.id_type_first='.$filtr_t;
	if ($filtr_r != 0) $where[] = 'org.ter='.$filtr_r;
	
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
}

//формируем выборку для вывода на экран
if ($action == 'show') {
	// Выборка по условиям
	$str_query = 'SELECT org.edrpou, org.name, org.ter'
						.', r.id_period_report AS id_period, pr.name AS period'
						.', r.not_in_sukup'
						.', r.date_first AS d_f, tr_f.name AS t_f, u_f.login AS u_f'
						.', r.date_second AS d_s, tr_s.name AS t_s, u_s.login AS u_s'
						.', c.num_srok'
					.' FROM reports AS r'
						.' LEFT JOIN organizations AS org'
							.' ON r.id_organization=org.id'
						.' LEFT JOIN forms AS f'
							.' ON r.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN periods_report AS pr'
							.' ON r.id_period_report=pr.id'
						.' LEFT JOIN years AS y'
							.' ON pr.id_year=y.id'
						.' LEFT JOIN types_report AS tr_f'
							.' ON r.id_type_first=tr_f.id'
						.' LEFT JOIN types_report AS tr_s'
							.' ON r.id_type_second=tr_s.id'
						.' LEFT JOIN users AS u_f'
							.' ON r.id_user_first=u_f.id'
						.' LEFT JOIN users AS u_s'
							.' ON r.id_user_second=u_s.id'
						.' LEFT JOIN charts AS c'
							.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
					.$whereStr
					.' ORDER BY org.edrpou, pr.id';
	$resId = mysql_query($str_query);
	
	if ($resId) {	
		$listItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {		
			$nameRow = '<strong>'.$row['edrpou'].'</strong><br />Територія: '.$row['ter'].'<br />'.$row['name'];
			
			if (!isset($listItems[$nameRow])) $listItems[$nameRow] = array();
			$listItems[$nameRow][$row['id_period']] = array(
				'period' => $row['period'],
				'date_f' => $row['d_f'],
				'type_f' => $row['t_f'],
				'user_f' => $row['u_f'],
				'date_s' => $row['d_s'],
				'type_s' => $row['t_s'],
				'user_s' => $row['u_s'],
				'srok' => $row['num_srok']
				);
		}
		@mysql_free_result($resId);
	} else {
		$ERROR_MSG .= 'Помилка в запиті<br />';
	}
	
	//**************************
	$limit = getFormValue('limit', 50, 'reports');
	$limitstart = getFormValue('limitstart', 0, 'reports');
	
	$pageItems = array();
	$pagePeriods = array();
	$cnt = 0;
	foreach ($listItems as $k => $v) {
		if ($cnt++ < $limitstart) continue;
		if ($cnt > $limitstart+$limit) break;
		$pageItems[$k] = $v;
		foreach ($v as $k1 => $v1) {
			$pagePeriods[$k1] = $v1['period'];
		}
	}
	ksort($pagePeriods);
	
	$total = count($listItems);
	$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
	//**************************
	
//формируем выборку для вывода в файл
} else if ($action == 'exp_xls') {
	//== раcкомментировать, если файл не будет загружаться	
	/*
	header('Content-Type: application/force-download');
	header('Content-Type: application/octet-stream');
	header('Content-Type: application/download');
	*/

	//== стандартный заголовок, которого обычно хватает
	header('Content-Type: text/x-csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.date('d-m-Y').'-export.xls');
	header('Content-Transfer-Encoding: binary');

	//== шапка страницы
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
							.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
						.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
						.'<head>'
						.'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'
						.'<title>Export to excel</title>'
						.'</head>'
						.'<body>';
	
	//== заголовок таблицы
	//Выборка по условиям
	$str_query = 'SELECT f.name AS form, pf.name AS f_period'
					.' FROM forms AS f'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
					.' WHERE f.id='.$filtr_f
					.' LIMIT 1';
	$resId = mysql_query($str_query);

	if ($resId) {	
		$listItems = mysql_fetch_assoc($resId);
		@mysql_free_result($resId);
	}
	//Выборка по условиям
	$str_query = 'SELECT y.name AS year'
					.' FROM years AS y'
					.' WHERE y.id='.$filtr_y
					.' LIMIT 1';
	$resId = mysql_query($str_query);

	if ($resId) {	
		$row = mysql_fetch_assoc($resId);
		@mysql_free_result($resId);
	}	
	
	echo '<strong>Динаміка подання звітності'
						.' за '.$row['year'].' рік'
						.' у розрізі форми '.$listItems['form'].' ('.$listItems['f_period'].')'
						.'</strong>'
						.'<br /><br />';
	
	//== построение таблицы с даными
	echo '<table border="1">';
	//== шапка таблицы
	echo '<tr>';
	echo '<th colspan="3">Дані по організації</th>';
	echo '<th rowspan="2">Період</th>';
	echo '<th colspan="3">Подання первинного звіту</th>';
	echo '<th colspan="3">Подання коректуючого звіту</th>';
	echo '<th rowspan="2">Поза сукупністю</th>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<th>ЄДРПОУ</th>';
	echo '<th>Назва організації</th>';
	echo '<th>Код території</th>';
	echo '<th>Дата</th>';
	echo '<th>Спосіб</th>';
	echo '<th>Користувач</th>';		
	echo '<th>Дата</th>';
	echo '<th>Спосіб</th>';
	echo '<th>Користувач</th>';		
	echo '</tr>';
	
	//== тело таблицы	
	//Выборка по условиям
	$str_query = 'SELECT org.edrpou, org.name, org.ter'
						.', pr.name AS period'
						.', r.not_in_sukup'
						.', r.date_first AS d_f, tr_f.name AS t_f, u_f.login AS u_f'
						.', r.date_second AS d_s, tr_s.name AS t_s, u_s.login AS u_s'
					.' FROM reports AS r'
						.' LEFT JOIN organizations AS org'
							.' ON r.id_organization=org.id'
						.' LEFT JOIN forms AS f'
							.' ON r.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN periods_report AS pr'
							.' ON r.id_period_report=pr.id'
						.' LEFT JOIN years AS y'
							.' ON pr.id_year=y.id'
						.' LEFT JOIN types_report AS tr_f'
							.' ON r.id_type_first=tr_f.id'
						.' LEFT JOIN types_report AS tr_s'
							.' ON r.id_type_second=tr_s.id'
						.' LEFT JOIN users AS u_f'
							.' ON r.id_user_first=u_f.id'
						.' LEFT JOIN users AS u_s'
							.' ON r.id_user_second=u_s.id'
					.$whereStr
					.' ORDER BY org.edrpou, pr.id';
	$resId = mysql_query($str_query);

	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {		
			echo '<tr>';
		
			echo '<td>'.$row['edrpou'].'</td>';
			echo '<td>'.$row['name'].'</td>';
			echo '<td>'.$row['ter'].'</td>';
			echo '<td>'.$row['period'].'</td>';
			echo '<td>'.(($row['d_f'] != 0) ? phpDateFull($row['d_f']) : 'звіт не подано').'</td>';
			echo '<td>'.$row['t_f'].'</td>';
			echo '<td>'.$row['u_f'].'</td>';
			echo '<td>'.(($row['d_s'] != 0) ? phpDateFull($row['d_s']) : '').'</td>';
			echo '<td>'.$row['t_s'].'</td>';
			echo '<td>'.$row['u_s'].'</td>';
			echo '<td align="center">'.(($row['not_in_sukup'] == 1) ? '+' : '&nbsp;').'</td>';
			
			echo '</tr>';			
		}
		@mysql_free_result($resId);
	}
	
	//== закрываем тело таблицы
	echo '</table>';
	//== закрываем тело страницы
	echo '</body>'
		  .'</html>';	
}

//подготовка строки со списком форм
$allForm = getDoubleListTwoForms($filtr_f, '(оберіть форму)');
$allPeriod = getDblListTwoPeriodsR($filtr_p);
$allSukup = getListSukup($filtr_s);
$allRay = getDblListOneTerritories($filtr_r);
$allGiven = getListGiven($filtr_g);
$allType = getListTypes($filtr_t);
$allIntime = getListIntime($filtr_inT);
$allYear = getListIdYears($filtr_y, '');

require_once('templates/an_forms.php');
require_once('_stop.php');
?>