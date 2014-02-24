<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Добавить"
if ($action == 'add') {
	$query_str = 'INSERT INTO departments'
					.' (name, tel, tel_mob, fax, room_num, ter, adres)'
					.' VALUES ('
						.'"'.addslashes(stripslashes($_POST['name'])).'"'
						.', "'.addslashes(stripslashes($_POST['tel'])).'"'
						.', "'.addslashes(stripslashes($_POST['telMob'])).'"'
						.', "'.addslashes(stripslashes($_POST['fax'])).'"'
						.', "'.addslashes(stripslashes($_POST['room'])).'"'
						.', "'.addslashes(stripslashes($_POST['ter'])).'"'
						.', "'.addslashes(stripslashes($_POST['adres'])).'"'
						.')';
	mysql_query($query_str);

//обработка нажатия кнопки "Редактировать"
} else if ($action == 'edit') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'UPDATE departments SET'
								.' name="'.addslashes(stripslashes($_POST["name_$v"])).'"'
								.', tel="'.addslashes(stripslashes($_POST["tel_$v"])).'"'
								.', tel_mob="'.addslashes(stripslashes($_POST["telMob_$v"])).'"'
								.', fax="'.addslashes(stripslashes($_POST["fax_$v"])).'"'
								.', room_num="'.addslashes(stripslashes($_POST["room_$v"])).'"'
								.', ter="'.addslashes(stripslashes($_POST["ter_$v"])).'"'
								.', adres="'.addslashes(stripslashes($_POST["adres_$v"])).'"'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}

//обработка нажатия кнопки "Удалить"
} else if ($action == 'del') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 4) == 'chk_') {
			$query_str = 'DELETE FROM departments'
							.' WHERE id='.$v;
			mysql_query($query_str);
		}
	}
	
	mysql_query('REPAIR TABLE departments');
	mysql_query('OPTIMIZE TABLE departments');

//импорт данных из файла .csv в таблицу
} else if ($action == 'import') {
	$countIns = 0;
	$countUpd = 0;
	
	if (!file_exists($tmpFile=$_FILES["fileImp"]['tmp_name'])) {
		$ERROR_MSG .= '<br />Ошибка загрузки файла';
	}
	$d = @fopen($tmpFile, "r");
	if ($d != false) {
		while (!feof($d)) {
			$str = chop(fgets($d));
			if ($str == '') continue;
			$fields = explode(";", $str);
			for ($i=0; $i<count($fields); $i++){
				if ($fields[$i][0] == '"') $fields[$i] = substr($fields[$i], 1);
				if ($fields[$i][strlen($fields[$i]) - 1] == '"') $fields[$i] = substr($fields[$i], 0, strlen($fields[$i]) - 1);				
			}
			
			$query_str = 'SELECT name, room_num'
							.' FROM departments'
							.' WHERE name="'.$fields[0].'"'
								.' AND room_num="'.$fields[1].'"'
							.' LIMIT 1';
			$listSnames = mysql_query($query_str);			
			
			if ($listSnames) {						
				if (mysql_num_rows($listSnames) == 1) {
					$query_str = 'UPDATE departments SET'
										.' name="'.$fields[0].'"'
										.', room_num="'.$fields[1].'"'
										.', tel="'.$fields[2].'"'
										.', tel_mob="'.$fields[3].'"'
										.', fax="'.$fields[4].'"'
										.', ter="'.$fields[5].'"'
										.', adres="'.$fields[6].'"'
									.' WHERE name="'.$fields[0].'"'
										.' AND room_num="'.$fields[1].'"';					
					$countUpd++;
				} else {
					$query_str = 'INSERT INTO departments'
										.' (name, room_num, tel, tel_mob, fax, ter, adres)'
									.' VALUES ('
										.'"'.$fields[0].'"'
										.', "'.$fields[1].'"'
										.', "'.$fields[2].'"'
										.', "'.$fields[3].'"'
										.', "'.$fields[4].'"'
										.', "'.$fields[5].'"'
										.', "'.$fields[6].'"'
									.')';
					$countIns++;
				}
				mysql_query($query_str);				 				
				@mysql_free_result($listSnames);
			}
		}
		fclose($d);		
		$ERROR_MSG .= "<br />Імпорт завершено. Оновлено записів: $countUpd. Додано: $countIns";		
		unlink($tmpFile);
		
	} else $ERROR_MSG .= "<br />Неможливо відкрити файл імпорта";
}

//подготовка массива со списком отделов
$query_str = 'SELECT *'
				.' FROM departments'
				.' ORDER BY ter, id';
$resId = mysql_query($query_str);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listItems[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/departments.php');
require_once('_stop.php');
?>