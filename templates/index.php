<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>���� � �����</title>

<script type="text/javascript">
	<?php
        if ($ERROR_MSG != '') echo "alert('".strip_tags($ERROR_MSG)."')";
    ?>
</script>

</head>

<body onload="document.forms['adminForm'].elements['login'].focus()">
<div class="centeredDIV">
    <div class="welcome">³���� � online-�����<br />"��������� ������� �� ������ ������"</div>
    
    <h3>������ ����-������ ��� �����</h3>
    
    <form name="adminForm" action="index.php" method="post">        
    <table>
      <tr>
        <td width="30%">����:</td>
        <td><input type="text" name="login" value="<?php echo $login; ?>" /></td>
      </tr>
      <tr>
        <td>������:</td>
        <td><input type="password" name="pass" /></td>
      </tr>
      <tr>
        <td colspan="2"><input class="button" type="submit" value="�����" /></td>
      </tr>
    </table>
    </form>
</div>

</body>
</html>