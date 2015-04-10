<?PHP
/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



require_once($_SERVER['DOCUMENT_ROOT']."/include/conf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/lib.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/auth.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/messages.IT.php");


class exportCsv  extends Exception {



	public $numbersOfUploadedFiles;
	public $namesOfUploadedFiles;
	public $tableToBeExported; 
	public $fieldToBeExported; 
	public $fieldToBeExportedString; 
	public $fileName;

	public function __construct ( $tableToBeExported, $fieldToBeExported=array(), $nomeFile="")  {
		$this->tableToBeExported	= $tableToBeExported;
		$this->fieldToBeExported	= $fieldToBeExported;
		
		if(count($fieldToBeExported)==0) {
			$this->fieldToBeExportedString=" * ";
		} else { 

			$this->fieldToBeExportedString=" ";
			foreach ($this->fieldToBeExported as $field)  {
				$this->fieldToBeExportedString.=$field.",";
			}
			$this->fieldToBeExportedString=substr($this->fieldToBeExportedString,0,-1);
		}
		if($nomeFile=="") { 
			$this->fileName=$this->tableToBeExported.".csv";
		} else { 
			$this->fileName=$nomeFile;
		}

	} 	


/////////////////////////////////////////////////////////
			
	public function dump() {
		global $mysqli;
		$dumpTxt=$this->fieldToBeExportedString."\n";
		$query="select ".$this->fieldToBeExportedString." from ".$this->tableToBeExported."";
		
		$r=$mysqli->query($query) or die($mysqli->error);
		while($row = $r->fetch_row()){
			$rowTxt="";
			foreach ($row as $rowField) {
				$rowTxt.=$rowField.CSV_SEPARATORE;
			}
			$rowTxt=substr( $rowTxt, 0 , -1);
			$rowTxt.="\n";
			$dumpTxt.=$rowTxt;
		}			
		return ( $dumpTxt ); 	
	}


	public function dumpFile() { 
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$this->fileName.'"');
		echo $this->dump(); 


	}
}
?>
