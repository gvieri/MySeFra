<?PHP 
/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/


class stepOfFlow {

	private $token;	// this is the assigned token ... it is a string 
	private $value;	// not typized value .. .so number string etc .
	private $code ;	// code it is the name of the function that must be called... 


	public function __construct ($token,$value=null, $code=null) { 

		$this->token= $token ; 
		$this->value= $value ;
		$this->code = $code  ;

	}

	public function callCode() {
                return (call_user_func($this->code));
        }
	public getToken() {
		return $this->token ;
	}
	public getValue() {
		return $this->value; 
	}
	public getCode() {
		return $this->code; 
	}
	public setToken($token) {
		$this->token= $token;
	}
	public setValue($value) {
		$this->value=$value; 
	}
	public setCode($code) {
		$this->code=$code; 
	}
	
	

}


class flowOfSteps {
	private $flow;  // array of step of flow ... 

	public function __construct($flow=null) {

		$this->flow = $flow ; 

	}
	

	public function searchToken($tok) { 
		$ret=null; 
		foreach ($this->flow as $step )  {

			if (strcmp($step->token, $token ) == 0 ) {
				$ret=$step ;
				break; 
	
			}
		}
		return $ret; 			

	}

	public function searchTokenExecCode ($tok) { 
		foreach ($this->flow as $step )  {

			if (strcmp($step->token, $token ) == 0 ) {
				$step->callCode() ;
				break; 
	
			}
		}

	}
	
}
?>	
