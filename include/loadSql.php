



function loadSqlIntoTables ($filename,$tablename,$fieldNames ) {
	global $_FILES; 
	global $mysqli;
	$contatore=0;
	global $uploader;
	$authAna= new myAuth(myAuthType::ANA_LOGIN);

	
		// lo apre
		$handle = fopen("$filename", "r");
		// cancella il contenuto attuale
		$query="delete from $tablename";
		$mysqli->query($query) or die($mysqli->error());
		$query="truncate table $tablename";
		$mysqli->query($query) or die($mysqli->error());
/// se eseguo una truncate resetto i contatori e i valori auto incrementment... /// se non cancello tutto prima non innesco i trigger.... 

		$numberOfFields=count($fieldNames);

		// lo legge riga per riga\
		$data = fgetcsv($handle, 20000, ",",'"');	
//					scarta la prima riga
		$dummyFieldNames=implode(",",$fieldNames);
	
		while (($data = fgetcsv($handle, 20000, ",",'"')) !== FALSE)
			{

			// elimino i caratteri indesiderati <32
			str_replace ("\r","",$data);
			str_replace ("\n","",$data);
			str_replace ("\t","",$data);
			str_replace ("\b","",$data);
			str_replace ("\v","",$data);
			str_replace ("\f","",$data);


						// fine eliminazione
			
			for ($zi=0;$zi<$numberOfFields;$zi++){
				if (!get_magic_quotes_gpc()) {
					$dummy=addslashes($data[$zi]);
					$data[$zi]=$dummy;
				}
			}
			$dummyFieldsValues="";			
			for ($zi=0;$zi<$numberOfFields;$zi++){
					$dummyFieldsValues.="'$data[$zi]',";
			}
			$dummyFieldsValues=substr($dummyFieldsValues,0,-1);
			
//					$importEscaped=mysqli::real_escape_string($import); 
			$import="insert into $tablename ($dummyFieldNames) values($dummyFieldsValues)";
//			echo "<hr>$import<hr>";
			$mysqli->query($import) or die($mysqli->error());
			$contatore++;

			// lo carica nel db
		}
		fclose($handle);
		$retMsg="Sono stati inserite $contatore righe nella tabella $tablename";
	return ($retMsg);

