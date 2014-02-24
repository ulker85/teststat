<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Аналітика</title>

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
        
        <form name="adminForm" action="an_blanks.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Видача бланків</h2>
        
        <div class="item_blue" style="float:left; margin-left:39%; width:250px;">
            <p>
       	  		<div class="navigation_left"><strong>На звітний період:</strong></div>
                <div class="navigation_right"><select name="filtr_y" style="width:88px"><?php echo $allYear; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <h2>Вибірка за датою</h2>
            
            <p>
            	<div class="navigation_left">Починаючи з:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_s" value="<?php echo $filtr_d_s; ?>" style="width:122px" /></div>
                <div class="clr"></div>
            </p>
            
            <p>
            	<div class="navigation_left">Закінчуючи:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_e" value="<?php echo $filtr_d_e; ?>" style="width:122px" /></div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
        
		<div align="center">
        	<input type="button" id="show" value="Показати" class="button" onclick="submitForm('show')" />
            <input type="button" id="exp" value="Вивантажити" class="button" onclick="submitForm('exp_xls')" /><br />
		</div>
        
        <?php	if (isset($listItems)) { ?>
        <table>
          <tr class="class_TH">
            <th colspan="2">Назва форми</th>
          </tr>          
          <tr class="class_TH">
            <th>ЄДРПОУ</th>
            <th>Кількість бланків</th>
          </tr>
          
          <?php foreach ($listItems as $k => $v) { ?>
          <tr class="class_TD">
            <td colspan="2" align="center">
                <strong><?php echo $k; ?></strong>
            </td>
          </tr>
          
          <?php foreach ($v as $k1 => $v1) { ?>
          <tr class="class_TD">  
            <td align="right">
                <?php echo $v1['edrpou']; ?>
            </td> 
            <td align="right">
                <?php echo $v1['amount']; ?>
            </td> 
          </tr>
          <?php } ?>
          <?php } ?>
        </table>
        <?php } ?>
                    
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>
    </div>
</div>
    
<?php require_once('_footer.php'); ?>
</body>
</html>