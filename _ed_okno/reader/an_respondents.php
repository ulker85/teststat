<?php
require_once('_start.php');

$filtr_d_s = isset($_POST['filtr_d_s']) ? $_POST['filtr_d_s'] : '';           //date start
$filtr_d_e = isset($_POST['filtr_d_e']) ? $_POST['filtr_d_e'] : '';          //date end
$filtr_g = isset($_POST['filtr_g']) ? $_POST['filtr_g'] : 2;                 //given
$filtr_inT = isset($_POST['filtr_inT']) ? $_POST['filtr_inT'] : 2;        //in time
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : '';                 //orgs
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
	$where[] = 'org.edrpou='.$filtr_o;
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
	$str_query = 'SELECT r.id_period_report AS id_period, pr.name AS period'
						.', f.name AS f_name, pf.name AS f_period'
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
					.' ORDER BY f.name, pf.id';
	$resId = mysql_query($str_query);
	
	if ($resId) {
		$listItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {
			$nameRow = $row['f_name'].' ('.$row['f_period'].')';
			
			if (!isset($listItems[$nameRow])) $listItems[$nameRow] = array();
			$listItems[$row['f_name'].' ('.$row['f_period'].')'][$row['id_period']] = array(
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
	
}

//подготовка строки со списком респондентов
$allPeriod = getDblListTwoPeriodsR($filtr_p);
$allSukup = getListSukup($filtr_s);
$allRay = getDblListOneTerritories($filtr_r);
$allGiven = getListGiven($filtr_g);
$allType = getListTypes($filtr_t);
$allIntime = getListIntime($filtr_inT);
$allYear = getListIdYears($filtr_y, '');

require_once('templates/an_respondents.php');
require_once('_stop.php');
?>