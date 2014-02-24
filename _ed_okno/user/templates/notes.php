<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Примітки</title>

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
        
        <form name="adminForm" action="notes.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Введення приміток для респондентів</h2>
        
        <table>
          <tr class="class_TH">
            <th>ЄДРПОУ</th>
            <th>Примітка</th>
            <th>Розмістив</th>
            <th>Видалив</th>

          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td valign="top" align="right">
            	<?php echo $v['edrpou']; ?>
            </td>
            
            <td>
            	<?php echo $v['name']; ?>
            </td>
            
            <td valign="top">
            	<?php echo $v['user_add']; ?>
            </td>
            
            <td>
            	<?php echo $v['user_del']; ?>
            </td>

          </tr>
          <?php  } ?>
          
          <tr>
            <td valign="top">
            	<input type="text" name="edrpou_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
            	<textarea name="note_<?php echo $v['id']; ?>" rows="3" cols="45" onchange="chkOn('chk_<?php echo $v['id']; ?>')"></textarea>
            </td>
            
            <td valign="top">
            	<span class="grey"><?php echo $user['login']; ?></span>
            </td>
            
            <td>&nbsp;</td>
          </tr>
        </table>

        <p align="center">
            <input type="button" value="Додати" class="button" onclick="submitForm('add')" />
        </p>                    
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>