<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<link href="../../css/tabtastic.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<style type="text/css" media="screen">
#menu{float:none;}
body{behavior:url(../../script/csshover.htc); font-size:100%;}
#menu ul li{float:left; width: 100%;}
#menu a{height:1%;}
</style>
<![endif]-->

<title>���������</title>

<script type="text/javascript" src="../../script/func.js"></script>
<script type="text/javascript" src="../../script/csshover.htc"></script>

<script type="text/javascript" src="../../script/addclasskillclass.js"></script>
<script type="text/javascript" src="../../script/attachevent.js"></script>
<script type="text/javascript" src="../../script/tabtastic.js"></script>

<script type="text/javascript">	
	var listForms = new Array();	
	<?php 
		for ($i=0; $i<count($listForms); $i++) {
			echo 'listForms['.$i.'] = new Array ("'.$listForms[$i]['f_id'].'", "'
															.$listForms[$i]['nakaz'].'", "'
															.$listForms[$i]['p_id'].'", "'
															.$listForms[$i]['period'].'", "'
															.$listForms[$i]['year'].'", "'
															.phpDateShort($listForms[$i]['num_srok']).'");';
		}
	?>	
	
	function submitForm(mode) {
		form = document.forms['adminForm'];
		correct = true;
		
		if (mode == 'write_blank' && form.name_person.value == 0) {
			correct = false;
			alert('�������� ��������� ������ �����, ��� ������ ������');
		}
		
		if (correct) {
			form.mode.value = mode;
			form.submit();
		}
	}
	
	function select_form(i) {
		form = document.forms['adminForm'];
		
		if (form.elements['form_' + i].value != 0) {			
			str = '<option value="0">(������ �����)</option>';		
		
			for (num=0; num<<?php echo count($listForms); ?>; num++) {
				if (listForms[num][0] == form.elements['form_' + i].value) {
					document.getElementById('nakaz_' + i).innerText = listForms[num][1];
					if (listForms[num][2])
						str += '<option value="' + listForms[num][2] + '">'
								 + listForms[num][3] + ' (' + listForms[num][4] + ')'
								 + '</option>';
				}
			}
			
			form.elements['period_' + i].outerHTML = '<select name="period_' + i + '" style="width:157px" onchange="select_period(' + i + ')">'
																	+ str
																	+ '</select>';
			document.getElementById('srok_' + i).innerHTML =  '&nbsp;';
			document.getElementById('date_f_' + i).innerHTML = '<input type="checkbox" name="date_first_' + i + '" disabled="disabled" \>';
			document.getElementById('cmp_date_' + i).innerHTML = '&nbsp;';
			document.getElementById('date_s_' + i).innerHTML = '<input type="checkbox" disabled="disabled" \>';
		} else {
			form.elements['period_' + i].disabled = true;
			form.elements['period_' + i].selectedIndex = 0;
			
			document.getElementById('nakaz_' + i).innerHTML = '&nbsp;';
			document.getElementById('srok_' + i).innerHTML =  '&nbsp;';
			document.getElementById('date_f_' + i).innerHTML = '&nbsp;';
			document.getElementById('cmp_date_' + i).innerHTML = '&nbsp;';
			document.getElementById('date_s_' + i).innerHTML = '&nbsp;';
		}
	}
	
	function select_period(i) {
		form = document.forms['adminForm'];
		
		if (form.elements['period_' + i].value != 0) {
			document.getElementById('srok_' + i).innerHTML = '&nbsp;';
			document.getElementById('cmp_date_' + i).innerHTML = '&nbsp;';
			
			for (num=0; num<<?php echo count($listForms); ?>	; num++) {
				if (listForms[num][0] == form.elements['form_' + i].value && listForms[num][2] == form.elements['period_' + i].value) {
					document.getElementById('srok_' + i).innerText = listForms[num][5];
					document.getElementById('cmp_date_' + i).innerHTML = '&nbsp;';
				}
			}
			
			form.elements['date_first_' + i].disabled = false;
			form.elements['date_first_' + i].checked = true;
			
			if (!form.elements['form_' + (i + 1)]) add_report(i + 1);
		} else {	
			document.getElementById('srok_' + i).innerHTML =  '&nbsp;';
			
			form.elements['date_first_' + i].checked = false;
			form.elements['date_first_' + i].disabled = true;
			
			document.getElementById('cmp_date_' + i).innerHTML = '&nbsp;';
		}
	}
	
	function add_report(i) {
		//�������� � ���������� ����� ������ � �������
		var newrow = document.getElementById("t_reports").insertRow(i);
		
		newrow.insertCell(0).innerHTML = '<select name="form_' + i + '"'
														+ ' onchange="select_form(' + i + ')">'
															+ '<?php echo $form; ?>'
														+ '</select>';
														
		newrow.insertCell(1).innerHTML = '<select name="period_' + i + '" disabled="disabled" style="width:157px">'
														+ '<option value="0">(������ �����)</option>'
														+ '</select>';
		
		newrow.insertCell(2).outerHTML = '<td id="nakaz_' + i + '" class="grey">&nbsp;</td>';
		newrow.insertCell(3).outerHTML = '<td id="srok_' + i + '" align="center" class="grey">&nbsp;</td>';
		newrow.insertCell(4).outerHTML = '<td id="date_f_' + i + '" align="center">&nbsp;</td>';
		newrow.insertCell(5).innerHTML = '&nbsp;';
		newrow.insertCell(6).outerHTML = '<td id="cmp_date_' + i + '" class="orange">&nbsp;</td>';
		newrow.insertCell(7).outerHTML = '<td id="date_s_' + i + '">&nbsp;</td>';
		newrow.insertCell(8).innerHTML = '&nbsp;';
	}
	
	function add_blank(i) {
		//�������� � ���������� ����� ������ � �������
		var newrow = document.getElementById("t_blanks").insertRow(i);
				
		newrow.insertCell(0).innerHTML = '<select name="b_form_' + i + '" onchange="add_blank(' + (i + 1) + ')">'
															+ '<?php echo $form; ?>'
														+ '</select>';
														
		newrow.insertCell(1).outerHTML = '<td align="right"><input type="text" name="b_amount_' + i + '" size="5" /></td>';
		
		//���������� ������� ��������� �������� ��������
		//��������� (� ��� �����������) ��������� �� �������� ������� ���������� ������
		document.forms['adminForm'].elements['b_form_' + (i - 1)].onchange = '';
	}
</script>

</head>

<body onload="showMsg('<?php echo $ERROR_MSG; ?>')">
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
        <form name="adminForm" action="reg_org.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
            
        <?php require_once('../_respondent.php'); ?>
        
        <ul class="tabset_tabs">
            <li><a href="#report" class="active">���� ������ �������</a></li>
            <li><a href="#blank"<?php echo ($action == 'write_blank') ? ' class="active"' : ''; ?>>���� ������� ������</a></li>
        </ul>
        
        <div id="report" class="tabset_content">
            <h2 class="tabset_label">��������� �������</h2>
                    
            <h3>
            	������ �������,<br />
                ��� �� �������� ���������� �
                <select name="filtr_p" class="in_text" onchange="this.form.submit()">
					<?php echo $allPeriods; ?>
                </select>
                <select name="filtr_y" class="in_text" onchange="this.form.submit()">
					<?php echo $allYears; ?>
                </select>
            </h3>
            
            <table id="t_reports" width="95%">
              <tr class="class_TH">
                <th rowspan="2" width="260">����� �� �����������<br />�����</th>
                <th rowspan="2" width="151">�����,<br /> �� ���� �������� <br />�����</th>
                <th rowspan="2" width="17">���� �� � ������<br />���������<br />������</th>
                <th rowspan="2" width="90">���������<br />����� �������</th>
                <th colspan="2" width="140">�������<br />���������� ����</th>
                <th rowspan="2" width="139">³�����<br />��� ���������</th>
                <th colspan="2">�������<br />����������� ����</th>
              </tr>
              
              <tr class="class_TH">
                <th>����</th>
                <th width="37">�����</th>
                <th>����</th>
                <th width="37">�����</th>
              </tr>
              
              <tr class="class_TD">
                <td colspan="9" align="center"><h4>�� �����Ͳ���</h4></td>
              </tr>
              
              <?php foreach ($listItems as $v) { ?>
              <tr class="class_TD">
                <td><?php echo ($v['form']) ? $v['form'] : '&nbsp;'; ?></td>
                <td><?php echo ($v['period']) ? $v['period'] : '&nbsp;'; ?></td>
                <td><?php echo ($v['nakaz']) ? $v['nakaz'] : '&nbsp;'; ?></td>                
                <td align="center"><?php echo ($v['num_srok']) ? phpDateShort($v['num_srok']) : '&nbsp;'; ?></td>
               
                <td align="center">
                     <?php
                        if ($v['date_first'] == 0) {
                     ?>
                            <input name="date_f_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" />
                     <?php
                        } else {
                            echo phpDateShort($v['date_first']);
                        }
                     ?>
                </td>
                
                <td><?php echo ($v['type_f']) ? $v['type_f'] : '&nbsp;'; ?></td>                
                <td><?php echo cmpDateText($v['date_first'], $v['num_srok']); ?></td>
                
                <td>
                    <input type="checkbox" name="date_s_<?php echo $v['id']; ?>" value="<?php echo $v['id']; ?>"
                        <?php if ($v['date_first'] == 0) { ?>
                            disabled="disabled"
                        <?php } ?>
                    />
					<?php echo ($v['date_second'] != 0) ? phpDateShort($v['date_second']) : '&nbsp;'; ?>
                </td>
                
                <td><?php echo ($v['type_s']) ? $v['type_s'] : '&nbsp;'; ?></td>
              </tr>
              <?php } ?>            
    
              <tr class="class_TD">
                <td colspan="9" align="center"><h4>���� �����Ͳ���</h4></td>
              </tr>
            
              <?php foreach ($listItemsNot as $v) { ?>
              <tr class="class_TD">
                <td><?php echo ($v['form']) ? $v['form'] : '&nbsp;'; ?></td>
                <td><?php echo ($v['period']) ? $v['period'] : '&nbsp;'; ?></td>
                <td><?php echo ($v['nakaz']) ? $v['nakaz'] : '&nbsp;'; ?></td>
                
                <td align="center"><?php echo ($v['num_srok']) ? phpDateShort($v['num_srok']) : '&nbsp;'; ?></td>
                <td align="center"><?php echo ($v['date_first']) ? phpDateShort($v['date_first']) : '&nbsp;'; ?></td>
                <td><?php echo ($v['type_f']) ? $v['type_f'] : '&nbsp;'; ?></td>
                <td><?php echo cmpDateText($v['date_first'], $v['num_srok']); ?></td>
                <td>
                    <input type="checkbox" name="date_s_<?php echo $v['id']; ?>" value="<?php echo $v['id']; ?>" />
                    <?php echo ($v['date_second'] != 0) ? '&nbsp;'.phpDateShort($v['date_second']) : ''; ?>
                </td>
                <td><?php echo ($v['type_s']) ? $v['type_s'] : '&nbsp;'; ?></td>
              </tr>
              <?php } ?>
              
              <tr>
                <td>
                    <select name="form_<?php echo $countItems+4; ?>"
                    			onchange="select_form(<?php echo $countItems+4; ?>);">
                    	<?php echo $form; ?>
                    </select>
                </td>
                
                <td>
                	<select name="period_<?php echo $countItems+4; ?>"
                    			disabled="disabled" style="width:157px">
						<option value="0">(������ �����)</option>
                    </select>
                </td>
                <td id="nakaz_<?php echo $countItems+4; ?>" class="grey">&nbsp;</td>
                <td id="srok_<?php echo $countItems+4; ?>" align="center" class="grey">&nbsp;</td>
                <td id="date_f_<?php echo $countItems+4; ?>" align="center">&nbsp;</td>
                <td>&nbsp;</td>
                <td id="cmp_date_<?php echo $countItems+4; ?>" class="orange">&nbsp;</td>
                <td id="date_s_<?php echo $countItems+4; ?>" >&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
            
            <div align="center">
                <input type="button" id="write" value="������������ ����" class="button" onclick="submitForm('write_report')" />
            </div>            
        </div>
        
        <div id="blank" class="tabset_content">
          <h2 class="tabset_label">���� ������ ������</h2>
          
          <h3>
          	������ ������, �� ���������<br />
          	������ ����������� ��
            <select name="filtr_y_b" class="in_text" onchange="this.form.submit()">
				<?php echo $allYears_b; ?>
            </select>
            ������� ����
          </h3>
            
            <div align="center">
            	�����, ��� ������ ������: <input type="text" name="name_person" size="40" />
            </div>
            
            <table id="t_blanks" width="50%">
              <tr class="class_TH">
                <th>����� �� �����������<br />�����</th>
                <th>ʳ������ �������<br />���������</th>
              </tr>
              
              <?php foreach ($listBlanks as $v) { ?>
              <tr class="class_TD">
                <td><?php echo $v['form']; ?></td>
                <td align="right"><input type="text" name="b_amount_<?php echo $v['id_form']; ?>" size="5" /></td>        
              </tr>
              <?php } ?>
              
              <tr>
                <td>
                	<select name="b_form_<?php echo $countBlanks+1; ?>"
                			onchange="add_blank(<?php echo $countBlanks+2; ?>)">
						<?php echo $form; ?>
                    </select>
                </td>
                <td align="right"><input type="text" name="b_amount_<?php echo $countBlanks+1; ?>" size="5" /></td>
              </tr>
            </table>
            
            <div align="center">
            	<input id="write" type="button" value="������������ ������" class="button" onclick="submitForm('write_blank')" />
            </div>                                   
            
            <?php if (count($listBlanksOut)) { ?>
                <h3>
                	������ ������, �� ����<br />
                    ������ ����������� ��
                    <script>
                        document.write(document.adminForm.filtr_y_b.options[document.adminForm.filtr_y_b.selectedIndex].text);
                    </script>
                    ������� ����
                </h3>
                       
                <table width="50%">
                  <?php foreach ($listBlanksOut as $k => $v) { ?>
                  <tr class="class_TH">
                    <th colspan="2"><?php echo phpDateShort($k); ?></td>
                  </tr>
                  
                      <?php foreach ($v as $key => $value) { ?>
                      <tr align="center">
                        <td colspan="2"><h4><?php echo $key; ?></h4></td>
                      </tr>              
                      
                          <?php foreach ($value as $val) { ?>
                          <tr class="class_TD_top">
                            <td><?php echo $val['form'].' ('.$val['period_f'].')'; ?></td>
                            <td width="10%" align="right"><?php echo $val['amount'].' ��.'; ?></td>
                          </tr>
                          <?php } ?>
                      <?php } ?>
                  <?php } ?>
                </table>
            <?php } ?>
        </div>
        
        <div align="center" style="margin-bottom:22px">
            <input id="write" type="button" value="������ �� �������� �����" class="button"
            						onclick="printDoc()" />
            <input id="write" type="button" value="������ �� �������� ��" class="button"
            						onclick="window.open('../../_tabel/index.php', 'tabel')" />
        </div>
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>