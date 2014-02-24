<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Причини неподання</title>

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
		
		if (mode == 'del')
			correct = confirm('Видалити відмічені записи?');
		else if (mode == 'add')
			form = document.forms['subAdminForm'];
		
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
        
        <form name="adminForm" action="reasons.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
            
        <h2>Причини неподання звітності</h2>
            
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
    
        <table>
          <tr class="class_TH">
            <th>Id</th>
            <th>Причини неподання</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          <?php foreach ($listItems as $v) { ?>
          <tr>
            <td align="right"><?php echo $v['id']; ?></td>
            
            <td>
            	<input type="text" name="name_<?php echo $v['id']; ?>" value="<?php echo $v['name']; ?>"
            			style="width:500px;"
                        onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td><input type="checkbox" id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php  } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
            <h2>Додати нову причину</h2>
            
            <form name="subAdminForm" action="reasons.php" method="post">
            <input type="hidden" name="mode" />
            
            <table width="400" border="0">
              <tr>
                <td>Причини неподання</td>
                <td><input name="name" type="text" /></td>
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