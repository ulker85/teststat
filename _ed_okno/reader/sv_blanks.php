<?php
require_once('_start.php');

$filtr_d_s = isset($_POST['filtr_d_s']) ? $_POST['filtr_d_s'] : '';           //date start
$filtr_d_e = isset($_POST['filtr_d_e']) ? $_POST['filtr_d_e'] : '';          //date end
$filtr_y = isset($_POST['filtr_y']) ? $_POST['filtr_y'] : 1;                 //years

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

if ($action) {
	//== ���������� ������� �� ������� �����������, ����, �������...
	// ������� ������� ������
	$where = array();	
	$where[] = 'b.id_year='.$filtr_y;
	
	if ($filtr_d_s) {
		if (sqlDateShort($filtr_d_s)) {
			$where[] = 'b.date>="'.sqlDateShort($filtr_d_s).'"';
			$filtr_d_s = phpDateShort($filtr_d_s);
		} else
			$ERROR_MSG .= '������� ����������� ����. "<em>��������� �: '.$filtr_d_s.'</em>"';
	}
		
	if ($filtr_d_e) {
		if (sqlDateShort($filtr_d_e)) {
			$where[] = 'b.date<="'.sqlDateShort($filtr_d_e).'"';
			$filtr_d_e = phpDateShort($filtr_d_e);
		} else
			$ERROR_MSG .= '������� ����������� ����: "<em>���������: '.$filtr_d_e.'</em>"';
	}
	
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );	
}

//��������� ������� ��� ������ �� �����
if ($action == 'show') {
	// ----------���-�� �������----------
	$total = 0;
	
	$query_str = 'SELECT count(DISTINCT id_form)'
					.' FROM blanks AS b'
					.$whereStr;
	$resId = mysql_query($query_str);
	
	if ($resId) {	
		$row = mysql_fetch_row($resId);
		$total = $row[0];
		@mysql_free_result($resId);
	}
	
	$limit = getFormValue('limit', 50, 'blanks');
	$limitstart = getFormValue('limitstart', 0, 'blanks');
	$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
	// -----------------------------------
	// ������� �� ��������
	$str_query = 'SELECT sum(b.amount) AS amount'
							.', f.name AS f_name, pf.name AS p_name'
					.' FROM blanks AS b'
						.' LEFT JOIN forms AS f'
							.' ON b.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN years AS y'
							.' ON b.id_year=y.id'
					.$whereStr
					.' GROUP BY b.id_form'
					.' ORDER BY f.name, pf.id'
					." LIMIT $limitstart, $limit";
	$resId = mysql_query($str_query);
	
	if ($resId) {
		$listItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {
			$listItems[] = $row;
		}
		@mysql_free_result($resId);
	} else
		$ERROR_MSG .= '������� � �����<br />';
		
//��������� ������� ��� ������ � ����
} else if ($action == 'exp_xls') {
	//== ��c��������������, ���� ���� �� ����� �����������	
	/*
	header('Content-Type: application/force-download');
	header('Content-Type: application/octet-stream');
	header('Content-Type: application/download');
	*/

	//== ����������� ���������, �������� ������ �������
	header('Content-Type: text/x-csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.date('d-m-Y').'-export.xls');
	header('Content-Transfer-Encoding: binary');

	//== ����� ��������
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
							.' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
						.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'
						.'<head>'
						.'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'
						.'<title>Export to excel</title>'
						.'</head>'
						.'<body>';
	
	//== ��������� �������
	//������� �� ��������
	$str_query = 'SELECT y.name AS year'
					.' FROM years AS y'
					.' WHERE y.id='.$filtr_y
					.' LIMIT 1';
	$resId = mysql_query($str_query);

	if ($resId) {	
		$row = mysql_fetch_assoc($resId);
		@mysql_free_result($resId);
	}	

	echo '<strong>������ ������ �� ������� '.$row['year'].' ����</strong><br /><br />';
	
	//== ���������� ������� � ������
	echo '<table border="1">';
	//== ����� �������
	echo '<tr>';
	echo '<th>����� �����</th>';
	echo '<th>ʳ������ ������� ������</th>';
	echo '</tr>';
		
	//== ���� �������	
	// ������� �� ��������
	$str_query = 'SELECT sum(b.amount) AS amount'
							.', f.name AS f_name, pf.name AS p_name'
					.' FROM blanks AS b'
						.' LEFT JOIN forms AS f'
							.' ON b.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN years AS y'
							.' ON b.id_year=y.id'
					.$whereStr
					.' GROUP BY b.id_form'
					.' ORDER BY f.name, pf.id';
	$resId = mysql_query($str_query);
	
	if ($resId) {
		while ($row = mysql_fetch_assoc($resId)) {
			echo '<tr>';
		
			echo '<td>'.$row['f_name'].' ('.$row['p_name'].')</td>';
			echo '<td>'.$row['amount'].'</td>';
			
			echo '</tr>';			
		}
		@mysql_free_result($resId);
	}
	
	//== ��������� ���� �������
	echo '</table>';
	//== ��������� ���� ��������
	echo '</body>'
		  .'</html>';	
}

//���������� ������ �� ������� ������������
$allYear = getListIdYears($filtr_y, '');

require_once('templates/sv_blanks.php');
require_once('_stop.php');
?>