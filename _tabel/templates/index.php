<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>������ �������</title>

<script type="text/javascript" src="../script/func.js"></script>
</head>

<body>
<div id="container">
	<span class="nonprint">
		<?php require_once('_header.php'); ?>
    </span>
    
    <div id="content">    	
        <div align="justify" style="text-indent:15px; width:80%; margin-left:10%; margin-top:20px; margin-bottom:20px">
            <p>� ���� 2013 ���� ��� �������� ����������� �� ��������� ������� �� ��������� ����� ����������� �� ��������� <a href="../../windows1.htm">�������� ����</a> � �� ������� ������ ��������� ��������� ���������� (�. � 11, 37-50-55).</p><br />
            <p>�������������� �������� ���������, ���� ������� ����������� ������� ���� � ���������� ������, � <a href="http://www.ukrstat.gov.ua/elektr_zvit/menu.htm" target="_blank">������� ���������� �������</a> (�. � 22, 37-50-87, 095-288-25-22).</p>
        </div>
        
        <h3>������ ���� ����������� �� ��������� �������,<br />
        �� � <?php echo date('Y'); ?> ���� �� �������� ���'��� ������ � ���������������� �����:
        <u><?php echo $listItems[0]['edrpou']; ?></u><br />
        <?php echo $listItems[0]['org_name']; ?><br />
		������ �� <font class="red"><?php echo date('d.m.Y'); ?></font></h3>
		
        <table width="95%">
          <tr class="class_TH">
            <th width="70">���� ��������� ���������</th>
            <th>�����</th>
            <th>����� �������</th>
            <th>�������</th>
            <th>����������� (�������������) �������,<br />������������ �� ���������� �������������</th>
            <th class="nonprint">P��������� ����������<br />��������� ������������ ������������</th>
          </tr>
          
          <?php if (!$flag || ($flag && isset($listItems[1]))) {foreach ($listItems as $v) { ?>
          <tr valign="top" class="class_TD">
            <td align="center"><?php echo phpDateShort($v['date_update']); ?></td>
            
            <td>
				<strong><?php echo $v['f_name']; ?>
                <?php echo ' ('.$v['f_period'].')'; ?></strong><br />
                <?php echo $v['f_name_full']; ?>
                <span class="nonprint">
                	<br />
                	<?php echo (($v['blank']) ? '<br />'.makeLink($v['blank'], '����� �����') : ''); ?>
                	<?php echo (($v['instr']) ? '<br />'.makeLink($v['instr'], '���������� ���� ����������') : ''); ?>
                	<?php echo (($v['rozyasn']) ? '<br />'.makeLink($v['rozyasn'], '���\'������� ���� ���������� �����') : ''); ?>
                </span>
            </td>
            
            <td><?php echo $v['srok_sdachi']; ?></td>
            <td><?php echo $v['note']; ?></td>
            <td><?php echo $v['department']
                                    .(($v['room']) ? ', ���. '.$v['room'] : '')
                                    .(($v['adres']) ? '<br /><em>('.$v['adres'].')</em>' : '')
                                    .(($v['tel']) ? '<br />���.: '.$v['tel'] : '')
                                    .(($v['fax']) ? '<br />����: '.$v['fax'] : '')
                                    .(($v['mob']) ? '<br />���.���.: '.$v['mob'] : '')
                                    ; ?></td>
            <td class="nonprint">
                <?php echo ($v['ekspres']) ? '<a href="http://mk.ukrstat.gov.ua/expres/expres_t.htm#'.$v['ekspres'].'">�������-������</a><br />' : '';
                          echo (($v['region']) || ($v['m_rayon'])) ? '����������� ����������:<br />' : '';
                          echo ($v['region']) ? '- <a href="http://mk.ukrstat.gov.ua/stat.htm#'.$v['region'].'">�� ������</a><br />' : '';
                          echo ($v['m_rayon']) ? '- <a href="http://mk.ukrstat.gov.ua/stat.htm#'.$v['m_rayon'].'">�������� ���������</a>' : ''; ?>    
            </td>
          </tr>
          <?php } } ?>
        </table>
        
        <div align="center">
            <span class="red">�������� �����������!</span><br /><br />
            ��������� ������ � ��������� � ���� ����������<br />
            � ��������� �� ���� ������������� �������������, ���������� ���������,<br />
            ������������� ���� �� ���������, ���� ��������� ���� �������� ����.<br />
            <p>&nbsp;</p>
        </div>
	</div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>