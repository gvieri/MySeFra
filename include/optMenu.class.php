<?PHP 

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



// (C) Giovambattista Vieri 

require_once($_SERVER['DOCUMENT_ROOT']."/include/conf.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/auth.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/messages.IT.php");


class simpleOptMenu {
	public $query; // qui ci va' la query che tira fuori tutti gli option della select// 
	public $arrayOfElements; // qui vanno gli option della select


	public $selectedItems; // elementi da mostrare preselezionati se presenti

	public $idSelect;
	public $classSelect;
	public $arrayOfAttributes; // autofocus, disabled , form, multiple, name, required, size//
	public $theMenu;
	public $nameOfMenu;

	 
	function __construct ($idSelect, $classSelect="", $arrayOfElements=array(), $selectedItems=array(),$nameOfMenu="") {

		$this->idSelect			=	$idSelect;
		$this->classSelect		=	$classSelect;
		$this->arrayOfElements		=	$arrayOfElements;
		$this->arrayOfAttributes	=array();
		$this->selectedItems		=	$selectedItems;
		$this->theMenu			="";
		$this->nameOfMenu		=$nameOfMenu;

	}

	public function setSelectedItems($selectedItems=array()) {
		$this->selectedItems=$selectedItems;	

	}
	
	public function setArrayOfAttributes( $attr) { 

		$this->arrayOfAttributes=$attr;

	}

	public function setQuery($query) {
		$this->query=$query;
	}
	
	public function useQuery() {
		global $mysqli; 
		
		if(!$r = $mysqli->query($this->query)){
                    die('There was an error running the query [' . $mysqli->error . ']');
                }
		$i=0;
		while($row = $r->fetch_row()) {
			$this->arrayOfElements[$i++]=$row[0];
		}
	
	}

	public function generateSimpleOptMenu() {
		$len=count($this->arrayOfElements) ;

		$ret="<select id='".$this->idSelect."' name='".$this->nameOfMenu."' class='".$this->classSelect."'>";
		for ($i=0 ; $i<$len ; $i++) { 
			$dummy=$this->arrayOfElements[$i];
			$sel="";
			if (in_array($dummy,$this->selectedItems)) {
				$sel=" selected ";
			}
			$ret.="<option value='$dummy' $sel >$dummy</option>";
		}
		$ret.="</select>";
		$this->theMenu=$ret;
		return($this->theMenu);

	}
	public function getTheMenu() {
		return($this->theMenu);
	}

	public function setName($nameOfMenu) {
		$this->nameOfMenu=$nameOfMenu;
	}
		
}
?>
