<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Табель звітності</title>
</head>

<body onload="document.forms['adminForm'].elements['login'].focus();">
<div id="container">
	<?php require_once('_header.php'); ?>
    
    <div id="content">
        <div class="centeredDIV">
            <form name="adminForm" action="login.php" method="post">
            
            <table>
              <tr>
                <td valign="top">
                    <h4 style="color:#f89a49">Введіть ідентифікаційний код суб'єкта ЄДРПОУ:</h4>
                    <input type="text" name="login" size="25"  /><br />
                    <input type="submit" value="Пошук" class="button"  />
                    <?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>
                </td>
            
                <td>
              <h4>Шановний респонденте!</h4>
              <p align="justify">Введіть ідентифікаційний  код суб'єкта Єдиного державного реєстру підприємств і організацій (ЄДРПОУ) і Ви  отримаєте перелік форм державних статистичних спостережень, які має подавати Ваше  підприємство до органів державної статистики.</p>
                </td>
              </tr>
            </table>
            </form>
        </div>
	</div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>