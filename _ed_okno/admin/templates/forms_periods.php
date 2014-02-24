<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>�����-������</title>

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
		
		if (mode == 'del')
			correct = confirm('�������� ������ ������?');
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
        
        <form name="adminForm" action="forms_periods.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>³��������� ������ ������</h2>
        
        <div class="item_beige" style="float:left; margin-left:40%; width:300px;">
            <h2>��������� ����������� ������</h2>
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
                <div class="navigation_left">
                    <input id="btnAdd" type="button" value="������" onclick="document.getElementById('addForm').style['display']='block'" class="button" />
                    <input id="edit" type="button" value="����������" class="button" onclick="submitForm('edit')" />
                </div>
                
                <div class="navigation_right">
                    <input id="del" type="button" value="��������" class="button" onclick="submitForm('del')" />
                </div>
            </p>
        </div>
        <div class="clr"></div>
                
        <table>
          <tr class="class_TH">
            <th>Id</th>
            <th>����� �����</th>
            <th>����� ������ ����</th>
            <th>����� �������� ������</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD" valign="top">
            <td align="right"><?php echo $v['id']; ?></td>
 
            <td>
                <select name="form_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                    <?php echo $v['form']; ?>
                </select>
            </td>
            
            <td>
                <select name="period_r_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                    <?php echo $v['period_r']; ?>
                </select>
            </td>
            
            <td>
                <select name="period_<?php echo $v['id']; ?>" style="width:200px;" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                    <?php echo $v['period']; ?>
                </select>
            </td>
            
            <td>
                <input type="checkbox" id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>"
                        value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" />
            </td>
          </tr>
          <?php  } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
        <h2>������ ���� ����������</h2>
        
        <form name="subAdminForm" action="forms_periods.php" method="post">
        <input type="hidden" name="mode" />
        <table width="400" border="0">
          <tr>
            <td>����� �����</td>
            <td><select name="form"><?php echo $form; ?></select></td>
          </tr>
          <tr>
            <td>����� ������ ����</td>
            <td><select name="period_r"><?php echo $period_r; ?></select></td>
          </tr>
          <tr>
            <td>����� �������� ������</td>
            <td><select name="period"><?php echo $period; ?></select></td>
          </tr>
          <tr>
            <td><input id="add" type="button" value="������ �����" onclick="submitForm('add')" /></td>
            <td><input type="button" value="³������" onclick="document.getElementById('addForm').style['display']='none'" /></td>
          </tr>
        </table>
        </form>
        </div>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>