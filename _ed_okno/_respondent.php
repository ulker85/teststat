<?php
//��������� ������� ������ "��������"
if ($action == 'write_email') {
	$query_str = 'UPDATE organizations SET'
						.' e_mail="'.addslashes(stripslashes($_POST['email'])).'"'
					.' WHERE id='.$_SESSION['edrpou'];
	mysql_query($query_str);
	
//��������� ������� ������ "�������"
} else if (substr($action, 0, 8) == 'del_note') {
	if ( isset($_SESSION[$_ses_expert]) || isset($_SESSION[$_ses_user]) ) {
		$user = isset($_SESSION[$_ses_expert]) ? $_SESSION[$_ses_expert] : $_SESSION[$_ses_user];
		
		$str_query = 'UPDATE notes_org SET'
							.' id_user_del='.$user
						.' WHERE id='.substr($action, 9);
		$resId = mysql_query($str_query);
	}
}

//-- ���������� ������� � ������� ��� �����������
// ������� ������� ������
$where = array();
$where[] = 'id='.$_SESSION['edrpou'];
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT *'
				.' FROM organizations'
				.$whereStr
				.' LIMIT 1';
$resId = mysql_query($str_query);

if ($resId) {	
	$org = mysql_fetch_assoc($resId);
	@mysql_free_result($resId);
}

//-- ���������� ������� � ��������� ��� �����������
// ������� ������� ������
$where = array();
$where[] = 'id_organization='.$_SESSION['edrpou'];
$where[] = 'id_user_del=0';
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT *'
				.' FROM notes_org'
				.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {	
	$listNotes = array();
	
	while ($row = mysql_fetch_assoc($resId)) {
		$listNotes[] = $row;
	}
	@mysql_free_result($resId);
}

require_once('templates/_respondent.php');
?>