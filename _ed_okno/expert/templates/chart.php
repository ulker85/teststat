<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<style type="text/css" media="screen">
#menu{float:none;}
body{behavior:url(../../script/csshover.htc); font-size:100%;}
#menu ul li{float:left; width: 100%;}
#menu a{height:1%;}
</style>
<![endif]-->

<title>Графік надання звітності</title>

<script type="text/javascript" src="../../script/func.js"></script>
<script type="text/javascript" src="../../script/csshover.htc"></script>

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
		correct = true;
		form = document.forms['adminForm'];
		
		if (mode == 'del') {
			correct = confirm('Видалити відмічені записи?');
		}
		
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
			
			form.elements['period_' + i].outerHTML = '<select name="period_' + i + '" style="width:157px" onchange="add_row(' + (i + 1) + ')">'
																	+ str
																	+ '</select>';
		} else {
			form.elements['period_' + i].disabled = true;
			form.elements['period_' + i].selectedIndex = 0;
		}
	}
	
	function add_row(i) {
		form = document.forms['adminForm'];
		
		myYear = document.forms['adminForm'].filtr_y.value;
		myMonth = form.elements['filtr_p'].selectedIndex + 1;
		if (myMonth < 10) myMonth = '0' + myMonth;
		
		//отключение события изменение значения элемента
		//повторное (и все последующие) изменение не вызывает функцию добавления строки
		form.elements['period_' + (i - 1)].onchange = '';
				
		//создание и заполнение новой строки в таблице
		var newrow = document.getElementById("my_table").insertRow(i);
				
		newrow.insertCell(0).innerHTML = '<select name="form_' + i + '"onchange="select_form(' + i + ')">'
														+ '<?php echo $form_name; ?>'
														+ '</select>';
		
		newrow.insertCell(1).innerHTML = '<select name="period_' + i + '" disabled="disabled" style="width:157px">'
													+ '<option value="0">(оберіть період)</option>'
													+ '</select>';
		
		newrow.insertCell(2).innerHTML = '<div align="center">'
														+ '<input type="text" name="day_' + i + '" style="width:16px" />'
														+ ' .' + myMonth + '.' + myYear
														+ '</div>';
	}
</script>

</head>

<body>
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
        <?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>
        
    	<form name="adminForm" action="chart.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />                
       
        <h3 style="margin-top:30px">
            Графік<br />надання державної звітності на
        	<select name="filtr_p" id="fitr_p" class="in_text" style="width:100px;" onchange="submitForm();">
				<?php echo $allPeriods; ?>
            </select>
             <select name="filtr_y" class="in_text" onchange="submitForm();">
					<?php echo $allYears; ?>
                </select>
        </h3>
        
        <table id="my_table" width="700">
          <tr class="class_TH">
            <th>Назва та періодичність<br />форми</th>
            <th>За який період<br />подається</th>
            <th>Граничний<br />термін подання</th>
            <th>Редагування</th>
            <th>Видалення</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td><?php echo $v['form']; ?></td>
            <td><?php echo $v['period_r']; ?></td>
            <td align="center"><?php echo phpDateShort($v['num_srok']); ?></td>
            
            <td align="center">
                <input type="text" name="txt_<?php echo $v['id']; ?>" style="width:16px" />
                <script type="text/javascript">makeDate();</script>
            </td>
            
            <td align="center">
            	<input type="checkbox" name="chk_<?php echo $v['id']; ?>" value="<?php echo $v['id']; ?>" />
            </td>
          </tr>
          <?php } ?>
          
          <tr>
            <td>
                <select name="form_<?php echo count($listItems)+1; ?>"
                		onchange="select_form(<?php echo count($listItems)+1; ?>)">
					<?php echo $form_name; ?>
                </select>
            </td>
            
            <td>
                <select name="period_<?php echo count($listItems)+1; ?>"
                    			disabled="disabled" style="width:157px">
					<option value="0">(оберіть період)</option>
                </select>
            </td>
            <td align="center">
                <input type="text" name="day_<?php echo count($listItems)+1; ?>" style="width:16px" />
                <script type="text/javascript">makeDate();</script>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
          	<td colspan="2">&nbsp;</td>
            <td align="center"><input type="button" id="write" value="Записати" class="button" onclick="submitForm('write')" /></td>
            <td align="center"><input type="button" id="edit" value="Редагувати" class="button" onclick="submitForm('edit')" /></td>
            <td align="center"><input type="button" id="del" value="Видалити" class="button" onclick="submitForm('del')" /></td>
          </tr>
        </table>           
        
        <div align="center" style="margin-bottom:22px">
            <input id="exp" type="button" value="Вивантажити" class="button" onclick="submitForm('exp_doc')" />
        </div>
        </form>
    </div>
</div>
    
<?php require_once('_footer.php'); ?>
</body>
</html>