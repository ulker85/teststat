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

<title>Коригування</title>

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
															.$listForms[$i]['p_id'].'", "'
															.$listForms[$i]['period'].'", "'
															.$listForms[$i]['year'].'");';
		}
	?>	
	
	function submitForm(mode) {
		form = document.forms['adminForm'];
		correct = true;
		
		if (correct) {
			form.mode.value = mode;
			form.submit();
		}
	}
	
	function select_form(i) {
		form = document.forms['adminForm'];
		
		if (form.elements['form_' + i].value != 0) {			
			str = '<option value="0">(оберіть період)</option>';		
		
			for (num=0; num<<?php echo count($listForms); ?>; num++) {
				if (listForms[num][0] == form.elements['form_' + i].value) {
					if (listForms[num][1])
						str += '<option value="' + listForms[num][1] + '">'
								 + listForms[num][2] + ' (' + listForms[num][3] + ')'
								 + '</option>';
				}
			}
			
			form.elements['period_' + i].outerHTML = '<select name="period_' + i + '" style="width:178px" onchange="select_period(' + i + ')">'
																	+ str
																	+ '</select>';
			document.getElementById('date_f_' + i).innerHTML = '<input type="text" name="date_first_' + i + '" style="width:65px;" disabled="disabled" \>';
			document.getElementById('type_f_' + i).innerHTML = '<select name="type_first_' + i + '" style="width:143px;" disabled="disabled" \>'
																					+ '<?php echo $type; ?>'
																					+ '</select>';
			document.getElementById('user_f_' + i).innerHTML = '<select name="user_first_' + i + '" disabled="disabled" \>'
																					+ '<?php echo $user; ?>'
																					+ '</select>';
			document.getElementById('date_s_' + i).innerHTML = '<input type="text" style="width:65px;" disabled="disabled" \>';
			document.getElementById('type_s_' + i).innerHTML = '<select style="width:143px;" disabled="disabled" \>'
																					+ '<option value="0">(оберіть спосіб)</option>'
																					+ '</select>';
			document.getElementById('user_s_' + i).innerHTML = '<select disabled="disabled" \>'
																					+ '<option value="0">(оберіть користув.)</option>'
																					+ '</select>';
		} else {
			form.elements['period_' + i].disabled = true;
			form.elements['period_' + i].selectedIndex = 0;
			
			document.getElementById('date_f_' + i).innerHTML = '&nbsp;';
			document.getElementById('type_f_' + i).innerHTML = '&nbsp;';
			document.getElementById('user_f_' + i).innerHTML = '&nbsp;';
			document.getElementById('date_s_' + i).innerHTML = '&nbsp;';
			document.getElementById('type_s_' + i).innerHTML = '&nbsp;';
			document.getElementById('user_s_' + i).innerHTML = '&nbsp;';
		}
	}
	
	function select_period(i) {
		form = document.forms['adminForm'];
		
		if (form.elements['period_' + i].value != 0) {
			form.elements['date_first_' + i].disabled = false;
			form.elements['type_first_' + i].disabled = false;
			form.elements['user_first_' + i].disabled = false;
			
			if (!form.elements['form_' + (i + 1)]) add_report(i + 1);
		} else {				
			form.elements['date_first_' + i].value = '';
			form.elements['date_first_' + i].disabled = true;
			
			form.elements['type_first_' + i].disabled = true;
			form.elements['user_first_' + i].disabled = true;
		}
	}
	
	function add_report(i) {
		//создание и заполнение новой строки в таблице
		var newrow = document.getElementById("t_reports").insertRow(i);
		
		newrow.insertCell(0).innerHTML = '<select name="form_' + i + '"'
														+ ' onchange="select_form(' + i + ')">'
															+ '<?php echo $form; ?>'
														+ '</select>';
														
		newrow.insertCell(1).innerHTML = '<select name="period_' + i + '" disabled="disabled" style="width:178px">'
														+ '<option value="0">(оберіть період)</option>'
														+ '</select>';
		
		newrow.insertCell(2).outerHTML = '<td id="date_f_' + i + '">&nbsp;</td>';
		newrow.insertCell(3).outerHTML = '<td id="type_f_' + i + '">&nbsp;</td>';
		newrow.insertCell(4).outerHTML = '<td id="user_f_' + i + '">&nbsp;</td>';
		newrow.insertCell(5).outerHTML = '<td id="date_s_' + i + '">&nbsp;</td>';
		newrow.insertCell(6).outerHTML = '<td id="type_s_' + i + '">&nbsp;</td>';
		newrow.insertCell(7).outerHTML = '<td id="user_s_' + i + '">&nbsp;</td>';
	}
</script>

</head>

<body onload="showMsg('<?php echo $ERROR_MSG; ?>')">
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
        <form name="adminForm" action="kor_org.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
            
        <?php require_once('../_respondent.php'); ?>
        
        <ul class="tabset_tabs">
            <li><a href="#report" class="active">Облік поданої звітності</a></li>
            <li><a href="#blank"<?php echo ($action == 'write_blank') ? ' class="active"' : ''; ?>>Облік виданих бланків</a></li>
        </ul>
        
        <div id="report" class="tabset_content">
            <h2 class="tabset_label">Реєстрація звітності</h2>
                    
            <h3>
            	Перелік звітності,<br />
                яку має подавати респондент у
                <select name="filtr_p" class="in_text" onchange="this.form.submit()">
					<?php echo $allPeriods; ?>
                </select>
                <select name="filtr_y" class="in_text" onchange="this.form.submit()">
					<?php echo $allYears; ?>
                </select>
            </h3>
            
            <table id="t_reports">
              <tr class="class_TH">
                <th rowspan="2" width="260">Назва та періодичність форми</th>
                <th rowspan="2" width="151">Період, за який подається форма</th>
                <th colspan="3" width="140">Подання первинного звіту</th>
                <th colspan="3">Подання коригуючого звіту</th>
                <th rowspan="2">&nbsp;</th>
              </tr>
              
              <tr class="class_TH">
                <th>Дата</th>
                <th width="37">Спосіб</th>
                <th>Користувач</th>
                <th>Дата</th>
                <th width="37">Спосіб</th>
                <th>Користувач</th>
              </tr>
              
              <tr class="class_TD">
                <td colspan="9" align="center"><h4>ЗА СУКУПНІСТЮ</h4></td>
              </tr>
              
              <?php foreach ($listItems as $v) { ?>
              <tr class="class_TD">
                <td>
					<?php echo ($v['form']) ? $v['form'] : '&nbsp;'; ?>
                </td>
                
                <td>
					<?php echo ($v['period']) ? $v['period'] : '&nbsp;'; ?>
                </td>
               
                <td>
                	<input type="text" name="date_f_<?php echo $v['id']; ?>" style="width:65px;"
                        value="<?php echo phpDateShort($v['date_first']); ?>"
                        onchange="getValue('<?php echo $v['id']; ?>')" />
                </td>
                
                <td>
                    <select name="type_f_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['type_f']; ?>
                    </select>
                </td>
                
                <td>
                    <select name="user_f_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['user_f']; ?>
                    </select>
                </td>
                
                <td>
                	<input type="text" name="date_s_<?php echo $v['id']; ?>" style="width:65px;"
                        value="<?php echo phpDateShort($v['date_second']); ?>"
                        onchange="getValue('<?php echo $v['id']; ?>')" />
                </td>
                
                <td>
                    <select name="type_s_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['type_s']; ?>
                    </select>
                </td>
                
                <td>
                    <select name="user_s_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['user_s']; ?>
                    </select>
                </td>
                
                <td>
                	<input type="hidden" id="<?php echo $v['id']; ?>" name="edit_<?php echo $v['id']; ?>" value="" />
                </td>
              </tr>
              <?php } ?>            
    
              <tr class="class_TD">
                <td colspan="9" align="center"><h4>ПОЗА СУКУПНІСТЮ</h4></td>
              </tr>
            
              <?php foreach ($listItemsNot as $v) { ?>
              <tr class="class_TD">
                <td>
                    <select name="form_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['form']; ?>
                    </select>
                </td>
                
                <td>
                    <select name="period_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['period']; ?>
                    </select>
                </td>
                
                <td>
                	<input type="text" name="date_f_<?php echo $v['id']; ?>" style="width:65px;"
                        value="<?php echo phpDateShort($v['date_first']); ?>"
                        onchange="getValue('<?php echo $v['id']; ?>')" />
                </td>
                
                <td>
                    <select name="type_f_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['type_f']; ?>
                    </select>
                </td>
                
                <td>
                    <select name="user_f_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['user_f']; ?>
                    </select>
                </td>
                
                <td>
                	<input type="text" name="date_s_<?php echo $v['id']; ?>" style="width:65px;"
                        value="<?php echo phpDateShort($v['date_second']); ?>"
                        onchange="getValue('<?php echo $v['id']; ?>')" />
                </td>
                
                <td>
                    <select name="type_s_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['type_s']; ?>
                    </select>
                </td>
                
                <td>
                    <select name="user_s_<?php echo $v['id']; ?>" onchange="getValue('<?php echo $v['id']; ?>')">
                    	<?php echo $v['user_s']; ?>
                    </select>
                </td>
				
                <td>
                	<input type="hidden" id="<?php echo $v['id']; ?>" name="edit_<?php echo $v['id']; ?>" value="" />
                    <input type="checkbox" name="del_<?php echo $v['id']; ?>"
                            value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" />
                </td>
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
                    		disabled="disabled" style="width:178px">
						<option value="0">(оберіть період)</option>
                    </select>
                </td>
                
                <td id="date_f_<?php echo $countItems+4; ?>">&nbsp;</td>
                <td id="type_f_<?php echo $countItems+4; ?>">&nbsp;</td>
                <td id="user_f_<?php echo $countItems+4; ?>">&nbsp;</td>
                <td id="date_s_<?php echo $countItems+4; ?>">&nbsp;</td>
                <td id="type_s_<?php echo $countItems+4; ?>">&nbsp;</td>
                <td id="user_s_<?php echo $countItems+4; ?>">&nbsp;</td>
                
                <td>
                	<input type="hidden" id="<?php echo $countItems+4; ?>" name="add_<?php echo $countItems+4; ?>" />
                </td>
              </tr>
            </table>
            
            <div align="center">
                <input type="button" id="write" value="Внести зміни в звіти" class="button" onclick="submitForm('edit_report')" />
            </div>            
        </div>
        
        <div id="blank" class="tabset_content">
           <h2 class="tabset_label">Облік видачі бланків</h2>
            
            <h3>
                Перелік бланків, які було<br />
                видано респонденту по
                <select name="filtr_y_b" class="in_text" onchange="this.form.submit()">
                    <?php echo $allYears_b; ?>
                </select>
                звітному року
            </h3>
                       
            <?php if (count($listBlanksOut)) { ?>
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
                            <td width="10%" align="right"><?php echo $val['amount'].' шт.'; ?></td>
                          </tr>
                          <?php } ?>
                      <?php } ?>
                  <?php } ?>
                </table>
            <?php } ?>
          
            <div align="center">
            	<input id="write" type="button" value="Внести зміни в бланки" class="button" onclick="submitForm('edit_blank')" />
            </div>                                   
        </div>
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>