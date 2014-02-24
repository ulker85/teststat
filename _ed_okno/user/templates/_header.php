<div id="header">
    <?php echo $_header; ?>
</div>

<div id="head_line">
    <div class="navigation_left">
        <?php
        	echo ($mark['name'])
				? 'Звітність вводиться із позначкою <span style="text-transform:uppercase">"'.$mark['name'].'"</span>' 
				: ''; 
		?>
    </div>
    
    <div class="navigation_right">
        Поточна дата: <?php echo date('d.m.Y'); ?>
    </div>
</div>

<div id="navigation">
    <div id="menu">
        <div class="navigation_left">
            <ul>
                <li><a class="top_parent" href="#">Вхідна інформації</a>
                <ul>
                    <li><a href="index.php">Реєстрація</a></li>
                    <?php if ($_SESSION['type_report'] != 4) { ?>
                    <li><a href="notes.php">Примітка</a></li>
                    <?php } ?>
                </ul>
                </li>
            </ul>
            
            <ul>
                <li><a class="top_parent" href="#">Вихідна інформація</a>
                <ul>
                    <li><a class="parent" href="#">Довідники</a>
                        <ul>
                            <li><a href="../reader/forms.php">форм</a></li>
                            <li><a href="../reader/organizations.php">організацій</a></li>
                            <li><a href="../reader/departments.php">відділів</a></li>
                            <li><a href="../reader/territories.php">територій</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="parent" href="#">Аналітика</a>
                        <ul>
                            <li><a class="parent" href="#">по звітах</a>
                                 <ul>
                                    <li><a href="../reader/an_respondents.php">у розрізі респондентів</a></li>
                                    <li><a href="../reader/an_forms.php">у розрізі форм</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="../reader/an_blanks.php">по бланках</a></li>
                        </ul>
                    </li>

                    <li><a class="parent" href="#">Зведення</a>
                        <ul>
                            <li><a href="../reader/sv_general.php">загальна статистика</a></li>
                            <li><a href="../reader/sv_reports.php">по поданих звітах</a></li>
                            <li><a href="../reader/sv_blanks.php">по бланках</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="parent" href="#">Інше</a>
                        <ul>
                            <li><a href="../reader/aggregates.php">сукупності</a></li>
                            <li><a href="../reader/chart.php">графік</a></li>
                            <li><a href="#">навантаженність</a></li>
                            <li><a href="#">коректури звітів</a></li>
                        </ul>
                    </li>                    
                </ul>
                </li>
            </ul>
        </div>
        
        <div class="navigation_right">
            <ul>	
                <li><a href="../../<?php echo $_loginPHP; ?>">Вийти</a></li>
            </ul>
        </div>
    </div>
</div>