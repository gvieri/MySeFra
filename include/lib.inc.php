<?php


/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/




	
	$easyList0= "#EEEEEE";
	$easyList1= "#DDDDDD";
	$tableBcg0= "#CCCCCC";
	$formBcg0 = "#EEEEEE";
	$msgTableBcg="cyan";
	$msgTableCol="black";
	$wrngTableBcg="orange";
	$wrngTableCol="black";
	$genfontface="courier,verdana,arial";
//	$genListLimit = 50;		// valore massimo di default di item presenti in liste
	$genListStart = 0;		// valore iniziale di default di item presneti in lista
	$listFontFace="courier,verdana,arial";
	$listHeaderFontFace="courier,verdana,arial";
	$listHeaderFontSize="";
	$listFontSize="";
	$genfontfaceSize="";
	$actionKeyFontFace="";
	$actionKeyFontSize="8";

	$headerBcgColor="";
	$headerFgColor="";
	$headerFontFace="";
	$footerBcgColor="";
	$footerFgColor="";
	$footerFontFace="";
	$headerFontSize="";
	$footerFontSize="";
	$searchNavBarBcgColor="";
	$searchNavBarFgColor="";
	$searchNavBarFontFace="";
	$searchNavBarFontSize="";
	$globalBorderTableBcgColor="";


	/////////////////////////////////////////////////////////////
/*
	$_internal_color_array=array(
		array(_COL_BLACK,"black"),
		array(_COL_BLACK,"black"),
		array(_COL_SILVER,"silver"),
		array(_COL_GRAY,"gray"),
		array(_COL_WHITE,"white"),
		array(_COL_MAROON,"maroon"),
		array(_COL_RED,"red"),
		array(_COL_PURPLE,"purple"),
		array(_COL_FUCHSIA,"fuchsia"),
		array(_COL_GREEN,"green"),
		array(_COL_OLIVE,"olive"),
		array(_COL_YELLOW,"yellow"),
		array(_COL_NAVY,"navy"),
		array(_COL_BLUE,"blue"),
		array(_COL_TEAL,"teal"),
		array(_COL_AQUA,"aqua"),
		array(_COL_ORANGE,"orange"),
		array(_COL_CYAN,"cyan"),
		array(_COL_GRAY_1,"#EEEEEE"),
		array(_COL_GRAY_2,"#DDDDDD"),
		array(_COL_GRAY_3,"#CCCCCC"),
array(_COL_LIGHTGREEN ,"LightGreen"),
array(_COL_LIGHTPINK ,"LightPink"),
array(_COL_LIGHTSALMON ,"LightSalmon"),
array(_COL_LIGHTSEAGREEN ,"LightSeaGreen"),
array(_COL_LIGHTSKYBLUE ,"LightSkyBlue"),
array(_COL_LIGHTSLATEGREY ,"LightSlateGray"),
array(_COL_LIGHTSTEELBLUE ,"LightSteelBlue"),
		array(_COL_YELLOW_1,"#F8B800"),
		array(_COL_YELLOW_GREEN_1,"#fffbf7"),
		array(_COL_YELLOW_GREEN_2,"#85917C"),
		array(_COL_YELLOW_GREEN_3,"#DBE3CD"),
		array(_COL_YELLOW_GREEN_4,"#55734E")
	);
*/

	//////////////////////////////////////////////////////////////
	$_internal_searchtype_array=array(
		array("semplice","semplice"),
		array("complessa","complessa")
	);
	//////////////////////////////////////////////////////////////


	$mslink= mysql_connect($dbhost, $dbuser, $dbpwd) or mysql_error();
	$msconn= mysql_select_db($dbname);

	if(!$mslink or (isset($msconn) and !$msconn)) {

		// insert here an explicative messages
		
		die("<b>Database connection failed!</b><br>Call admin, please.");
	}
	///////////////////////////////////////////////////////////////
	$sql="select * from genconf where conf_id=2";
	$res= mysql_query($sql);
	if($res) {
		$row=mysql_fetch_row($res);
		$sitename = $row[1];
		$easyList0= $row[2];
		$easyList1= $row[3];
		$tableBcg0= $row[4];
		$msgTableBcg=$row[5];
		$msgTableCol=$row[6];
		$wrngTableBcg=$row[7];
		$wrngTableCol=$row[8];
		$genfontface= $row[9];
		$genconfloglevel=$row[10];
		$genconfSearch= $row[11];

		$formBcg0 = "#EEEEEE";

		$listFontFace=$row[12];
		$listHeaderFontFace=$row[13];
		$listHeaderFontSize=$row[14];
		$listFontSize=$row[15];
		$actionKeyFontFace=$row[16];
		$actionKeyFontSize=$row[17];
		$genfontfaceSize=$row[18];

		$headerBcgColor=$row[19];
		$headerFgColor=$row[20];
		$headerFontFace=$row[21];
		$footerBcgColor=$row[22];
		$footerFgColor=$row[23];
		$footerFontFace=$row[24];
		$headerFontSize=$row[25];
		$footerFontSize=$row[26];
		$searchNavBarBcgColor=$row[27];
		$searchNavBarFgColor=$row[28];
		$searchNavBarFontFace=$row[29];
		$searchNavBarFontSize=$row[30];
		$globalBorderTableBcgColor=$row[31];
		$loglevel=$genconfloglevel;

	}
	//////////////////////////////////////////////////////////////


	function head_cont()
	{
		global $script_dir;
		global $root_dir;
		global $sitename;
		$ret = "<head>"	
		."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>"
		."<meta http-equiv=\"Expires\" content=\"-1\"/>"
		."<meta http-equiv=\"Pragma\" content=\"no-cache\"/>"
		."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> "
		."<link href=\"/bootstrap/css/bootstrap.css\" rel=\"stylesheet\">"
		."<script language=\"JavaScript\" type=\"text/javascript\">\n <!--\n"
		."function noenter(event,field) {\n"
		."if(window.event==13 || event.keyCode==13 ||window.event.keyCode==13 ||event.wich==13) {\n"
		." 	var i;\n"
	       	."	for (i = 0; i < field.form.elements.length; i++)\n"
		."		if (field == field.form.elements[i]) break;\n"
		."// 	i = (i + 1) % field.form.elements.length;\n"
		." 	i = (i + 1); // % field.form.elements.length;\n"
		." 	field.form.elements[i].focus();\n"
		."	return false;\n"
		." }\n"
		."return true;\n"
		." }"
		."\n"
		."function changeBcg(field,color) {\n"
		."	field.style.backgroundColor=color;\n"
		."	return true;\n"
		."}\n"
		."//-->"
		."</script>"
		."<script language=\"JavaScript\" type=\"text/javascript\">"
		."var domTT_classPrefix = 'domTTOverlib';"
		."</script>"
		."<TITLE>$sitename</TITLE>\n</head>\n";
		return ($ret);
	}
	
	function pHeader()
	{
		global $sitename;
		global $genfontface;
		global $headerFgColor,$headerFontFace,$headerBcgColor,$headerFontSize;


		$ret= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/html4/loose.dtd\">"
		."<HTML>".head_cont()."<BODY>";
		$ret .=
		"<table width='100%' align='center' bgcolor='$headerBcgColor'>"
		. "<tr>\n"
		. "<td align='center'>\n"
		. "<font face='$headerFontFace' color='$headerFgColor' size='$headerFontSize'><b>$sitename</b></font>\n"
		. "</td>\n"
		. "</tr>\n"
		. "</table>\n";

		return ($ret);
	}


	function pFooter()
	{
		global $genfontface;
		global $version_number;
		global $footerFgColor,$footerFontFace,$footerBcgColor,$footerFontSize;

		return ($ret);
		
	}

	function messageTable($message) {
		global $msgTableBcg,$msgTableCol;
		global $genfontface;
		global $globalBorderTableBcgColor;
		$ret= "<table width='100%' align='center' bgcolor='$globalBorderTableBcgColor' border='1'>"
		. "<tr>\n"
		. "<td align='center'>\n"
		. "<font face='$genfontface' color='white' size='4'><b></b></font>\n"
		. "</td>\n"
		. "</tr>\n"
		."<tr><td align='center' bgcolor='$msgTableBcg'><font color='$msgTableCol' size='4'><b>$message</b></font></td></tr>"
		. "</table>\n";
		return ($ret);
	}

	function returngpar($stringname)	// return global parameter
        {
		/*
	        if (isset($_GET[$stringname])) {
			return $_GET[$stringname];
		}
	        elseif (isset($_POST[$stringname])) {
			return $_POST[$stringname];
		}
		return ;
		corretto per problema sulle quotes
		*/
		$return_v="";
	        if (isset($_GET[$stringname])) {
			$return_v= $_GET[$stringname];
		}
	        elseif (isset($_POST[$stringname])) {
			$return_v= $_POST[$stringname];
		}
		if(!get_magic_quotes_gpc()) {
			$return_v=addslashes($return_v);
		}
		return $return_v;
	}

	function pAddNewUser($scriptname) {
		global $formmethod;

		$mess="<table width='80%' border='0' cellpadding='0' cellspacing='0' align='center'><FORM name='form' action='$scriptname' method='$formmethod'>"
		."<tr><td>user</td><td> <input type='text' name='username' size='10' /></td></tr>"
		."<tr><td>password</td><td><input type='password' name='password' size='10' /></td></tr>"
		."<tr><td colspan='2' align='center'><input type='submit' name='action' value='"._ADDNEWUSER."' /></td></tr>"
		."</form></table>";
		
		return ($mess);

	}



	function warningTable($msg) {
		global $wrngTableBcg,$wrngTableCol;
		global $globalBorderTableBcgColor;
		global $genfontface;
		$ret= "<table width='100%' align='center' bgcolor='$globalBorderTableBcgColor' border='1'>"
		. "<tr>\n"
		. "<td align='center'>\n"
		. "<font face='$genfontface' color='white' size='4'><b></b></font>\n"
		. "</td>\n"
		. "</tr>\n"
		."<tr><td align='center' bgcolor='$wrngTableBcg'><font color='$wrngTableCol' size='4'><b>$msg</b></font></td></tr>"
		. "</table>\n";
		return ($ret);

		
	}

	function navigationTable($msg) {
		global $wrngTableBcg,$wrngTableCol;
		global $globalBorderTableBcgColor;
		global $genfontface;
		global $searchNavBarBcgColor,$searchNavBarFgColor,$searchNavBarFontFace,$searchNavBarFontSize;
		$ret= "<!-- start navigationTable-->\n"
		."<table width='100%' align='center' bgcolor='$globalBorderTableBcgColor' border='0'>"
		."<tr><td align='center' bgcolor='$searchNavBarBcgColor'><font color='$searchNavBarFgColor' size='$searchNavBarFontSize'>$msg</font></td></tr>"
		. "</table>\n"
		."<!-- end navigationTable-->\n";
		return ($ret);

		
	}


	

	function stringTipoLavorazioni($tipo) {
		global $tipo_lavorazioni;
		if($tipo>count($tipo_lavorazioni)) {
			$res = "sconosciuto";
		} else {
			$res = $tipo_lavorazioni[$tipo];
		}
				
		return($res);
	}

	function optMenuTipoLavorazioni($nome,$tipo) {
		// $tipo e ' il valor di default
		global $tipo_lavorazioni;
		$count=count($tipo_lavorazioni);
		$res="<select name='$nome' >";
	for($i=1;$i<$count+1;$i++) {
		$res.="<option value='$i'".($i==$tipo ?" selected ":"").">".$tipo_lavorazioni[$i]."</option>";
	}
	$res .="</select>";
	return ($res);
	}

	function stringTipoSupporto($tipo) {
		global $_tipo_supporto;
		if($tipo>count($_tipo_supporto)) {
			$res = "sconosciuto";
		} else {
			$res = $_tipo_supporto[$tipo];
		}
				
		return($res);
	}

	function optMenuTipoSupporto($nome,$tipo) {
		// $tipo e ' il valor di default
		global $_tipo_supporto;
		$count=count($_tipo_supporto);
		$res="<select name='$nome' >";
	for($i=1;$i<$count+1;$i++) {
		$res.="<option value='$i'".($i==$tipo ?" selected ":"").">".$_tipo_supporto[$i]."</option>";
	}
	$res .="</select>";
	return ($res);
	}


function optMenuStringValue($optStrValArray,$nome,$value) {
	$count=count($optStrValArray);
	$res="<select name='$nome' >";
	for($i=0;$i<$count;$i++) {
		 $res.="<option value='".$optStrValArray[$i][1]."'".($optStrValArray[$i][1]==$value ?" selected ":"").">".$optStrValArray[$i][0]."</option>";
	}
	$res .="</select>";
	return ($res); 
	}

	function optColorMenuChoose($nome,$value) {
		global $_internal_color_array;
		return optMenuStringValue( $_internal_color_array,$nome,$value) ;
	}
	function optSearchTypeMenuChoose($nome,$value) {
		global $_internal_searchtype_array;
		return optMenuStringValue( $_internal_searchtype_array,$nome,$value) ;
	}
	
	
//function actionKey($script,$key,$string,$dest) {
function actionKey($script,$key,$string,$dest,$fontsize="") {
	global $genfontface;
	global $actionKeyFontFace,$actionKeyFontSize;
	if ($fontsize<8) {
		
		$fontsize=$actionKeyFontSize;
		}

	$len=strlen($key);
	$keylen=$fontsize+$fontsize*$len;
	$res="<input style='height:".($fontsize*2)." ; width: $keylen"."px; font-size: $fontsize ; font-face: $actionKeyFontFace' type='submit' value='$key' title='"
	.$string."' onClick=\"window.open('$script', '$dest')\" />\n";

	return ($res);
}

function actionKeyConfirm($script,$key,$string,$dest,$confirmMsg="") {
	// fare check per caratteri strani in confirmMsg
	global $genfontface;
	global $actionKeyFontFace,$actionKeyFontSize;
	$confirmMsg=ereg_replace("'","\&#39;",$confirmMsg);
	$fontsize=$actionKeyFontSize;
	$len=strlen($key);
	$keylen=$fontsize+$fontsize*$len;
	$res="<input style='height: ".($fontsize*2)."; width: $keylen; font-size: $actionKeyFontSize ; font-face: $genfontface' type='submit' value='$key' title='"
	.$string."' onClick=\" if (confirm('Sei sicuro $confirmMsg ')) window.open('$script', '$dest')\" />\n";

return ($res);
}





function orderedHeader($header,$script,$commandline)
{
	global $images_dir;
	global $setfont;
	$res= "<th align='left' valign='top'>";
	// cerca ogni argomento $script
	$commandline_array=split('&',$commandline);

	// cerca argomento orderby
	for ($i=0;$i<count($commandline_array);$i++) {
		
		if (substr($commandline_array[$i],0,12)=="searchstring") {
			if (get_magic_quotes_gpc()) {
				$commandline_array[$i]=stripslashes($commandline_array[$i]);
				} 
			}
		}
	$commandline=implode('&amp;',$commandline_array);
	
	$res.="<a href=\"$script?$commandline\">"
	."<img src='$images_dir/DownArrow.gif' alt='"._SORT_BY."$header' border='0' align='left'></a>";
	// cerca ogni argomento $script

	// cerca argomento orderby
	for ($i=0;$i<count($commandline_array);$i++) {
		if (substr($commandline_array[$i],0,8)=="orderby=") {
			$commandline_array[$i].="%20desc";
			}
	}
	// modifica argomento orderby
	$commandline=implode('&',$commandline_array);
	// ricomponi $commandline
	$res.=	""
	."<a href=\"$script?$commandline\">"
	."<img src='$images_dir/UpArrow.gif' alt='"._SORT_BY."$header' border='0' align='left'></a>"
	."$setfont"."$header"
        ."</th>\n";
	return($res);
}




function euDate2MysqlDate($date) {
	$res="";
	if(strpos($date,"/")!=FALSE) {
		list($day,$month,$year)=split("/",$date);
		$res=$year."-".$month."-".$day;
	}

	return($res);
}

function mysqlDate2Eu($date) {
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);

	return($day."/".$month."/".$year);
}

function numberOfRecordForQuery($table,$whereClause=""){
	$query="SELECT count(*) from $table $whereClause";
	$res= mysql_query($query) or die ("cannot execute $query");		
	$t= mysql_fetch_row($res);
	return($t[0]);	
}


function fastBrowse($script,$start,$limit,$totalItem,$order="",$searchstring="",$arrayOfParameters=array()) {
	global $images_dir;
	global $searchNavBarBcgColor,$searchNavBarFgColor,$searchNavBarFontFace,$searchNavBarFontSize;
	global $dest_frame;

	$res1="";
	if (get_magic_quotes_gpc()) {
		$searchstring=stripslashes($searchstring);
	}
	if ($limit > $totalItem) {$limit=$totalItem;}
		$next=$start+$limit;
		$last=$start-$limit;
		$end=$totalItem-$limit;
		if ($end < 0) {$end=0;}
		if ($last <0) {$last=0;}
		if ($next >= $totalItem) {$next=$totalItem-$limit;}
		if ($end < 0) {$end=0;}

	foreach ($arrayOfParameters as $nameOfParameter=>$valueOfParameter) {
		if($nameOfParameter=="searchstring") {
			if (get_magic_quotes_gpc()) {
				$valueOfParameter=stripslashes($valueOfParameter);
			}

		}
		$res1 .="&$nameOfParameter=$valueOfParameter";
	}


		$res=""	
		."\t\t\t<input type='image' align='left' hspace='0' border='0' src='$images_dir/databegin.gif' title='"
		._D_BEGIN."' onClick=\"window.open('$script&amp;start=0&amp;limit=$limit&amp;orderby=$order&amp;searchstring=$searchstring$res1','$dest_frame')\" />\n"
		."\t\t\t<img src='$images_dir/blank.gif' width='5' height='20' border='0' hspace= '0' align='left'>\n"
		."\t\t\t<input type='image' align='left' hspace='0' border='0' src='$images_dir/databack.gif' title='"
		._D_BACK."' onClick=\"window.open('$script&amp;start=$last&amp;limit=$limit&amp;orderby=$order&amp;searchstring=$searchstring$res1','$dest_frame')\" />\n"
		."\t\t\t<img src='$images_dir/blank.gif' width='20' height='20' border='0' hspace= '0' align='left'>\n"
		."\t\t\t<input type='image' align='left' hspace='0' border='0' src='$images_dir/dataforward.gif' title='"
		._D_FORWARD."' onClick=\"window.open('$script&amp;start=$next&amp;limit=$limit&amp;orderby=$order&amp;searchstring=$searchstring$res1','$dest_frame')\"/>\n"
		."\t\t\t<img src='$images_dir/blank.gif' width='5' height='20' border='0' hspace= '0' align='left'>\n"
		."\t\t\t<input type='image' align='left' hspace='0' border='0' src='$images_dir/dataend.gif' title='"
		._D_END."' onClick=\"window.open('$script&amp;start=$end&amp;limit=$limit&amp;orderby=$order&amp;searchstring=$searchstring$res1','$dest_frame')\" />\n"
		."\t\t\t<img src='$images_dir/seperator.gif' border='0' hspace='0' align='left'>\n"
		."";
		return($res);
		
	}

function setLimit($script,$start,$limit,$arrayOfParameters) {
		global $genfontface;
		global $images_dir;
		global $slstyle,$btstyle;
	global $searchNavBarBcgColor,$searchNavBarFgColor,$searchNavBarFontFace,$searchNavBarFontSize;

	$ret="\n<!-- setLimit -->\n"
	."<form action='$script'>\n"
	."\t\t<font size='$searchNavBarFontSize' face='$searchNavBarFontFace'>\n"
	."\t\t\t"._ITEM_FOR_PAGE."<input type='text' $slstyle size='4' value='$limit' name='limit'>\n"
	."\t\t\t"._START_ITEM."<input type='text' $slstyle size='4' value='$start' name='start'>\n"
	."\t\t\t<input type='submit' value='"._ITEM_SHOW."' $btstyle>\n"
	."\t\t";

	foreach ($arrayOfParameters as $nameOfParameter=>$valueOfParameter) {
		if($nameOfParameter=="searchstring") {
			$ret.="<!-- test slashes -->";
			if (get_magic_quotes_gpc()) {
				$valueOfParameter=stripslashes($valueOfParameter);
			}

		}
		$ret .="\t\t<input type='hidden' name='$nameOfParameter' value=\"$valueOfParameter\">\n";
	}	


	$ret.="\t\t</form>\n<!-- end setLimit-->\n";

	return ($ret);
}

function menuBarItem($image,$string,$altString,$script,$param,$destWindow)
{
	$ret="<input type='image' src='$image' title='"
	        .$altString."' border='0' align='left' hspace='0' onClick=\"window.open('$script?$param', '$destWindow')\">\n";
	return $ret;
}

function blankItem() {
	global $images_dir;
	return ("<img src='$images_dir/blank.gif' width='11' border='0' hspace='0' align='left'>\n");
}

function separatorItem() {
	global $images_dir;
	return ("<img src='$images_dir/seperator.gif' border='0' hspace='0' align='left'>\n");
}

function menuBar () {
// every item contains images , string, alt string, scripts, parameteters (as string) window destination (top,blank etc)


	
}
	
$userTypeArray = array('_U_S_DISABLED','_U_S_ENABLED','_U_S_RESTRICTED');


	function stringUserType($tipo) {
		global $userTypeArray;
		if($tipo>count($userTypeArray)) {
			$res = "sconosciuto";
		} else {
			$res = $userTypeArray[$tipo];
		}
				
		return($res);
	}

	function optMenuuserTypeArray($nome,$tipo) {
		// $tipo e ' il valor di default
		global $userTypeArray;
		$count=count($userTypeArray);
		$res="<select name='$nome' >";
	for($i=1;$i<$count+1;$i++) {
		$res.="<option value='$i'".($i==$tipo ?" selected ":"").">".$userTypeArray[$i]."</option>";
	}
	$res .="</select>";
	return ($res);
	}


	function textFieldInputTableRow($intestazione,$nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= "<tr>\n"
		."\t<td align='right' width='20%'>$setfont<b>".$intestazione.":</b></td>\n"
		."\t<td bgcolor='$formBcg0'>$setfont<input onfocus=\"return changeBcg(this,'aliceBlue')\" onblur=\"return changeBcg(this,'white')\"onkeypress=\"return noenter(event,this)\" type='text' $slstyle size='30' name='$nome' value=\"$defval\"></td>\n"
		."</tr>\n";

		return $res;
	}

        function textFieldInput($nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= ""
		."$setfont<input onfocus=\"return changeBcg(this,'aliceBlue')\" onblur=\"return changeBcg(this,'white')\"onkeypress=\"return noenter(event,this)\" type='text' $slstyle size='30' name='$nome' value=\"$defval\">"
		."";
		return $res;
	}

	function passwordFieldInputTableRow($intestazione,$nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= "<tr>\n"
		."\t<td align='right' width='20%'>$setfont<b>".$intestazione.":</b></td>\n"
		."\t<td bgcolor='$formBcg0'>$setfont<input onfocus=\"return changeBcg(this,'aliceBlue')\" onblur=\"return changeBcg(this,'white')\"onkeypress=\"return noenter(event,this)\" type='password' $slstyle size='30' name='$nome' value=\"$defval\"></td>\n"
		."</tr>\n";

		return $res;
	}


	function fileFieldInputTableRow($intestazione,$nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= "<tr>\n"
		."\t<td align='right' width='20%'>$setfont<b>".$intestazione.":</b></td>\n"
		."\t<td bgcolor='$formBcg0'>$setfont<input type='file' $slstyle size='30' name='$nome' value='$defval'></td>\n"
		."</tr>\n";

		return $res;
	}



	
	function labelFieldInputTableRow($intestazione,$nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= "<tr>\n"
		."\t<td align='right' width='20%'>$setfont<b>".$intestazione.":</b></td>\n"
		."\t<td bgcolor='$formBcg0'>$setfont $defval</td>\n"
		."</tr>\n";

		return $res;
	}


        function labelField($nome,$defval)
	{
		global $setfont,$formBcg0,$slstyle;
		$res= ""
		."\t<span bgcolor='$formBcg0'>$setfont $defval</span>\n"
		."";

		return $res;
	}




	function fieldInputTableRow($intestazione,$fieldselector) 
	{
		global $setfont,$formBcg0,$slstyle;
		$res= "<tr>\n"
		."\t<td align='right' width='20%'>$setfont<b>".$intestazione.":</b></td>\n"
		."\t<td bgcolor='$formBcg0'>$setfont $fieldselector </td>\n"
		."</tr>\n";

		return $res;
	}
function optMenuGenericSNNoBlank($name,$value) {
	$arrayin= array(
		array('S','s'),
		array('N','n')
	);
	
	$res=optMenuStringValue($arrayin,$name,$value);
	return($res);
	}
function printDoubleAsEuro($value) {
		$res= sprintf("%.2f",$value);
		return (strtr($res,".",","));

		}
function euro2double($value) {
		$res=strtr($value,",",".");
		settype($res,"double");
		return($res);
	}

function actionKeyGr($script,$image,$string,$dest,$fontsize=8) {
	global $genfontface;
	global $images_dir;
	$len=strlen($key);
	$keylen=$fontsize+$fontsize*$len;
	$res="<input style='height:".($fontsize*2)." ; width: $keylen"."px; font-size: $fontsize ; font-face: $genfontface' type='image' src='$image' value='$key' title='"
	.$string."' onClick=\"window.open('$script', '$dest')\" />\n";
	return ($res);
}




/////////////////////////////////////////////////////////////////////////
function tableHeader() {
	// questa funzione riceve un numero variabile di parametri ciascuno dei quali e' un array associativo 
	//text=scritta 
	//fontsize dimensione dei font
	// background colore di sfondo
	// foreground colore
	// il primo item contiene i valori di default

$numargs = func_num_args();
$tableHeaderDefault=func_get_arg(0);
$tableHeaderFontsize="{$tableHeaderDefault['fontsize']}";
$tableHeaderBgcolor="{$tableHeaderDefault['bgcolor']}";
$tableHeaderFgcolor="{$tableHeaderDefault['fgcolor']}";
$res="<tr bgcolor='$tableHeaderBgcolor'>";
	for($i=1;$i<$numargs;$i++) { 
		$item=func_get_arg($i);
		$text="{$item['text']}";
		$bgcolor="{$item['bgcolor']}";
		$fgcolor="{$item['fgcolor']}";
		if($bgcolor=="") {$bgcolor=$tableHeaderBgcolor;}
		
			
		$res.="<td bgcolor='$bgcolor'>"
		."$text</td>";
	
	}
	$res.="</tr>";	
	return($res);

}

function tableRow() {
	// questa funzione riceve un numero variabile di parametri ciascuno dei quali e' un array associativo 
	//text=scritta 
	//fontsize dimensione dei font
	// background colore di sfondo
	// foreground colore
	// il primo item contiene i valori di default

$numargs = func_num_args();
$Default=func_get_arg(0);
$Fontsize="{$Default['fontsize']}";
$Bgcolor="{$Default['bgcolor']}";
$Fgcolor="{$Default['fgcolor']}";
$res="<tr bgcolor='$Bgcolor'>";
	for($i=1;$i<$numargs;$i++) {
		$item=func_get_arg($i);

		$text="{$item['text']}";
		$bgcolor="{$item['bgcolor']}";
		$fgcolor="{$item['fgcolor']}";

		if($bgcolor=="") {$bgcolor=$Bgcolor;}
			
		$res.="<td bgcolor='$bgcolor'>"
		."$text</td>";
	

	
	}
	$res.="</tr>";	
	return($res);


}


/////////////////////////////////////////////////////////////////////////////

function app_bar() {
// il primo array contiene parametri della barra...
// questa funzione riceve in input un numero variabile di array associativi...
// oggni array associativo contiene informazioni su un item del menu:
// text == scritta sul bottone
// textlong == alt tag
// script == script da attivare e relativi parametri
// dest == "_top" "_blank" etc
// fontsize == 8 /16  deimensione dei font
// grayed o meno
// icon == icona
	global $images_dir;

$numargs = func_num_args();
//      echo "<HR>$numargs<HR>";
$barpar=func_get_arg(0);
if (isset($barpar['align'])) {$bar_align="{$barpar['align']}";} else {$bar_align="";}
if (isset($barpar['border'])) {$bar_border="{$barpar['border']}";} else {$bar_border="";}
if (isset($barpar['solid'])) {$bar_solid="{$barpar['solid']}";} else {$bar_solid="";}
if (isset($barpar['bgcolor'])) {$bar_bgcolor="{$barpar['bgcolor']}";} else {$bar_bgcolor="";}

	$res="<table width='100%' align='$bar_align' style='border: 1px solid $bar_solid' cellpadding='1' cellspacing='0'>";
	$res.="<tr bgcolor='$bar_bgcolor'>";
	for ($i=1;$i<$numargs;$i++) {
		$item=func_get_arg($i);
		$flag=0;
		if (isset($item['image']) && "{$item['image']}"!="") {
			$res.="<TD>";
			$res.= actionKeyGr("{$item['script']}","{$item['image']}","{$item['textlong']}","{$item['dest']}","{$item['fontsize']}");
			$flag=1;
		}
		if (isset($item['text']) && "{$item['text']}"!="") {
			if($flag==0)
			$res.="<TD>";
			$res.= actionKey("{$item['script']}","{$item['text']}","{$item['textlong']}","{$item['dest']}","{$item['fontsize']}");
			$res.="</TD>";
		}
		$res.="\t\t\t<TD><img src='$images_dir/seperator.gif' border='0' hspace='0' align ='left'>\n</TD>";
	}

	$res.="</TR></TABLE>";
	return ($res);
}

///////////////////////////

function optMenuGenericNoBlank($arrayin,$name,$value) {
	$count=count($arrayin);
	$res="";
	$res.="<select name='$name' >";
	$selected=0;
	for($i=0;$i<=$count;$i++) { // se <= mette un blank item...
		if(!empty($arrayin[$i]) ) {
			$res.="<option value='$i'";
			if($i==$value) {
				$selected=1;
				$res .=" selected ";
			}
			$res.=">".$arrayin[$i]."</option>";
		}
	}
	if($selected==0) $res.="<option value='$count' ></option>";
	$res .="</select>";
	return ($res);
}


?>
