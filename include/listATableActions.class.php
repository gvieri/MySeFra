<?PHP
/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/


require_once($_SERVER['DOCUMENT_ROOT']."/include/conf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/lib.inc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/auth.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/messages.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/authenticatedPage.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/simpleCrypt.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/pagination.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/navMenuFromDb.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/operationCommands.class.php");



class  listATableActions  {
	private $tablename;
	private	$indexFieldName;
	public 	$mysqli; 

	private $namecolumnarray;
	private $formFile;
	private $footerFile;
	private $headerFile;
	private $asterisk;
	private $headerSubstitutionArray;
	private $columnInfoArray;
	private $startColumn;
	private $fieldNamesNotToBeUpdated;
	private $searchFieldName;		// campo su cui cercare 
	private $searchContent;			// contenuto da cercare
	private $pageLen;			// numero di righe per pagina se zero non c'e' paginazione
	private $orderby;			// campo su cui ordinare
	private $startRow;			// riga da cui comincia la pagina
	private $pagingFlag;			// flag di abilitazione della paginazione

	private $columnToBeLinkedInternal;	// array associativo di colonne che devono essere collegate con altre url interne al sito. 
	private $enableSearchFormFlag;		// abilita il form della search

	private $orderByField; 			// campo su cui inserire la clausola order by
	private $orderByFieldAscDesc; 			// campo su cui inserire la clausola order by
	private $orderByFlag;			// flag di attivazione uso clausola orderBy
	private $specialSearch; 		// ricerca speciale 
	private $specialSearchForm; 		// ricerca speciale 
	private $opCom;				// operational commands of list ... see operationCommands.class.php
		
///////////////////////////////////////////////////////////////////
	function __construct($tablename, $indexFieldName, $formFile=null, $footerFile="footer_list.html",$headerFile="header_list.html" )	 {
		global $mysqli;
		$this->specialSearch		= "";
		$this->specialSearchForm	= "";
		$this->tablename		= $tablename;
		$this->indexFieldName		= $indexFieldName;
		$this->formFile			= $formFile;
		$this->footerFile		= $footerFile;
		$this->headerFile		= $headerFile;
		$this->asterisk			= " * ";
		$this->searchContent		= "";
		$this->searchFieldName		= "";
		$this->enableSearchFormFlag	= 0; 
		$this->orderByFlag		= 0;
		$this->fieldNamesNotToBeUpdated = array("dataoradiinserimento");
	
		$this->columnToBeLinkedInternal	=array();
		$this->namecolumnarray		=array();
		$this->headerSubstitutionArray	=array();
		$this->columnInfoArray		=array();
		$this->opCom			= new operationCommands();

		$this->startColumn		=3; /// importante cosi' scarta le prime tre colonne dagli aggiornamenti etc. (e anche dalle intestazioni).
		$query="SHOW FULL COLUMNS FROM ".$this->tablename;
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$auxQuery="SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '".$this->tablename."'";
		$i=0;
		while($row = $r->fetch_assoc()){
			$this->namecolumnarray[$i]=$row['Field'];
			if ($row['Type']=='timestamp') {
				$this->asterisk.=",date_format(".$row['Field'].",'%d/%m/%Y %T') as ".$row['Field']." ";
			}
			if ($row['Type']=='date' ) {
				$this->asterisk.=",date_format(".$row['Field'].",'%d/%m/%Y') as ".$row['Field']." ";

			}
			$this->columnInfoArray[$i]['Field']	=$row['Field'];
			$this->columnInfoArray[$i]['Type']	=$row['Type'];
			$this->columnInfoArray[$i]['Collation']	=$row['Collation'];
			$this->columnInfoArray[$i]['Null']	=$row['Null'];
			$this->columnInfoArray[$i]['Key']	=$row['Key'];
			$this->columnInfoArray[$i]['Default']	=$row['Default'];
			$this->columnInfoArray[$i]['Extra']	=$row['Extra'];
			$this->columnInfoArray[$i]['Privileges']	=$row['Privileges'];
			$this->columnInfoArray[$i]['Comment']	=$row['Comment'];
			$i++;	
		}
		$r->free();


	}

///////////////////////////////////////////////////////////////////

	public function setOperationCommands($opCom) {
		$this->opCom=$opCom;
	}
///////////////////////////////////////////////////////////////////
	private function drawArrowUp($val) {
/// val e' il nome del campo da ordinare
		$par="";
		$par.="order{".$val."=ASC}|";
		$mycrypt=new simpleCrypt();
		$val=base64_encode($mycrypt->encode($par));
		$ret="<a href='".$_SERVER['PHP_SELF']."?enc=".$val."' style='text-decoration: none'>&#9660;</a>";
		return($ret);
	}
///////////////////////////////////////////////////////////////////
	private function drawArrowDown($val) {
/// val e' il nome del campo da ordinare
		$par="";
		$par.="order{".$val."=DESC}|";
		$mycrypt=new simpleCrypt();
		$val=base64_encode($mycrypt->encode($par));
		$ret="<a href='".$_SERVER['PHP_SELF']."?enc=".$val."' style='text-decoration: none'>&#9650;</a>";
		return($ret);

	}
///////////////////////////////////////////////////////////////////
	public function setSpecialSearchForm($val) {
		$this->specialSearchForm=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getSpecialSearchForm($val) {
		return($this->specialSearchForm);
	}
///////////////////////////////////////////////////////////////////
	public function setSpecialSearch($val) {
		$this->specialSearch=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getSpecialSearch($val) {
		return($this->specialSearch);
	}
///////////////////////////////////////////////////////////////////
	public function setSearchContent($val) {
		$this->searchContent=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getSearchContent() {
		return($this->searchContent);
	}
///////////////////////////////////////////////////////////////////
	public function setSearchFieldName($val) {
		$this->searchFieldName=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getSearchFieldName() {
		return($this->searchFieldName);
	}
///////////////////////////////////////////////////////////////////
	public function setColumnToBeLinkedInternal($val=array()) {
		$this->columnToBeLinkedInternal=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getColumnToBeLinkedInternal() {
		return($this->columnToBeLinkedInternal);
	}
///////////////////////////////////////////////////////////////////
	public function setEnableSearchFormFlag($val) {
		$this->enableSearchFormFlag=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getEnableSearchFormFlag(){
		return ($this->enableSearchFormFlag);
	}	
///////////////////////////////////////////////////////////////////
	public function setPagingFlag($val) {
		$this->pagingFlag=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getPagingFlag() {
		return($this->pagingFlag);
	}
///////////////////////////////////////////////////////////////////
	public function setOrderByFlag($val) {
		$this->orderByFlag=$val;
	}
///////////////////////////////////////////////////////////////////
	public function getOrderByFlag() {
		return($this->orderByFlag);
	}
///////////////////////////////////////////////////////////////////

	private function getATemporaryTableName() {
		$name=substr($this->tablename."".md5(time().implode($_SERVER)),0,63);
		$ret($name);
	}
///////////////////////////////////////////////////////////////////
// questa funzione prepare una where su tutti i campi di payload con 
// la stringa contenuta in $this->searchContent

	private function getGlobalWhere ()  {
		$where=" where ";
		$len=count($this->namecolumnarray);
		for ($i=$this->startColumn; $i<$len ; $i++) {
			$where.= $this->namecolumnarray[$i]." LIKE '%".$this->searchContent."%' OR ";
		}
		$where=substr($where,0,-3);
		return($where);
	}

///////////////////////////////////////////////////////////////////
// questa funziona ritorna una where per una ricerca su un solo campo
//

	private function getSingleFieldWhere() {
		$where = " where ".$this->searchFieldName." LIKE '%".$this->searchContent."%'";
		return ($where);
	}

///////////////////////////////////////////////////////////////////
	public function setHeaderSubstitutionArray($substArray=array()) {
		$this->headerSubstitutionArray=$substArray;
	}
///////////////////////////////////////////////////////////////////
	public function deleteItem($id){ 
		global $mysqli;
	
		$query="delete from $this->tablename where $this->indexFieldName='$id'";
		
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
	}

///////////////////////////////////////////////////////////////////

	public function multipleDeleteItem( ) {
		global $mysqli;
		// it will find the interested items that have he "selected" field with
		// a value T

		$query="delete from $this->tablename where selected='T'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
	}

///////////////////////////////////////////////////////////////////

	public function showDetail($id)  {
		global $mysqli;
		$query="select * from $this->tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
	}

///////////////////////////////////////////////////////////////////

	public function upPosition($id) {
		// verify the position of the record
		// if is up then do nothing 
		// otherwise alter the field posizione
		global $mysqli;
		

		$query="select position from $this->tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$row = $r->fetch_assoc();
		$old_position=$position=$row['position'];
		$r->free();
		if($position==0) {
			$this->renumberPosition();	
		}
		if($position<=1) {
			// do nothing 
		} else { 
			// look for the preceding
			$newPosition=$position-1;
			$query= "select $this->indexFieldName from $this->tablename where position='".$newPosition."'";
			if(!$r = $mysqli->query($query)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$row = $r->fetch_assoc();
			$idToBeSwapped=$row[$this->indexFieldName];
			// now prepare the two update...
			$upd1="update $this->tablename set position='$newPosition' where $this->indexFieldName='$id'";
			$upd2="update $this->tablename set position='$position' where $this->indexFieldName='$idToBeSwapped'";

			if(!$r = $mysqli->query($upd1)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
					
			if(!$r = $mysqli->query($upd2)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
		}	
	}
///////////////////////////////////////////////////////////////////

	public function renumberPosition(){
		global $mysqli;
		
		$query="select $this->indexFieldName from $this->tablename";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$i=1;
		while ($row=$r->fetch_assoc()) {
			$id=$row[$this->indexFieldName];	
			$upd="update  $this->tablename set position='$i' where $this->indexFieldName='$id'";
			if(!$r1 = $mysqli->query($upd)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$i++;
			
		}
		$r->free();
	}
///////////////////////////////////////////////////////////////////

	public function downPosition($id) {
		// verify the position of the record
		// if is down then do nothing 
		// otherwise alter the field posizione
		global $mysqli;
		

		$query="select position from $this->tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$row = $r->fetch_assoc();
		$old_position=$position=$row['position'];
		$r->free();
		if($position==0) {
			renumberPosition();	
		}
		if(!$result = $mysqli->query("SELECT COUNT(*) FROM `$this->tablename`")){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}

		$row = $result->fetch_row();
		$rowNumber=$row[0];
		
		if($position>=$rowNumber) {
			// do nothing 
		} else { 
			// look for the next
			$newPosition=$position+1;
			$query= "select $this->indexFieldName from $this->tablename where position='".$newPosition."'";
			if(!$r = $mysqli->query($query)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$row = $r->fetch_assoc();
			$idToBeSwapped=$row[$this->indexFieldName];
			// now prepare the two update...
			$upd1="update $this->tablename set position='$newPosition' where $this->indexFieldName='$id'";
			$upd2="update $this->tablename set position='$position' where $this->indexFieldName='$idToBeSwapped'";

			if(!$r = $mysqli->query($upd1)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
					
			if(!$r = $mysqli->query($upd2)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
		}	
	}
///////////////////////////////////////////////////////////////////

	public function ToggleSel($id) {
		global $mysqli;

		$query="select selected from $this->tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$row=$r->fetch_row();
		if ($row[0]=='F') {
			$upd="update $this->tablename set selected='T' where $this->indexFieldName='$id'";
		} else { 
			$upd="update $this->tablename set selected='F' where $this->indexFieldName='$id'";
		}
		if(!$r = $mysqli->query($upd)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
	}

///////////////////////////////////////////////////////////////////
	private function processEncodedCommands($enc) {
		$commands=explode("|",$enc);
		foreach($commands as $command) {

			if (substr($command,0,7)=="search{")  {
				// e' una ricerca
				$dummy=substr($command,7); 
				$dummy=substr($dummy,0,-1);
				list($field,$content)=explode("=",$dummy);
			
				$this->setSearchContent	($content);
			} else if (substr($command,0,6)=="order{") {
				$dummy=substr($command,6);
				$dummy=substr($dummy,0,-1);
				list($this->orderByField,$this->orderByFieldAscDesc)=explode("=",$dummy);
			} 
		}

	}
///////////////////////////////////////////////////////////////////
	private function prepareCommandsToBeEncoded($val="")  {
		$ret="";
		if ($this->searchFieldName!="" ) {
			if ($this->searchContent!="")  {
				$ret.="search{".$this->searchFieldName."=".$this->searchContent."}|";
			}
		} else if ($this->searchContent!="")  {
				$ret.="search{"."=".$this->searchContent."}|";
		}

		$ret.=$val;
	
		return ($ret);			

	}

///////////////////////////////////////////////////////////////////

	public function showList() {

		global $mysqli;
		$mycrypt=new simpleCrypt();
		if (isset($_REQUEST['enc'])) {	
			$enc=base64_decode($_REQUEST['enc']);
			$enc=$mycrypt->decode($enc);
			$this->processEncodedCommands($enc);
			/// il caratter | e' usato per delimitare i vari "Comandi"
		}
		$header=file_get_contents('header_list.html');
		$header=str_replace("--TABLENAME--",$this->tablename,$header);
		

		$result = $mysqli->query("SELECT COUNT(*) FROM `".$this->tablename."`");
		$row = $result->fetch_row();
		$rowCount= $row[0]; /// il numero delle linee della tabella. 


		if ($_REQUEST['azione']=== "specialsearch") {
			foreach($_REQUEST as $key=>$value){
				if($key==="azione") {
					if ($value === "search") {
						break;
					}
				}  
				if($key==="toBeSearced") { 
					
				} else { 	
					$$key = $value;
				}
			}
		}
		echo $header;
		echo "<TR><TD>"
		."<form action='".$_SERVER['PHP_SELF']."' method='post'><input type='hidden' name='azione' value='A'><input type='submit' class='btn' value='".LISTE_AGGIUNGI."'></form>"
		."</TD><td><form method='post' class='form-search'> <input type='text' name='toBeSearched' class='input-medium search-query'> <button type='submit' class='btn'>".__RICERCA__."</button><input type='hidden' name='azione' value='search'> </form></td>";
		if (strlen($this->specialSearchForm)) { 
			echo "<td><form method='post' class='form-search'>".$this->specialSearchForm."<button type='submit' class='btn'>".__RICERCASPECIALE__."</button><input type='hidden' name='azione' value='specialsearch'> </form></td>";
		}
		echo ""	
		."</TR>"
		."</table><table data-resizable-columns-id=\"demo-table\" class=\"table table-striped table-bordered\">";

		echo "<thead><tr>";
		
		$len= count( $this->namecolumnarray ) ;
		echo "<th >".LISTE_SELEZIONI."</th>";
		echo "<TH colspan='".count($this->opCom)."'>".LISTE_OPERAZIONI."</TH>";
		echo "<TH colspan='2'>".LISTE_POSIZIONI."</TH>";
		for ($i=$this->startColumn; $i<$len ; $i++) { 
			$value=$this->namecolumnarray[$i];
			{
				$dummy=	@$this->headerSubstitutionArray[$this->namecolumnarray[$i]];
				if(strlen($dummy)) {
					$value=$dummy;
				}
			} 
			$valueString=$value.$this->drawArrowUp($this->namecolumnarray[$i]).$this->drawArrowDown($this->namecolumnarray[$i]);
			
			echo "<TH data-resizable-column-id=\"".$value."\">".$valueString."</TH>";
		}

		echo "</tr></thead>";


		/// now check if a search is involved..
		$where="";
		if (strlen($this->specialSearch)) {
			eval("\$where.=\"$this->specialSearch\";");
		}
		if ($this->searchFieldName!="" ) {
			$where.=$this->getSingleFieldWhere();
		} else if ($this->searchContent!="")  {
			$where.=$this->getGlobalWhere();
		}
		//////////////////////////////////////////////
		// adesso la clausola order by
		$orderby="  order by position"; 	
//		if ( $this->orderByFlag!=0) {
		/////////////////////////////////////////////
		// order by enabled
			if( $this->orderByField!= "") {
				$orderby = " order by ".$this->orderByField." ".$this->orderByFieldAscDesc;
			}
//		}
		$limitClause="";
		if( $this->pagingFlag!=0) {
			$position=intval($_REQUEST['page'],10);
			$myPage=new pagination($rowCount,20,$position);
			if (isset($_REQUEST['enc'])) {	
				$myPage->addParameter("enc=".$_REQUEST['enc']);
			}
				
			$limitClause=$myPage->getLimitClause();
		}
		$query="select ".$this->asterisk." from ".$this->tablename." ".$where." ".$orderby." ".$limitClause;
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		while($row = $r->fetch_assoc()){
			echo "<tr>";
			echo "<td>";
			if ($row[$this->namecolumnarray[2]]!='F') { $toggleselIcon="icon-ok-sign";$toggleselButton=__LISTE_CHECKED__;} else {$toggleselIcon="icon-remove-circle";$toggleselButton=__LISTE_TOBEDELETED__;}
	//		echo "<input type='checkbox' name='selezionate[]' value='".$row[$this->namecolumnarray[0]]."' id='id_selezionate".$row[$this->namecolumnarray[0]]."' />";
			echo "<form action='".$_SERVER['PHP_SELF']."' method='post'><input type='hidden' name='azione' value='ToggleSel'><input type='hidden' name='id' value='".$row[$this->namecolumnarray[0]]."' ><button type='submit' class='btn btn-info btn-small'><i class='$toggleselIcon icon-white'>$toggleselButton</i></button></form>";
			
			echo "</td>";
				foreach ($this->opCom as $op) {
					echo "<td>";
					echo $op->toString();
					echo "</td>";
				}
			echo "<td>"
			."<form method='post' action='".$_SERVER['PHP_SELF']."'><input type='hidden' name='azione' value='UP'><input type='hidden' name='id' value='".$row[$this->namecolumnarray[0]]."' ><button type='submit' class='btn btn-info btn-small'><i class='icon-arrow-up icon-white'>".__LISTE_UPMARK__."</i></button></form>"
			."</td><td>"
			."<form method='post' action='".$_SERVER['PHP_SELF']."'><input type='hidden' name='azione' value='DOWN'><input type='hidden' name='id' value='".$row[$this->namecolumnarray[0]]."' ><button type='submit' class='btn btn-info btn-small'><i class='icon-arrow-down icon-white'>".__LISTE_DOWNMARK__."</i></button></form>"

			."</td>";


			for ($i=$this->startColumn; $i<$len ; $i++) {
				$colName=$this->namecolumnarray[$i];
				if(array_key_exists($colName, $this->columnToBeLinkedInternal)	) {
					$url=$this->columnToBeLinkedInternal[$colName]['url'];
					$azione=$this->columnToBeLinkedInternal[$colName]['azione'];
					$field=$this->columnToBeLinkedInternal[$colName]['field'];
					
					$dummy=$azione."{".$field."=".$row[$colName]."}|";
					$dummy=base64_encode($mycrypt->encode($dummy));
					echo "<td><a href='$url?enc=$dummy&'>".$row[$colName]."</a></td>";
				} else { 
					echo "<td>".$row[$colName]."</td>";
				}
			}

			echo "</tr>\n";
		}

		echo "</table>";
			echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
		echo "<input type='hidden' name='azione' value='MDelete'><input type='submit' value='".LISTE_CANC_MULTIPLA."' class='btn'></input></form>";


		if( $this->pagingFlag!=0) {
//			$position=intval($_REQUEST['page'],10);
//			$myPage=new pagination($rowCount,20,$position);
			echo "<div>".$myPage->display()."</div>";
		}
		
		$footer=file_get_contents('footer_list.html');
		echo $footer;
	}
///////////////////////////////////////////////////////////////////

	public function showModifica($id){
		global $mysqli;


		$query="select ".$this->asterisk." from ".$this->tablename." where $this->indexFieldName='$id'"; 

		//$query= "select * from $this->tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$row = $r->fetch_assoc();
		$azione_da_eseguire='execModifica';
		$scrittaSuPulsante =LISTE_MODIFICA;
	echo 	require_once($this->formFile);



	}
///////////////////////////////////////////////////////////////////


	public function execModifica ($id) {
		global $mysqli;
		global $_REQUEST;
		
		$upd="update $this->tablename set ";
/*		$colArray=array_slice($this->namecolumnarray,3);
		foreach( $colArray as $field ) {
			$upd.=" $field='".$_REQUEST[$field]."',";
		}
*/
		$end=count($this->namecolumnarray);
		for ($i=$this->startColumn; $i<$end; $i++) {

			$dummy=$this->columnInfoArray[$i];
			if (in_array($dummy['Field'],$this->fieldNamesNotToBeUpdated)) {
				// do nothing
			} else {

				$field= $dummy['Field'];
				switch ($dummy['Type']) {
					case 'timestamp': 
						$dummyString=" $field=str_to_date('".$_REQUEST[$field]."', '%d/%m/%Y %T'),  ";
					
			
					break;
					case 'date':
					// str_to_date('06/12/2007', '%d/%m/%Y')"; 
						$dummyString=" $field=str_to_date('".$_REQUEST[$field]."', '%d/%m/%Y'),  ";

					break;
					default:
						$dummyString=" $field='".$_REQUEST[$field]."',";

					break;
				}
				$upd.=$dummyString;
			}
		} 
		$upd= substr($upd,0,-1); 
		$upd.= " where $this->indexFieldName='$id'";

		if(!$r = $mysqli->query($upd)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}

	}

	public function  mostraAggiungiItem() {
		global $mysqli;


	/*
		$query= "select * from $tablename where $this->indexFieldName='$id'";
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		$row = $r->fetch_assoc();
	*/
		$azione_da_eseguire='EA';
		$scrittaSuPulsante =LISTE_AGGIUNGI;
//	echo 	require_once("forms/form.anagrafica.html");
	echo 	require_once("$this->formFile");




	}
///////////////////////////////////////////////////////////////////


	public function eseguiAggiunta($id) {
		global $mysqli;
		global $_REQUEST;
		
		$upd="insert into  $this->tablename ";
		$colArray=array_slice($this->namecolumnarray,3);
		$values="( ";
		$values1="(";
		foreach( $colArray as $field ) {
			$values.=$field.",";
			$values1.="'".$_REQUEST[$field]."',";
		}
		$values= substr($values,0,-1); 
		$values.=") ";
		$values1= substr($values1,0,-1); 
		$values1.=") ";
		//// controlla che i campi siano non tutti vuoti 
		$emptyFlag=1;	
		foreach( $colArray as $field ) {
			if(isset($_REQUEST[$field])) {
				$emptyFlag=0;
			}
		}	
		$upd.= $values." VALUES ".$values1;
	//	echo "<hr>$upd<hr>";
		if($emptyFlag==0) {
			if(!$r = $mysqli->query($upd)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			else { 
			/// eventuale messaggio di errore

			}
		}
					


	}

///////////////////////////////////////////////////////////////////

	public function sortAsc() {


	}
///////////////////////////////////////////////////////////////////

	public function sortDesc() {


	}
///////////////////////////////////////////////////////////////////

	public function sortAscDesc() {
	// if someone call this function for first thing we have to  verify if that column is already sorted and in wich sensee
	// the sort order can be ASC or DESC



	}
///////////////////////////////////////////////////////////////////

	public function payloadFunction($auth) {
		global $mysqli;
		global $_REQUEST;
		

		$azione	=$_REQUEST['azione'];
		$id	=$_REQUEST['id'];
		switch($azione) {
			case 'exit':
				// delete session
			break;
			case 'A':
				$this->mostraAggiungiItem();
	//			$this->showList();
			break;
			case 'EA':
				$this->eseguiAggiunta();
				$this->showList($auth);
			break;	
			case 'M':
				// show the modify form
				$this->showModifica($id);
			break;
			case 'showModifica':
				// execute modification
				$this->showModifica($id);
			break;
			case 'execModifica':
				$this->execModifica($id);
				$this->showList($auth);
			break;
			case 'UP':
				//move up the record using posizione field
				$this->upPosition($id);
				$this->showList($auth);
			break;
			case 'DOWN':
				//move down the record using posizione field
				$this->downPosition($id);
				$this->showList($auth);
			break;
			case 'Delete':
				// delete the record
				$this->deleteItem($id);
				$this->showList($auth);
			break;
			case 'MDelete':
				//multiple delete
				$this->multipleDeleteItem();
				$this->showList($auth);
			break;
			case 'ToggleSel':
				$this->ToggleSel($id);
				$this->showList($auth);
			break;
			case 'sortAsc':
				$this->sortAsc();
				$this->showList($auth);
			break;	
			case 'sortDesc':
				$this->sortDesc();
				$this->showList($auth); 
			break;
			case 'sortAscDesc':
				$this->sortAscDesc();
				$this->showList($auth);
			break;
			case 'search':
				$this->setSearchContent($_REQUEST['toBeSearched']);
				$this->showList($auth);

			break;
			case 'specialsearch':
//				$this->setSearchContent($_REQUEST['toBeSearched']);
				$this->showList($auth);

			break;
			default:
				$this->showList($auth);
			break;
		}	

	}

///////////////////////////////////////////////////////////////////


}







?>


