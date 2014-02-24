// JavaScript Document
function chkOn(id){
	document.getElementById(id).checked='checked';
	document.getElementById('del').disabled='disabled';
	document.getElementById('btnAdd').disabled='disabled';
	document.getElementById('import').disabled='disabled';
	document.getElementById('export').disabled='disabled';
}

function getValue(id){
	document.getElementById(id).value=id;
}


function chkOnForDel($formName) {
	count=0;
	for (i=0; i<document.forms[$formName].elements.length; i++) {
		if (document.forms[$formName].elements[i].name.substr(0, 4) == 'chk_') 
			if (document.forms[$formName].elements[i].checked) count++;
	}
	document.getElementById('edit').disabled = count>0 ? 'disabled' : '';
	document.getElementById('btnAdd').disabled = count>0 ? 'disabled' : '';
	document.getElementById('import').disabled = count>0 ? 'disabled' : '';
	document.getElementById('export').disabled = count>0 ? 'disabled' : '';
}

function selection() {
	form = document.forms['adminForm'];
	
	if (form.rdEdrpou.checked) {
		/*form.filtr_f.disabled = 'disabled';
		form.filtr_f.selectedIndex = 0;
		
		form.filtr_p.disabled = 'disabled';
		form.filtr_p.selectedIndex = 0;*/
		
		form.sname.disabled = 'disabled';
		form.sname.style['background'] = '#EEEEEE';
		form.sname.value = '';
		
		form.edrpou.disabled = '';
		form.edrpou.style['background'] = '#FFFFFF';		
	} else if (form.rdName.checked) {
		/*form.filtr_f.disabled = 'disabled';
		form.filtr_f.selectedIndex = 0;
		
		form.filtr_p.disabled = 'disabled';
		form.filtr_p.selectedIndex = 0;*/
		
		form.edrpou.disabled = 'disabled';
		form.edrpou.style['background'] = '#EEEEEE';
		form.edrpou.value = '';
		
		form.sname.disabled = '';
		form.sname.style['background'] = '#FFFFFF';		
	/*} else {
		form.edrpou.disabled = 'disabled';
		form.edrpou.style['background'] = '#EEEEEE';
		form.edrpou.value = '';
		
		form.sname.disabled = 'disabled';
		form.sname.style['background'] = '#EEEEEE';
		form.sname.value = '';
		
		form.filtr_f.disabled = '';
		form.filtr_p.disabled = '';*/
	}
}

function chkOnOff(id){
	idFirst = 'first_' + id.substr(4);
	idSecond = 'second_' + id.substr(4);	
	if (document.getElementById(idFirst)) {
		if ((document.getElementById(idFirst).checked) || (document.getElementById(idSecond).checked)) {
			document.getElementById(id).checked = 'checked';
		} else {
			document.getElementById(id).checked = '';
		}
	} else {
		if (document.getElementById(idSecond).checked) {
			document.getElementById(id).checked = 'checked';
		} else {
			document.getElementById(id).checked = '';
		}
	}
}



function showMsg(msg) {
	if (msg) 	alert(msg);
}

function checkForEnter(evt, mode) {
	evt = (evt) ? evt : event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode == 13) {
		document.forms['adminForm'].mode.value = mode;
		document.forms['adminForm'].submit();
		return false;
	}
	return true;
}

function printDoc() {
	var lefto = screen.availWidth/2-450;
	var topto = screen.availHeight/2-300;
	
	var params = "height=600, width=900, resizable=no, scrollbars=no, left="+lefto+", top="+topto+"";
	
	var newWindow = window.open("../tabel.php", "tabel", params);
	if (newWindow == null) alert('Не вдалося відправити документ на друк');
}

function makeDate() {
	var myYear = document.forms['adminForm'].filtr_y.value;
	var myMonth = document.forms['adminForm'].filtr_p.selectedIndex + 1;
	if (myMonth < 10) myMonth = '0' + myMonth;
	
	var str = document.write('.' + myMonth + '.' + myYear);
	
	return str;
}