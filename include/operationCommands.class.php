<?php

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



/**
 * contains the classes describing the operational commands that can be performed in a "list window"
 *
 * @author G.Vieri https://github.com/gvieri
 *
 */


class operationCommand {
/**
 * 
 * @access private
 *
 */
	private $submitValue; //value to be used  in form
	private $action; // action in form (it is the script url ... ) 
	private $method; // method of form 
	private $actionName="azione" ; // it is the name of the form hidden paramenter
	private $actionValue=""; 
	private $idName="";
	private $idValue="";
	private $msg ;
	private $payloadF; // this is a "function pointer" so that we can associate code to actionValue. 
/**
 * @access public
 */
	public function __construct( $actionValue,$submitValue,$payloadF,$idName="id",$actionName="azione",$action="default_value",$method="POST",$idValue="__IDVALUE_TO_BE_SUST__") {
	

		$this->actionValue	= $actionValue;
		$this->idValue		= $idValue;
		$this->submitValue	= $submitValue;
		$this->idName		= $idName;
		$this->actionName	= $actionName;
		$this->action		= $action;
		$this->method		= $method;
		$this->payloadF		= $payloadF;
		if( strcmp($this->action,"default_value")==0 ) { 
			$this->action = $_SERVER['PHP_SELF'] ; }
//		$this->		= $
//		$this->		= $

		$this->msg="<form action='".$this->action."' method='".$this->method."'><input type='hidden' name='".$this->actionName."' value='".$this->actionValue."'><input type='hidden' name='".$this->idName."' value='".$this->idValue."' ><input type='submit' value='".$this->submitValue."'></form>";
		
		
	}
/**
 * @access public
 */
	

	public function getActionValue()  { 
		return $this->actionValue;
		
	}

/**
 * @access public
 */
	
	public function toString() { 
		return $this->msg; 
	
	}	

/**
 * @access public
 */
	public function getMsg() {
		return $this->msg;
	}


	public function callPayload() {
                return (call_user_func($this->payloadF,$auth));

        }


}




class operationCommands {
	private $opCommandArray;

        public function __construct( $op=null) {
		$x= new operationCommand("M","M") ;
		$y= new operationCommand("C","C") ;
		$this->opCommandArray=array( $x,$y );
                if ($op!=null) {
                        $this->opCommandArray=$op;
                }   
        }   



/**
 * @access public
 */
	 public function printAll()  {
                $ret="";
                foreach( $this->opCommandArray as $opCom ) { 
                        $ret.=$opCom->toString(); 
                }   
                return $ret; 

        }   

/**
 * @access public
 */
	public function getOpComValue($value) {
		$ret=null; 
		foreach ($this->opCommandArray as $opCom ) { 
			if (strcmp($opCom->getActionValue(),$value)) {
				$ret=$opCom;
				break; 
			}
		}
		return $ret;

	}
} 
/*
$c= new operationCommand("M","M","id","azione",$_SERVER['PHP_SELF'],"POST") ;
$a=new operationCommands(); 

echo $a->printAll(); 

print_r($a);
*/
?>

