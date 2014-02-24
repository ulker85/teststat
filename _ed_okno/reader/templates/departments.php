<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Відділи</title>

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
        
        <form name="adminForm" action="departments.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h2>Довідник відділів</h2>
                
        <table width="95%">
          <tr class="class_TH">
            <th>Назва відділу</th>
            <th>Номер<br />кабінету</th>
            <th>Контакти</th>
            <th>Код<br />території</th>
            <th>Адреса</th>
          </tr>
          
          <?php foreach ($listItems as $v) { ?>
          <tr class="class_TD">
            <td><?php echo $v['name']; ?></td>
            <td align="right"><?php echo $v['room_num'] ? $v['room_num'] : '&nbsp;'; ?></td>
            <td>
				<?php echo $v['tel'] ? '<strong>Телефон:</strong> '.$v['tel'] : '&nbsp;'; ?>
                <?php echo $v['tel_mob'] ? '<br /><strong>Мобільний:</strong> '.$v['tel_mob'] : '&nbsp;'; ?>
                <?php echo $v['fax'] ? '<br /><strong>Факс:</strong> '.$v['fax'] : '&nbsp;'; ?>
            </td>
            <td align="right"><?php echo $v['ter'] ? $v['ter'] : '&nbsp;'; ?></td>
            <td><?php echo $v['adres'] ? $v['adres'] : '&nbsp;'; ?></td>
          </tr>
          <?php  } ?>
        </table>
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>