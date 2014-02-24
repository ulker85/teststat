<?php
//��������� ������� ������ "������ ��.����������"
} else if ($action == 'elzv') {
	set_time_limit(300);
	
	do {
		if (!file_exists($tmp_file = $_FILES['fileImpElzv']['tmp_name'])) {
			$str = '������� ������������ �����. ������ ���������.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		}
		
		//=================================================================
		//���������� � ���������� ������������
		//=================================================================
		
		//-------�������� ������� sukup_tmp-------
		mysql_query('TRUNCATE TABLE elzv_tmp');
		//=================================================================
		
		//��������� ������ �� �����
		$file_csv = file($tmp_file);
		unlink($tmp_file);
		
		//������������ ������ �� �����
		foreach ($file_csv as $v) {
			$fields = explode(';', trim(chop($v)));
			
			$query_str = 'INSERT INTO elzv_tmp'
							.' (date, form, period, edrpou, reg_num)'
							.' VALUES ('
							    .'"'.sqlDateFull($fields[0]).'"'
								.', "'.$fields[1].'"'
								.', "'.substr($fields[2], 0, strrpos($fields[2], ' ')).'"'
								.', '.$fields[3]
								.', '.$fields[4]
							.')';
			
			$resId = mysql_query($query_str);
			if ($resId) {
				$str = '��������� ����� "������������ � '.$fields[4].'".';
				traceMsg($str);
			} else {
				$str = '������� ������ ��� ������� ������ "������������ � '.$fields[4].'". ������ ���������.';
				$ERROR_MSG .= $str.'<br />';
				traceMsg($str);
				break(2);
			}
		}
		
		$query_str = 'SELECT et.*, o.id AS id_org, cf.id_form, pr.id AS id_pr, r.id AS id_reports, r.date_first, r.num_registration'
						.' FROM elzv_tmp AS et'
							.' LEFT JOIN periods_report AS pr'
								.' ON et.period=pr.name_short'
							.' LEFT JOIN organizations AS o'
								.' ON et.edrpou=o.edrpou'
							.' LEFT JOIN codes_form AS cf'
								.' ON et.form=cf.code_elzvit'
							.' LEFT JOIN reports AS r'
								.' ON cf.id_form=r.id_form AND pr.id=r.id_period_report AND o.id=r.id_organization'
							.' ORDER BY et.date';
		traceMsg($query_str);	
		
		$resId = mysql_query($query_str);
		if ($resId) {
			while ($row = mysql_fetch_assoc($resId)) {
				if ($row['id_form'] == '') {
					$str = '�������� �������� ���� ����� '.$row['form'].' � �������� "codes_form"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				} 
				if ($row['id_org'] == '') {
					$str = '�������� �������� ���������� '.$row['edrpou'].' � �������� "organizations"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				}
				if ($row['id_pr'] == '') {
					$str = '�������� �������� ����� ������ '.$row['period'].' � �������� "periods_report"';
					$ERROR_MSG .= $str.'<br />';
					traceMsg($str);
					continue;
				}
				
				if ($row['id_reports'] == '') {
					$query_str = 'INSERT INTO reports'
									.' (num_registration, id_organization, id_form, id_period_report, date_first, id_type_first, id_user_first, not_in_sukup)'
									.' VALUES ('
										.'"'.$row['reg_num'].'"'
										.', '.$row['id_org']
										.', '.$row['id_form']
										.', '.$row['id_pr']
										.', "'.$row['date'].'"'
										.', '.$_SESSION['type_report']
										.', '.$_SESSION['user']
										.', 1'
									.')';
					traceMsg($query_str);	
		
					mysql_query($query_str);
				} else {
					if ($row['date_first'] == 0) {
						$query_str = 'UPDATE reports'
										.' SET id_organization='.$row['id_org']
											.', id_form='.$row['id_form']
											.', id_period_report='.$row['id_pr']
											.', num_registration="'.$row['reg_num'].'"'
											.', date_first="'.$row['date'].'"'
											.', id_type_first='.$_SESSION['type_report']
											.', id_user_first='.$_SESSION['user']
										.' WHERE id='.$row['id_reports'];
						traceMsg($query_str);
						
						mysql_query($query_str);
					} else {
						if ($row['num_registration'] == '') {
							$query_str = 'UPDATE reports'
											.' SET id_organization='.$row['id_org']
												.', id_form='.$row['id_form']
												.', id_period_report='.$row['id_pr']
												.', num_registration="'.$row['reg_num'].'"'
												.', date_second="'.$row['date'].'"'
												.', id_type_second='.$_SESSION['type_report']
												.', id_user_second='.$_SESSION['user']
											.' WHERE id='.$row['id_reports'];
							traceMsg($query_str);
							
							mysql_query($query_str);
						} else {
							$query_str = 'UPDATE reports'
											.' SET id_organization='.$row['id_org']
												.', id_form='.$row['id_form']
												.', id_period_report='.$row['id_pr']
												.', date_second="'.$row['date'].'"'
												.', id_type_second='.$_SESSION['type_report']
												.', id_user_second='.$_SESSION['user']
											.' WHERE id='.$row['id_reports'];
							traceMsg($query_str);
							
							mysql_query($query_str);
						}
					}
				}
			}
			
			$str = '������ ���������.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		} else {
			$str = '������� ������ ��� �������. ������ ���������.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		}
	} while (false);
	
//��������� ������� ������ "������ ���.����������"
} else if ($action == 'rayzv') {
	set_time_limit(300);
	
	do {
		if (!file_exists($tmp_file = $_FILES['fileImpRayzv']['tmp_name'])) {
			$str = '������� ������������ �����. ������ ���������.';
			$ERROR_MSG .= $str.'<br />';
			traceMsg($str);
			break;
		}
	} while (false);
?>