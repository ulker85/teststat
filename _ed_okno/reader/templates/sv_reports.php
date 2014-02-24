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
        
        <form name="adminForm" action="sv_reports.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Облік поданих звітів</h2>
        
        <div class="item_blue" style="float:left; margin-left:20%; width:300px;">
            <h2>Вибірка за датою подання</h2>
            
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
        
        <div class="item_blue" style="float:right; margin-right:20%; width:300px;">
            <p>
       	  		<div class="navigation_left"><strong>Статистика подання звітності за </strong></div>
                <div class="navigation_right"><select name="filtr_y" style="width:58px"><?php echo $allYear; ?></select><strong> рік</strong></div>
                <div class="clr"></div>
            </p>
            
            <p>
       	  		<div class="navigation_left">
                	<input type="radio" name="choice" value="first"
                    		<?php echo $choice != 'second' ? 'checked="checked"' : ''; ?> />по первинних звітах
                </div>
                <div class="navigation_right">
                	<input type="radio" name="choice" value="second"
                    		<?php echo $choice == 'second' ? 'checked="checked"' : ''; ?> />по коригуючих звітах
                </div>
                <div class="clr"></div>
            </p>
            
            <h2>Вибірка за критеріями</h2>
           
            <p>
                <div class="navigation_left">За формою:</div>
                <div class="navigation_right"><select name="filtr_f" style="width:155px"><?php echo $allForm; ?></select></div>
                <div class="clr"></div>
            </p>
                        
            <p>
                <div class="navigation_left">За галузевим відділом:</div>
                <div class="navigation_right"><select name="filtr_d" style="width:155px"><?php echo $allDepartment; ?></select></div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За територією:</div>
                <div class="navigation_right"><select name="filtr_r" style="width:155px"><?php echo $allRay; ?></select></div>
                <div class="clr"></div>
            </p>            
        </div>
        <div class="clr"></div>
        
        <div align="center">
        	<input type="button" id="show" value="Показати" class="button" onclick="submitForm('show')" />
            <input type="button" id="exp" value="Вивантажити" class="button" onclick="submitForm('exp_xls')" />
		</div>
        
        <?php if (isset($listColumns)) { ?>
        <table>
          <tr class="class_TH">
            <th rowspan="2">Форма</th>
            <th rowspan="2">Загальна<br />сукупність</th>
            <th rowspan="2">Усього<br />прийнято</th>
            <th colspan="5">за способом подання</th>
            <th colspan="2">за належністю до сукупності</th>
            <th colspan="2">за вчасністю подання</th>
          </tr>               
          <tr class="class_TH">
			<?php for ($i=3; $i<count($listColumns); $i++) { ?>
            <th>
                <?php echo substr($listColumns[$i], 0, strpos($listColumns[$i], ' '))
								.(strpos($listColumns[$i], ' ') ? '<br />' : '')
								.substr($listColumns[$i], strpos($listColumns[$i], ' '));
				?>
            </th>
		  	<?php } ?>
          </tr>
          
          <?php
          foreach ($listItems as $key => $value) {
          	foreach ($value as $k => $v) {
		  ?>
          	<tr class="class_TD">
            	<?php
                foreach ($listColumns as $k1 => $v1) {
					if ($k1 == 0) {
						echo '<td>'.$v['name'].'</td>';
					} else {
						if ($k == 0)
							echo '<td align=right><strong>'.(isset($v[$v1]) ? $v[$v1] : '&nbsp;').'</strong></td>';
						else
							echo '<td align=right>'.(isset($v[$v1]) ? $v[$v1] : '&nbsp;').'</td>';
					}
                }
				?>
            </tr>
          <?php
		  	}
		  }
		  ?>
        </table>
        <?php } ?>
        </form>
    </div>
</div>
    
<?php require_once('_footer.php'); ?>
</body>
</html>