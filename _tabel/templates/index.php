<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/dropdown_menu.css" media="screen" rel="stylesheet" type="text/css" />

<title>Табель звітності</title>

<script type="text/javascript" src="../script/func.js"></script>
</head>

<body>
<div id="container">
	<span class="nonprint">
		<?php require_once('_header.php'); ?>
    </span>
    
    <div id="content">    	
        <div align="justify" style="text-indent:15px; width:80%; margin-left:10%; margin-top:20px; margin-bottom:20px">
            <p>З січня 2013 року збір державної статистичної та фінансової звітності на паперових носіях здійснюється за принципом <a href="../../windows1.htm">«єдиного вікна»</a> – на першому поверсі Головного управління статистики (к. № 11, 37-50-55).</p><br />
            <p>Альтернативним способом звітування, який виключає необхідність подання звіту у паперовому вигляді, є <a href="http://www.ukrstat.gov.ua/elektr_zvit/menu.htm" target="_blank">система електронної звітності</a> (к. № 22, 37-50-87, 095-288-25-22).</p>
        </div>
        
        <h3>Перелік форм статистичної та фінансової звітності,<br />
        які у <?php echo date('Y'); ?> році має подавати суб'єкт ЄДРПОУ з ідентифікаційним кодом:
        <u><?php echo $listItems[0]['edrpou']; ?></u><br />
        <?php echo $listItems[0]['org_name']; ?><br />
		станом на <font class="red"><?php echo date('d.m.Y'); ?></font></h3>
		
        <table width="95%">
          <tr class="class_TH">
            <th width="70">Дата оновлення сукупності</th>
            <th>Форма</th>
            <th>Термін подання</th>
            <th>Примітка</th>
            <th>Структурний (територіальний) підрозділ,<br />відповідальний за проведення спостереження</th>
            <th class="nonprint">Pезультати проведених<br />державних статистичних спостережень</th>
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
                	<?php echo (($v['blank']) ? '<br />'.makeLink($v['blank'], 'Бланк форми') : ''); ?>
                	<?php echo (($v['instr']) ? '<br />'.makeLink($v['instr'], 'Інструкція щодо заповнення') : ''); ?>
                	<?php echo (($v['rozyasn']) ? '<br />'.makeLink($v['rozyasn'], 'Роз\'яснення щодо заповнення форми') : ''); ?>
                </span>
            </td>
            
            <td><?php echo $v['srok_sdachi']; ?></td>
            <td><?php echo $v['note']; ?></td>
            <td><?php echo $v['department']
                                    .(($v['room']) ? ', каб. '.$v['room'] : '')
                                    .(($v['adres']) ? '<br /><em>('.$v['adres'].')</em>' : '')
                                    .(($v['tel']) ? '<br />тел.: '.$v['tel'] : '')
                                    .(($v['fax']) ? '<br />факс: '.$v['fax'] : '')
                                    .(($v['mob']) ? '<br />моб.тел.: '.$v['mob'] : '')
                                    ; ?></td>
            <td class="nonprint">
                <?php echo ($v['ekspres']) ? '<a href="http://mk.ukrstat.gov.ua/expres/expres_t.htm#'.$v['ekspres'].'">Експрес-випуск</a><br />' : '';
                          echo (($v['region']) || ($v['m_rayon'])) ? 'Статистична інформація:<br />' : '';
                          echo ($v['region']) ? '- <a href="http://mk.ukrstat.gov.ua/stat.htm#'.$v['region'].'">по регіону</a><br />' : '';
                          echo ($v['m_rayon']) ? '- <a href="http://mk.ukrstat.gov.ua/stat.htm#'.$v['m_rayon'].'">міжрайонні порівняння</a>' : ''; ?>    
            </td>
          </tr>
          <?php } } ?>
        </table>
        
        <div align="center">
            <span class="red">Шановний респонденте!</span><br /><br />
            Наведений перелік є орієнтовним і може змінюватися<br />
            в залежності від мети статистичного спостереження, економічних показників,<br />
            демографічних подій на підприємстві, зміни основного виду діяльності тощо.<br />
            <p>&nbsp;</p>
        </div>
	</div>
</div>        
    
<?php require_once('_footer.php'); ?>
</body>
</html>