<?php
/*if (isset($_SESSION[$_ses_user])) {
	require_once('../user/templates/_header.php');
} else if (isset($_SESSION[$_ses_reader])) {*/
?>
    <div id="header">
        ���������� ��������� ������� �� ������ ������
    </div>
    
    <div id="head_line">
        <div class="navigation_left">
        </div>
        
        <div class="navigation_right">
            ������� ����: <?php echo date('d.m.Y'); ?>
        </div>
    </div>
    
    <div id="navigation">
        <div id="menu">
            <div class="navigation_left">
                <ul>
                    <li><a class="top_parent" href="#">��������</a>
                        <ul>
                            <li><a href="forms.php">����</a></li>
                            <li><a href="organizations.php">����������</a></li>
                            <li><a href="departments.php">�����</a></li>
                            <li><a href="territories.php">��������</a></li>    
                        </ul>
                    </li>  
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">��������</a>
                        <ul>
                            <li><a class="parent" href="#">�� �����</a>
                                <ul>
                                    <li><a href="an_respondents.php">� ����� �����������</a></li>
                                    <li><a href="an_forms.php">� ����� ����</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="an_blanks.php">�� �������</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">��������</a>
                        <ul>
                            <li><a href="sv_general.php">�������� ����������</a></li>
                            <li><a href="sv_reports.php">�� ������� �����</a></li>
                            <li><a href="sv_blanks.php">�� �������</a></li>
                        </ul>
                    </li>
                </ul>
                
                <ul>    
                    <li><a class="top_parent" href="#">����</a>
                        <ul>
                           <li><a href="aggregates.php">���������</a></li>
                           <li><a href="chart.php">������</a></li>
                           <li><a href="#">��������������</a></li>
                           <li><a href="#">��������� ����</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div class="navigation_right">
                <ul>	
                    <li><a href="../../<?php echo $_loginPHP; ?>">�����</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php //} ?>