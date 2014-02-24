<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link href="../css/style.css" rel="stylesheet" type="text/css">
<title>������ �������</title>

<script type="text/javascript" src="../script/func.js"></script>
</head>

<body>

<?php if ($ERROR_MSG != '') echo '<p class="error">'.$ERROR_MSG.'</p>'; ?>

<div align="center" style="margin-top:50px">
    <h4>������ ����<br />
    	����������� �� ��������� �������, �� � <u><?php echo $listHeaders['name_rod'].' '.date('Y').' ����'; ?></u><br />
    	�� �������� ���'��� ������ � ���������������� ����� <u><?php echo $listHeaders['edrpou']; ?></u><br />
    	<u><?php echo $listHeaders['name']; ?></u>
    </h4>
</div>

<table width="90%">
  <tr class="class_TH">
    <th>����� �����<br />�����</th>
    <th>������ �� �����������<br />�����</th>
    <th>�� ���� �����<br />��������</th>
    <th>ʳ������ �����<br />�������</th>
    <th>�������� ���<br />�������</th>
  </tr>
  
  <?php foreach ($listItems as $v) { ?>
  <tr class="class_TD">
    <td><?php echo $v['name_full']; ?></td>
    <td><?php echo $v['name_short'].' ('.$v['period'].')'; ?></td>
    <td><?php echo $v['period_report']; ?></td>
    <td align="center"><?php echo phpDateShort($v['num_srok']); ?></td>
    <td align="center"><em><strong><?php echo $v['date']; ?></strong></em></td>
  </tr>
  <?php } ?>
</table>

<div style="margin-top:20px; margin-left:20px; font-size:12px">����������: <u><?php echo date('d.m.Y'); ?></u></div>
<br />
<div align="center">
<form class="nonprint" name="adminForm" action="blank_absent.php" method="post" enctype="multipart/form-data">
	<input type="button" value="���������" class="button" onclick="window.print()" />
</form>
</div>

</body>
</html>