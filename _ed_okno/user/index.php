<?php
require_once('_start.php');

$edrpou = isset($_POST['edrpou']) ? stripslashes(substr($_POST['edrpou'], 0, 10)) : '';
$sname = isset($_POST['sname']) ? stripslashes(substr($_POST['sname'], 0, 20)) : '';

$filtr_f = isset($_POST['filtr_f']) ? $_POST['filtr_f'] : '';
$filtr_p = isset($_POST['filtr_p']) ? $_POST['filtr_p'] : '';

$action = isset($_POST['mode']) ? $_POST['mode'] : '';

session_name('stat');
session_start();
unset($_SESSION['edrpou']);
unset($_SESSION['form']);
unset($_SESSION['period']);
	
if ($action == 'search') {
	if ($_POST['rdSearch'] == 'edrpou') {
		if (checkEdrpou($edrpou)) {
			$_SESSION['edrpou'] = $id;
			header('Location: reg_org.php');
			exit();
		} else {
			$ERROR_MSG .= 'Підприємство з таким кодом не знайдено.';
		}
	} else if ($_POST['rdSearch'] == 'name') {
		if (!checkName($sname)) {
			$ERROR_MSG .= 'Підприємство з таким керівником не знайдено.';
		}
	} else if ($_POST['rdSearch'] == 'form') {
		if (checkForm($filtr_f, $filtr_p)) {
			$_SESSION['form'] = $filtr_f;
			$_SESSION['period'] = $filtr_p;
			header('Location: reg_form.php');
			exit();
		} else
			$ERROR_MSG .= 'Форма з таким періодом не розробляється.';
	}
}

function checkEdrpou($edrpou) {
	global $id;
	$result = false;
	
	$str_query = 'SELECT *'
					.' FROM organizations'
					.' WHERE edrpou='.addslashes($edrpou)
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	if ($resId) {
		$result = (mysql_num_rows($resId) != 0);
		$row = mysql_fetch_assoc($resId);
		$id = ($row !== false) ? $row['id'] : '';
		@mysql_free_result($resId);
	}
	return $result;
}

function checkName($name) {
	$result = false;
	
	$str_query = 'SELECT * FROM organizations'
					.' WHERE leader LIKE "%'.addslashes($name).'%"';
	$resId = mysql_query($str_query);
	if ($resId) {
		if ( $result = (mysql_num_rows($resId) != 0) ) {
			global $msg;
			$msg = 'Вашому запиту відповідають наступні підприємства:';
			
			while ($row = mysql_fetch_assoc($resId)) {
				$msg .= '<br /><span style="padding-left:14px">'.$row['edrpou'].' - '.$row['name'];
			}
			@mysql_free_result($resId);
			
			$msg .= '<br />оберіть з них необхідний код ЄДРПОУ.';
		}
	}
	return $result;
}

function checkForm($form, $period) {
	$result = false;
	
	$str_query = 'SELECT *'
					.' FROM reports'
					.' WHERE id_form='.addslashes($form)
						.' AND id_period_report='.addslashes($period)
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	if ($resId) {
		$result = (mysql_num_rows($resId) != 0);
		@mysql_free_result($resId);
	}
	return $result;
}

$allForm = getDoubleListTwoForms(0, '(оберіть форму)');
$allPeriodReport = getDblListTwoPeriodsR(0, '(оберіть період)');

require_once('templates/index.php');
require_once('_stop.php');
?>