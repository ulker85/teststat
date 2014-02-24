<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>�������</title>

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
			correct = confirm('�������� ������ ������?');
		} else if (mode == 'add')  {
			form = subform;
		} else if (mode == 'update' && form.fileImp.value == '') {
			correct = false;			
			alert('�������� ��������� ������ ���� ��� ������� ���������');
		} else if (mode == 'update' && form.index_im.value == 0) {
			correct = false;			
			alert('�������� ��������� ������ �����, ��� ��� ����������� ���������');
		} else if (mode == 'update' && form.period_im.value == 0) {
			correct = false;			
			alert('�������� ��������� ������ �����, �� ���� ����������� ���������');
		} else if (mode == 'del_sukup' && form.form_del.value == 0) {
			correct = false;			
			alert('�������� ��������� ������ ����� �����, ��� ��� ����������� ���������');
		} else if (mode == 'del_sukup' && form.period_del.value == 0) {
			correct = false;			
			alert('�������� ��������� ������ �����, �� ���� ����������� ���������');
		} else if (mode == 'del_sukup') {
			correct = confirm('�������� ��� ��������� �� ����?');
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
        
        <form name="adminForm" action="reports.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>������ �������</h2>
        
        <div class="item_beige" style="float:left; margin-left:10%; width:300px; height:193px;">
            <h2>��������� ����������� ������</h2>
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
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
        
        <div class="item_beige" style="float:left; margin-left:25px; width:300px;">
            <h2>������ �����������</h2>
            
            <p>������ ���� �� ���������:</p>
            <p><input type="file" name="fileImp" style="width:256px" /></p>
            <p>������ ����� ��� ���������:</p>
            <p><select name="index_im" style="width:256px"><?php echo $nameForm; ?></select></p>
            <p>������ �����, �� ���� �������� ���������:</p>
            <p><select name="period_im" style="width:256px"><?php echo $namePeriodReport; ?></select></p>
            <p>
            	<input type="checkbox" name="tabel_imp" />������
                <input type="checkbox" name="edok_imp" />����� ����
            </p> 
            <p align="center">
                <input type="button" value="�����������" class="button" onclick="submitForm('update')" />
            </p>
        </div>
        
        <div class="item_beige" style="float:right; margin-right:10%; width:300px; height:193px;">
            <h2>��������� �����������</h2>
            
            <p>������ �����, ��� ��� ����������� ���������:</p>
            <p><select name="form_del" style="width:256px"><?php echo $nameForm; ?></select></p>     
            <p>������ �����, �� ���� ����������� ���������:</p>
            <p><select name="period_del" style="width:256px"><?php echo $namePeriodReport; ?></select></p>
            <p>&nbsp;</p>            
            <p>
            	<input type="checkbox" name="tabel" />������
                <input type="checkbox" name="edok" />����� ����
            </p>            
            <p align="center">
                <input type="button" value="��������" class="button" onclick="submitForm('del_sukup')" />
            </p>
        </div>
        <div class="clr"></div>
        
        <div class="item_blue" style="float:left; margin-left:37%; width:300px;">
            <h2>������ �����</h2>
            
            <p>
                <div class="navigation_left">�� ������:</div>
                <div class="navigation_right">
                    <input type="text" name="filtr_o" style="width:203px"
                             value="<?php echo ($filtr_o != 0 ? $filtr_o : ''); ?>"
                             onchange="this.form.submit()" />
                </div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">�� �������:</div>
                <div class="navigation_right"><select name="filtr_f" style="width:206px" onchange="this.form.submit()"><?php echo $allForm; ?></select></div>
                <div class="clr"></div>
            </p>
                    
            <p>
                <div class="navigation_left">�� ��������:</div>
                <div class="navigation_right"><select name="filtr_p" style="width:206px" onchange="this.form.submit()"><?php echo $allPeriodsReport; ?></select></div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
    
        <table width="95%">
          <tr class="class_TH">
            <th>id</th>
            <th>������</th>
            <th>������ �����<br />�� �����������</th>
            <th>�����,<br />�� ����<br />�������� �������</th>
            <th>����<br />��-<br />���.</th>
            <th>�������<br />������. ����</th>
            <th>����� �������<br />���������� ����</th>
            <th>����������, ����<br />������� �������</th>
            <th width="70">�������<br />���������. ����</th>
            <th>����� �������<br />������������ ����</th>
            <th>����������, ����<br />������� �������</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td><?php echo $v['id']; ?></td>            
            <td><?php echo $v['edrpou']; ?></td>
            
            <td>
                <select name="form_<?php echo $v['id']; ?>" style="width:150px;" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
					<?php echo $v['form']; ?>
                </select>
            </td>
            
            <td>
                <select name="period_<?php echo $v['id']; ?>" style="width:150px;" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
					<?php echo $v['period']; ?>
                </select>
            </td>
            
            <td align="center">
                <input type="checkbox" name="notInSukup_<?php echo $v['id']; ?>" value="1"
                    <?php echo $chk=($v['not_in_sukup']) ? 'checked="checked"' : ''; ?>
                    onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
                <input type="text" name="firstDate_<?php echo $v['id']; ?>" style="width:90px"
                    value="<?php echo phpDateFull($v['date_first']); ?>"
                    onclick="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
                <select name="typeFirst_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                	<?php echo $v['typeFirst']; ?>
                </select>
            </td>
            
            <td><select name="userFirst_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['userFirst']; ?></select></td>
            
            <td>
                <input type="text" name="secondDate_<?php echo $v['id']; ?>" style="width:90px"
                    value="<?php echo phpDateFull($v['date_second']); ?>"
                    onclick="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td><select name="typeSecond_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['typeSecond']; ?></select></td>
            
            <td><select name="userSecond_<?php echo $v['id']; ?>" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['userSecond']; ?></select></td>
            
            <td><input id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>" type="checkbox" value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" /></td>
          </tr>
          <?php } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
            <h2>������ ����� ���</h2>
            
            <form name="subAdminForm" action="reports.php" method="post">
            <input type="hidden" name="mode" />
            <table width="400" border="0">
              <tr>
                <td>������</td>
                <td><select name="edrpou"><?php echo $edrpou; ?></select></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><select name="form"><?php echo $form; ?></select></td>
              </tr>
              <tr>
                <td>�����</td>
                <td><select name="period"><?php echo $period; ?></select></td>
              </tr>
              <tr>
                <td>���� ���������</td>
                <td><input type="checkbox" name="notInSukup" value="1" /></td>
              </tr>  
              <tr>
                <td>������� ������. ����</td>
                <td><input type="text" name="date_f" /></td>
              </tr>
              <tr>
                <td>����� �������</td>
                <td><select name="type_f"><?php echo $type; ?></select></td>
              </tr>
              <tr>
                <td>����������</td>
                <td><select name="user_f"><?php echo $user; ?></select></td>
              </tr>
              <tr>
                <td>������� ���������. ����</td>
                <td><input type="text" name="date_s" /></td>
              </tr>
              <tr>
                <td>����� �������</td>
                <td><select name="type_s"><?php echo $type; ?></select></td>
              </tr>
              <tr>
                <td>����������</td>
                <td><select name="user_s"><?php echo $user; ?></select></td>
              </tr>
              <tr>
                <td><input type="button" id="add" value="������ �����" class="button" onclick="submitForm('add')" /></td>
                <td><input type="button" value="³������" class="button" onclick="document.getElementById('addForm').style['display']='none'" /></td>
              </tr>
            </table>
            </form>
        </div>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>