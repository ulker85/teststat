<div id="header">
    <?php echo $_header; ?>
</div>

<div id="head_line">
    <div class="navigation_left">
        <?php
        	echo ($mark['name'])
				? '������� ��������� �� ��������� <span style="text-transform:uppercase">"'.$mark['name'].'"</span>' 
				: ''; 
		?>
    </div>
    
    <div class="navigation_right">
        ������� ����: <?php echo date('d.m.Y'); ?>
    </div>
</div>

<div id="navigation">
    <div id="menu">
        <div class="navigation_left">
            <ul>
                <li><a class="top_parent" href="#">������ ����������</a>
                <ul>
                    <li><a href="index.php">���������</a></li>
                    <?php if ($_SESSION['type_report'] != 4) { ?>
                    <li><a href="notes.php">�������</a></li>
                    <?php } ?>
                </ul>
                </li>
            </ul>
            
            <ul>
                <li><a class="top_parent" href="#">������� ����������</a>
                <ul>
                    <li><a class="parent" href="#">��������</a>
                        <ul>
                            <li><a href="../reader/forms.php">����</a></li>
                            <li><a href="../reader/organizations.php">����������</a></li>
                            <li><a href="../reader/departments.php">�����</a></li>
                            <li><a href="../reader/territories.php">��������</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="parent" href="#">��������</a>
                        <ul>
                            <li><a class="parent" href="#">�� �����</a>
                                 <ul>
                                    <li><a href="../reader/an_respondents.php">� ����� �����������</a></li>
                                    <li><a href="../reader/an_forms.php">� ����� ����</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="../reader/an_blanks.php">�� �������</a></li>
                        </ul>
                    </li>

                    <li><a class="parent" href="#">��������</a>
                        <ul>
                            <li><a href="../reader/sv_general.php">�������� ����������</a></li>
                            <li><a href="../reader/sv_reports.php">�� ������� �����</a></li>
                            <li><a href="../reader/sv_blanks.php">�� �������</a></li>
                        </ul>
                    </li>
                    
                    <li><a class="parent" href="#">����</a>
                        <ul>
                            <li><a href="../reader/aggregates.php">���������</a></li>
                            <li><a href="../reader/chart.php">������</a></li>
                            <li><a href="#">��������������</a></li>
                            <li><a href="#">��������� ����</a></li>
                        </ul>
                    </li>                    
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