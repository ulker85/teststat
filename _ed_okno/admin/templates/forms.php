<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Форми</title>

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
        
        <form name="adminForm" action="forms.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Форми</h2>
        
        <div class="item_beige" style="float:left; margin-left:40%; width:300px;">
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
        
        <div class="item_blue" style="float:left; margin-left:40%; width:300px;">
            <h2>Вибірка даних</h2>
            
            <p>
                <div class="navigation_left">За періодичностю:</div>
                <div class="navigation_right"><select name="filtr_p" style="width:180px" onchange="this.form.submit()"><?php echo $allPeriod; ?></select></div>
                <div class="clr"></div>
            </p>
                
            <p>
                <div class="navigation_left">За відділами:</div>
                <div class="navigation_right"><select name="filtr_o" style="width:180px" onchange="this.form.submit()"><?php echo $allOtdel; ?></select></div>
                <div class="clr"></div>
            </p>    
        </div>
        <div class="clr"></div>
        
        <span class="small_letter">
        <table width="95%">
          <tr class="class_TH">
            <th>Id</th>
            <th>Дата<br />оновлення<br />сукупності</th>
            <th>Індекс форми</th>
            <th>Найменування форми</th>
            <th>Періодичність</th>
            <th>Не<br />розроб-<br />ляється</th>
            <th>Термін подання</th>
            <th>Дата та № наказу<br />Держстата України</th>
            <th>Дво-<br />рів-<br />нева</th>
            <th>Назва відділу</th>
            <th>Бланк/<br />Інструкція/<br />Роз'яснення</th>
            <th>Експрес/<br />Регіон/<br />Район</th>
            <th>Всі<br />зві-<br />тують</th>
            <th>Примітка</th>
            <th>&nbsp;<br />&nbsp;</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD" valign="top">
            <td align="right"><?php echo $v['id']; ?></td>
 
            <td>
                <span class="green">
                	<?php echo $v['date_update'] ? phpDateShort($v['date_update']) : '&nbsp;'; ?>
                </span>
            </td>
            
            <td>
                <input type="text" name="sname_<?php echo $v['id']; ?>" value="<?php echo $v['name']; ?>"
                        size="12" onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
                <textarea name="fname_<?php echo $v['id']; ?>" rows="7" cols="14" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['name_full']; ?></textarea>
            </td>    
            
            <td>
                <select name="period_<?php echo $v['id']; ?>" style="width:90px"
                		onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                    <?php echo $v['period']; ?>
                </select>
            </td>
            
            <td align="center">
                <input type="checkbox" name="not_in_use_<?php echo $v['id']; ?>"
                        <?php echo $chk=($v['not_in_use']) ? 'checked="checked"' : ''; ?> value="1"
                        onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
                <textarea name="srok_<?php echo $v['id']; ?>" rows="7" cols="7" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['srok_sdachi']; ?></textarea>
            </td>
            
            <td>
                <input type="text" name="nakaz_<?php echo $v['id']; ?>" value="<?php echo $v['nakaz']; ?>"
                        size="14" onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td align="center">
                <input type="checkbox" name="two_level_<?php echo $v['id']; ?>"
                        <?php echo $chk=($v['two_level']) ? 'checked="checked"' : ''; ?> value="1"
                        onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
            
            <td>
                <select name="department_<?php echo $v['id']; ?>" style="width:150px"
                		onchange="chkOn('chk_<?php echo $v['id']; ?>')">
                    <?php echo $v['department']; ?>
                </select>
            </td>
            
            <td>
                <textarea name="blank_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['blank']; ?></textarea>
                
                <textarea name="instr_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['instr']; ?></textarea>
                
                <textarea name="rozyasn_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['rozyasn']; ?></textarea>
            </td>
                        
            <td>
                <textarea name="ekspres_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['ekspres']; ?></textarea>
                
                <textarea name="region_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['region']; ?></textarea>
                
                <textarea name="m_rayon_<?php echo $v['id']; ?>" rows="2" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['m_rayon']; ?></textarea>
            </td>

            <td align="center">
                <input type="checkbox" name="to_all_resp_<?php echo $v['id']; ?>"
                		<?php echo $chk=($v['to_all_resp']) ? 'checked="checked"' : ''; ?> value="1"
                        onchange="chkOn('chk_<?php echo $v['id']; ?>')" />
            </td>
           
            <td>
            	<textarea name="note_<?php echo $v['id']; ?>" rows="7" cols="11" onchange="chkOn('chk_<?php echo $v['id']; ?>')"><?php echo $v['note']; ?></textarea>
            </td>
            
            <td>
                <input type="checkbox" id="chk_<?php echo $v['id']; ?>" name="chk_<?php echo $v['id']; ?>"
                        value="<?php echo $v['id']; ?>" onchange="chkOnForDel('adminForm')" />
            </td>
          </tr>
          <?php  } ?>
        </table>
        </span>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
        
        <div id="addForm">
        <h2>Додати нову форму</h2>
        
        <form name="subAdminForm" action="forms.php" method="post">
        <input type="hidden" name="mode" />
        <table width="400" border="0">
          <tr>
            <td>Індекс форми</td>
            <td><input name="sname" type="text" /></td>
          </tr>
          <tr>
            <td>Найменування форми</td>
            <td><input name="fname" type="text" /></td>
          </tr>  
          <tr>
            <td>Періодичність</td>
            <td><select name="period"><?php echo $period; ?></select></td>
          </tr>
          <tr>
            <td>Термін подачі</td>
            <td><input name="srok" type="text" /></td>
          </tr>
          <tr>
            <td>Дата та № наказу Держстата України</td>
            <td><input name="nakaz" type="text" /></td>
          </tr>
          <tr>
            <td>Дворівневість</td>
            <td><input name="two_level" type="checkbox" value="1" /></td>
          </tr>
          <tr>
            <td>Назва відділу</td>
            <td><select name="department"><?php echo $otdel; ?></select></td>
          </tr>
          <tr>
            <td>Бланк</td>
            <td><input name="blank" type="text" /></td>
          </tr>
          <tr>
            <td>Інструкція</td>
            <td><input name="instr" type="text" /></td>
          </tr>
          <tr>
            <td>Роз'яснення</td>
            <td><input name="rozyasn" type="text" /></td>
          </tr>
          <tr>
            <td>Експрес</td>
            <td><input name="ekspres" type="text" /></td>
          </tr>
          <tr>
            <td>Регіон</td>
            <td><input name="region" type="text" /></td>
          </tr>
          <tr>
            <td>Район</td>
            <td><input name="m_rayon" type="text" /></td>
          </tr>
          <tr>
            <td>Примітка</td>
            <td><input name="note" type="text" /></td>
          </tr>
          <tr>
            <td>Всі звітують</td>
            <td><input name="to_all_resp" type="checkbox" value="1" /></td>
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