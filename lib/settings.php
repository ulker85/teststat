<?php
//== все переменные в данном настроечном файле
//== начинаются с   $_   для более легкой идентификации в коде

//настройки БД
$user_bd = 'root';
$pass_bd = '';
$name_bd = 'oblstat';

//имена сессий
$_ses_admin = 'admin';
$_ses_reader = 'reader';
$_ses_user = 'user';
$_ses_expert = 'expert';

//путь к файлу, в котором вводятся логин-пароль для входа в систему
$_loginPHP = 'index.php';

$_header = 'Online-сервіс "Реєстрація звітності та видачі бланків"';
$_footer = '© Головне управління статистики у Миколаївській області, 2012-2013';

//массив с кодами ОПФ предприятий, которые не попадают
//в круг для сдачи Финансовой отчетности
$opfFinZvitn = array();
$opfFinZvitn[] = 320;
for ($i=400;$i<=495;$i++) $opfFinZvitn[] = $i;
for ($i=600;$i<=620;$i++) $opfFinZvitn[] = $i;
for ($i=800;$i<=860;$i++) $opfFinZvitn[] = $i;
$opfFinZvitn[] = 910;
$opfFinZvitn[] = 950;

//коды территорий, которые относятся к областному уровню
$_terInCity = array();
$_terInCity[] = 101;
$_terInCity[] = 233;
$_terInCity[] = 242;

//id записи "Единое окно" из таблицы otdels
$_edOkno = 1;

?>