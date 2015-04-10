<?PHP

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



require_once($_SERVER['DOCUMENT_ROOT']."/include/conf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/lib.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/auth.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/authenticatedPage.class.php");

class navMenuFromDb { 

	private $areaTable;
	private $ruoliTable;
	private $auth;

	function __construct($pippo, $areaTable="servizio_areas",$ruoliTable="servizio_ruoli_url") {
		$this->areaTable	=	$areaTable;
		$this->ruoliTable	=	$ruoliTable;		
		$this->auth		=	$pippo;	
	}

	function drawMenu() { 
		global $mysqli;
//		$query="select * from servizio_areas order by id"; 
		$query="select * from ".$this->areaTable." where not (isEnabled=0) order by id"; 
		if(!$r = $mysqli->query($query)){
			    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$col=$r->num_rows;
//		$urole=$auth->getRole();
		$urole=$this->auth->getRole();
//		echo "<thead><tr>";
// start of navbar 
		echo '<div class="navbar">'."\n";
		echo '<div class="navbar-inner">'."\n".'<div class="container">'."\n";

		echo '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">'."\n";
		echo '<span class="icon-bar"></span>'."\n";
		echo '        <span class="icon-bar"></span>'."\n";
		echo '        <span class="icon-bar"></span>'."\n";
		echo '        <span class="icon-bar"></span>'."\n";
		echo '</a>'."\n";
echo '<div class="nav-collapse">'."\n";
echo	'<ul class="nav">'."\n";

//echo '<li class="active"><a href="#">Home</a></li>'."\n";
// end of start 
		$colIndex=0;
		$colArray=array();
		$colMax=array();
		while ($row=$r->fetch_assoc()) {
//		 $row=$r->fetch_assoc(); {
echo '            <li class="dropdown">'."\n";
//			echo '<li class="active"><a href="#">'.$row['area'].'</a></li>'."\n";		
			echo ' <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$row['area'].' <b class="caret"></b></a>'."\n";

			$areaSearched=$row['area'];	
//			$query="select * from servizio_ruoli_url where area ='$areaSearched' and role <= '$urole' order by id";
			$query="select * from ".$this->ruoliTable." where area ='$areaSearched' and role <= '$urole' and not (isEnabled=0) order by id";
			if(!$r1 = $mysqli->query($query)){
				    die('There was an error running the query [' . $mysqli->error . ']');
			}

/// now start the drop down ... 
			echo '<ul class="dropdown-menu">'."\n";

			while($row1=$r1->fetch_assoc()) {
				echo '<li><a href="'.$row1['url'].'">'.$row1['descrizione'].'</a></li>'."\n";
			}
			echo '</ul>'."\n";
/// end of drop down and of the menu dropdown... 
			echo '</li> '."\n";		
		}


//		echo "</table>";
		echo '</ul>'."\n";
		echo '</div>'."\n";
		echo '</div>'."\n";
//		echo '<script type="text/javascript">$(\'.dropdown-toggle\').dropdown()</script>';
		echo "\n<!--  separazione -->\n";
	}
}



?>

