<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Графік подання звітності</title>

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
        
        <form name="adminForm" action="chart.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Графік надання державної звітності<br /> на
        	<select name="filtr_p" id="fitr_p" class="in_text" style="width:100px;" onchange="submitForm();">
				<?php echo $allPeriods; ?>
            </select>
            <select name="filtr_y" class="in_text" onchange="submitForm();">
				<?php echo $allYears; ?>
            </select>
        </h2>
                
		<?php if (isset($listItems)) { ?>
		<table width="70%">
			<?php foreach ($listItems as $k => $v) { ?>            
            <tr class="class_TH">
                <th colspan="2" nowrap="nowrap">
                	<?php echo phpDateShort($k); ?>
                </th>
            </tr>
            
				<?php foreach ($v as $value) { ?>
                <tr class="class_TD">
                    <td valign="top"><?php echo $value['form']; ?></td>
                    <td align="right"><?php echo $value['period']; ?></td>
                </tr>
				<?php } ?>            
			<?php } ?>
        </table>
        <?php } ?>
        </form>        
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>