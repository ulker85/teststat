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
		
		if ((mode == 'exp_xls' || mode == 'show') && form.filtr_f.selectedIndex == 0) {
			correct = false;
			alert('Спочатку необхідно обрати форму');
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
        
        <form name="adminForm" action="an_forms.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />        
        
        <h2>Динаміка подання звітності у розрізі форм</h2>
                
        <div class="item_blue" style="float:left; margin-left:20%; width:300px;">
            <p>
       	  		<div class="navigation_left"><strong>Статистика подання звітності за </strong></div>
                <div class="navigation_right"><select name="filtr_y" style="width:58px"><?php echo $allYear; ?></select><strong> рік</strong></div>
                <div class="clr"></div>
            </p>
            
			<h2>Вибірка за датою подання</h2>
            
            <p>
            	<div class="navigation_left">Починаючи з:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_s" value="<?php echo $filtr_d_s; ?>" style="width:125px" /></div>
                <div class="clr"></div>
            </p>
            
            <p>
            	<div class="navigation_left">Закінчуючи:</div>
                <div class="navigation_right"><input type="text" name="filtr_d_e" value="<?php echo $filtr_d_e; ?>" style="width:125px" /></div>
                <div class="clr"></div>
            </p>
                       
			<h2>Вибірка за поданням</h2>
            
            <p>
                <div class="navigation_left">За відміткою про подання:</div>
                <div class="navigation_right"><select name="filtr_g" style="width:129px"><?php echo $allGiven; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За вчасністю подання:</div>
                <div class="navigation_right"><select name="filtr_inT" style="width:129px"><?php echo $allIntime; ?></select></div>
                <div class="clr"></div>
            </p>
        </div>
        
        <div class="item_blue" style="float:right; margin-right:20%; width:300px;">
            <h2>Вибірка за критеріями</h2>
            
            <p>
                <div class="navigation_left">За формою:</div>
                <div class="navigation_right"><select name="filtr_f" style="width:212px"><?php echo $allForm; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За періодом:</div>
                <div class="navigation_right"><select name="filtr_p" style="width:212px"><?php echo $allPeriod; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <h2>&nbsp;</h2>
            
            <p>
                <div class="navigation_left">За належністю до сукупності:</div>
                <div class="navigation_right"><select name="filtr_s" style="width:112px"><?php echo $allSukup; ?></select></div>
                <div class="clr"></div>
            </p>            
            
            <p>
                <div class="navigation_left">За способом подання:</div>
                <div class="navigation_right"><select name="filtr_t" style="width:154px"><?php echo $allType; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За територією:</div>
                <div class="navigation_right"><select name="filtr_r" style="width:154px"><?php echo $allRay; ?></select></div>
                <div class="clr"></div>
            </p>            
        </div>
        <div class="clr"></div>
        
        <div align="center">
        	<input type="button" id="show" value="Показати" class="button" onclick="submitForm('show')" />
            <input type="button" id="exp" value="Вивантажити" class="button" onclick="submitForm('exp_xls')" /><br />
		</div>
        
		<?php if (isset($pagePeriods)) { ?>
        <table>
          <tr class="class_TH">
            <th>&nbsp;</th>
            
            <?php foreach ($pagePeriods as $v) { ?>
            <th nowrap="nowrap">
                <?php echo substr($v, 0, 2).'<br />'.substr($v, 2); ?>
            </th>
            <?php } ?>
          </tr>
          
          <?php foreach ($pageItems as $k => $v) { ?>
          <tr class="class_TD">
            <td valign="top">
                <?php echo $k; ?>
            </td>
            
            <?php foreach ($pagePeriods as $k1 => $v1) {  ?>
            <td align="right" valign="top">
                <?php 
                    if (isset($v[$k1])) {
						echo cmpDateColor($v[$k1]['date_f'], $v[$k1]['srok'])
							.'<br />'.$v[$k1]['type_f']
							.'<br />'.$v[$k1]['user_f']
							.($v[$k1]['type_s'] ? 
								'<hr />'
								.phpDateShort($v[$k1]['date_s'])
								.'<br />'.$v[$k1]['type_s']
								.'<br />'.$v[$k1]['user_s']
								: '');
                    } else
                        echo '&nbsp;';
                ?>
            </td> 
            <?php } ?>
          </tr>
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