<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Періоди</title>

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
		
		if (mode == 'del') {
			correct = confirm('Видалити відмічені записи?');
		} else if (mode == 'add') {
			form = document.forms['subAdminForm'];
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
    
        <form name="adminForm" action="periods.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
            
        <h2>Періоди звітності</h2>
        
        <div class="item_beige" style="float:left; margin-left:37%; width:300px;">
            <h2>Порядкове редагування записів</h2>
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
                <div class="navigation_left">
                    <input type="button" id="btnAdd" value="Додати" class="button"
                        onclick="document.getElementById('addForm').style['display']='block'" />
                    <input type="button" id="edit" value="Редагувати" class="button"
                        onclick="submitForm('edit')" />
                </div>
                
                <div class="navigation_right">
                    <input type="button" id="del" value="Видалити" class="button"
                        onclick="submitForm('del')" />
                </div>
            </p>
        </div>
        <div class="clr"></div>
        
        <table>
          <tr class="class_TH">
            <th>id</th>
            <th>Назва<br />періоду</th>
            <th>Номер<br />місяця</th>
            <th>Назва<br />(родов. відм.)</th>
            <th>Назва<br />(місцев. відм.)</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr>
            <td><?php echo $v['id']; ?></td>
            <td><input type="text" name="name_<?php echo $v['id']; ?>" value="<?php echo $v['name']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input type="text" name="number_<?php echo $v['id']; ?>" value="<?php echo $v['number']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input type="text" name="nameRod_<?php echo $v['id']; ?>" value="<?php echo $v['name_rod']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input type="text" name="nameMisc_<?php echo $v['id']; ?>" value="<?php echo $v['name_misc']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
            <h3>Додати новий період</h3>
            
            <form name="subAdminForm" action="periods.php" method="post">
            <input type="hidden" name="mode" />  
              
            <table width="400" border="0">
              <tr>
                <td>Назва періоду</td>
                <td><input name="name" type="text" /></td>
              </tr>
              <tr>
                <td>Номер місяця</td>
                <td><input name="number" type="text" /></td>
              </tr>
              <tr>
                <td>Назва (род.)</td>
                <td><input name="nameRod" type="text" /></td>
              </tr>
              <tr>
                <td>Назва (місц.)</td>
                <td><input name="nameMisc" type="text" /></td>
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