<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Організації</title>

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
<body>
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
		<?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>
        
        <form name="adminForm" action="organizations.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Довідник організацій</h2>
        
        <div class="item_blue" style="float:left; margin-left:37%; width:300px;">
            <h2>Вибірка даних</h2>
            
            <p>
                <div class="navigation_left">За ЄДРПОУ (ІНН):</div>
                <div class="navigation_right">
                    <input type="text" name="filtr_o" style="width:174px"
                             value="<?php echo ($filtr_o != 0 ? $filtr_o : ''); ?>"
                             onchange="this.form.submit()" />
                </div>
                <div class="clr"></div>
            </p>
                
            <p>
                <div class="navigation_left">За суб'єктом:</div>
                <div class="navigation_right">
                	<select name="filtr_s" style="width:180px" onchange="this.form.submit()">
						<?php echo $allSubject; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
            <p>
                <div class="navigation_left">За територією:</div>
                <div class="navigation_right">
                	<select name="filtr_t" style="width:180px" onchange="this.form.submit()">
						<?php echo $allTerritories; ?>
                    </select>
                </div>
                <div class="clr"></div>
            </p>
        </div>
        <div class="clr"></div>
                
        <table width="95%">
          <tr class="class_TH">
            <th>ЄДРПОУ</th>
            <th>Фіз.<br />особа</th>
            <th>Назва організації</th>
            <th>Керівник</th>
            <th>Юридична адреса<br />Телефон, e-mail</th>
            <th>Код<br />території</th>
            <th>ОПФ</th>
          </tr>
          
		  <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td><?php echo $v['edrpou']; ?></td>
            <td align="center"><?php echo $v['fiz_person'] ? '+' : '&nbsp;'; ?></td>
            <td><?php echo htmlspecialchars($v['name']); ?></td>
            <td><?php echo $v['leader'] ? $v['leader'] : '&nbsp;'; ?></td>
            <td>
				<?php echo $v['adres_yur'] ? htmlspecialchars($v['adres_yur']) : '&nbsp;'; ?>
            	<?php echo $v['phone'] ? '<br /><strong>телефон:</strong> '.$v['phone'] : '&nbsp;'; ?>
				<?php echo $v['e_mail'] ? '<br /><strong>e-mail:</strong> '.$v['e_mail'] : '&nbsp;'; ?>
            </td>
            <td align="right"><?php echo $v['ter']; ?></td>
            <td align="right"><?php echo $v['opf'] ? $v['opf'] : '&nbsp;'; ?></td>
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