<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Зведення</title>

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
        
        <form name="adminForm" action="sv_general.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Загальна статистика</h2>
        
        <div class="item_blue" style="float:left; margin-left:37%; width:300px;">
            <h2>Вибірка за датою</h2>
            
            <p>
            	<div class="navigation_left">Починаючи з:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_s" value="<?php echo $filtr_d_s; ?>" style="width:130px" /></div>
                <div class="clr"></div>
            </p>
            
            <p>
            	<div class="navigation_left">Закінчуючи:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_e" value="<?php echo $filtr_d_e; ?>" style="width:130px" /></div>
                <div class="clr"></div>
            </p>
            
            <h2>Вибірка за граничним терміном</h2>
            
            <p>
                <div class="navigation_left">Починаючи з:</div>
                <div class="navigation_right"><input type="text" name="filtr_t_s" value="<?php echo $filtr_t_s; ?>" style="width:130px" /></div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">Закінчуючи:</div>
                <div class="navigation_right"><input type="text" name="filtr_t_e" value="<?php echo $filtr_t_e; ?>" style="width:130px" /></div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
        
        <div align="center">
        	<input type="button" id="show" value="Показати" class="button" onclick="submitForm('show')" />
		</div>
        
        <?php if ($action == 'show') { ?>
        <table>          
           <tr class="class_TH">
                <th rowspan="2">&nbsp;</th>
                <th colspan="2">Статистика по звітах</th>
                <th rowspan="2">Статистика по бланках</th>
            </tr>
            <tr class="class_TH">
                <th>по первинних</th>
                <th>по коригуючих</th>
            </tr>
            
            <tr class="class_TD">
                <td><strong>Кількість зібраних звітів<br />Кількість виданих бланків</strong></td>
                <td align="right"><?php echo $report_f; ?></td>
                <td align="right"><?php echo $report_s; ?></td>
                <td align="right"><?php echo $blanks; ?></td>
            </tr>
            <tr class="class_TD">
                <td><strong>Кількість унікальних респондентів</strong></td>
                <td align="right"><?php echo $resondents_f; ?></td>
                <td align="right"><?php echo $resondents_s; ?></td>
                <td align="right"><?php echo $resondents_b; ?></td>
            </tr>
            <tr class="class_TD">
                <td><strong>Кількість унікальних форм</strong></td>
                <td align="right"><?php echo $forms_f; ?></td>
                <td align="right"><?php echo $forms_s; ?></td>
                <td align="right"><?php echo $forms_b; ?></td>
            </tr>
        </table>
        <?php } ?>
        </form>
    </div>
</div>
    
<?php require_once('_footer.php'); ?>
</body>
</html>