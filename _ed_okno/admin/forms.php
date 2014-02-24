<?php
require_once('_start.php');

$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : 0;
$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO forms'
						.' (name, name_full, srok_sdachi, nakaz, id_period_form, two_level, id_department'
						.', note, blank, instr, rozyasn, to_all_resp, ekspres, region, m_rayon)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['sname'])).'"'
						.', "'.addslashes(stripslashes($_POST['fname'])).'"'
						.', "'.addslashes(stripslashes($_POST['srok'])).'"'
						.', "'.addslashes(stripslashes($_POST['nakaz'])).'"'
						.', "'.addslashes(stripslashes($_POST['period'])).'"'
						.', "'.addslashes(stripslashes($_POST['two_level'])).'"'
						.', "'.addslashes(stripslashes($_POST['department'])).'"'
						.', "'.addslashes(stripslashes($_POST['note'])).'"'
						.', "'.addslashes(stripslashes($_POST['blank'])).'"'
						.', "'.addslashes(stripslashes($_POST['instr'])).'"'
						.', "'.addslashes(stripslashes($_POST['rozyasn'])).'"'
						.', "'.addslashes(stripslashes($_POST['to_all_resp'])).'"'
						.', "'.addslashes(stripslashes($_POST['ekspres'])).'"'
						.', "'.addslashes(stripslashes($_POST['region'])).'"'
						.', "'.addslashes(stripslashes($_POST['m_rayon'])).'"'
						.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			
			if ($_POST["not_in_use_$v"] == 1) {
				$query_str = 'UPDATE forms SET'
									.' name="'.addslashes(stripslashes($_POST["sname_$v"])).'"'
									.', name_full="'.addslashes(stripslashes($_POST["fname_$v"])).'"'
									.', srok_sdachi="'.addslashes(stripslashes($_POST["srok_$v"])).'"'
									.', nakaz=""'
									.', id_period_form="'.addslashes(stripslashes($_POST["period_$v"])).'"'
									.', two_level="'.addslashes(stripslashes($_POST["two_level_$v"])).'"'
									.', id_department="'.addslashes(stripslashes($_POST["department_$v"])).'"'
									.', not_in_use=1'
									.', note=""'
									.', blank=""'
									.', instr=""'
									.', rozyasn=""'
									.', to_all_resp=0'
									.', ekspres=0'
									.', region=0'
									.', m_rayon=0'
									.', date_update=0'
								.' WHERE id='.$v;
				mysql_query($query_str);
				
				//удаление совокупности для формы, отмеченной как не рарабатывающаяся
				$query_str = 'DELETE FROM krugi'
								.' WHERE id_form='.$v;
				mysql_query($query_str);
				
				mysql_query('REPAIR TABLE krugi');
				mysql_query('OPTIMIZE TABLE krugi');
			} else {
				$query_str = 'UPDATE forms SET'
									.' name="'.addslashes(stripslashes($_POST["sname_$v"])).'"'
									.', name_full="'.addslashes(stripslashes($_POST["fname_$v"])).'"'
									.', srok_sdachi="'.addslashes(stripslashes($_POST["srok_$v"])).'"'
									.', nakaz="'.addslashes(stripslashes($_POST["nakaz_$v"])).'"'
									.', id_period_form="'.addslashes(stripslashes($_POST["period_$v"])).'"'
									.', two_level="'.addslashes(stripslashes($_POST["two_level_$v"])).'"'
									.', id_department="'.addslashes(stripslashes($_POST["department_$v"])).'"'
									.', not_in_use="'.addslashes(stripslashes($_POST["not_in_use_$v"])).'"'
									.', note="'.addslashes(stripslashes($_POST["note_$v"])).'"'
									.', blank="'.addslashes(stripslashes($_POST["blank_$v"])).'"'
									.', instr="'.addslashes(stripslashes($_POST["instr_$v"])).'"'
									.', rozyasn="'.addslashes(stripslashes($_POST["rozyasn_$v"])).'"'
									.', to_all_resp="'.addslashes(stripslashes($_POST["to_all_resp_$v"])).'"'
									.', ekspres="'.addslashes(stripslashes($_POST["ekspres_$v"])).'"'
									.', region="'.addslashes(stripslashes($_POST["region_$v"])).'"'
									.', m_rayon="'.addslashes(stripslashes($_POST["m_rayon_$v"])).'"'
								.' WHERE id='.$v;
				mysql_query($query_str);
			}
		}		
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM forms'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE forms');
	mysql_query('OPTIMIZE TABLE forms');
}

//подготовка массива со списком форм
// Готовим условия отбора
$where = array();
if ($filtr_p!=0) $where[] = 'id_period_form='.$filtr_p;
if ($filtr_o!=0) $where[] = 'id_department='.$filtr_o;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM forms'
				.$whereStr;
$resId = mysql_query($query_str);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}
$limit = getFormValue('limit', 50, 'forms');
$limitstart = getFormValue('limitstart', 0, 'forms');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$query_str = 'SELECT *'
				.' FROM forms'
				.$whereStr
				." LIMIT $limitstart, $limit";
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('period' => getListPeriodsForm($row['id_period_form'], ''),
											 'department' => getListDepartments($row['id_department'], ''));
	}	
	@mysql_free_result($resId);
}

//подготовка строки со списком периодов. отделов
$period = getListPeriodsForm(0, '');
$otdel = getListDepartments(0, '');

//подготовка строки со списком периодов+ Все периоды, отделов + Все отделы
$allPeriod = getListPeriodsForm($filtr_p);
$allOtdel = getListDepartments($filtr_o);

require_once('templates/forms.php');
require_once('_stop.php');
?>