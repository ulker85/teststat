<?php
function traceMsg($msg, $fname='debug.log') {
	global $_SERVER;
	$dir = $_SERVER['DOCUMENT_ROOT'];
	if (substr($dir, -1, 1) != '/')
		$dir .= '/'.'db/';  //$dir .= '/';
	if ($fd = fopen($dir.$fname, 'a+')) {
		flock($fd, LOCK_EX);
		fwrite($fd, date('d.m.Y H:i:s')." $msg\n\n");
	    fflush($fd);
	    flock($fd, LOCK_UN);
    	fclose($fd);
	}
}

function dumpFile(&$var) {
	traceMsg("\n\n".textDump($var, 0, false));
}

function dumpVar(&$var) {
	return textDump($var);
}

function dumpScreen(&$var) {
	if ((is_array($var) || is_object($var)) && count($var))
		echo "<pre>\n".textDump($var)."</pre>\n";
	else
		echo "<tt>".textDump($var)."</tt>\n";
}

function textDump(&$var, $level=0, $html=true)  {
	if (is_array($var)) $type="Array[".count($var)."]";
	else if (is_object($var)) $type="Object";
	else $type="";

	if ($type) {
		$rez = "$type\n";
		for (Reset($var), $level++; list($k, $v)=each($var); ) {
			if (is_array($v) && $k==="GLOBALS") continue;
			for ($i=0; $i<$level*3; $i++) $rez .= " ";
			$rez .= ($html ? '<b>'.HtmlSpecialChars($k).'</b>' : $k) .' => '. textDump($v, $level, $html);
		}
	} else
		$rez = ($html ? '"'.HtmlSpecialChars($var).'"' : $var)."\n";
	return $rez;
}
?>
