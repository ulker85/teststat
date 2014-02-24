<?php
require_once('_start.php');

$edrpou = isset($_POST['edrpou']) ? stripslashes(substr($_POST['edrpou'], 0, 10)) : '';

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
			header('Location: kor_org.php');
			exit();
		} else {
			$ERROR_MSG .= 'Підприємство з таким кодом не знайдено.';
		}
	} else if ($_POST['rdSearch'] == 'form') {
		if (checkForm($filtr_f, $filtr_p)) {
			$_SESSION['form'] = $filtr_f;
			$_SESSION['period'] = $filtr_p;
			header('Location: kor_form.php');
			exit();
		} else
			$ERROR_MSG .= 'Форма з таким періодом не розробляється.';
	}
}

function checkEdrpou($edrpou) {
	global $id;
	$result = false;
	
	$str_query = 'SELECT * FROM organizations'
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

function checkForm($form, $period) {
	$result = false;
	
	$str_query = 'SELECT * FROM reports'
					.' WHERE id_form='.addslashes($form)
					.' AND id_period='.addslashes($period)
					.' LIMIT 1';
	$resId = mysql_query($str_query);
	if ($resId) {
		$result = (mysql_num_rows($resId) != 0);
		@mysql_free_result($resId);
	}
	return $result;
}

$allForm = getDoubleListTwoForms(0, '(оберіть форму)');
$allPeriodReport = getListPeriodsReport(0, '(оберіть період)');

require_once('templates/index_kor.php');
require_once('_stop.php');
?>