<?php
//=======================================
//==  соединение с БД
//=======================================
function connectDB () {
	global $user_bd, $pass_bd, $name_bd;
	if (!@mysql_connect('localhost', $user_bd, $pass_bd)) {
		echo 'Не вдалося з\'єднатися з БД';
		exit;
	}
	@mysql_select_db($name_bd);
	mysql_query('SET CHARACTER SET cp1251');
}

//================================================================
//==  Возвращает значение из массива _GET или _POST или _SESSION
//==  Если задан параметр $page - запоминаем изменения в сессии
//================================================================
function getFormValue($name, $defa='', $page='') {
global $_GET, $_POST, $_SESSION;
	$putToSession = $page!='';
	$rez = $defa;
	if (isset($_GET[$name])) {
		$rez = stripslashes(trim($_GET[$name]));
	} else if (isset($_POST[$name])) {
		$rez = stripslashes(trim($_POST[$name]));
	} else if (isset($_SESSION[$name.($page!='' ? '_'.$page : '')])) {
		$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.$page : '')]));
		$putToSession = false;
	}
	if ($putToSession)
		setSessionValue($name, $rez, $page);
	return $rez;
}

//=====================================================
//==  Сохраняет значение в SESSION
//=====================================================
function setSessionValue($name, $value, $page='') {
	global $_SESSION;
	$_SESSION[$name.($page!='' ? '_'.$page : '')] = $value;
}

//=======================================
//==  Возвращает список страниц
//=======================================
function getPagination($options) {
	$groupCnt = 10;
	$limits = array(0,5,10,15,20,25,30,50,100);

	$MORE_PAGES = '<div id="pagination">';
	// Всего записей ххх
	$MORE_PAGES .= '<div class="limit">Всього записів: '.$options['total'].'</div>';
	// Список с кол-вом записей на странице
	$MORE_PAGES .= '<div class="limit">Показувати по: '
		.'<select name="limit" size="1" onchange="this.form.limitstart.value=0;this.form.submit();">';
	foreach($limits as $v) {
		$MORE_PAGES .= '<option value="'.$v.'"'.($options['limit']==$v ? ' selected="selected"' : '').'>'.($v==0 ? 'Все' : $v).'</option>';
	}
	$MORE_PAGES .= '</select></div>';

	if ($options['limit']>0 && $options['total'] > $options['limit']) {
		$partAll = ceil($options['total']/$options['limit']);
		$partCur = ceil(($options['limitstart']+1)/$options['limit']);
		$groupCur = ceil($partCur/$groupCnt);
		// Страница хх из ххх
		$MORE_PAGES .= '<div class="limit">Сторінка '.$partCur.' з '.$partAll.'</div><div class="clr"></div>';
		// Кнопка "Первая"
		$MORE_PAGES .= '<div class="button2-right">'; 
		if ($options['limitstart'] > 0) 
			$MORE_PAGES .= '<div class="start"><a href="javascript:dummy();" onclick="document.adminForm.limitstart.value=0;document.adminForm.submit();">На початок</a></div>';
		else
			$MORE_PAGES .= '<div class="start-off"><span>На початок</span></div>';
		$MORE_PAGES .= '</div>'; 
		// Кнопка "Предыдущая"
		$MORE_PAGES .= '<div class="button2-right">'; 
		if ($options['limitstart'] > 0) 
			$MORE_PAGES .= '<div class="prev"><a href="javascript:dummy();" onclick="document.adminForm.limitstart.value='.max(0, $options['limitstart']-$options['limit']).';document.adminForm.submit();">Попередня</a></div>';
		else
			$MORE_PAGES .= '<div class="prev-off"><span>Попередня</span></div>';
		$MORE_PAGES .= '</div>'; 
		// Кнопки страниц
		$MORE_PAGES .= '<div class="pages"><div class="page">';
		$curPage = 0;
		for ($i=0; $i<$options['total']; $i+=$options['limit']) {
			$curPage++;
			if ($curPage%$groupCnt==1) {
				$curGroup = ceil($curPage/$groupCnt);
				if ($curGroup!=$groupCur)
					$MORE_PAGES .= '<a href="javascript:dummy();" onclick="document.adminForm.limitstart.value='.$i.';document.adminForm.submit();">'.$curPage.'-'.min($curPage+$groupCnt-1, $partAll).'</a>';
			}
			if ($curGroup==$groupCur) {
				if ($i==$options['limitstart']) {
					$MORE_PAGES .= "<span>$curPage</span>";
				} else 
					$MORE_PAGES .= '<a href="javascript:dummy();" onclick="document.adminForm.limitstart.value='.$i.';document.adminForm.submit();">'.$curPage.'</a>';
			}
		}
		$MORE_PAGES .= '<div class="clr"></div></div></div>';
		// Кнопка "Следующая"
		$MORE_PAGES .= '<div class="button2-left">'; 
		if ($partCur < $partAll)
			$MORE_PAGES .= '<div class="next"><a href="javascript:dummy();" onclick="document.adminForm.limitstart.value='.($options['limitstart']+$options['limit']).';document.adminForm.submit();">Наступна</a></div>';
		else
			$MORE_PAGES .= '<div class="next-off"><span>Наступна</span></div>';
		$MORE_PAGES .= '</div>'; 
		// Кнопка "Последняя"
		$MORE_PAGES .= '<div class="button2-left">'; 
		if ($partCur < $partAll) 
			$MORE_PAGES .= '<div class="end"><a href="javascript:dummy();" onclick="document.adminForm.limitstart.value='.(($partAll-1)*$options['limit']).';document.adminForm.submit();">В кінець</a></div>';
		else
			$MORE_PAGES .= '<div class="end-off"><span>В кінець</span></div>';
		$MORE_PAGES .= '</div>'; 
	}
	$MORE_PAGES .= '<input type="hidden" name="limitstart" value="'.$options['limitstart'].'" />';
	$MORE_PAGES .= '<div class="clr"></div></div><br />';
	return $MORE_PAGES;
}

//=======================================
//==  выборка полей из таблицы
//==  для формирования списка
//=======================================
function getList($tbl, $fldId='id', $fldName='name', $curId=0, $all='', $orderBy=0, $wWhere='') {
	$str = ($all != '') ? '<option value="0">'.$all.'</option>' : '';
	$strOrder = ($orderBy) ? ' ORDER BY '.$fldName : '';
	$strWhere = ($wWhere) ? ' AND '.$wWhere : '';
	
	$resId = mysql_query('SELECT '.$fldId.', '.$fldName
								.' FROM '.$tbl
								.' WHERE '.$fldId.'<>0'
								.$strWhere
								.' GROUP BY '.$fldId
								.$strOrder
								);
	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {
			$str .= '<option value="'.$row[$fldId].'"'
					.($row[$fldId] == $curId ? ' selected="selected"' : '')
					.'>'.addslashes(stripslashes($row[$fldName])).'</option>';
		}
		@mysql_free_result($resId);
	}
	return $str;
}
//--------------------------------------------------
function getListTypes($curId=0, $all='- Усі способи -', $orderBy=0, $wWhere='') {
	return getList('types_report', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListPeriods($curId=0, $all='- Усі періоди -', $orderBy=0, $wWhere='') {
	return getList('periods', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//---------------------------------------------------
function getListMonths($curId=0, $all='- Усі місяці -', $orderBy=0, $wWhere='') {	
	return getList('periods', 'number', 'name', $curId, $all, $orderBy, $wWhere);
}
//---------------------------------------------------
function getListMonthsR($curId=0, $all='- Усі місяці -', $orderBy=0, $wWhere='') {	
	return getList('periods', 'number', 'name_rod', $curId, $all, $orderBy, $wWhere);
}
//---------------------------------------------------
function getListYears($curId=0, $all='- Усі роки -', $orderBy=1, $wWhere='') {	
	return getList('years', 'name', 'name', $curId, $all, $orderBy, $wWhere);
}
//---------------------------------------------------
function getListIdYears($curId=0, $all='- Усі роки -', $orderBy=1, $wWhere='') {	
	return getList('years', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListPeriodsReport($curId=0, $all='- Усі періоди -', $orderBy=0, $wWhere='') {
	return getList('periods_report', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListOrganizations($curId=0, $all='- Усі організації -', $orderBy=1, $wWhere='') {
	return getList('organizations', 'id', 'edrpou', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListForms($curId=0, $all='- Усі форми -', $orderBy=1, $wWhere='not_in_use=0') {
	return getList('forms', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListPeriodsForm($curId=0, $all='- Усі періоди -', $orderBy=0, $wWhere='') {
	return getList('periods_form', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListDepartments($curId=0, $all='- Усі відділи -', $orderBy=0, $wWhere='ter=101') {
	return getList('departments', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListDepartmentsFull($curId=0, $all='- Усі відділи -', $orderBy=0, $wWhere='') {
	return getList('departments', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListRays($curId=0, $all='- Усі райони -', $orderBy=1, $wWhere='ter>=108') {
	return getList('departments', 'id', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListRaysFull($curId=0, $all='- Усі райони -', $orderBy=1, $wWhere='ter>101') {
	return getList('departments', 'ter', 'name', $curId, $all, $orderBy, $wWhere);
}
//--------------------------------------------------
function getListUsers($curId=0, $all='- Усі користувачі -', $orderBy=1, $wWhere='') {
	return getList('users', 'id', 'login', $curId, $all, $orderBy, $wWhere);
}

//=======================================
function getListSukup($curId=2, $all='- Усі звіти -') {
	$str = ($all != '') ? '<option value="2">'.$all.'</option>' : '';
	$str .= '<option value="0"'.(0 == $curId ? ' selected="selected"' : '').">за сукупністю</option>";
	$str .= '<option value="1"'.(1 == $curId ? ' selected="selected"' : '').">поза сукупністю</option>";
	return $str;
}
//--------------------------------------------------
function getListGiven($curId=2, $all='- Усі звіти -') {
	$str = ($all != '') ? '<option value="2">'.$all.'</option>' : '';
	$str .= '<option value="1"'.(1 == $curId ? ' selected="selected"' : '').">подані</option>";
	$str .= '<option value="0"'.(0 == $curId ? ' selected="selected"' : '').">не подані</option>";
	return $str;
}
//--------------------------------------------------
function getListIntime($curId=2, $all='- Усі звіти -') {
	$str = ($all != '') ? '<option value="2">'.$all.'</option>' : '';
	$str .= '<option value="1"'.(1 == $curId ? ' selected="selected"' : '').">подані вчасно</option>";
	$str .= '<option value="0"'.(0 == $curId ? ' selected="selected"' : '').">подані з запізненням</option>";
	return $str;
}
//--------------------------------------------------
function getListSubject($curId=2, $all='- Усі суб\'єкти -') {
	$str = ($all != '') ? '<option value="2">'.$all.'</option>' : '';
	$str .= '<option value="1"'.(1 == $curId ? ' selected="selected"' : '').">фіз. особи</option>";
	$str .= '<option value="0"'.(0 == $curId ? ' selected="selected"' : '').">юр. особи</option>";
	return $str;
}

//=======================================
//==  выборка 2х полей из одной таблицы 
//==  в одну (двойную) запись списка
//=======================================
function getDoubleListOne($tbl, $fldId='id', $fldName='name', $fldSubName, $curId=0, $all='') {
	$str = ($all != '') ? "<option value=\"0\">$all</option>" : '';
	$str_query = "SELECT $tbl.$fldId, $tbl.$fldName, $tbl.$fldSubName"
					." FROM $tbl";
	$resId = mysql_query($str_query);
	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {
			$str .= "<option value=\"{$row[$fldId]}\"".($row[$fldId] == $curId ? ' selected="selected"' : '').">"
			          ."{$row[$fldName]}"." ("."{$row[$fldSubName]}".")"
					  ."</option>";
		}
		@mysql_free_result($resId);
	}
	return $str;
}
//--------------------------------------------------
function getDblListOneTerritories($curId=0, $all='- Усі території -') {
	return getDoubleListOne('territories', 'id', 'id', 'name', $curId, $all);
}

//=======================================
//==  выборка 2х полей из разных таблиц 
//==  в одну (двойную) запись списка
//=======================================
function getDoubleListTwo($tbl, $subTbl, $fldId='id', $fldName='name', $fldSubName, $relFld, $orderBy, $wWhere='', $curId=0, $all='') {
	$str = ($all != '') ? '<option value="0">'.$all.'</option>' : '';
	$strWhere = ($wWhere) ? ' AND '.$wWhere : '';
	$strOrder = ($orderBy) ? " ORDER BY $tbl.$fldName" : '';
	
	$str_query = "SELECT $tbl.$fldId AS id, $tbl.$fldName AS main_fld, $subTbl.$fldSubName AS sub_fld"
					." FROM $tbl"
						." LEFT JOIN $subTbl"
							." ON $tbl.$relFld=$subTbl.$fldId"
					." WHERE $tbl.$fldId<>0"
					.$strWhere
					.$strOrder;
	$resId = mysql_query($str_query);
	
	if ($resId) {	
		while ($row = mysql_fetch_assoc($resId)) {
			$str .= "<option value=\"{$row['id']}\"".($row['id'] == $curId ? ' selected="selected"' : '').">"
			          ."{$row['main_fld']}"." ("."{$row['sub_fld']}".")"
					  ."</option>";
		}
		@mysql_free_result($resId);
	}
	return $str;
}
//--------------------------------------------------
function getDoubleListTwoForms($curId=0, $all='- Усі форми -') {
	return getDoubleListTwo('forms', 'periods_form', 'id', 'name', 'name', 'id_period_form', $orderBy=1, $wWhere='forms.not_in_use=0', $curId, $all);
}
//--------------------------------------------------
function getDblListTwoPeriodsR($curId=0, $all='- Усі періоди -') {
	return getDoubleListTwo('periods_report', 'years', 'id', 'name', 'name', 'id_year', $orderBy=0, $wWhere='', $curId, $all);
}

//=======================================
//==  перевод даты любого формата
//==  в формат TIMESTAMP
//=======================================
function dateToTimestamp($curDate) {
	$timestamp = '';

	if ($curDate != 0) {
		$arrFull = explode(' ', $curDate);
		
		if (substr($arrFull[0], 4, 1) == '-') {
			$arrDate = explode('-', $arrFull[0]);
			
			if (checkdate($arrDate[1], $arrDate[2], $arrDate[0])) {
				$day = $arrDate[2];
				$month = $arrDate[1];
				$year = $arrDate[0];
			}
		} else if (substr($arrFull[0], 1, 1) == '.' || substr($arrFull[0], 2, 1) == '.') {
			$arrDate = explode('.', $arrFull[0]);
			
			if (checkdate($arrDate[1], $arrDate[0], $arrDate[2])) {
				$day = $arrDate[0];
				$month = $arrDate[1];
				$year = $arrDate[2];
			}
		} else if (substr($arrFull[0], 1, 1) == ',' || substr($arrFull[0], 2, 1) == ',') {
			$arrDate = explode(',', $arrFull[0]);
			
			if (checkdate($arrDate[1], $arrDate[0], $arrDate[2])) {
				$day = $arrDate[0];
				$month = $arrDate[1];
				$year = $arrDate[2];
			}
		}
		
		/*if (substr($arrFull[0], 1, 1) == '-' || substr($arrFull[0], 2, 1) == '-' || substr($arrFull[0], 4, 1) == '-')
			$arrDate = explode('-', $arrFull[0]);
		else if (substr($arrFull[0], 1, 1) == '.' || substr($arrFull[0], 2, 1) == '.' || substr($arrFull[0], 4, 1) == '.')
			$arrDate = explode('.', $arrFull[0]);
		else if (substr($arrFull[0], 1, 1) == ',' || substr($arrFull[0], 2, 1) == ',' || substr($arrFull[0], 4, 1) == ',')
			$arrDate = explode(',', $arrFull[0]);
		else if (substr($arrFull[0], 1, 1) == '\\' || substr($arrFull[0], 2, 1) == '\\' || substr($arrFull[0], 4, 1) == '\\')
			$arrDate = explode('\\', $arrFull[0]);
		else if (substr($arrFull[0], 1, 1) == '/' || substr($arrFull[0], 2, 1) == '/' || substr($arrFull[0], 4, 1) == '/')
			$arrDate = explode('/', $arrFull[0]);
		
		if (checkdate($arrDate[1], $arrDate[0], $arrDate[2])) {
			$day = $arrDate[0];
			$month = $arrDate[1];
			$year = $arrDate[2];
		} else if (checkdate($arrDate[1], $arrDate[2], $arrDate[0])) {
			$day = $arrDate[2];
			$month = $arrDate[1];
			$year = $arrDate[0];
		} else if (checkdate($arrDate[0], $arrDate[1], $arrDate[2])) {
			$day = $arrDate[1];
			$month = $arrDate[0];
			$year = $arrDate[2];
		}*/
		
		if (isset($day)) {
			if (isset($arrFull[1])) {
				list($hour, $minute, $second) = explode(':', $arrFull[1]);
				$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			} else {
				$timestamp = mktime(0, 0, 0, $month, $day, $year);
			}
		}
	}
	
	return $timestamp;
}

//--------------------------------------------------
//--  перевод даты в заданый формат
//--------------------------------------------------
function formatDate($formatDate, $curDate) {
	$phpDate = dateToTimestamp($curDate) ? date($formatDate, dateToTimestamp($curDate)) : '';	
	return $phpDate;
}
//========================================
function phpDateShort($curDate) {
	return formatDate('d.m.Y', $curDate);
}
//---------------------------------------------------
function phpDateFull($curDate) {	
	return formatDate('d.m.Y H:i:s', $curDate);
}
//========================================
function sqlDateShort($curDate) {
	return formatDate('Y-m-d', $curDate);
}
//---------------------------------------------------
function sqlDateFull($curDate) {
	return formatDate('Y-m-d H:i:s', $curDate);
}
//========================================
function dayDate($curDate) {	
	return formatDate('d', $curDate);
}
//---------------------------------------------------
function monthDate($curDate) {
	return formatDate('m', $curDate);
}
//---------------------------------------------------
function yearFullDate($curDate) {	
	return formatDate('Y', $curDate);
}
//---------------------------------------------------
function yearShortDate($curDate) {	
	return formatDate('y', $curDate);
}
//---------------------------------------------------
function timeDate($curDate) {	
	return formatDate('H:i:s', $curDate);
}

//--------------------------------------------------
//--  сравнение двух дат
//--  с учетом времени
//--------------------------------------------------
function comparisonDates($date1, $date2) {
	$timestamp1 = dateToTimestamp($date1) ? dateToTimestamp($date1) : 0;
	$timestamp2 = dateToTimestamp($date2) ? dateToTimestamp($date2) : 0;
	
	$difference = $timestamp1 - $timestamp2;
	return $difference;
}
//--------------------------------------------------
//--  сравнение двух дат
//--  без учета времени
//--------------------------------------------------
function cmpDate($date1, $date2, $type) {	
	if ($date2 != 0) {	
		if ($date1 != 0) {
			$difference = comparisonDates($date1, $date2);
			
			if ($type == 'text') {
				$res = ceil($difference/86400);
				
				if ($difference > 0)
					$str = '<span class="red" style="font-style:italic;">подано з запізненням<br />'.$res.' дні(в)</span>';
				else
					$str = '&nbsp;';
			} else if ($type == 'color') {
				if ($difference > 0)
					$str = '<span class="red">'.phpDateShort($date1).'</span>';
				else
					$str = '<span class="green">'.phpDateShort($date1).'</span>';
			}
		} else {
			$difference = comparisonDates(date('Y-m-d H:i:s', mktime()), $date2);
			
			if ($type == 'text') {
				$res = ceil($difference/86400);
				
				if ($difference > 0)
					$str = '<span class="orange" style="font-style:italic;">подається з запізненням<br />'.$res.' дні(в)</span>';
				else
					$str = '&nbsp;';
			} else if ($type == 'color') {
				if ($difference > 0)
					$str = '<span class="red">звіт не подано</span>';
				else
					$str = '<span class="green">звіт не подано</span>';
			}
		}
	} else {
		if ($date1 != 0) {
			if ($type == 'text') {
				$str = '&nbsp;';
			} else if ($type == 'color') {
				$str = phpDateShort($date1);
			}
		} else {
			if ($type == 'text') {
				$str = '&nbsp;';
			} else if ($type == 'color') {
				$str = 'звіт не подано';
			}
		}
	}
	
	return $str;
}
//---------------------------------------------------
function cmpDateText($date1, $date2) {	
	return cmpDate($date1, $date2, 'text');
}
//---------------------------------------------------
function cmpDateColor($date1, $date2) {	
	return cmpDate($date1, $date2, 'color');
}
?>