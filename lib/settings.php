<?php
//== ��� ���������� � ������ ����������� �����
//== ���������� �   $_   ��� ����� ������ ������������� � ����

//��������� ��
$user_bd = 'root';
$pass_bd = '';
$name_bd = 'oblstat';

//����� ������
$_ses_admin = 'admin';
$_ses_reader = 'reader';
$_ses_user = 'user';
$_ses_expert = 'expert';

//���� � �����, � ������� �������� �����-������ ��� ����� � �������
$_loginPHP = 'index.php';

$_header = 'Online-����� "��������� ������� �� ������ ������"';
$_footer = '� ������� ��������� ���������� � ����������� ������, 2012-2013';

//������ � ������ ��� �����������, ������� �� ��������
//� ���� ��� ����� ���������� ����������
$opfFinZvitn = array();
$opfFinZvitn[] = 320;
for ($i=400;$i<=495;$i++) $opfFinZvitn[] = $i;
for ($i=600;$i<=620;$i++) $opfFinZvitn[] = $i;
for ($i=800;$i<=860;$i++) $opfFinZvitn[] = $i;
$opfFinZvitn[] = 910;
$opfFinZvitn[] = 950;

//���� ����������, ������� ��������� � ���������� ������
$_terInCity = array();
$_terInCity[] = 101;
$_terInCity[] = 233;
$_terInCity[] = 242;

//id ������ "������ ����" �� ������� otdels
$_edOkno = 1;

?>