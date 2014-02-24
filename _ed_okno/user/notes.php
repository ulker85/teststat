<?php
require_once('_start.php');

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

//обработка нажатия кнопки "Записать"
if ($action == 'add') {
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 7) == 'edrpou_' && $v != '') {
			
			if ($_POST['note_'.substr($k, 7)] == '') {
				$ERROR_MSG .= 'Для ЄДРПОУ '.$v.' не було введено примітку.<br />';
				continue;
			}
			
			$query_str = 'SELECT id'
							.' FROM organizations'
							.' WHERE edrpou='.addslashes(stripslashes($v))
							.' LIMIT 1';
			$resId = mysql_query($query_str);
			
			if ($resId) {
				if (mysql_num_rows($resId)) {
					$row = mysql_fetch_assoc($resId);
					
					$query_str = 'INSERT INTO notes_org'
										.' (id_organization, name, id_user_add)'
									.' VALUES ('
										.$row['id']
										.', "'.addslashes(stripslashes($_POST['note_'.substr($k, 7)])).'"'
										.', '.$_SESSION[$_ses_user]
										.')';traceMsg($query_str);
					mysql_query($query_str);
				} else {
					$ERROR_MSG .= 'ЄДРПОУ '.$v.' відсутній в довіднику.<br />';
					continue;
				}
				@mysql_free_result($resId);
			}
		}
	}
}

//подготовка массива со списком форм, сроков их сдачи и т.д.
// Готовим условия отбора
$where = array();
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$str_query = 'SELECT org.edrpou, n.name, u_a.login AS user_add, u_d.login AS user_del'
				.' FROM notes_org AS n'
					.' LEFT JOIN organizations AS org'
						.' ON n.id_organization=org.id'
					.' LEFT JOIN users AS u_a'
						.' ON n.id_user_add=u_a.id'
					.' LEFT JOIN users AS u_d'
						.' ON n.id_user_del=u_d.id'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {	
	$listItems = array();
	
	while ($row = mysql_fetch_assoc($resId)) {		
		$listItems[] = $row;		
	}
	@mysql_free_result($resId);
}

//поиск пользователя
// Готовим условия отбора
$where = array();
$where[] = 'id='.$_SESSION[$_ses_user];
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// Выборка по условиям
$str_query = 'SELECT login'
				.' FROM users'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {	
	$user = mysql_fetch_assoc($resId);
	@mysql_free_result($resId);
}

require_once('templates/notes.php');
require_once('_stop.php');
?>