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

//��������� ������� ������ "������������� ������"
if ($action == 'edit_report') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 5) == 'edit_' && $v != 0) {
			$date_f = addslashes(stripslashes($_POST['date_f_'.$v]));
			$date_s = addslashes(stripslashes($_POST['date_s_'.$v]));
			
			$form = isset($_POST['form_'.$v]) ? $_POST['form_'.$v] : '';
			$period = isset($_POST['period_'.$v]) ? $_POST['period_'.$v] : '';
			
			if (!sqlDateShort($date_f) && $date_f != 0) {
				$ERROR_MSG .= '������� ���������� ����: '.$date_f;
				continue;
			}
			
			if (!sqlDateShort($date_s) && $date_s != 0) {
				$ERROR_MSG .= '������� ���������� ����: '.$date_s;
				continue;
			}
			
			$query_str = 'UPDATE reports SET'
								.' date_first="'.sqlDateShort($date_f).date(' H:i:s').'"'
								.', id_type_first='.addslashes(stripslashes($_POST['type_f_'.$v]))
								.', id_user_first='.addslashes(stripslashes($_POST['user_f_'.$v]))
								.', date_second="'.sqlDateShort($date_s).date(' H:i:s').'"'
								.', id_type_second='.addslashes(stripslashes($_POST['type_s_'.$v]))
								.', id_user_second='.addslashes(stripslashes($_POST['user_s_'.$v]))
								.( $form != '' ? ', id_form='.$form : '' )
								.( $period != '' ? ', id_period_report='.$period : '' )
							.' WHERE id='.$v;
			mysql_query($query_str);
		
		} else if (substr($k, 0, 4) == 'add_' && $v != 0) {
			if ($_POST['form_'.$v] == 0 || $_POST['period_'.$v] == 0) {
				continue;
			}
			
			$query_str = 'SELECT r.id, f.name AS form, pr.name AS period'
							.' FROM reports AS r'
								.' LEFT JOIN forms AS f'
									.' ON r.id_form=f.id'
								.' LEFT JOIN periods_report AS pr'
									.' ON r.id_period_report=pr.id'
							.' WHERE id_organization='.$_SESSION['edrpou']
								.' AND id_form='.addslashes(stripslashes($_POST['form_'.$v]))
								.' AND id_period_report='.addslashes(stripslashes($_POST['period_'.$v]))
							.' LIMIT 1';
			$resId = mysql_query($query_str);
			
			if ($resId) {
				if (mysql_num_rows($resId)) {
					$row = mysql_fetch_assoc($resId);
					$ERROR_MSG .= '��� '.$row['form'].' ('.$row['period'].') ��� � � ������.';
					@mysql_free_result($resId);
					continue;
				}
				
				$query_str = 'INSERT INTO reports'
									.' (id_organization, id_form, id_period_report'
										.', date_first, id_type_first, id_user_first'
										.', date_second, id_type_second, id_user_second'
										.', not_in_sukup)'
								.' VALUES ('
									.$_SESSION['edrpou']
									.', '.addslashes(stripslashes($_POST['form_'.$v]))
									.', '.addslashes(stripslashes($_POST['period_'.$v]))
									.', '.sqlDateShort($_POST['date_first_'.$v])
									.', '.addslashes(stripslashes($_POST['type_first_'.$v]))
									.', '.addslashes(stripslashes($_POST['user_first_'.$v]))
									.', '.sqlDateShort($_POST['date_second_'.$v])
									.', '.addslashes(stripslashes($_POST['type_second_'.$v]))
									.', '.addslashes(stripslashes($_POST['user_second_'.$v]))
									.', 1)';
				mysql_query($query_str);
				
				@mysql_free_result($resId);
			}			
		}
	}

//��������� ������� ������ "������������� ������"
} else if ($action == 'edit_blank') {
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

//-- ���������� ������� �� ������� ����������,
//-- ������� ����� ������ �����������
//===========
// ������� ������� ������
$where = array();
$where[] = 'r.id_organization='.$_SESSION['edrpou'];

$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT r.*'
							.', f.name AS name_f, pf.name AS period_f'
							.', pr.name AS period_r, y.name_short AS year'
							.', c.num_srok'
				.' FROM reports AS r'
					.' LEFT JOIN forms AS f'
						.' ON r.id_form=f.id'
					.' LEFT JOIN periods_form AS pf'
						.' ON f.id_period_form=pf.id'
					.' LEFT JOIN periods_report AS pr'
						.' ON r.id_period_report=pr.id'
					.' LEFT JOIN years AS y'
						.' ON pr.id_year=y.id'
					.' LEFT JOIN charts AS c'
						.' ON r.id_form=c.id_form AND r.id_period_report=c.id_period_report'
				.$whereStr
				.' ORDER BY r.not_in_sukup, f.name, pf.id, pr.id';
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	$listItemsNot = array();
	$countItems = 0;
	
	while ($row = mysql_fetch_assoc($resId)) {		
		if ( (($filtr_p == monthDate($row['num_srok']) || $filtr_p == 0) && $filtr_y == yearFullDate($row['num_srok'])) || !isset($row['num_srok'])) {
			if ($row['not_in_sukup'] != 1)
				$listItems[] = $row + array('form' => $row['name_f'].' ('.$row['period_f'].')',
													 'period' => $row['period_r'].' '.$row['year'],
													 'type_f' => getListTypes($row['id_type_first'], '(������ �����)'),
													 'user_f' => getListUsers($row['id_user_first'], '(������ ��������.)'),
													 'type_s' => getListTypes($row['id_type_second'], '(������ �����)'),
													 'user_s' => getListUsers($row['id_user_second'], '(������ ��������.)'));
			else
				$listItemsNot[] = $row + array('form' => getDoubleListTwoForms($row['id_form'], ''),
														  'period' => getDblListTwoPeriodsR($row['id_period_report'], ''),
													 	  'type_f' => getListTypes($row['id_type_first'], '(������ �����)'),
													 'user_f' => getListUsers($row['id_user_first'], '(������ ��������.)'),
													 'type_s' => getListTypes($row['id_type_second'], '(������ �����)'),
													 'user_s' => getListUsers($row['id_user_second'], '(������ ��������.)'));
			
			$countItems += 1;
		}
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

//-- ���������� ������� �� ������� ������ �������
// ������� ������� ������
$where = array();
$where[] = 'b.id_organization='.$_SESSION['edrpou'];
$where[] = 'b.id_year='.$filtr_y_b;
$whereStr = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
// ������� �� ��������
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

//���������� ������ �� ������� ������� + ��� ������
$allPeriods = getListMonthsR($filtr_p, '�������� ����');
$allYears = getListYears($filtr_y, '');
$allYears_b = getListIdYears($filtr_y_b, '');

//���������� ������ �� ������� ��������, ����+�������������
$form = getDoubleListTwoForms(0, '(������ �����)');
$type = getListTypes(0, '(������ �����)');
$user = getListUsers(0, '(������ ��������.)');

require_once('templates/kor_org.php');
require_once('_stop.php');
?>