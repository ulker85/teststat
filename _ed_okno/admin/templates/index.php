<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Користувачі</title>

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
		} else if (mode == 'add' && subform.login.value != 0 && subform.pass.value != 0) {
			form = subform;
		} else if (mode == 'add' && subform.login.value == 0) {
			correct = false;
			alert('Спочатку необхідно ввести логін');
		} else if (mode == 'add' && subform.pass.value == 0) {
			correct = false;
			alert('Спочатку необхідно ввести пароль');
		}
		<?php foreach ($listItems as $v) { ?>
		else if (mode == 'edit' && form.login_<?php echo $v['id']; ?>.value == 0 && form.chk_<?php echo $v['id']; ?>.checked == true) {
			correct = false;		
			alert('Спочатку необхідно ввести логін');
		} else if (mode == 'edit' && form.pass_<?php echo $v['id']; ?>.value == 0 && form.chk_<?php echo $v['id']; ?>.checked == true) {
			correct = false;
			alert('Спочатку необхідно ввести пароль');
		}
		<?php } ?>
		
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
        
        <form name="adminForm" action="index.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Користувачі</h2>
        
        <div class="item_beige" style="float:left; margin-left:37%; width:300px;">
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
        <div class="clr"></div>
        
        <table width="80%">
          <tr class="class_TH">
            <th>id</th>
            <th>Логін</th>
            <th>Пароль</th>
            <th>Шлях для перенаправлення</th>
            <th>Спосіб подання звіту</th>
            <th>Назва відділу</th>
            <th>Назва сесії</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr>
            <td align="right"><?php echo $v['id']; ?></td>
            <td><input name="login_<?php echo $v['id']; ?>" type="text" size="10" value="<?php echo $v['login']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="pass_<?php echo $v['id']; ?>" type="text" size="26" value="<?php echo $v['pass']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="location_<?php echo $v['id']; ?>" type="text" size="18" value="<?php echo $v['location']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><select name="type_<?php echo $v['id']; ?>" style="width:145px" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['types_report']; ?></select></td>
            <td><select name="dep_<?php echo $v['id']; ?>" style="width:350px" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['department']; ?></select></td>
            <td><input name="session_<?php echo $v['id']; ?>" type="text" size="10" value="<?php echo $v['session_name']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php } ?>
        </table>
        
        </form>
        
        <div id="addForm">
            <h3>Додати нового користувача</h3>
            
            <form name="subAdminForm" action="index.php" method="post">
            <input type="hidden" name="mode" />
            
            <table width="400">
              <tr>
                <td>Логін</td>
                <td><input name="login" type="text" /></td>
              </tr>
              <tr>
                <td>Пароль</td>
                <td><input name="pass" type="text" /></td>
              </tr>  
              <tr>
                <td>Шлях для перенаправлення</td>
                <td><input name="location" type="text" /></td>
              </tr>
              <tr>
                <td>Спосіб подання звіту</td>
                <td><select name="type"><?php echo $types_report; ?></select></td>
              </tr>
              <tr>
                <td>Назва відділу</td>
                <td><select name="department" style="width:200px;"><?php echo $departments; ?></select></td>
              </tr>
              <tr>
                <td>Назва сесії</td>
                <td><input name="session" type="text" /></td>
              </tr>
              <tr>
                <td align="center" colspan="2">
                    <input type="button" id="add" value="Додати запис" class="button" onclick="submitForm('add')" />
                    <input type="button" value="Відмінити" class="button" onclick="document.getElementById('addForm').style['display']='none'" />
                </td>
              </tr>
            </table>
            </form>
    	</div>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>