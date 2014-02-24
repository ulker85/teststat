<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Редактировать"
if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE organizations SET'
								.' fiz_person="'.addslashes(stripslashes($_POST["fizPerson_$v"])).'"'
								.', edrpou="'.addslashes(stripslashes($_POST["edrpou_$v"])).'"'
								.', name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
								.', leader="'.addslashes(stripslashes($_POST["leader_$v"])).'"'
								.', adres_yur="'.addslashes(stripslashes($_POST["adryur_$v"])).'"'
								.', phone="'.addslashes(stripslashes($_POST["phone_$v"])).'"'
								.', e_mail="'.addslashes(stripslashes($_POST["email_$v"])).'"'
								.', ter="'.addslashes(stripslashes($_POST["ter_$v"])).'"'
								.', opf="'.addslashes(stripslashes($_POST["opf_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
//обработка нажатия кнопки "Добавить"
} else if ($action == 'add') {
	$query_str = 'INSERT INTO organizations'
						.' (edrpou, name, leader, adres_yur, phone, e_mail, ter, opf, fiz_person)'
					.' VALUES ('
							.'"'.addslashes(stripslashes($_POST['edrpou'])).'"'
							.', "'.addslashes(stripslashes($_POST['name'])).'"'
							.', "'.addslashes(stripslashes($_POST['leader'])).'"'
							.', "'.addslashes(stripslashes($_POST['adryur'])).'"'
							.', "'.addslashes(stripslashes($_POST['phone'])).'"'
							.', "'.addslashes(stripslashes($_POST['email'])).'"'
							.', "'.addslashes(stripslashes($_POST['ter'])).'"'
							.', "'.addslashes(stripslashes($_POST['opf'])).'"'
							.', "'.addslashes(stripslashes($_POST['fizPerson'])).'")';
	mysql_query($query_str);
	
//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM organizations'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE organizations');
	mysql_query('OPTIMIZE TABLE organizations');

//обработка нажатия кнопки "Импорт"
} else if ($action == 'import') {
	set_time_limit(300);
	$countIns = 0;
	$countUpd = 0;
	
	/*if (!file_exists($tmpFile=$_FILES["fileImpEdrpou"]['tmp_name'])) {
		$ERROR_MSG .= '<br />Ошибка загрузки файла';
	}
	$d = @fopen($tmpFile, "r");*/
	$d = @fopen("import/reestr.csv", "r");
	
	if ($d != false) {
		while (!feof($d)) {
			$str = chop(fgets($d)); //считываем очередную строку из файла до \n включительно
			if ($str == '') continue;
			$fields = explode(";", $str);
			for ($i=0; $i<count($fields); $i++) {
				if ($fields[$i][0] == '"') $fields[$i] = substr($fields[$i], 1);
				if ($fields[$i][strlen($fields[$i]) - 1] == '"') $fields[$i] = substr($fields[$i], 0, strlen($fields[$i]) - 1);
			}
			
			$str_query = 'SELECT edrpou'
							.' FROM organizations'
							.' WHERE edrpou='.$fields[0]
							.' LIMIT 1';
			$listSnames = mysql_query($str_query);
			
			if ($listSnames) {
				if (mysql_num_rows($listSnames) == 1) {
					$query_str='UPDATE organizations SET'
										.' edrpou="'.$fields[0].'"'
										.', name="'.$fields[1].'"'
										.', leader="'.$fields[2].'"'
										.', adres_yur="'.$fields[3].'"'
										.', phone="'.$fields[4].'"'
										.', ter="'.$fields[5].'"'
										.', opf="'.$fields[6].'"'
									.' WHERE edrpou="'.$fields[0].'"'
									.' LIMIT 1';
					mysql_query($query_str);
					
					$countUpd++;
				} else {
					$query_str='INSERT INTO organizations'
										.' (edrpou, name, leader, adres_yur, phone, ter, opf)'
									.' VALUES ('
										.$fields[0]
										.', "'.$fields[1].'"'
										.', "'.$fields[2].'"'
										.', "'.$fields[3].'"'
										.', "'.$fields[4].'"'
										.', "'.$fields[5].'"'
										.', '.$fields[6]
									.')';
					mysql_query($query_str);
					
					$countIns++;
				}
				@mysql_free_result($listSnames);
			}
		}		
		fclose($d);
		
		$ERROR_MSG .= "<br />Імпорт завершено. Оновлено: $countUpd. Додано: $countIns. Всього: ".($countIns+$countUpd);
	} else
		$ERROR_MSG .= "<br />Неможливо відкрити файл імпорта";
	
//обработка нажатия кнопки "Другие территории" и "Физ. лица"
} else if (($action == 'impOther') || ($action == 'impFiz')) {
	$countIns = 0;
	$countUpd = 0;
	
	$person = ($action == 'impFiz') ? 1 : 0;
	
	if (!file_exists($tmpFile=$_FILES["fileImpIndust"]['tmp_name'])) {
		$ERROR_MSG .= '<br />Помилка завантаження файлу.';
	}
	$d = @fopen($tmpFile, "r");
	
	if ($d != false) {		
		while (!feof($d)) {
			$str = chop(fgets($d)); //считываем очередную строку из файла до \n включительно
			if ($str == '') continue;
			$fields = explode(";", $str);
			for ($i=0; $i<count($fields); $i++) {
				if ($fields[$i][0] == '"') $fields[$i] = substr($fields[$i], 1);
				if ($fields[$i][strlen($fields[$i]) - 1] == '"') $fields[$i] = substr($fields[$i], 0, strlen($fields[$i]) - 1);				
			}	
						
			$str_query = 'SELECT edrpou'
							.' FROM organizations'
							.' WHERE edrpou="'.$fields[0].'"'
							.' LIMIT 1';
			$listSnames = mysql_query($str_query);
			
			if ($listSnames) {	
				if (mysql_num_rows($listSnames) == 1) {					
					$query_str = 'UPDATE organizations SET'
										.'name="'.$fields[1].'"'
										.(($fields[2] != '') ? ', leader="'.$fields[2].'"' : '')
										.(($fields[3] != '') ? ', adres_yur="'.$fields[3].'"' : '')
										.(($fields[4] != '') ? ', phone="'.$fields[4].'"' : '')
										.(($fields[5] != '') ? ', ter="'.$fields[5].'"' : '')
										.(isset($fields[6]) ? ', opf="'.$fields[6].'"' : '')
										.', fiz_person='.$person
									.' WHERE edrpou="'.$fields[0].'"'
									.' LIMIT 1';
					mysql_query($query_str);
					
					$countUpd++;
				} else {
					$query_str = 'INSERT INTO organizations'
										.' (edrpou, name, leader, adres_yur, phone, ter'
										.(isset($fields[6]) ? ', opf' : '')
										.', fiz_person)'
									.' VALUES ('
										.$fields[0]
										.', "'.$fields[1].'"'
										.', "'.$fields[2].'"'
										.', "'.$fields[3].'"'
										.', "'.$fields[4].'"'
										.', "'.$fields[5].'"'
										.(isset($fields[6]) ? ', '.$fields[6] : '')
										.', '.$person.')';
					mysql_query($query_str);
					
					$countIns++;
				}
				@mysql_free_result($listSnames);
			} else {
				$ERROR_MSG .= 'Помилка виконання запиту для ЄДРПОУ '.$fields[0];
				continue;
			}
		}		
		fclose($d);
				
		$ERROR_MSG .= "<br />Імпорт завершено. Оновлено: $countUpd. Додано: $countIns. Всього: ".($countIns+$countUpd);
	} else
		$ERROR_MSG .= "<br />Неможливо відкрити файл імпорта";
}

//подготовка массива со списком организаций
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ----------Кол-во записей----------
$total = 0;

$str_query = 'SELECT count(*)'
				.' FROM organizations';
$resId = mysql_query($str_query);
if ($resId) {	
	$row = mysql_fetch_row($resId);
	$total = $row[0];
	@mysql_free_result($resId);
}

$limit = getFormValue('limit', 50, 'organizations');
$limitstart = getFormValue('limitstart', 0, 'organizations');
$pagination = getPagination(array('total'=>$total, 'limitstart'=>$limitstart, 'limit'=>$limit));
// -----------------------------------
// Выборка по условиям
$str_query = 'SELECT *'
				.' FROM organizations'
				." LIMIT $limitstart, $limit";
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/organizations.php');
require_once('_stop.php');
?>