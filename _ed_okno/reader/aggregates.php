<?php
require_once('_start.php');

$choice = isset($_POST['choice']) ? $_POST['choice'] : 'tabel';
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : '';
$filtr_t = isset($_POST['filtr_t']) ? $_POST['filtr_t'] : '';
$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : 0;
$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;
$filtr_d = isset($_POST['filtr_d']) ? $_POST['filtr_d'] : 0;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//== подготовка массива со списком организаций, форм и отделов
// Готовим условия отбора
$where = array();
if ($filtr_o!=0) $where[] = 'org.edrpou='.$filtr_o;
if ($filtr_t!=0) $where[] = 'org.ter='.$filtr_t;
if ($filtr_f!=0) $where[] = 'f.id='.$filtr_f;
if ($filtr_d!=0) $where[] = 'd.id='.$filtr_d;
if ($filtr_p!=0 && $choice == 'edok') $where[] = 'r.id_period_report='.$filtr_p;

$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

//делаем выборку совокупностей из табеля
if ($choice == 'tabel') {
	// ----------Кол-во записей----------
	$total = 0;
	
	$query_str = 'SELECT count(*)'
					.' FROM krugi AS k'
						.' LEFT JOIN organizations AS org'
							.' ON k.id_organization=org.id'
						.' LEFT JOIN forms AS f'
							.' ON k.id_form=f.id'
						.' LEFT JOIN departments AS d'
							.' ON f.id_department=d.id'
					.$whereStr;
	$resId = mysql_query($query_str);
	
	if ($resId) {	
		$row = mysql_fetch_row($resId);
		$total = $row[0];
		@mysql_free_result($resId);
	}
	
	$limit = getFormValue('limit', 50, 'krugi');
	$limitstart = getFormValue('limitstart', 0, 'krugi');
	$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
	// -----------------------------------
	// Выборка по условиям
	$query_str = 'SELECT org.edrpou, org.name AS org, org.ter'
						.', f.name AS form, pf.name AS period_f'
						.', d.name AS department'
					.' FROM krugi AS k'
						.' LEFT JOIN organizations AS org'
							.' ON k.id_organization=org.id'
						.' LEFT JOIN forms AS f'
							.' ON k.id_form=f.id'
						.' LEFT JOIN periods_form AS pf'
							.' ON f.id_period_form=pf.id'
						.' LEFT JOIN departments AS d'
							.' ON f.id_department=d.id'
					.$whereStr
					.' ORDER BY f.name, pf.id, org.edrpou'
					." LIMIT $limitstart, $limit";
	$resId = mysql_query($query_str);
	
	if ($resId) {	
		$listItems = array();
		
		while ($row = mysql_fetch_assoc($resId)) {
			$listItems[] = $row;
		}
		@mysql_free_result($resId);
	}

//делаем выборку совокупностей из единого окна
} else if ($choice == 'edok') {
	// ----------Кол-во записей----------
	$total = 0;
	
	$query_str = 'SELECT count(*)'
					.' FROM reports AS r'
						.' LEFT JOIN organizations AS org'
							.' ON r.id_organization=org.id'
						.' LEFT JOIN forms AS f'
							.' ON r.id_form=f.id'
						.' LEFT JOIN departments AS d'
							.' ON f.id_department=d.id'
					.$whereStr;
	$resId = mysql_query($query_str);
	
	if ($resId) {	
		$row = mysql_fetch_row($resId);
		$total = $row[0];
		@mysql_free_result($resId);
	}
	
	$limit = getFormValue('limit', 50, 'reports');
	$limitstart = getFormValue('limitstart', 0, 'reports');
	$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
	// -----------------------------------
	// Выборка по условиям
	$query_str = 'SELECT org.edrpou, org.name AS org, org.ter'
						.', f.name AS form, pf.name AS period_f'
						.', pr.name AS period_r, y.name_short AS year'
						.', d.name AS department'
					.' FROM reports AS r'
						.' LEFT JOIN organizations AS org'
							.' ON r.id_organization=org.id'
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
					.$whereStr
					.' ORDER BY f.name, pf.id, pr.id, org.edrpou'
					." LIMIT $limitstart, $limit";
	$resId = mysql_query($query_str);
	
	if ($resId) {	
		$listItems = array();
		
		if (mysql_num_rows($resId) == 0 && $filtr_o != '') {
			@mysql_free_result($resId);
			
			$query_str = 'SELECT edrpou'
							.' FROM organizations'
							.' WHERE edrpou='.$filtr_o;
			$resId = mysql_query($query_str);
			
			if (mysql_num_rows($resId) == 0) $ERROR_MSG .= 'Такого ЄДРПОУ немає в довіднику';
			
		} else {			
			while ($row = mysql_fetch_assoc($resId)) {
				$listItems[] = $row;
			}
		}
		
		@mysql_free_result($resId);
	}
}

//подготовка строки со списком форм + Все формы
$allTerritories = getDblListOneTerritories($filtr_t);
$allForm = getDoubleListTwoForms($filtr_f);
$allPeriodsReport = getDblListTwoPeriodsR($filtr_p);
$allDepartment = getListDepartments($filtr_d);

require_once('templates/aggregates.php');
require_once('_stop.php');
?>