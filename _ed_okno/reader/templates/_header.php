<?php
/*if (isset($_SESSION[$_ses_user])) {
	require_once('../user/templates/_header.php');
} else if (isset($_SESSION[$_ses_reader])) {*/
?>
    <div id="header">
        Статистика реєстрації звітності та видачі бланків
    </div>
    
    <div id="head_line">
        <div class="navigation_left">
        </div>
        
        <div class="navigation_right">
            Поточна дата: <?php echo date('d.m.Y'); ?>
        </div>
    </div>
    
    <div id="navigation">
        <div id="menu">
            <div class="navigation_left">
                <ul>
                    <li><a class="top_parent" href="#">Довідники</a>
                        <ul>
                            <li><a href="forms.php">форм</a></li>
                            <li><a href="organizations.php">організацій</a></li>
                            <li><a href="departments.php">відділів</a></li>
                            <li><a href="territories.php">територій</a></li>    
                        </ul>
                    </li>  
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">Аналітика</a>
                        <ul>
                            <li><a class="parent" href="#">по звітах</a>
                                <ul>
                                    <li><a href="an_respondents.php">у розрізі респондентів</a></li>
                                    <li><a href="an_forms.php">у розрізі форм</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="an_blanks.php">по бланках</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">Зведення</a>
                        <ul>
                            <li><a href="sv_general.php">загальна статистика</a></li>
                            <li><a href="sv_reports.php">по поданих звітах</a></li>
                            <li><a href="sv_blanks.php">по бланках</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">Інше</a>
                        <ul>
                           <li><a href="aggregates.php">сукупності</a></li>
                           <li><a href="chart.php">графік</a></li>
                           <li><a href="#">навантаженність</a></li>
                           <li><a href="#">коректури звітів</a></li>
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
<?php //} ?>