<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Сукупності</title>

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
        
        <form name="adminForm" action="aggregates.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Сукупності</h2>
        
        <div class="item_blue" style="float:left; margin-left:37%; width:320px;">
            <h2>Вибірка даних</h2>
            
            <p>
                <div class="navigation_left">
                    Сукупність:
                </div>
                
                <div class="navigation_right">
                	<input type="radio" name="choice" value="tabel"
                    		<?php echo $choice != 'edok' ? 'checked="checked"' : ''; ?>
                            onchange="this.form.submit()" />для табеля
                	<input type="radio" name="choice" value="edok"
                    		<?php echo $choice == 'edok' ? 'checked="checked"' : ''; ?>
                            onchange="this.form.submit()" />для єдиного вікна
                </div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За ЄДРПОУ:</div>
                <div class="navigation_right">
                    <input type="text" name="filtr_o" style="width:211px"
                             value="<?php echo ($filtr_o != 0 ? $filtr_o : ''); ?>"
                             onchange="this.form.submit()" />
                </div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За територією:</div>
                <div class="navigation_right">
                    <select name="filtr_t" style="width:216px" onchange="this.form.submit()">
                    	<?php echo $allTerritories; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
            
            <p>
                <div class="navigation_left">За формою:</div>
                <div class="navigation_right">
                    <select name="filtr_f" style="width:216px" onchange="this.form.submit()">
                    	<?php echo $allForm; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
                    
            <?php if ($choice == 'edok') { ?>
            <p>
                <div class="navigation_left">За періодом:</div>
                <div class="navigation_right">
                    <select name="filtr_p" style="width:216px" onchange="this.form.submit()">
                    	<?php echo $allPeriodsReport; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
            <?php } ?>
            
            <p>
                <div class="navigation_left">За відділом:</div>
                <div class="navigation_right">
                    <select name="filtr_d" style="width:216px" onchange="this.form.submit()">
                    	<?php echo $allDepartment; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
    
        <table width="95%">
          <tr class="class_TH">
            <th>ЄДРПОУ, назва організації та код території</th>
            <th>
            	Індекс форми та періодичність
                <?php if ($choice == 'edok') { ?><br />Період, за який подається форма<?php } ?>
            </th>
            <th>Назва відділу, що супроводжує форму</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td>
				<?php echo '<strong>'.$v['edrpou'].'</strong>'; ?>
                <?php echo '<br />'.$v['org']; ?>
                <?php echo '<br />'.$v['ter']; ?>
            </td>
            
            <td valign="top">
				<?php echo $v['form'].' ('.$v['period_f'].')'; ?>
                <?php echo ($choice == 'edok') ? '<br /><em>'.$v['period_r'].' '.$v['year'].'</em>' : ''; ?>
            </td>
            
            <td><?php echo $v['department']; ?></td>
          </tr>
          <?php } ?>
        </table>
        
        <p>&nbsp;</p>
        <?php echo $pagination; ?>
        </form>        
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>