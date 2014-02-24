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
        
        <h2>Довідник форм</h2>
                
        <div class="item_blue" style="float:left; margin-left:37%; width:300px;">
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
        
        <table width="98%">
          <tr class="class_TH">
            <th>Дата оновлення сукупності</th>
            <th>Не<br />роз-<br />роб.</th>
            <th>Індекс форми (періодичність)<br />Найменування форми</th>
            <th>Наказ Держстату<br />Термін подання</th>
            <th>Дво-<br />рів-<br />нева</th>
            <th>Назва відділу</th>
            <th>Бланк<br />Інструкція<br />Роз'яснення</th>
            <th>Всі<br />зві-<br />тують</th>
            <th>Примітка</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD" valign="top">
            <td>
                <span class="green" style="font-weight:bold;">
                	<?php echo $v['date_update'] ? phpDateShort($v['date_update']) : '&nbsp;'; ?>
                </span>
            </td>
            
            <td align="center"><?php echo ($v['not_in_use']) ? '+' : '&nbsp;'; ?></td>
            <td><?php echo $v['name'].' ('.$v['period'].')<br />'.$v['name_full']; ?></td>
            <td><?php echo '<strong>'.$v['nakaz'].'</strong><br />'.$v['srok_sdachi']; ?></td>
            <td align="center"><?php echo ($v['two_level']) ? '+' : '&nbsp;'; ?></td>
            <td><?php echo $v['department']; ?></td>
            
            <td width="200">
            <?php
            	echo (($v['blank']) ? makeLink($v['blank'], 'Бланк форми') : '');
				echo (($v['instr']) ? "<br />".makeLink($v['instr'], 'Інструкція щодо заповнення') : '');
                echo (($v['rozyasn']) ? "<br />".makeLink($v['rozyasn'], 'Роз\'яснення щодо заповнення форми') : '');
			?>
            </td>
                        
            <td align="center"><?php echo ($v['to_all_resp']) ? '+' : '&nbsp;'; ?></td>

            <td><?php echo ($v['note']) ? $v['note'] : '&nbsp;'; ?></td>
            
          </tr>
          <?php  } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>        
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>