<?php
require_once('_start.php');

$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : 0;
$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : 0;

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO codes_form'
						.' (id_form, code_elzvit)'
					.' VALUES ('
						.addslashes(stripslashes($_POST['form_name']))
						.', "'.addslashes(stripslashes($_POST['elzvit'])).'"'
					.')';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE codes_form'
							.' SET id_form='.addslashes(stripslashes($_POST["form_$v"]))
								.', code_elzvit="'.addslashes(stripslashes($_POST["elzvit_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str='DELETE FROM codes_form'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE codes_form');
	mysql_query('OPTIMIZE TABLE codes_form');
	
//обработка нажатия кнопки "Импорт эл.отчетности"
} else if ($action == 'elzv') {
	set_time_limit(600);
	
	do {
		if (!file_exists($tmp_file = $_FILES['fileImpElzv']['tmp_name'])) {
			$str = 'Помилка завантаження файлу. Імпорт припинено.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		}
		
		//=================================================================
		//подготовка к обновлению совокупности
		//=================================================================
		
		//-------зануляем таблицу elzv_tmp-------
		mysql_query('TRUNCATE TABLE elzv_tmp');
		//=================================================================
		
		//считываем данные из файла
		$file_csv = file($tmp_file);
		unlink($tmp_file);
		
		//обрабатываем данные из файла
		foreach ($file_csv as $v) {
			$fields = explode(';', trim(chop($v)));
			
			$query_str = 'INSERT INTO elzv_tmp'
								.' (date, form, period, edrpou)'
							.' VALUES ('
							    .'"'.sqlDateFull($fields[0]).'"'
								.', "'.$fields[1].'"'
								.', "'.$fields[2].'"'//substr($fields[2], 0, strrpos($fields[2], ' ')).'"'
								.', '.$fields[3]
							.')';			
			$resId = mysql_query($query_str);
			
			if (!$resId) {
				$ERROR_MSG .= 'Помилка вставки запису |'.$fields[0]
																		.'|'.$fields[1]
																		.'|'.$fields[2]
																		.'|'.$fields[3].'"|. Імпорт припинено.<br />';
				break(2);
			}
		}
		
		
		
		
		
		
		
		$query_str = 'SELECT et.date, et.reg_num'
							.', o.id AS id_org, cf.id_form, pr.id AS id_period'
						.' FROM elzv_tmp AS et'
							.' LEFT JOIN organizations AS o'
								.' ON et.edrpou=o.edrpou'
							.' LEFT JOIN codes_form AS cf'
								.' ON et.form=cf.code_elzvit'
							.' LEFT JOIN periods_report AS pr'
								.' ON et.period=pr.name_short'
							.' ORDER BY o.id, cf.id_form, pr.id, et.date';
		traceMsg($query_str);
		
		$resId = mysql_query($query_str);
		if ($resId) {
			while ($row = mysql_fetch_assoc($resId)) {
				if ($row['id_org'] == '') {
					$str = 'Перевірте наявність організації '.$row['edrpou'].' в довіднику "organizations"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				}
				if ($row['id_form'] == '') {
					$str = 'Перевірте наявність кода форми '.$row['form'].' в довіднику "codes_form"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				} 
				if ($row['id_pr'] == '') {
					$str = 'Перевірте наявність назви періоду '.$row['period'].' в довіднику "periods_report"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				}
				
				$query_str = 'SELECT *'
								.' FROM repotrs'
								.$whereStr;
				traceMsg($query_str);
			}
			
			$resId = mysql_query($query_str);
			if ($resId) {
				while ($row = mysql_fetch_assoc($resId)) {
					$str = 'Імпорт завершено.';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					break;				
				}
			} else {
				$str = 'Помилка запиту при виборці. Імпорт припинено.';
				$ERROR_MSG .= $str.'<br />';
				traceMsg($str);
				break;
			}
		} else {
			$str = 'Помилка запиту при виборці. Імпорт припинено.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		}
		
		break;
	} while (false);	
}

//подготовка массива со списком періодов
// Готовим условия отбора
$where = array();
if ($filtr_f!=0) $where[] = 'codes_form.id_form='.$filtr_f;
if ($filtr_p!=0) $where[] = 'periods_form.id='.$filtr_p;
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$str_query = 'SELECT count(*)'
				.' FROM codes_form'
					.' LEFT JOIN forms'
						.' ON codes_form.id_form=forms.id'
					.' LEFT JOIN periods_form'
						.' ON forms.id_period_form=periods_form.id'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}
$limit = getFormValue('limit', 50, 'codes_form');
$limitstart = getFormValue('limitstart', 0, 'codes_form');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$str_query = 'SELECT codes_form.*'
				.' FROM  codes_form'
					.' LEFT JOIN forms'
						.' ON codes_form.id_form=forms.id'
					.' LEFT JOIN periods_form'
						.' ON forms.id_period_form=periods_form.id'
				.$whereStr
				.' ORDER BY forms.name, codes_form.code_elzvit'
				." LIMIT $limitstart, $limit";
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row + array('form' => getDoubleListTwoForms($row['id_form'], ''));
	}
	@mysql_free_result($resId);
}

//подготовка строки со списком форм
$form = getDoubleListTwoForms(0, '(оберіть форму)');

//подготовка строки со списком форм + Все формы
$allForm = getDoubleListTwoForms($filtr_f);
$allPeriodsReport = getListPeriodsForm($filtr_p);

require_once('templates/codes.php');
require_once('_stop.php');
?>