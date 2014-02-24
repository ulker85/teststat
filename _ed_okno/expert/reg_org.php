<?php
require_once('_start.php');

if (!isset($_SESSION['edrpou'])) {
	header('Location: index.php');
	exit;
}

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : date('m');
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : date('Y');
$filtr_y_b = isset($_POST['filtr_y_b']) ? $_POST['filtr_y_b'] : 1;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Записать отчеты"
if ($action == 'write_report') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 7) == 'date_f_') {
			$query_str = 'UPDATE reports SET'
								.' date_first="'.date('Y-m-d H:i:s').'"'
								.', id_type_first='.$_SESSION['type_report']
								.', id_user_first='.$_SESSION[$_ses_expert]
							.' WHERE id='.$v;				
			mysql_query($query_str);
			
		} else if (substr($k, 0, 7) == 'date_s_') {
			$query_str = 'UPDATE reports SET'
								.' date_second="'.date('Y-m-d H:i:s').'"'
								.', id_type_second='.$_SESSION['type_report']
								.', id_user_second='.$_SESSION[$_ses_expert]
							.' WHERE id='.$v;				
			mysql_query($query_str);
			
		} else if (substr($k, 0, 11) == 'date_first_') {
			if ($_POST['form_'.substr($k, 11)] == 0 || $_POST['period_'.substr($k, 11)] == 0) {
				continue;
			}
			
			$query_str = 'SELECT r.id, f.name AS form, pr.name AS period'
							.' FROM reports AS r'
								.' LEFT JOIN forms AS f'
									.' ON r.id_form=f.id'
								.' LEFT JOIN periods_report AS pr'
									.' ON r.id_period_report=pr.id'
							.' WHERE id_organization='.$_SESSION['edrpou']
								.' AND id_form='.addslashes(stripslashes($_POST['form_'.substr($k, 11)]))
								.' AND id_period_report='.addslashes(stripslashes($_POST['period_'.substr($k, 11)]))
							.' LIMIT 1';
			$resId = mysql_query($query_str);
			
			if ($resId) {
				if (mysql_num_rows($resId)) {
					$row = mysql_fetch_assoc($resId);
					$ERROR_MSG .= 'Звіт '.$row['form'].' ('.$row['period'].') вже є у списку.';
					@mysql_free_result($resId);
					continue;
				}
				
				$query_str = 'INSERT INTO reports'
									.' (id_organization, id_form, id_period_report, date_first, id_type_first, id_user_first, not_in_sukup)'
								.' VALUES ('
									.$_SESSION['edrpou']
									.', '.addslashes(stripslashes($_POST['form_'.substr($k, 11)]))
									.', '.addslashes(stripslashes($_POST['period_'.substr($k, 11)]))
									.', "'.date('Y-m-d H:i:s').'"'
									.', '.$_SESSION['type_report']
									.', '.$_SESSION[$_ses_expert]
									.', 1)';
				mysql_query($query_str);
				
				@mysql_free_result($resId);
			}			
		}
	}

//обработка нажатия кнопки "Выдать бланки"
} else if ($action == 'write_blank') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 9) == 'b_amount_' && $v > 0) {
			$query_str = 'INSERT INTO blanks'
								.' (id_organization, date, person, id_form, id_year, amount)'
							.' VALUES ('
								.'"'.addslashes(stripslashes($_SESSION['edrpou'])).'"'
								.', "'.date('Y-m-d').'"'
								.', "'.addslashes(stripslashes($_POST['name_person'])).'"'
								.', "'.( isset($_POST['b_form_'.substr($k, 9)])
																				? addslashes(stripslashes($_POST['b_form_'.substr($k, 9)]))
																				: substr($k, 9) ).'"'
								.', "'.addslashes(stripslashes($filtr_y_b)).'"'
								.', "'.addslashes(stripslashes($_POST['b_amount_'.substr($k, 9)])).'"'
							.')';
			mysql_query($query_str);
		}
	}
}

//-- подготовка массива со списком форм, сроков их сдачи и т.д.
//===========
// Готовим условия отбора
$where = array();
$where[] = 'r.id_organization='.$_SESSION['edrpou'];
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$str_query = 'SELECT r.id, r.date_first, r.date_second, r.not_in_sukup'
							.', f.name AS name_f, f.nakaz'
							.', pf.name AS period_f'
							.', pr.name AS period_r'
							.', y.name_short AS year'
							.', c.num_srok'
							.', tr_f.name AS type_f, tr_s.name AS type_s'
				.' FROM reports AS r'
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
					.' LEFT JOIN charts AS c'
						.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
				.$whereStr
				.' ORDER BY f.name, pf.id, pr.id';
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	$listItemsNot = array();
	$countItems = 0;
	
	while ($row = mysql_fetch_assoc($resId)) {		
		if ( (($filtr_p == monthDate($row['num_srok']) || $filtr_p == 0) && $filtr_y == yearFullDate($row['num_srok'])) || !isset($row['num_srok'])) {
			if ($row['not_in_sukup'] != 1)
				$listItems[] = $row + array('form' => $row['name_f'].' ('.$row['period_f'].')',
													 'period' => $row['period_r'].' '.$row['year']);
			else
				$listItemsNot[] = $row + array('form' => $row['name_f'].' ('.$row['period_f'].')',
														 'period' => $row['period_r'].' '.$row['year']);
			
			$countItems += 1;
		}
	}
	
	@mysql_free_result($resId);
}

//-- подготовка массива со списком форм 
//-- и соответствующих им периодов, наказов и сроков сдачи
//-- для отработки через java script
//===========
// Готовим условия отбора
$where = array();
$whereStr = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
// Выборка по условиям
$str_query = 'SELECT f.id AS f_id, f.nakaz'
					.', pr.id AS p_id, pr.name AS period, y.name AS year'
					.', c.num_srok'
				.' FROM forms AS f'
					.' LEFT JOIN forms_periods AS fp'
						.' ON f.id=fp.id_form'
					.' LEFT JOIN periods_report AS pr'
						.' ON fp.id_period_report=pr.id'
					.' LEFT JOIN years AS y'
						.' ON pr.id_year=y.id'
					.' LEFT JOIN charts AS c'
						.' ON f.id=c.id_form AND pr.id=c.id_period_report'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {
	$listForms = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listForms[] = $row;
	}
	@mysql_free_result($resId);
}

//-- подготовка массива со списком форм и количеством взятых по ним бланков
//===========
// Готовим условия отбора
$where = array();
$where[] = 'r.id_organization='.$_SESSION['edrpou'];
$where[] = 'r.not_in_sukup=0';
$where[] = 'pr.id_year='.$filtr_y_b;
$whereStr = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
// Выборка по условиям
$str_query = 'SELECT r.id_form, f.name AS name_f, pf.name AS period_f'
				.' FROM reports AS r'
					.' LEFT JOIN forms AS f'
						.' ON r.id_form=f.id'
					.' LEFT JOIN periods_form AS pf'
						.' ON f.id_period_form=pf.id'
					.' LEFT JOIN periods_report AS pr'
						.' ON r.id_period_report=pr.id'
				.$whereStr
				.' GROUP BY r.id_form'
				.' ORDER BY f.name';
$resId = mysql_query($str_query);

if ($resId) {	
	$listBlanks = array();
	
	while ($row = mysql_fetch_assoc($resId)) {		
		$listBlanks[] = $row + array('form' => $row['name_f'].' ('.$row['period_f'].')');
	}
	$countBlanks = mysql_num_rows($resId);
	@mysql_free_result($resId);
}
//===========
// Готовим условия отбора
$where = array();
$where[] = 'b.id_organization='.$_SESSION['edrpou'];
$where[] = 'b.id_year='.$filtr_y_b;
$whereStr = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
// Выборка по условиям
$str_query = 'SELECT b.*, f.name AS name_f, pf.name AS period_f'
				.' FROM blanks AS b'
					.' LEFT JOIN forms AS f'
						.' ON b.id_form=f.id'
					.' LEFT JOIN periods_form AS pf'
						.' ON f.id_period_form=pf.id'
					.' LEFT JOIN years AS y'
						.' ON b.id_year=y.id'
				.$whereStr
				.' ORDER BY b.date DESC, f.name, b.person ASC';
$resId = mysql_query($str_query);

if ($resId) {
	$listBlanksOut = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		if (!isset($listBlanksOut[$row['date']])) $listBlanksOut[$row['date']] = array();	
		if (!isset($listBlanksOut[$row['date']][$row['person']])) $listBlanksOut[$row['date']][$row['person']] = array();
		
		$listBlanksOut[$row['date']][$row['person']][$row['id_form']]['form'] = $row['name_f'];
		$listBlanksOut[$row['date']][$row['person']][$row['id_form']]['period_f'] = $row['period_f'];
		$listBlanksOut[$row['date']][$row['person']][$row['id_form']]['amount'] += $row['amount'];
	}
	@mysql_free_result($resId);
}

//подготовка строки со списком месяцев + Все месяца
$allPeriods = getListMonthsR($filtr_p, 'протягом року');
$allYears = getListYears($filtr_y, '');
$allYears_b = getListIdYears($filtr_y_b, '');

//подготовка строки со списком периодов, форм+периодичность
$form = getDoubleListTwoForms(0, '(оберіть форму)');

require_once('templates/reg_org.php');
require_once('_stop.php');
?>