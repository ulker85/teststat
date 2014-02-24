<div class="item_beige" style="margin:15px 30%; width:500px;">
	<h2>Респондент</h2>
    
    <p align="center"><strong><?php echo $org['name']; ?></strong></p>
	<p align="center"><strong><?php echo $org['edrpou']; ?></strong></p>
        		
	<?php
        if ($_SESSION['type_report'] != 4) {
		if (count($listNotes)) {
            foreach ($listNotes as $v) {
    ?>
                <div style="margin-bottom:7px; width:90%; margin-left:5%">
                    <div class="red_box" style="width:93%;"><?php echo $v['name']; ?></div>
                    
                    <div class="navigation_right" style="margin-top:-17px;">
                    	<input type="button" class="cancel" onclick="submitForm('del_note_<?php echo $v['id']; ?>')" />
                    </div>
                </div>
    <?php
            }
        }
		}
    ?>
    
    <p><strong>Керівник:</strong><br /><?php echo $org['leader']; ?></p>
	<p><strong>Юридична адреса:</strong><br /><?php echo $org['adres_yur']; ?></p>
    <p><strong>Телефон:</strong><br /><?php echo $org['phone'] ? $org['phone'] : '-'; ?></p>    
    <p>
    	<strong>Електронна адреса:</strong>
    	<input type="text" name="email"
        		  value="<?php echo $org['e_mail']; ?>"
                  class="green_box" />
        <input type="button" class="accept" onclick="submitForm('write_email')" />
    </p>
</div>
<div class="clr"></div>