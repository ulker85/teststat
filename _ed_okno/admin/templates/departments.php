<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Відділи</title>

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
		
		if (mode == 'import' && form.fileImp.value == '') {
			correct = false;			
			alert('Спочатку необхідно обрати файл для імпорту');
		} else if (mode == 'del')
			correct = confirm('Видалити відмічені записи?');
		else if (mode == 'add')
			form = subform;
			
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
        
        <form name="adminForm" action="departments.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Відділи</h2>
            
        <div class="item_beige" style="float:left; margin-left:20%; width:300px;">
            <h2>Порядкове редагування записів</h2>
            
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
        
        <div class="item_beige" style="float:right; margin-right:20%; width:300px;">
            <h2>Оновлення даних з файлу</h2>
            
            <p> Оберіть файл з інформацією:</p>
            <p><input type="file" name="fileImp" style="width:276px" /></p>     
            <p align="center"><input id="import" type="button" value="Імпортувати" class="button" onclick="submitForm('import')" /></p>
        </div>
        <div class="clr"></div>
        
        <table width="95%">
          <tr class="class_TH">
            <th>Id</th>
            <th>Назва відділу</th>
            <th>Номер<br />кабінету</th>
            <th>Телефон</th>
            <th>Мобільний<br />телефон</th>
            <th>Факс</th>
            <th>Код<br />території</th>
            <th>Адреса</th>
            <th>&nbsp;</th>
          </tr>
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td align="right"><?php echo $v['id']; ?></td>
            <td><input name="name_<?php echo $v['id']; ?>" type="text" size="50" value="<?php echo $v['name']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="room_<?php echo $v['id']; ?>" type="text" size="10" value="<?php echo $v['room_num']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="tel_<?php echo $v['id']; ?>" type="text" size="20" value="<?php echo $v['tel']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="telMob_<?php echo $v['id']; ?>" type="text" size="20" value="<?php echo $v['tel_mob']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="fax_<?php echo $v['id']; ?>" type="text" size="20" value="<?php echo $v['fax']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="ter_<?php echo $v['id']; ?>" type="text" size="5" value="<?php echo $v['ter']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="adres_<?php echo $v['id']; ?>" type="text" value="<?php echo $v['adres']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php  } ?>
        </table>
        </form>
        
        <div id="addForm">
            <h2>Додати новий відділ</h2>
            
            <form name="subAdminForm" action="departments.php" method="post">
            <input type="hidden" name="mode" />
            <table width="400" border="0">
              <tr>
                <td>Назва відділу</td>
                <td><input name="name" type="text" /></td>
              </tr>
              <tr>
                <td>№ кабинету</td>
                <td><input name="room" type="text" /></td>
              </tr>
              <tr>
                <td>Телефон</td>
                <td><input name="tel" type="text" /></td>
              </tr>
              <tr>
                <td>Мобільний телефон</td>
                <td><input name="telMob" type="text" /></td>
              </tr>  
              <tr>
                <td>Факс</td>
                <td><input name="fax" type="text" /></td>
              </tr>  
              <tr>
                <td>Код території</td>
                <td><input name="ter" type="text" /></td>
              </tr>
              <tr>
                <td>Адреса</td>
                <td><input name="adres" type="text" /></td>
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