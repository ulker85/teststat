<?php
require_once('_start.php');

$filtr_d_s = isset($_POST['filtr_d_s']) ? $_POST['filtr_d_s'] : '';
$filtr_d_e = isset($_POST['filtr_d_e']) ? $_POST['filtr_d_e'] : '';
$filtr_t_s = isset($_POST['filtr_t_s']) ? $_POST['filtr_t_s'] : '';
$filtr_t_e = isset($_POST['filtr_t_e']) ? $_POST['filtr_t_e'] : '';

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

if ($action == 'show') {
	//считаем отчеты
	for ($i=1; $i<=2; $i++) {
		$name_field = ($i == 1 ? 'date_first' : 'date_second');
		
		// Готовим условия отбора
		$where = array();
		$where[] = "r.$name_field<>0";
		
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
	
		// Выборка по условиям
		$str_query = 'SELECT count(r.id) AS cnt'
						.' FROM reports AS r'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' LIMIT 1';
		$resId = mysql_query($str_query);
		
		if ($resId) {			
			while ($row = mysql_fetch_assoc($resId)) {
				if ($i == 1)
					$report_f = $row['cnt'];
				else
					$report_s = $row['cnt'];
			}
			@mysql_free_result($resId);
		}
		
		// Выборка по условиям
		$str_query = 'SELECT r.id'
						.' FROM reports AS r'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' GROUP BY r.id_organization';
		$resId = mysql_query($str_query);
		
		if ($resId) {			
			if ($i == 1)
				$resondents_f = mysql_num_rows($resId);
			else
				$resondents_s = mysql_num_rows($resId);
			
			@mysql_free_result($resId);
		}
		
		// Выборка по условиям
		$str_query = 'SELECT r.id'
						.' FROM reports AS r'
							.' LEFT JOIN charts AS c'
								.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
						.$whereStr
						.' GROUP BY r.id_form';
		$resId = mysql_query($str_query);
		
		if ($resId) {			
			if ($i == 1)
				$forms_f = mysql_num_rows($resId);
			else
				$forms_s = mysql_num_rows($resId);
			
			@mysql_free_result($resId);
		}
	}
	
	//считаем бланки
	// Готовим условия отбора
	$where = array();
	
	if ($filtr_d_s) {
		if (sqlDateShort($filtr_d_s)) {
			$where[] = 'b.date>="'.sqlDateShort($filtr_d_s).'"';
			$filtr_d_s = phpDateShort($filtr_d_s);
		}
	}
	
	if ($filtr_d_e) {
		if (sqlDateShort($filtr_d_e)) {
			$where[] = 'b.date<="'.sqlDateShort($filtr_d_e).'"';
			$filtr_d_e = phpDateShort($filtr_d_e);	
		}
	}
	
	if ($filtr_t_s) {
		if (sqlDateShort($filtr_t_s)) {
			$where[] = 'c.num_srok>="'.sqlDateShort($filtr_t_s).' 23:59:59"';
			$filtr_t_s = phpDateShort($filtr_t_s);
		}
	}
	
	if ($filtr_t_e) {
		if (sqlDateShort($filtr_t_e)) {
			$where[] = 'c.num_srok<="'.sqlDateShort($filtr_t_e).' 23:59:59"';
			$filtr_t_e = phpDateShort($filtr_t_e);	
		}
	}

	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	// Выборка по условиям
	$str_query = 'SELECT sum(b.amount) AS cnt'
					.' FROM blanks AS b'
					.$whereStr
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	
	if ($resId) {			
		while ($row = mysql_fetch_assoc($resId)) {
			$blanks = $row['cnt'];
		}
		@mysql_free_result($resId);
	}
	
	// Выборка по условиям
	$str_query = 'SELECT b.id'
					.' FROM blanks AS b'
					.$whereStr
					.' GROUP BY b.id_organization';
	$resId = mysql_query($str_query);
	
	if ($resId) {			
		$resondents_b = mysql_num_rows($resId);
		@mysql_free_result($resId);
	}
	
	// Выборка по условиям
	$str_query = 'SELECT b.id'
					.' FROM blanks AS b'
					.$whereStr
					.' GROUP BY b.id_form';
	$resId = mysql_query($str_query);
	
	if ($resId) {			
		$forms_b = mysql_num_rows($resId);
		@mysql_free_result($resId);
	}


} else if ($action == 'exp_xls_b') {
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
	echo '<strong>Загальна статистика по виданих бланках</strong><br /><br />';
	
	//== построение таблицы с даными
	echo '<table border="1">';
	//== шапка таблицы
	echo '<tr>';
	echo '<th rowspan="2">ЄДРПОУ</th>';
	echo '<th rowspan="2">Форма</th>';
	echo '<th rowspan="2">Період</th>';
	echo '<th colspan="3">Подання первинного звіту</th>';
	echo '<th colspan="3">Подання коректуючого звіту</th>';
	echo '<th rowspan="2">Поза сукупністю</th>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<th>Дата</th>';
	echo '<th>Спосіб</th>';
	echo '<th>Користувач</th>';		
	echo '<th>Дата</th>';
	echo '<th>Спосіб</th>';
	echo '<th>Користувач</th>';
	echo '</tr>';
	
	//== тело таблицы	
	// Готовим условия отбора
	$where = array();
	$where[] = 'r.date_first<>0';
	
	if ($filtr_d_s) {
		if (sqlDateShort($filtr_d_s)) {
			$where[] = 'r.date_first>="'.sqlDateShort($filtr_d_s).' 00:00:00"';
			$filtr_d_s = phpDateShort($filtr_d_s);
		}
	}
	
	if ($filtr_d_e) {
		if (sqlDateShort($filtr_d_e)) {
			$where[] = 'r.date_first<="'.sqlDateShort($filtr_d_e).' 23:59:59"';
			$filtr_d_e = phpDateShort($filtr_d_e);		
		}
	}
	
	if ($filtr_t_s) {
		if (sqlDateShort($filtr_t_s)) {
			$where[] = 'c.num_srok>="'.sqlDateShort($filtr_t_s).' 23:59:59"';
			$filtr_t_s = phpDateShort($filtr_t_s);
		}
	}
	
	if ($filtr_t_e) {
		if (sqlDateShort($filtr_t_e)) {
			$where[] = 'c.num_srok<="'.sqlDateShort($filtr_t_e).' 23:59:59"';
			$filtr_t_e = phpDateShort($filtr_t_e);	
		}
	}

	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	// Выборка по условиям
	$str_query = 'SELECT org.edrpou'
					.', f.name AS f_name, pf.name AS p_name'
					.', pr.name AS period, y.name AS year'
					.', r.date_first, tf.name AS type_f, uf.login AS user_f'
					.', r.date_second, ts.name AS type_s, us.login AS user_s'
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
						.' LEFT JOIN types_report AS tf'
							.' ON r.id_type_first=tf.id'
						.' LEFT JOIN types_report AS ts'
							.' ON r.id_type_second=ts.id'
						.' LEFT JOIN users AS uf'
							.' ON r.id_user_first=uf.id'
						.' LEFT JOIN users AS us'
							.' ON r.id_user_second=us.id'
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
			echo '<td>'.phpDateFull($row['date_first']).'</td>';
			echo '<td>'.$row['type_f'].'</td>';
			echo '<td>'.$row['user_f'].'</td>';
			echo '<td>'.(($row['date_second'] != 0) ? phpDateFull($row['date_second']) : '').'</td>';
			echo '<td>'.$row['type_s'].'</td>';
			echo '<td>'.$row['user_s'].'</td>';
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

require_once('templates/sv_general.php');
require_once('_stop.php');
?>