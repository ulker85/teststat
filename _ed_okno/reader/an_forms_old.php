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

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

if ($action) {
	//== подготовка массива со списком организаций, форм, отделов...
	// Готовим условия отбора
	$where = array();
	$where[] = 'r.id_form='.$filtr_f;
	
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
						.', r.id_period_report AS id_period, pr.name AS period, y.name AS year'
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
				'period' => $row['period'].'<br />'.$row['year'],
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
	// Выборка по условиям
	$str_query = 'SELECT org.edrpou, org.name, org.ter'
						.', f.name AS form, pf.name AS f_period'
						.', pr.name AS period, y.name AS year'
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
		$listItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {		
			$listItems[] = $row;
		}
		@mysql_free_result($resId);
	}
	
	// раcкомментировать, если файл не будет загружаться	
	/*
	header('Content-Type: application/force-download');
	header('Content-Type: application/octet-stream');
	header('Content-Type: application/download');
	*/

	//стандартный заголовок, которого обычно хватает
	header('Content-Type: text/x-csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.date('d-m-Y').'-export.xls');
	header('Content-Transfer-Encoding: binary');

	//шапка страницы
	$csv_output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
							.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
						.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
						.'<head>'
						.'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'
						.'<title>Export to excel</title>'
						.'</head>'
						.'<body>';
	$csv_output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
							.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
						.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
						.'<head>'
						.'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'
						.'<title>Export to excel</title>'
						.'</head>'
						.'<body>';
	//заголовок таблицы
	$csv_output .= '<strong>Динаміка подання звітності у розрізі форми '
						.$listItems[0]['form'].' ('.$listItems[0]['f_period'].')'
						.'</strong>'
						.'<br /><br />';
	//построение таблицы с даными
	$csv_output .= '<table border="1">';
	//шапка таблицы
	$csv_output .= '<tr>';
	$csv_output .= '<th colspan="3">Дані по організації</th>';
	$csv_output .= '<th rowspan="2">Період</th>';
	$csv_output .= '<th colspan="3">Подання первинного звіту</th>';
	$csv_output .= '<th colspan="3">Подання коректуючого звіту</th>';
	$csv_output .= '<th rowspan="2">Поза сукупністю</th>';
	$csv_output .= '</tr>';
	
	$csv_output .= '<tr>';
	$csv_output .= '<th>ЄДРПОУ</th>';
	$csv_output .= '<th>Назва організації</th>';
	$csv_output .= '<th>Код території</th>';
	$csv_output .= '<th>Дата</th>';
	$csv_output .= '<th>Спосіб</th>';
	$csv_output .= '<th>Користувач</th>';		
	$csv_output .= '<th>Дата</th>';
	$csv_output .= '<th>Спосіб</th>';
	$csv_output .= '<th>Користувач</th>';		

	$csv_output .= '</tr>';
	//тело таблицы
	foreach ($listItems as $v) {
		$csv_output .= '<tr>';
		
		$csv_output .= '<td>'.$v['edrpou'].'</td>';
		$csv_output .= '<td>'.$v['name'].'</td>';
		$csv_output .= '<td>'.$v['ter'].'</td>';
		$csv_output .= '<td>'.$v['period'].' '.$v['year'].'</td>';
		$csv_output .= '<td>'.(($v['d_f'] != 0) ? phpDateFull($v['d_f']) : 'звіт не подано').'</td>';
		$csv_output .= '<td>'.$v['t_f'].'</td>';
		$csv_output .= '<td>'.$v['u_f'].'</td>';
		$csv_output .= '<td>'.(($v['d_f'] != 0) ? phpDateFull($v['d_s']) : '').'</td>';
		$csv_output .= '<td>'.$v['t_s'].'</td>';
		$csv_output .= '<td>'.$v['u_s'].'</td>';
		$csv_output .= '<td align="center">'.(($v['not_in_sukup'] == 1) ? '+' : '&nbsp;').'</td>';
		
		$csv_output .= '</tr>';
	}
	
	$csv_output .= '</table>';
	//закрываем тело страницы
	$csv_output .= '</body>'
						.'</html>';
	
	//выгрузка в EXCEL - в скрипте как обычный вывод
	//echo $csv_output;
}

//подготовка строки со списком форм
$allForm = getDoubleListTwoForms($filtr_f, '(оберіть форму)');
$allPeriod = getDblListTwoPeriodsR($filtr_p);
$allSukup = getListSukup($filtr_s);
$allRay = getDblListOneTerritories($filtr_r);
$allGiven = getListGiven($filtr_g);
$allType = getListTypes($filtr_t);
$allIntime = getListIntime($filtr_inT);

require_once('templates/an_forms.php');
require_once('_stop.php');
?>