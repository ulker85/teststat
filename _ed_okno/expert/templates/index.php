<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Головна (реєстрація)</title>

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
		if (form.rdName.checked && form.sname.value.length < 3) {
			correct = false;			
			alert('Надто мало символів для пошуку');
		}
		if (correct) {
			form.mode.value = mode;
			form.submit();
		}
	}
	
	function showMsg(msg) {
		if (msg) 	alert(msg);
	}
</script>

</head>

<body onload="document.forms['adminForm'].elements['edrpou'].focus(); 
					showMsg('<?php echo $ERROR_MSG; ?>')">
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
        <?php if ($msg) echo '<p class="error">'.$msg.'</p>'; ?>
        
        <form name="adminForm" action="index.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" />
        
        <div class="item_beige" style="margin-top:11%; margin-left:40%">
            <h2>Пошук звітності</h2>
            
            <fieldset>
                <legend>
                    <input type="radio" name="rdSearch" value="edrpou"
                              id="rdEdrpou" checked="checked"
                              onchange="selection()" />за ЄДРПОУ/ІНП
                </legend>
                
                <input type="text" name="edrpou" value="<?php echo $edrpou; ?>"
                          style="width:256px"
                          onkeypress="checkForEnter(event, 'search_edok')" />
            </fieldset>
            
            <fieldset>
                <legend>
                    <input type="radio" name="rdSearch" value="name"
                              id="rdName"
                              onchange="selection()" />за прізвищем керівника
                </legend>
                
                <input type="text" name="sname" value="<?php echo $sname; ?>"
                          style="width:256px; background:#EEEEEE" disabled="disabled"
                          onkeypress="checkForEnter(event, 'search_edok')" />
            </fieldset>
            
            <!--<fieldset>
                <legend>
                    <input type="radio" name="rdSearch" value="form"
                              id="rdForm"
                              onchange="selection()" />за формою
                </legend>
                
                <p><select name="filtr_f" style="width:256px" disabled="disabled"><?php //echo $allForm; ?></select></p>
                <select name="filtr_p" style="width:256px" disabled="disabled"><?php //echo $allPeriodReport; ?></select>
            </fieldset>-->
            
            <p align="center">
                <input type="button" value="Єдине вікно" class="button" onclick="submitForm('search_edok')" />
                <input type="button" value="Канцелярія" class="button" onclick="submitForm('search_kanc')" />
                <input type="button" value="Галузевий відділ" class="button" onclick="submitForm('search_depart')" />
            </p>    
        </div>
                    
        <div class="clr"></div>
        </form>
    </div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>