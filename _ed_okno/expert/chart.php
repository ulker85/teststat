<?php
require_once('_start.php');

if ((date('m') + 1) == 13) {
	$m = 1;
	$y = date('Y') + 1;
} else {
	$m = date('m') + 1;
	$y = date('Y');
}

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : $m;
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : $y;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//��������� ������� ������ "��������"
if ($action == 'write') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'day_' && $v != '') {
			
			if ($_POST['form_'.substr($k, 4)] == 0 || $_POST['period_'.substr($k, 4)] == 0) {
				continue;
			}
			
			if (checkdate($filtr_p, addslashes(stripslashes($v)), $filtr_y)) {
				$str_date = $filtr_y.'-'.$filtr_p.'-'.addslashes(stripslashes($v)).' 23:59:59';
			} else {
				$ERROR_MSG .= '������� ���������� ����: '
						.addslashes(stripslashes($v))
						.'.'.( $filtr_p < 10 ? '0' : '' ).$filtr_p
						.'.'.$filtr_y
						.'.<br />';
				continue;
			}
			
			$query_str = 'SELECT f.name AS form, pf.name AS period'
							.' FROM charts AS c'
							.' LEFT JOIN forms AS f'
								.' ON c.id_form=f.id'
							.' LEFT JOIN periods_form AS pf'
								.' ON f.id_period_form=pf.id'
							.' WHERE c.id_form='.addslashes(stripslashes($_POST['form_'.substr($k, 4)]))
								.' AND MONTH(c.num_srok)='.$filtr_p
								.' AND YEAR(c.num_srok)='.$filtr_y;
			$resId = mysql_query($query_str);
			
			if ($resId) {
				if (mysql_num_rows($resId)) {
					$row = mysql_fetch_assoc($resId);
					$ERROR_MSG .= '����� '.$row['form'].' ('.$row['period'].') ��� �������� �� ��� �����.<br />';
				} else {
					$query_str = 'INSERT INTO charts'
										.' (id_form, id_period_report, num_srok)'
									.' VALUES ('
										.addslashes(stripslashes($_POST['form_'.substr($k, 4)]))
										.', '.addslashes(stripslashes($_POST['period_'.substr($k, 4)]))
										.', "'.$str_date.'"'
										.')';
					mysql_query($query_str);
				}
				@mysql_free_result($resId);
			}
		}
	}

//��������� ������� ������ "�������������"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4)=='txt_' && $v!=0) {
			if (checkdate($filtr_p, addslashes(stripslashes($v)), date('Y'))) {
				$str_date = $filtr_y.'-'.$filtr_p.'-'.addslashes(stripslashes($v)).' 23:59:59';
	
				$query_str = 'UPDATE charts'
								.' SET num_srok="'.$str_date.'"'
								.' WHERE id='.substr($k, 4);
				mysql_query($query_str);
			} else {
				$ERROR_MSG .= '������� ���������� ����: '
					.addslashes(stripslashes($v))
					.'.'.$filtr_p
					.'.'.$filtr_y
					.'.';
			}
		}
	}

//��������� ������� ������ "�������"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM charts'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE charts');
	mysql_query('OPTIMIZE TABLE charts');
	
//��������� ������� ������ "���������"
} else if ($action == 'exp_doc') {
	// ������� ������� ������
	$where = array();
	$where[] = 'number='.$filtr_p;
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	// ������� �� ��������
	$str_query = 'SELECT name, name_misc'
					.' FROM periods'
					.$whereStr
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	
	if ($resId) {
		$month = mysql_fetch_assoc($resId);
		$year = $filtr_y;
		@mysql_free_result($resId);
	}
	
	// ������� ������� ������
	$where = array();
	$where[] = 'MONTH(num_srok)='.$filtr_p;
	$where[] = 'YEAR(num_srok)='.$filtr_y;
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	// ������� �� ��������
	$str_query = 'SELECT c.num_srok, c.id_form'
							.', f.name AS f_name, f.name_full'
							.', pf.name AS period'
							.', d.room_num, d.tel'
					.' FROM charts AS c'
						.' LEFT JOIN forms AS f'
							.' ON c.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN departments AS d'
							.' ON f.id_department=d.id'
					.$whereStr
					.' ORDER BY c.num_srok, f.name';
	$resId = mysql_query($str_query);
	
	if ($resId) {	
		$expListItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {
			
			if (!isset($expListItems[$row['num_srok']])) $expListItems[$row['num_srok']] = array();
			$expListItems[$row['num_srok']][$row['id_form']] = array(
				'form' => '<strong>�. � '.$row['f_name'].' ('.$row['period'].')</strong><br />"'.$row['name_full'].'"',
				'room' => $row['room_num'],
				'phone' => $row['tel']
				);
		}
		@mysql_free_result($resId);
	}
	
	// ��c��������������, ���� ���� �� ����� �����������	
	/*
	header('Content-Type: application/force-download');
	header('Content-Type: application/octet-stream');
	header('Content-Type: application/download');
	*/

	//����������� ���������, �������� ������ �������
	header('Content-Type: text/x-csv; charset=utf-8');
	header('Content-Disposition: attachment;filename='.date('d-m-Y').'-chart.doc');
	header('Content-Transfer-Encoding: binary');

	//����� ��������
	$csv_output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
							.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
						.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
						.'<head>'
						.'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'
						.'<title>Export to word</title>'
						.'</head>'
						.'<body>';
	//��������� �������
	$csv_output .= '<div align="center"><strong>������ ������� ������� ���� �������� �������<br />'
						.' �� '.$month['name'].$year.' ����'
						.'</strong></div>'
						.'<br />';
	//���������� ������� � ������
	$csv_output .= '<table align="center">';
	//����� �������
	$csv_output .= '<tr>';
	$csv_output .= '<th rowspan="2">������ �� ����� �����</th>';
	$csv_output .= '<th rowspan="2">� ������� ��� ������� ������� � ������ ������� ����</th>';
	$csv_output .= '<th colspan="2">��������� ������������</th>';
	$csv_output .= '</tr>';
	
	$csv_output .= '<tr>';
	$csv_output .= '<th>� �������</th>';
	$csv_output .= '<th>� �������� ���������, ������������� �� ���������� �������������</th>';
	$csv_output .= '</tr>';
	//���� �������
	foreach ($expListItems as $k => $v) {
		$csv_output .= '<tr>';
		$csv_output .= '<td colspan="4" align="center">'
							.'<strong>'.dayDate($k).' '.$month['name_misc'].'</strong>'
							.'</td>';
		$csv_output .= '</tr>';
		
		foreach ($v as $value) {
			$csv_output .= '<tr>';
			$csv_output .= '<td>'
								.$value['form']
								.'</td>';
			$csv_output .= '<td>'
								.$value['room_edok']
								.'</td>';
			$csv_output .= '<td>'
								.$value['room']
								.'</td>';
			$csv_output .= '<td>'
								.$value['phone']
								.'</td>';
			$csv_output .= '</tr>';
		}            
	}
	
	$csv_output .= '</table>';
	//��������� ���� ��������
	$csv_output .= '</body>'
						.'</html>';
	
	//�������� � EXCEL - � ������� ��� ������� �����
	echo $csv_output;
}

//���������� ������� �� ������� ����, ������ �� ����� � �.�.
// ������� ������� ������
$where = array();
$where[] = 'MONTH(num_srok)='.$filtr_p;
$where[] = 'YEAR(num_srok)='.$filtr_y;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT c.id, c.num_srok, f.name AS f_name, pf.name AS period, pr.name AS period_r'
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
		$listItems[] = $row + array('form' => $row['f_name'].' ('.$row['period'].')');		
	}
	@mysql_free_result($resId);
}

//-- ���������� ������� �� ������� ���� 
//-- � ��������������� �� ��������
//-- ��� ��������� ����� java script
//===========
// ������� ������� ������
$where = array();
$whereStr = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
// ������� �� ��������
$str_query = 'SELECT f.id AS f_id'
					.', pr.id AS p_id, pr.name AS period, y.name AS year'
				.' FROM forms AS f'
					.' LEFT JOIN forms_periods AS fp'
						.' ON f.id=fp.id_form'
					.' LEFT JOIN periods_report AS pr'
						.' ON fp.id_period_report=pr.id'
					.' LEFT JOIN years AS y'
						.' ON pr.id_year=y.id'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {
	$listForms = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listForms[] = $row;
	}
	@mysql_free_result($resId);
}

//���������� ������ �� ������� ������� + ��� ������
$allPeriods = getListMonths($filtr_p, 0);
$allYears = getListYears($filtr_y, '');

//���������� ������ �� ������� ��������, ����+�������������
$form_name = getDoubleListTwoForms(0, '(������ �����)');

require_once('templates/chart.php');
require_once('_stop.php');
?>