<div id="header">
    Адмінка для керування БД
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
                    <li><a class="parent" href="#">Єдине вікно</a>
                        <ul>
                            <li><a href="organizations.php">організації</a></li>
                            <li><a href="reports.php">сукупності</a></li>
                            <li><a href="codes.php">ел. звітність</a></li>
                            <li><a href="forms.php">форми</a></li>
                            <li><a class="parent" href="#">періоди</a>
                                <ul>
                                    <li><a href="periods_form.php">форм</a></li>
                                    <li><a href="periods_report.php">звітності</a></li>
                                    <li><a href="periods.php">загальні</a></li>
                                    <li><a href="years.php">роки</a></li>
                                </ul>
                            </li>
                            <li><a href="forms_periods.php">форми-періоди</a></li>
                            <li><a href="types.php">типи звітності</a></li>
                            <li><a href="reasons.php">причини неподання</a></li>
                        </ul>
                    </li>
                    <li><a class="parent" href="#">Анкета</a>
                        <ul>
                            <li><a href="#">питання</a></li>
                            <li><a href="#">відповіді</a></li>
                            <li><a href="#">результати</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php">Користувачі</a></li>
                    <li><a href="departments.php">Відділи</a></li>
                    <li><a href="territories.php">Территорії</a></li>
                </ul>
                </li>
            </ul>
            
            <ul>
                <li><a class="top_parent" href="#">Інші інтерфейси</a>
                <ul>
                    <li><a class="parent" href="#">Експерт</a>
                        <ul>
                            <li><a href="#">єдине вікно</a></li>
                        </ul>
                    </li>
                    <li><a class="parent" href="#">Користувач</a>
                        <ul>
                            <li><a href="#">єдине вікно</a></li>
                        </ul>
                    </li>
                    <li><a class="parent" href="#">Респондент</a>
                        <ul>
                            <li><a href="#">табель</a></li>
                            <li><a href="#">анкета</a></li>
                            <li><a href="#">гостьова</a></li>
                        </ul>
                    </li>
                </ul>
                </li>
            </ul>
            
            <ul>
                <li><a class="top_parent" href="#">Вихідні таблиці</a>
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
                    <hr />
                    <li><a href="../reader/chart.php">Графік</a></li>
                    <li><a href="#">Навантаженність</a></li>
                    <li><a href="#">Бланки</a></li>
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