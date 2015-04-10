<?php 

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



$dbhost="localhost";
$dbname="";
$dbuser="";
$dbpwd="";
$dbprefix = ""; // useful if  you have to share the db...  


$cookiename="questionario" ;

$decimalSeparator=",";
$notAllowedCharInLogin=array('\'','|','\t','\n','(',')'); 
$notAllowedCharAtAll=array('\'','|','\t','\n','(',')'); 

$img_dir=".";

define ('__DEBUG__',1);

define('CSV_SEPARATORE',';');
define('CSV_DELIMITATORE_CAMPO','"');



define ('SESSION_TIMEOUT', 300);
define ('FALSE', 0);
define ('TRUE', 1);

////////////////////////////////////////////////////////////////////
// livello autenticazione
////////////////////////////////////////////////////////////////////
define ('AUT_LIV_FRUITORI_5'	, 0); 	/// user junior
define ('AUT_LIV_USER_4'	, 0);  	/// user senior
define ('AUT_LIV_ADMINISTRATOR_3', 0);	/// admin junior 
define ('AUT_LIV_MASTER_2'	,'0');	/// admin senior
define ('AUT_LIV_PROGRAMMATORI_1','0');	/// root / programmer/ architect :-)

////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////
// elenco simboli "grafici"

define ('__LISTE_CHECKED__','&#9989;'); 
define ('__LISTE_TOBEDELETED__','&#10060;');
define ('__LISTE_UPMARK__','&#65087;');
define ('__LISTE_UPUPMARK__','&#65085;');
define ('__LISTE_DOWNMARK__','&#65088;');
define ('__LISTE_DOWNDOWNMARK__','&#65086;');






?>
