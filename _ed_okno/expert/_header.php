<?php
//���������� ������� � ������ � ���� ������
// ������� ������� ������
$where = array();
$where[] = 'id='.$_SESSION['type_report'];
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT name'
				.' FROM  types_report'
				.$whereStr
				.' LIMIT 1';
$resId = mysql_query($str_query);

if ($resId) {
	$mark = mysql_fetch_assoc($resId);
	@mysql_free_result($resId);
}

require_once('templates/_header.php');
?>