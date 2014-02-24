<?php
require_once('_start.php');

$choice = isset($_POST['choice']) ? $_POST['choice'] : 'first';

$filtr_d_s = isset($_POST['filtr_d_s']) ? $_POST['filtr_d_s'] : '';
$filtr_d_e = isset($_POST['filtr_d_e']) ? $_POST['filtr_d_e'] : '';
$filtr_t_s = isset($_POST['filtr_t_s']) ? $_POST['filtr_t_s'] : '';
$filtr_t_e = isset($_POST['filtr_t_e']) ? $_POST['filtr_t_e'] : '';
$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : 0;
$filtr_d = isset($_POST['filtr_d']) ? $_POST['filtr_d'] : 0;
$filtr_r = isset($_POST['filtr_r']) ? $_POST['filtr_r'] : 0;
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : 1;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

if ($action) {
	if ($choise == 'first')
		$name_field = 'date_first';
	else if ($choise == 'second')
		$name_field = 'date_second';
	
	// Готовим условия отбора
	$where = array();
	$where[] = 'y.id='.$filtr_y;
	
	if ($filtr_f != 0) $where[] = 'r.id_form='.$filtr_f;
	if ($filtr_d != 0) $where[] = 'd.id='.$filtr_d;
	if ($filtr_r != 0) $where[] = 'org.ter='.$filtr_r;
	
	if ($filtr_d_s) {
		if (sqlDateShort($filtr_d_s)) {
			$where[] = "r.$name_field>='".sqlDateShort($filtr_d_s)." 00:00:00'";
			$filtr_d_s = phpDateShort($filtr_d_s);
		} else
			$ERROR_MSG .= 'Введено некорректну дату: "<em>Починаючи з: '.$filtr_d_s.'</em>"';
	}
	
	if ($filtr_d_e) {
		if (sqlDateShort($filtr_d_e)) {
			$where[] = "r.$name_field<='".sqlDateShort($filtr_d_e)." 23:59:59'";
			$filtr_d_e = phpDateShort($filtr_d_e);
				
			if (!$filtr_d_s) $where[] = "r.$name_field<>0";
	
		} else
			$ERROR_MSG .= 'Введено некорректну дату: "<em>Закінчуючи: '.$filtr_d_e.'</em>"';
	}
	
		if ($filtr_t_s) {
		if (sqlDateShort($filtr_t_s)) {
			$where[] = 'c.num_srok>="'.sqlDateShort($filtr_t_s).' 23:59:59"';
			$filtr_t_s = phpDateShort($filtr_t_s);
		} else
			$ERROR_MSG .= 'Введено некорректну дату: "<em>Починаючи з: '.$filtr_t_s.'</em>"';
	}
	
	if ($filtr_t_e) {
		if (sqlDateShort($filtr_t_e)) {
			$where[] = 'c.num_srok<="'.sqlDateShort($filtr_t_e).' 23:59:59"';
			$filtr_t_e = phpDateShort($filtr_t_e);	
		} else
			$ERROR_MSG .= 'Введено некорректну дату: "<em>Закінчуючи: '.$filtr_t_e.'</em>"';
	}
	
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
}

if ($action == 'show') {
	//-- заголовки столбцов таблицы
	$listColumns = array();
	
	$listColumns[] = 'Форма, періодичність, період';
	$listColumns[] = 'Загальна сукупність';
	$listColumns[] = 'Усього прийнято';
	
	//способы принятия
	$str_query = 'SELECT name'
					.' FROM types_report'
					.' ORDER BY id';
	$resId = mysql_query($str_query);
	
	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {
			$listColumns[]= $row['name'];
		}
		@mysql_free_result($resId);
	}
	
	//принадлежность к совокупности
	$listColumns[] = 'за сукупністю';
	$listColumns[] = 'поза сукупністю';
	
	//своевременность подачи
	$listColumns[] = 'вчасно';
	$listColumns[] = 'запізно';
	
	//------------------------------------
	//-- заголовки строк 
	//-- тело таблицы
	// Выборка по условиям
	if ($choice == 'first')
		$str_query = 'SELECT r.id_form, r.id_period_report'
									.', f.name AS f_name, pf.name AS f_period, pr.name AS period'
									.', tr.name AS type'
									.', (CASE r.not_in_sukup
											WHEN 0 THEN "за сукупністю"
											ELSE "поза сукупністю"
										END) AS not_in_sukup'
									.', (CASE 
											WHEN r.date_first<=c.num_srok OR c.num_srok IS NULL THEN "вчасно"
											ELSE "запізно"
										END) AS srok'
									.', count(r.id) AS cnt'
						.' FROM reports AS r'
							.' LEFT JOIN forms AS f'
								.' ON r.id_form=f.id'
							.' LEFT JOIN periods_form AS pf'
								.' ON f.id_period_form=pf.id'
							.' LEFT JOIN departments AS d'
								.' ON f.id_department=d.id'
							.' LEFT JOIN periods_report AS pr'
								.' ON r.id_period_report=pr.id'
							.' LEFT JOIN years AS y'
								.' ON pr.id_year=y.id'
							.' LEFT JOIN types_report AS tr'
								.' ON r.id_type_first=tr.id'
							.' LEFT JOIN organizations AS org'
								.' ON r.id_organization=org.id'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' GROUP BY r.id_form, r.id_period_report, r.id_type_first, r.not_in_sukup, srok'
						.' ORDER BY f.name, pf.name, r.id_period_report, r.id_type_first';
	else if ($choice == 'second')
		$str_query = 'SELECT r.id_form, r.id_period_report'
									.', f.name AS f_name, pf.name AS f_period, pr.name AS period'
									.', tr.name AS type'
									.', (CASE r.not_in_sukup
											WHEN 0 THEN "за сукупністю"
											ELSE "поза сукупністю"
										END) AS not_in_sukup'
									.', (CASE 
											WHEN r.date_second<=c.num_srok OR c.num_srok IS NULL THEN "вчасно"
											ELSE "запізно"
										END) AS srok'
									.', count(r.id) AS cnt'
						.' FROM reports AS r'
							.' LEFT JOIN forms AS f'
								.' ON r.id_form=f.id'
							.' LEFT JOIN periods_form AS pf'
								.' ON f.id_period_form=pf.id'
							.' LEFT JOIN departments AS d'
								.' ON f.id_department=d.id'
							.' LEFT JOIN periods_report AS pr'
								.' ON r.id_period_report=pr.id'
							.' LEFT JOIN years AS y'
								.' ON pr.id_year=y.id'
							.' LEFT JOIN types_report AS tr'
								.' ON r.id_type_second=tr.id'
							.' LEFT JOIN organizations AS org'
								.' ON r.id_organization=org.id'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' GROUP BY r.id_form, r.id_period_report, r.id_type_second, r.not_in_sukup, srok'
						.' ORDER BY f.name, pf.name, r.id_period_report, r.id_type_second';
	$resId = mysql_query($str_query);
	
	if ($resId) {	
		$listItems = array();
		$listResults = array();
		
		while ($row = mysql_fetch_assoc($resId)) {
			if (!isset($listItems[$row['id_form']])) {
				$listItems[$row['id_form']] = array();
			}
			
			if (!isset($listItems[$row['id_form']][0])) {
				$listItems[$row['id_form']][0] = array();
				$listItems[$row['id_form']][0]['name'] = '<strong>'.$row['f_name'].' ('.$row['f_period'].')</strong>';
			} 
			if (!isset($listItems[$row['id_form']][$row['id_period_report']])) {
				$listItems[$row['id_form']][$row['id_period_report']] = array();
				$listItems[$row['id_form']][$row['id_period_report']]['name'] = '<span style="margin-left:14px;">- '.$row['period'].'</span>';
			}
										
			if ($row['type'] == '') {
				if ($filtr_d_s == '' && $filtr_d_e == '' && $filtr_r == 0) {
					$listItems[$row['id_form']][0][$listColumns[1]] += $row['cnt'];
					$listItems[$row['id_form']][$row['id_period_report']][$listColumns[1]] += $row['cnt'];
					$listResults[$listColumns[1]] += $row['cnt'];
				}
			} else {
				$listItems[$row['id_form']][0][$listColumns[2]] += $row['cnt'];
				$listItems[$row['id_form']][$row['id_period_report']][$listColumns[2]] += $row['cnt'];
				$listResults[$listColumns[2]] += $row['cnt'];
				
				$listItems[$row['id_form']][0][$row['not_in_sukup']] += $row['cnt'];
				$listItems[$row['id_form']][$row['id_period_report']][$row['not_in_sukup']] += $row['cnt'];
				$listResults[$row['not_in_sukup']] += $row['cnt'];
				
				$listItems[$row['id_form']][0][$row['type']] += $row['cnt'];
				$listItems[$row['id_form']][$row['id_period_report']][$row['type']] += $row['cnt'];
				$listResults[$row['type']] += $row['cnt'];
				
				$listItems[$row['id_form']][0][$row['srok']] += $row['cnt'];
				$listItems[$row['id_form']][$row['id_period_report']][$row['srok']] += $row['cnt'];
				$listResults[$row['srok']] += $row['cnt'];
	
				if ($row['not_in_sukup'] == 'за сукупністю') {
						if ($filtr_d_s == '' && $filtr_d_e == '' && $filtr_r == 0) {
							$listItems[$row['id_form']][0][$listColumns[1]] += $row['cnt'];
							$listItems[$row['id_form']][$row['id_period_report']][$listColumns[1]] += $row['cnt'];
							$listResults[$listColumns[1]] += $row['cnt'];
						}
				}		
			}		
		}
		
		@mysql_free_result($resId);
		
		//пустая строка в таблице
		$listItems['']['']['name'] = '&nbsp;';
		
		//итоговая строка в таблице
		$listItems[0][0]['name'] = '<span style="font-size:9pt; font-weight:bold;">Усього</span>';
		foreach ($listResults as $k => $v) {
			$listItems[0][0][$k] = '<span style="font-size:9pt">'.$v.'</span>';
		}	
	}

} else if ($action == 'exp_xls') {
	set_time_limit(300);
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
	echo '<strong>Загальна статистика по зібраних звітах</strong><br /><br />';
	
	//== построение таблицы с даными
	echo '<table border="1">';
	//== шапка таблицы
	echo '<tr>';
	echo '<th>ЄДРПОУ</th>';
	echo '<th>Форма</th>';
	echo '<th>Період</th>';
	echo '<th>Дата</th>';
	echo '<th>Спосіб</th>';
	echo '<th>Користувач</th>';		
	echo '<th>Поза сукупністю</th>';
	echo '</tr>';
	
	//== тело таблицы	
	// Выборка по условиям
	if ($choice == 'first')
		$str_query = 'SELECT org.edrpou'
						.', f.name AS f_name, pf.name AS p_name'
						.', pr.name AS period, y.name_short AS year'
						.', r.date_first AS date, tr.name AS type, u.login AS user'
						.', r.not_in_sukup'
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
							.' LEFT JOIN types_report AS tr'
								.' ON r.id_type_first=tr.id'
							.' LEFT JOIN users AS u'
								.' ON r.id_user_first=u.id'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' ORDER BY org.edrpou, f.name, p_name, y.name, pr.name';
	else
		$str_query = 'SELECT org.edrpou'
						.', f.name AS f_name, pf.name AS p_name'
						.', pr.name AS period, y.name_short AS year'
						.', r.date_second AS date, tr.name AS type, u.login AS user'
						.', r.not_in_sukup'
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
							.' LEFT JOIN types_report AS tr'
								.' ON r.id_type_second=tr.id'
							.' LEFT JOIN users AS u'
								.' ON r.id_user_second=u.id'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' ORDER BY org.edrpou, f.name, p_name, y.name, pr.name';
	$resId = mysql_query($str_query);

	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {		
			echo '<tr>';
		
			echo '<td>'.$row['edrpou'].'</td>';
			echo '<td>'.$row['f_name'].' ('.$row['p_name'].')</td>';
			echo '<td>'.$row['period'].' '.$row['year'].'</td>';
			echo '<td>'.(($row['date'] != 0) ? phpDateFull($row['date']) : '').'</td>';
			echo '<td>'.$row['type'].'</td>';
			echo '<td>'.$row['user'].'</td>';
			echo '<td align="center">'.(($row['not_in_sukup'] == 1) ? '+' : '').'</td>';
			
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

//подготовка строки со списком отделов
$allForm = getDoubleListTwoForms($filtr_f);
$allDepartment = getListDepartments($filtr_d);
$allRay = getDblListOneTerritories($filtr_r);
$allYear = getListIdYears($filtr_y, '');

require_once('templates/sv_reports.php');
require_once('_stop.php');
?>