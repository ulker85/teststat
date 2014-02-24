<?php
require_once('_start.php');

$flag = false;
//=========================================
//==  ������������ ������� �� ������� ����,
//==  ������� ������� �� ������������
//=========================================

// ������� ������� ������
$where = array();
$where[] = 'krugi.id_organization='.$_SESSION['edrpou'];
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT org.edrpou, org.name AS org_name, org.ter AS org_ter, org.opf'
						.', f.date_update, f.name AS f_name, f.name_full AS f_name_full, f.srok_sdachi, f.note'
						.', f.blank, f.instr, f.rozyasn, f.ekspres, f.region, f.m_rayon, f.two_level'
						.', p_f.name AS f_period'
						.', d_f.name AS df_name, d_f.room_num AS df_room, d_f.adres AS df_adres, d_f.tel AS df_tel, d_f.tel_mob AS df_mob, d_f.fax AS df_fax'
						.', d_o.name AS do_name, d_o.room_num AS do_room, d_o.adres AS do_adres, d_o.tel AS do_tel, d_o.tel_mob AS do_mob, d_o.fax AS do_fax'
				.' FROM krugi'
					.' LEFT JOIN organizations AS org'
						.' ON krugi.id_organization=org.id'
					.' LEFT JOIN forms AS f'
						.' ON krugi.id_form=f.id'
					.' LEFT JOIN periods_form AS p_f'
						.' ON f.id_period_form=p_f.id'
					.' LEFT JOIN departments AS d_f'
						.' ON f.id_department=d_f.id'
					.' LEFT JOIN departments AS d_o'
						.' ON org.ter=d_o.ter'
				.$whereStr
				.' GROUP BY f.id'
				.' ORDER BY f.name, p_f.name';
					
$resId = mysql_query($str_query);
if ($resId) {
	$listItems = array();
	
	if (mysql_num_rows($resId) > 0) {		
		while ($row = mysql_fetch_assoc($resId)) {
			if ($row['two_level'] == true || in_array($row['org_ter'], $_terInCity)) {
				$listItems[] = $row + array('department' => $row['df_name'],
													'room' => $row['df_room'],
													'adres' => $row['df_adres'],
													'tel' => $row['df_tel'],
													'mob' => $row['df_mob'],
													'fax' => $row['df_fax']);
			} else {
				$listItems[] = $row + array('department' => $row['do_name'],
													'room' => $row['do_room'],
													'adres' => $row['do_adres'],
													'tel' => $row['do_tel'],
													'mob' => $row['do_mob'],
													'fax' => $row['do_fax']);
			}
		}
		
		@mysql_free_result($resId);
	} else {
		@mysql_free_result($resId);
		$flag = true;
		
		// ������� ������� ������
		$where = array();
		$where[] = 'id='.$_SESSION['edrpou'];
		$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		// ������� �� ��������
		$str_query = 'SELECT org.edrpou, org.name AS org_name, org.ter AS org_ter, org.opf'
						.' FROM organizations AS org'
						.$whereStr
						.' LIMIT 1';
							
		$resId = mysql_query($str_query);
		if ($resId) {
			$listItems[] = mysql_fetch_assoc($resId);
			
			@mysql_free_result($resId);
		}
	}
}

//=========================================
//==  ������������ ������� �� ������� ����,
//==  ������� ������� �� �� ������������
//=========================================

// ������� ������� ������
$where = array();
$where[] = 'f.to_all_resp=1';
$whereStr = ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
// ������� �� ��������
$str_query = 'SELECT  f.date_update, f.name AS f_name, f.name_full AS f_name_full, f.srok_sdachi, f.note'
						.', f.blank, f.instr, f.rozyasn, f.ekspres, f.region, f.m_rayon, f.two_level, f.id AS f_id'
						.', p_f.name AS f_period'
						.', d.name AS d_name, d.room_num AS d_room, d.tel AS d_tel, d.tel_mob AS d_mob, d.fax AS d_fax, d.adres AS d_adres'
				.' FROM forms AS f'
					.' LEFT JOIN periods_form AS p_f'
						.' ON f.id_period_form=p_f.id'
					.' LEFT JOIN departments AS d'
						.' ON f.id_department=d.id'
					.$whereStr;
$resId = mysql_query($str_query);

if ($resId) {	
	while ($row = mysql_fetch_assoc($resId)) {
		//�������� �����������, ������� ������ ������������ � ���
		if ($row['two_level'] == true || in_array($listItems[0]['org_ter'], $_terInCity)) {
			//�������� ������ ���.���������� (95 - �������; 96 - �����������)
			if ($row['f_id'] == 95 || $row['f_id'] == 96) {
				//�������� ������: ������ ����������� � �������� ���
				if (!in_array($listItems[0]['opf'], $opfFinZvitn)) $listItems[] = $row + array('department' => $row['d_name'],
																													'room' => $row['d_room'],
																													'adres' => $row['d_adres'],
																													'tel' => $row['d_tel'],
																													'mob' => $row['d_mob'],
																													'fax' => $row['d_fax']);
			//��������� ����������
			} else
				$listItems[] = $row + array('department' => $row['d_name'],
													'room' => $row['d_room'],
													'adres' => $row['d_adres'],
													'tel' => $row['d_tel'],
													'mob' => $row['d_mob'],
													'fax' => $row['d_fax']);
		
		//���������� �����������, ������� ������ ������������ � �������� ��
		} else {
			//�������� ������ ���.���������� (95 - �������; 96 - �����������)
			if ($row['f_id'] == 95 || $row['f_id'] == 96) {
				if (!in_array($listItems[0]['opf'], $opfFinZvitn)) $listItems[] = $row + array('department' => $listItems[0]['do_name'],
																													'room' => $listItems[0]['do_room'],
																													'adres' => $listItems[0]['do_adres'],
																													'tel' => $listItems[0]['do_tel'],
																													'mob' => $listItems[0]['do_mob'],
																													'fax' => $listItems[0]['do_fax']);
			} else
				$listItems[] = $row + array('department' => $listItems[0]['do_name'],
													'room' => $listItems[0]['do_room'],
													'adres' => $listItems[0]['do_adres'],
													'tel' => $listItems[0]['do_tel'],
													'mob' => $listItems[0]['do_mob'],
													'fax' => $listItems[0]['do_fax']);
		}
	}
	@mysql_free_result($resId);
}

//������������ ������ ��� ������� � ����������
//��� ������ ������������
function makeLink ($strLink, $comment) {
	$prepStr = '';
	$masStrLink = explode(';', $strLink);
	foreach ($masStrLink as $value) {
		$value = trim($value);
		$p = explode('(', $value);
		if ($p[1] <> '') $p[1] = ' '.substr($p[1], 0, strlen($p[1])-1);
		if ($prepStr <> '') $prepStr .= '<br />';
		$prepStr .= '<a href="'.$p[0].'" target="_blank">'.$comment.$p[1].'</a>';
	}
	return $prepStr;
}

require_once('templates/index.php');
require_once('_stop.php');
?>