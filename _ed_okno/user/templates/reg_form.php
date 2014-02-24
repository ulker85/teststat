<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<link href="../../css/tabtastic.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<style type="text/css" media="screen">
#menu{float:none;}
body{behavior:url(../../script/csshover.htc); font-size:100%;}
#menu ul li{float:left; width: 100%;}
#menu a{height:1%;}
</style>
<![endif]-->

<title>Реєстрація</title>

<script type="text/javascript" src="../../script/func.js"></script>
<script type="text/javascript" src="../../script/csshover.htc"></script>

<script type="text/javascript" src="../../script/addclasskillclass.js"></script>
<script type="text/javascript" src="../../script/attachevent.js"></script>
<script type="text/javascript" src="../../script/tabtastic.js"></script>

<script type="text/javascript">	
	function submitForm(mode) {
		form = document.forms['adminForm'];
		correct = true;
		
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
        <form name="adminForm" action="reg_form.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <h3>Сторінка на реконструкції</h3>
            
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>