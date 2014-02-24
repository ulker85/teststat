<?php
require_once('_start.php');

$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : 0;
$filtr_o = isset($_POST['filtr_o']) ? $_POST['filtr_o'] : '';
$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO reports'
						.' (id_form, id_period_report, not_in_sukup'
							.', date_first, id_type_first, id_user_first'
							.', date_second, id_type_second, id_user_second)'
					.' VALUES ('
						.', '.addslashes(stripslashes($_POST['form']))
						.', '.addslashes(stripslashes($_POST['period']))
						.', "'.addslashes(stripslashes($_POST['notInSukup'])).'"'
						.', "'.sqlDateFull(addslashes(stripslashes($_POST['date_f']))).'"'
						.', '.addslashes(stripslashes($_POST['type_f']))
						.', '.addslashes(stripslashes($_POST['user_f']))
						.', "'.sqlDateFull(addslashes(stripslashes($_POST['date_s']))).'"'
						.', '.addslashes(stripslashes($_POST['type_s']))
						.', '.addslashes(stripslashes($_POST['user_s']))
						.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$fDate = isset($_POST["firstDate_$v"])
								? sqlDateFull(addslashes(stripslashes($_POST["firstDate_$v"])))
								: 0;
			$sDate = isset($_POST["secondDate_$v"])
									? sqlDateFull(addslashes(stripslashes($_POST["secondDate_$v"])))
									: 0;
			$notSukup = isset($_POST["notInSukup_$v"]) ? 1 : 0;
			
			$query_str = 'UPDATE reports'
							.' SET id_form='.addslashes(stripslashes($_POST["form_$v"]))
								.', id_period_report='.addslashes(stripslashes($_POST["period_$v"]))
								.', date_first="'.$fDate.'"'
								.', id_type_first='.addslashes(stripslashes($_POST["typeFirst_$v"]))
								.', id_user_first='.addslashes(stripslashes($_POST["userFirst_$v"]))
								.', date_second="'.$sDate.'"'
								.', id_type_second='.addslashes(stripslashes($_POST["typeSecond_$v"]))
								.', id_user_second='.addslashes(stripslashes($_POST["userSecond_$v"]))
								.', not_in_sukup='.$notSukup
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM reports'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE reports');
	mysql_query('OPTIMIZE TABLE reports');
	
//обработка нажатия кнопки "Импорт совокупностей"
} else if ($action == 'update') {
	set_time_limit(600);
	
	do {
		if (!file_exists($tmpFile = $_FILES['fileImp']['tmp_name'])) {
			$ERROR_MSG .= 'Помилка завантаження файлу. Оновлення припинено.<br />';
			break;
		}
		
		//=================================================================
		//подготовка к обновлению совокупности
		//=================================================================
		$list_edrpou = '';
		
		if ($_POST['index_im'])
		 	$id_form =  $_POST['index_im'];
		else {
			$ERROR_MSG .= 'Не обрано назву форми для імпорту.<br />';
			break;
		}
			
		if ($_POST['period_im'])
		 	$id_period_report =  $_POST['period_im'];
		else {
			$ERROR_MSG .= 'Не обрано період форми для імпорту.<br />';
			break;
		}
		
		//---------зануляем таблицу sukup_tmp---------
		mysql_query('TRUNCATE TABLE sukup_tmp');
		
		//---------удаляем все записи (всю совокупность) из табеля---------
		//----------------------для обновляемой формы----------------------
		if (isset($_POST['tabel_imp'])) {
			$str_query = 'DELETE FROM krugi'
							.' WHERE id_form='.$id_form;
			mysql_query($str_query);
									
			mysql_query('REPAIR TABLE krugi');
			mysql_query('OPTIMIZE TABLE krugi');
		}
		//=================================================================
		
		//считываем данные из файла, ...
		$file_csv = file($tmpFile);
		unlink($tmpFile);
		
		//...обрабатываем их...
		foreach ($file_csv as $v) {
			$list_edrpou .= ($list_edrpou) ? ', ('.trim(chop($v)).')' : '('.trim(chop($v)).')';
		}
	
		//...и вставляем в таблицу sukup_tmp
		$query_str = 'INSERT INTO sukup_tmp'
							.' (edrpou)'
						.' VALUES '
							.$list_edrpou;
		mysql_query($query_str);
		
		//если обновляем совокупность в табеле
		if (isset($_POST['tabel_imp'])) {
			$num = 0;
			
			$str_query = 'SELECT org.id, s.edrpou AS edrpou'
							.' FROM sukup_tmp AS s'
								.' LEFT JOIN organizations AS org'
									.' ON s.edrpou = org.edrpou';			
			$resId = mysql_query($str_query);
			
			if ($resId) {
				$ERROR_MSG .= 'Імпорт в "Табель"<br />';
				
				while ($row = mysql_fetch_assoc($resId)) {
					if ($row['id'] == '') {
						$ERROR_MSG .= 'відсутній в довіднику '.$row['edrpou'].'<br />';
					} else {							
						$str_query = 'INSERT INTO krugi'
											.' (id_organization, id_form)'
										.' VALUES ('
											.$row['id']
											.', '.$id_form
										.')';
						mysql_query($str_query);
						
						$num += 1;
					}
				}				
				@mysql_free_result($resId);
				
				$ERROR_MSG .= 'додано в сукупність - '.$num.'<br /><br />';
			} else {
				$ERROR_MSG .= 'Помилка в запиті';
				break;
			}
		}
		
		//если обновляем совокупность в едином окне
		if (isset($_POST['edok_imp'])) {
			$list_edrpou = '';
			
			$count_plus = 0;
			$count_minus = 0;
			$count_del = 0;
			$count_add = 0;
			$count_absent = 0;			
			
			$query_str = 'SELECT r.*, org.edrpou, s.edrpou AS edrpou_tmp'
							.' FROM reports AS r'
								.' LEFT JOIN organizations AS org'
									.' ON r.id_organization=org.id'
								.' LEFT JOIN sukup_tmp AS s'
									.' ON org.edrpou=s.edrpou'
							.' WHERE r.id_form='.$id_form
								.' AND r.id_period_report='.$id_period_report;
			$resId = mysql_query($query_str);
			
			if ($resId) {			
				while ($row = mysql_fetch_assoc($resId)) {
					//формирование списка ЕДРПОУ
					$list_edrpou .= ($list_edrpou) ? ', '.$row['edrpou'] : $row['edrpou'];
					
					//если отчет есть в базе (независимо от наличия даты)
					//и в принесенной совокупности - вводим его в совокупность
					if ($row['edrpou'] == $row['edrpou_tmp']) {
						$query_str = 'UPDATE reports'
											.' SET not_in_sukup=0'
										.' WHERE id='.$row['id'];						
						mysql_query($query_str);
						
						$count_plus += 1;
					} else {
						//если отчет есть в базе (и у него проставлена дата),
						//но нет в принесенной совокупности - выводим его из совокупности
						if ($row['date_first'] <> 0) {
							$query_str = 'UPDATE reports'
												.' SET not_in_sukup=1'
											.' WHERE id='.$row['id'];
							mysql_query($query_str);
											
							$count_minus += 1;
						//если отчет есть в базе (и у него не проставлена дата),
						//но нет в принесенной совокупности - удаляем его из совокупности
						} else {							
							$query_str = 'DELETE FROM reports'
											.' WHERE id='.$row['id'];
							mysql_query($query_str);
											
							$count_del += 1;
						}
					}
				}
				@mysql_free_result($resId);
			} else {
				$ERROR_MSG .= 'Помилка в запиті.<br />';
			}				
	
			//если отчета нет в базе,
			//но есть в принесенной совокупности - добавляем его в совокупность
			if ($list_edrpou == '') $list_edrpou = '""';
			
			$query_str = 'SELECT sukup_tmp.edrpou AS edrpou_tmp'
								.', organizations.edrpou, organizations.id'
							.' FROM sukup_tmp'
								.' LEFT JOIN organizations'
									.' ON sukup_tmp.edrpou=organizations.edrpou'
							.' WHERE sukup_tmp.edrpou NOT IN ('.$list_edrpou.')';			
			$resId = mysql_query($query_str);
			
			if ($resId) {
				$ERROR_MSG .= 'Імпорт в "Єдине вікно"<br />';
				
				while ($row = mysql_fetch_assoc($resId)) {
					if ($row['edrpou'] == '') {
						$ERROR_MSG .= 'відсутній в довіднику '.$row['edrpou_tmp'].'<br />';
					} else {
						$query_str = 'INSERT INTO reports'
											.' (id_organization, id_form, id_period_report)'
										.' VALUES ('
											.$row['id']
											.', '.$id_form
											.', '.$id_period_report
										.')';
						mysql_query($query_str);
									
						$count_add += 1;
					}
				}
				@mysql_free_result($resId);
			} else {
				$ERROR_MSG .= 'Помилка в запиті<br />';
				break;
			}
			
			$ERROR_MSG .= 'введено - '.$count_plus
								.'; виведено - '.$count_minus
								.'; видалено - '.$count_del
								.'; додано - '.$count_add
								.'<br />';
		}
		
		//вставляем дату обновления совокупности в таблицу forms
		if (isset($_POST['tabel_imp'])) {
			$str_query = 'UPDATE forms'
								.' SET date_update="'.date('Y-m-d').'"'
							.' WHERE id='.$id_form;
			mysql_query($str_query);
			
			if (mysql_affected_rows() == 0) {
				$ERROR_MSG .= 'Неможливо оновити запис для форми з кодом '.$id_form.'.<br />';
			}
		}
		
		break;
	} while (false);

//обработка нажатия кнопки "Удалить совокупность"
} else if ($action == 'del_sukup') {
	// Готовим условия отбора
	$where = array();
	$where[] = 'id_form='.$_POST['form_del'];
	$where[] = 'id_period_report='.$_POST['period_del'];
	
	$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	if ($whereStr) {
		if (isset($_POST['tabel'])) {
			$query_str = 'DELETE FROM krugi'
							.' WHERE '.$where[0];
			mysql_query($query_str);
			
			$num_tabel = mysql_affected_rows();
			
			mysql_query('REPAIR TABLE krugi');
			mysql_query('OPTIMIZE TABLE krugi');
	
			$ERROR_MSG .= 'Видалено '.$num_tabel.' записів з "Табелю".<br />';
		}
		if (isset($_POST['edok'])) {
			$query_str = 'DELETE FROM reports'
							.$whereStr;
			mysql_query($query_str);
			
			$num_edok = mysql_affected_rows();
			$ERROR_MSG .= 'Видалено '.$num_edok.' записів з "Єдиного вікна".<br />';
		}
		
		if (isset($_POST['tabel'])) {
			$query_str = 'UPDATE forms'
							.' SET date_update=0'
							.' WHERE id='.$_POST['form_del'];
			mysql_query($query_str);
			
			if (mysql_affected_rows() == 0)
				$ERROR_MSG .= 'Неможливо оновити запис для форми з кодом '.$_POST['form_del'].'<br />';
		}
		
		mysql_query('REPAIR TABLE reports');
		mysql_query('OPTIMIZE TABLE reports');
	} else {
		$ERROR_MSG .= 'Відсутні критерії відбору. Перевірте код форми та код періоду.<br />';
	}
}

//== подготовка массива со списком организаций, форм и отделов
// Готовим условия отбора
$where = array();
if ($filtr_f!=0) $where[] = 'r.id_form='.$filtr_f;
if ($filtr_o!=0) $where[] = 'org.edrpou='.$filtr_o;
if ($filtr_p!=0) $where[] = 'r.id_period_report='.$filtr_p;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$query_str = 'SELECT count(*)'
				.' FROM reports AS r'
					.' LEFT JOIN organizations AS org'
						.' ON r.id_organization=org.id'
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
$query_str = 'SELECT r.id, r.id_form, r.id_period_report, r.not_in_sukup'
					.', org.edrpou'
					.', r.date_first, r.id_type_first, r.id_user_first'
					.', r.date_second, r.id_type_second, r.id_user_second'
				.' FROM reports AS r'
					.' LEFT JOIN organizations AS org'
						.' ON r.id_organization=org.id'
				.$whereStr
				.' ORDER BY id'
				." LIMIT $limitstart, $limit";
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('form' => getDoubleListTwoForms($row['id_form'], '(формa)'),
											'period' => getDblListTwoPeriodsR($row['id_period_report'], '(період)'),
											'typeFirst' => getListTypes($row['id_type_first'], '(спосіб подання)'),
											'userFirst' => getListUsers($row['id_user_first'], '(користувач)'),
											'typeSecond' => getListTypes($row['id_type_second'], '(спосіб подання)'),
											'userSecond' => getListUsers($row['id_user_second'], '(користувач)'));
	}
	@mysql_free_result($resId);
}

//подготовка строки со списком периодов. отделов
$edrpou = getListOrganizations(0, '(оберіть організацію)');
$form = getDoubleListTwoForms(0, '(оберіть форму)');
$period = getDblListTwoPeriodsR(0, '(оберіть період)');
$type = getListTypes(0, '(спосіб подання)');
$user = getListUsers(0, '(користувач)');

//подготовка строки с периодом отчетности
$namePeriodReport = getDblListTwoPeriodsR($filtr_p, '(список періодів)');
//подготовка строки со списком форм
$nameForm = getDoubleListTwoForms($filtr_f, '(список форм)');

//подготовка строки со списком форм + Все формы
$allForm = getDoubleListTwoForms($filtr_f);
$allPeriodsReport = getDblListTwoPeriodsR($filtr_p);

require_once('templates/reports.php');
require_once('_stop.php');
?>