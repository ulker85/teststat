<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Організації</title>

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
		
		if (mode == 'import')
			correct = confirm('Почати імпорт?');
		else if (mode == 'del')
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
<body>
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
		<?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>
        
        <form name="adminForm" action="organizations.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Організації</h2>
        
        <div class="item_beige" style="float:left; margin-left:10%; width:300px;">
            <h2>Порядкове редагування записів</h2>
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
                <div class="navigation_left">
                    <input type="button" id="btnAdd" value="Додати" onclick="document.getElementById('addForm').style['display']='block'" class="button" />
                    <input type="button" id="edit" value="Редагувати" class="button" onclick="submitForm('edit')" />
                </div>
                
                <div class="navigation_right">
                    <input type="button" id="del" value="Видалити" class="button" onclick="submitForm('del')" />
                </div>
            </p>
        </div>
        
        <div class="item_beige" style="float:left; margin-left:30px; width:290px;">
            <h2>Оновлення даних з відділу ЄДРПОУ</h2>
            
            <p> Оберіть файл з інформацією:</p>
            <p><input type="file" name="fileImpEdrpou" style="width:276px" disabled="disabled" /></p>     
            <p align="center"><input id="import" type="button" value="Імпортувати" class="button" onclick="submitForm('import')" /></p>
        </div>
        
        <div class="item_beige" style="float:right; margin-right:10%; width:300px;">
            <h2>Оновлення даних з галузевого відділу</h2>
            
            <p> Оберіть файл з інформацією:</p>
            <p><input type="file" name="fileImpIndust" style="width:276px" /></p>     
            <p align="center"><input id="impFiz" type="button" value="Фізич. особи" onclick="submitForm('impFiz')" class="button" />
                    <input id="impOther" type="button" value="Інші території" onclick="submitForm('impOther')" class="button" /></p>
        </div>
        <div class="clr"></div>
        
        <table width="95%">
          <tr class="class_TH">
            <th>Id</th>
            <th>Фіз.<br />особа</th>
            <th>ЄДРПОУ</th>
            <th>Назва</th>
            <th>Юридична адреса</th>
            <th>Керівник</th>
            <th>Телефон</th>
            <th>E-mail</th>
            <th>Код<br />терит.</th>
            <th>ОПФ</th>
            <th>&nbsp;</th>
          </tr>
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td align="right"><?php echo $v['id']; ?></td>    
            <td align="center"><input name="fizPerson_<?php echo $v['id']; ?>" type="checkbox" <?php echo $chk=($v['fiz_person']) ? 'checked="checked"' : ''; ?> value="1" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="edrpou_<?php echo $v['id']; ?>" type="text" size="9" value="<?php echo $v['edrpou']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="name_<?php echo $v['id']; ?>" type="text" size="50" value="<?php echo htmlspecialchars($v['name']); ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="adryur_<?php echo $v['id']; ?>" type="text" size="35" value="<?php echo htmlspecialchars($v['adres_yur']); ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="leader_<?php echo $v['id']; ?>" type="text" size="16" value="<?php echo $v['leader']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
             <td><input name="phone_<?php echo $v['id']; ?>" type="text" size="9" value="<?php echo $v['phone']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input name="email_<?php echo $v['id']; ?>" type="text" size="9" value="<?php echo $v['e_mail']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td align="center"><input name="ter_<?php echo $v['id']; ?>" type="text" size="3" value="<?php echo $v['ter']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td align="center"><input name="opf_<?php echo $v['id']; ?>" type="text" size="3" value="<?php echo $v['opf']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')" /></td>
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
            <h2>Додати нове підприємство</h2>
            
            <form name="subAdminForm" action="organizations.php" method="post">
            <input type="hidden" name="mode" />
            <table width="400" border="0">
              <tr>
                <td>ЄДРПОУ</td>
                <td><input name="edrpou" type="text" /></td>
              </tr>
              <tr>
                <td>Фізич. особа</td>
                <td><input name="fizPerson" type="checkbox" value="1" /></td>
              </tr>
              <tr>
                <td>Назва організації</td>
                <td><input name="name" type="text" /></td>
              </tr>
              <tr>
                <td>Керівник</td>
                <td><input name="leader" type="text" /></td>
              </tr>
              <tr>
                <td>Юр. адреса</td>
                <td><input name="adryur" type="text" /></td>
              </tr>
              <tr>
                <td>Телефон</td>
                <td><input name="phone" type="text" /></td>
              </tr>
              <tr>
                <td>E-mail</td>
                <td><input name="email" type="text" /></td>
              </tr>
              <tr>
                <td>Код території</td>
                <td><input name="ter" type="text" /></td>
              </tr>
              <tr>
                <td>ОПФ</td>
                <td><input name="opf" type="text" /></td>
              </tr>
              <tr>
                <td><input id="add" type="button" value="Додати запис" onclick="submitForm('add')" /></td>
                <td><input type="button" value="Відмінити" onclick="document.getElementById('addForm').style['display']='none'" /></td>
              </tr>
            </table>
            </form>
        </div>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>