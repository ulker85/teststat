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
                    <li><a href="index_kor.php">Коригування</a></li>
                    <li><a href="chart.php">Графік</a></li>
                </ul>
                </li>
            </ul>
            
            <ul>
                <li><a class="top_parent" href="#">Вихідна інформація</a>
                <ul>
                    <li><a class="parent" href="#">Аналітика</a>
                        <ul>
                            <li><a href="#">у розрізі респондентів</a></li>
                            <li><a href="#">у розрізі форм</a></li>
                        </ul>
                    </li>
                    <li><a class="parent" href="#">Зведення</a>
                        <ul>
                            <li><a href="#">загальна статистика</a></li>
                            <li><a href="#">статистика по коректурах</a></li>
                        </ul>
                    </li>
                    <li><a class="parent" href="#">Інше</a>
                        <ul>
                            <li><a href="../reader/chart.php">графік</a></li>
                            <li><a href="#">навантаженність</a></li>
                            <li><a href="#">бланки</a></li>
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