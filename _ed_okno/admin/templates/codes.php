<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Електронка</title>

<script type="text/javascript" src="../../script/func.js"></script>
<script type="text/javascript" src="../../script/csshover.htc"></script>

<!--[if lt IE 7]>
<style type="text/css" media="screen">
#menu{float:none;}
body{behavior:url(../../script/csshover.htc); font-size:100%;}
#menu ul li{float:left; width: 100%;}
#menu a{height:1%;}
</style>
<![endif]-->

<script type="text/javascript">
	function submitForm(mode) {
		correct = true;
		form = document.forms['adminForm'];
		subform = document.forms['subAdminForm'];
		
		if (mode == 'del') {
			correct = confirm('Видалити відмічені записи?');
		} else if (mode == 'add' && subform.form_name.value == 0) {
			correct = false;			
			alert('Спочатку необхідно обрати форму');
		} else if (mode == 'add' && subform.elzvit.value == '') {
			correct = false;			
			alert('Спочатку необхідно заповнити код ел. звітності');
		} else if (mode == 'add') {
			form = subform;
		}
		
		if (correct) {
			form.mode.value = mode;
			form.submit();
		}
	}
</script>

</head>

<body>
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
		<?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>
        
        <form name="adminForm" action="codes.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
         <h2>Коди форм звітності</h2>
            
        <div class="item_beige" style="float:left; margin-left:25%; width:300px;">
            <h2>Порядкове редагування записів</h2>
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
                <div class="navigation_left">
                    <input id="btnAdd" type="button" value="Додати" onclick="document.getElementById('addForm').style['display']='block'" class="button" />
                    <input id="edit" type="button" value="Редагувати" class="button" onclick="submitForm('edit')" />
                </div>
                
                <div class="navigation_right">
                    <input id="del" type="button" value="Видалити" class="button" onclick="submitForm('del')" />
                </div>
            </p>
        </div>
        
        <div class="item_beige" style="float:left; margin-left:25px; width:300px;">
            <h2>Імпорт ел. звітності</h2>
            
            <p>Оберіть файл з інформацією для оновлення:</p>
            <p><input type="file" name="fileImpElzv" style="width:256px" /></p>     
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p align="center"><input type="button" id="elzv" value="Імпортувати" class="button" onclick="submitForm('elzv')" /></p>
        </div>
        <div class="clr"></div>
        
        <div class="item_blue" style="float:left; margin-left:37%; width:300px;">
            <h2>Вибірка даних</h2>
            
            <p>
                <div class="navigation_left">За формами:</div>
                <div class="navigation_right"><select name="filtr_f" style="width:206px" onchange="this.form.submit()"><?php echo $allForm; ?></select></div>
                <div class="clr"></div>
            </p>
                    
            <p>
                <div class="navigation_left">За періодами:</div>
                <div class="navigation_right"><select name="filtr_p" style="width:206px" onchange="this.form.submit()"><?php echo $allPeriodsReport; ?></select></div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
    
        <table>
          <tr class="class_TH">
            <th>id</th>
            <th>Індекс форми<br />та періодичність</th>
            <th>Код за<br />ел. звітністю</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td><?php echo $v['id']; ?></td>
    
            <td><select name="form_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['form']; ?></select></td>
            
            <td><input type="text" name="elzvit_<?php echo $v['id']; ?>" value="<?php echo $v['code_elzvit']; ?>" onclick="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
                                        
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
            <h2>Додати новий код</h2>
            
            <form name="subAdminForm" action="codes.php" method="post">
            <input type="hidden" name="mode" />    
            <table width="400" border="0">
              <tr>
                <td>Назва форми</td>
                <td><select name="form_name"><?php echo $form; ?></select></td>
              </tr>
              <tr>
                <td>Код за ел. звітністю</td>
                <td><input name="elzvit" type="text" /></td>
              </tr>
        
              <tr>
                <td><input id="add" type="button" value="Додати запис" class="button" onclick="submitForm('add')" /></td>
                <td><input type="button" value="Відмінити" class="button" onclick="document.getElementById('addForm').style['display']='none'" /></td>
              </tr>
            </table>            
            </form>
        </div>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>