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


class multipleUpload  extends Exception {


	const maxFileSize=3000000;
	public $extensionsKnown	  = array(
	'text/html',
         'text/plain',
         'text/css',
         'image/gif',
         'image/x-png',
         'image/jpeg',
         'image/tiff',
         'image/x-ms-bmp',
         'audio/x-wav',
         'application/x-pn-realaudio',
         'video/mpeg',
         'video/quicktime',
         'video/x-msvideo',
         'application/postscript',
         'application/rtf',
         'application/pdf',
         'application/x-pdf',
         'application/x-gtar',
         'application/x-tar',
         'application/zip',
         'application/x-zip-compressed',
         'application/mac-binhex40',
         'application/x-stuffit',
         'application/octet-stream',
         'text/javascript',
         'application/x-javascript',
         'application/x-sh',
         'application/x-csh',
         'application/x-perl',
         'application/x-tcl',
         'application/vnd.ms-powerpoint',
         'application/ms-powerpoint',
         'application/vnd.ms-excel',
         'application/msword',
         'video/avi',
         'java/*',
         'application/java',
         'image/x-icon',
         'image/bmp',
         'image/pjpeg',
         'application/x-bittorrent'
);
	public $extensionsAllowed = array ( "image/gif", "image/png", "image/jpeg", "image/pjpeg", "text/csv","text/plain", "application/octet-stream", "application/vnd.ms-excel");

	public $numbersOfUploadedFiles;
	public $namesOfUploadedFiles;

		public function __construct ( $namesOfUploadedFiles=array(), $numbersOfUpload=1, $allowdExtension =null )  {
			if($allowdExtension!=null)  {
				$this->extensionsAllowed	= $allowdExtension;
//				echo "settata extensionsAllowed";
			}
			
			$this->namesOfUploadedFiles	= $namesOfUploadedFiles;
			$this->numbersOfUploadedFiles	= $numbersOfUpload;
		} 	


		public function getInfoOnUploadedFiles() {
			global $_FILES ;
			$cont=0;
			$message="";
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			    	$file_name = $_FILES['files']['name'][$key];
			    	$file_size = $_FILES['files']['size'][$key];
			    	$file_tmp  = $_FILES['files']['tmp_name'][$key];
			    	$file_type = $_FILES['files']['type'][$key];
//				echo "<hr>key = $key <hr>";
				$message.="il file ".$file_name." di dimensioni $file_size &egrave; stato  caricato. Nome file temp=$file_tmp tipo file=$file_type\n";
				$cont++;
			}	
			$message.="totale file caricati = <$cont>";
			return ($message);
		}


		public function areAcceptable() {
			global $_FILES;
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				if ($_FILES["files"]["size"][$key] >self::maxFileSize ) {
////				throw new Exception ("file di dimensioni eccessive", 1 );
					error_log ("areAcceptable(): Il file ".$_FILES["files"]['name'][$key]."e' troppo grande,");
//					echo ("<hr>"."areAcceptable(): Il file ".$_FILES["files"]['name'][$key]."e' troppo grande,"."<HR>");
					return (FALSE);
				}
/*
				$filetype= $_FILES["files"]["type"][$key];	
				echo "<hr> filetype= $filetype <hr>";
				foreach ($this->extensionsAllowed  as $ext ) {
					
					if (strcmp($filetype, $ext)!=0) {
						echo "<hr> file +".$key."+ found type +".$_FILES["file"]["type"][$key]."+<hr>";
						
						return (FALSE) ; 
					}
				}
*/
			}

			return (TRUE);
		}
/////////////////////////////////////////////////////////
		public function areFileNamesPresent() {
			$dummy=array();
//			echo "<HR>called areFileNamesPresent() <BR>";
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			    	$file_name = $_FILES['files']['name'][$key];
				array_push($dummy,$file_name);
			}
/*
			echo "<hr>";
			print_r($dummy);
			echo "<hr>";
*/

			foreach($this->namesOfUploadedFiles as $name) { 
				if(in_array($name, $dummy)==FALSE) {
					error_log("areFileNamesPresent(): il file $name non e' in: ".implode(",",$dummy).".");
/*
					echo "<HR>";
					echo("areFileNamesPresent(): il file $name non e' in: ".implode(",",$dummy).".");
					echo "<HR>";
*/				
					return(FALSE); 
				}
			}
			return (TRUE);

		}


		public function getTmpFileNameOfAGivenFile($filename) {
			global $_FILES;
//			echo "called getTmpFileNameOfAGivenFile with arg $filename <br>";
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			    	$file_name = $_FILES['files']['name'][$key];
				if (strncmp($filename,$file_name, strlen($filename))==0  ) { 
					return($_FILES['files']['tmp_name'][$key]);
				}
			}
			return (null); 
		}
		

		public static function isAcceptable($nomefile) {
			global $_FILES;
					
			if ($_FILES["file"]["size"][$nomefile] >self::maxFileSize ) {
//				throw new Exception ("file di dimensioni eccessive", 1 );
				return (1);
			}
			foreach ($this->extensionsAllowed  as $ext ) {
				if ($_FILES["file"]["type"][$nomefile]== $ext) {
					return (0) ; 
				}
			}
			return (1);
		}


		public function areErrorsInFiles() {
			$retMsg=false;
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				if($_FILES["files"]["error"][$key] > 0 ){
					$retMsg=true;
				}
			}	
			return ($retMsg);
		}
		

		public function getErrorsInFiles() {
			$retMsg="";
			foreach($_FILES['files']['name'] as $key => $name ){
				if($_FILES["files"]["error"][$key] > 0 ) {
					$dummy=sprintf("Il file %s ha errore %s.  ",$name, $_FILES["files"]["error"][$key]);
					$retMsg.=$dummy;
				}
			}
			return ($retMsg);
		}	


		public function isUploaded($nomefile)  {
			global $_FILES;
			if ($_FILES["file"]["size"][$nomefile] > 0 ) {
				return(0) ;
			}
			return(1);
		}
			
		public function exposeLoadedData ()  {
			global $_FILES ;
			$ret =   "Upload: " . $_FILES["file"]["name"] . "<br />"
                                ."Type: " . $_FILES["file"]["type"] . "<br />"
                                ."Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />"                          
                                ."Stored in: " . $_FILES["file"]["tmp_name"]
                                ."";
			return ($ret);
		}


		public function uploadForm () {
			global $_SERVER; 
//////////////////////////////////////////////////////////////
// vedi http://markusslima.github.io/bootstrap-filestyle/
//////////////////////////////////////////////////////////////
			$ret =  ""
				."<form action=\"".$_SERVER['PHP_SELF']."?action=upload\" method=\"post\" "
////				."<form class=\"form-inlinee\" action=\"".$_SERVER['PHP_SELF']."?action=upload\" method=\"post\" "
				."enctype=\"multipart/form-data\">"
				."<div class=\"container\">"
				."<label >Selezionate i file da caricare:</label>";
			for($i=0; $i<$this->numbersOfUploadedFiles; $i++) {
				$ret.="<div class=\"row\">";
				$ret.="<div class=\"fileUpload btn btn-primary input-xlarge\">";
				$ret.="<input type=\"file\" name=\"files[]\" id=\"file$i\" class=\"upload\" />";
////				$ret.="<input name=\"files[]\" tabindex=\"-1\" class=\"filestyle\" id=\"filestyle-3\" style=\"left: -9999px; position: absolute;\" type=\"file\" data-classbutton=\"btn btn-primary\">";
////				$ret.="<div tabindex=\"0\" class=\"bootstrap-filestyle\" style=\"display: inline;\"><input disabled=\"\" class=\"input-large\" type=\"text\"> <label class=\"btn btn-primary\" for=\"filestyle-3\"><i class=\" icon-white icon-folder-open\"></i> <span>Choose file</span></label></div>";
				$ret.="</div>";
				$ret.="</div>";
			}
			$ret .=	""
				."<br />"
				."<div>"
				."<input type=\"submit\" name=\"submit\" value=\"".__CARICA__."\" />"
				."</div>"
				."</div>"
				."</form>"
				."";
			return ($ret) ;
		}

//////////////////////////////////////////////////////////
function loadSqlIntoTables ($filename,$tablename,$fieldNames ) {
//	global $_FILES; 
	global $mysqli;
	$contatore=0;
//	global $uploader;
		$retMsg="errore generico non tipizzato";
	
		// lo apre
		$handle = fopen("$filename", "r");
		if ($handle === false ) {
			$retMsg=sprintf("non posso aprire il file %s",$filename);
			return($retMsg);
		}
			
		// cancella il contenuto attuale
		$query="delete from $tablename";
		$mysqli->query($query) or die($mysqli->error);
		$query="truncate table $tablename";
		$mysqli->query($query) or die($mysqli->error);
/// se eseguo una truncate resetto i contatori e i valori auto incrementment... /// se non cancello tutto prima non innesco i trigger.... 

		$numberOfFields=count($fieldNames);

		// lo legge riga per riga\
//		$data = fgetcsv($handle, 20000, ",",'"');	
		$data = fgetcsv($handle, 20000, CSV_SEPARATORE,'"');	
//					scarta la prima riga
		$dummyFieldNames=implode(",",$fieldNames);
	
//		while (($data = fgetcsv($handle, 20000, ",",'"')) !== false)
		while (($data = fgetcsv($handle, 20000, CSV_SEPARATORE,'"')) !== false)
			{

			// elimino i caratteri indesiderati <32
			str_replace ("\r","",$data);
			str_replace ("\n","",$data);
			str_replace ("\t","",$data);
			str_replace ("\b","",$data);
			str_replace ("\v","",$data);
			str_replace ("\f","",$data);


						// fine eliminazione
//		echo "<hr> numberOfFields $numberOfFields <hr>";	
/*
			for ($zi=0;$zi<$numberOfFields;$zi++){
//				if (!get_magic_quotes_gpc()) {
					$dummy=addslashes($data[$zi]);
					$data[$zi]=$dummy;
//				}
			}
*/
			// riconoscere la riga vuota... 
			if (count($data)>1) {
				$dummyFieldsValues="";			
				for ($zi=0;$zi<$numberOfFields;$zi++){
						$dummyFieldsValues.="'$data[$zi]',";
				}
				$dummyFieldsValues=substr($dummyFieldsValues,0,-1);
			
//					$importEscaped=mysqli::real_escape_string($import); 
				$import="insert into $tablename ($dummyFieldNames) values($dummyFieldsValues)";
	//			echo "<hr>$import<hr>";
				$mysqli->query($import) or die($mysqli->error);
				$contatore++;
			} // fine if riga vuota

			// lo carica nel db
		}
		fclose($handle);
		$retMsg="Sono stati inserite $contatore righe nella tabella $tablename";
	return ($retMsg);

}


}
?>
