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

class frontMenuFromDb { 

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
		$query="select * from ".$this->areaTable." order by id"; 
		if(!$r = $mysqli->query($query)){
			    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$col=$r->num_rows;
		$urole=$this->auth->getRole();
		echo "<thead><tr>";
		$colIndex=0;
		$colArray=array();
		$colMax=array();
		while ($row=$r->fetch_assoc()) {
			echo "<TD>".$row['area']."</TD>";		
			$areaSearched=$row['area'];	
//			$query="select * from servizio_ruoli_url where area ='$areaSearched' and role <= '$urole' order by id";
			$query="select * from ".$this->ruoliTable." where area ='$areaSearched' and role <= '$urole' order by id";
			if(!$r1 = $mysqli->query($query)){
				    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$rowIndex=0;
			$colMax [ $colIndex ] = $rowIndex;
			while($row1=$r1->fetch_assoc()) {
				$colArray[$colIndex][$rowIndex]['url']=$row1['url'];
				$colArray[$colIndex][$rowIndex]['descrizione']=$row1['descrizione'];
				$rowIndex++;
				$colMax [ $colIndex ] = $rowIndex;
			}
			$colIndex++;
		
		}
		$max=max($colMax);
		echo "</tr></thead>\n";
		for ($righe=0 ; $righe < $max ; $righe++) { 
		
			echo "<tr>";
			for ($i=0 ; $i < $colIndex; $i++) {
				$url = @$colArray[$i][$righe]['url'];
				$descrizione = @$colArray[$i][$righe]['descrizione'];
				// the @ in the above lines is an abominy ... i have to use isset instead of @ ... but i am so lazy... 

				$lista0= "<A href=\"$url\">$descrizione</a>";
				echo "<td>".$lista0."</td>";
			}

			echo "</tr>";
		}


		echo "</table>";

	}
}

ob_flush() ;


?>

