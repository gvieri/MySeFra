<?php 

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



final class myAuthType {

	const STAFF_LOGIN 	= '0';
	const ANA_LOGIN		= '1';
	private function __contstruct() {} 
}


class myAuth { 
	// we assume the db already configurated with 
	// some records in the right tables
	// we assume that the connection to the db is already
	// started, that the session if already established,
	// contains a valid token.
	const ammministrative_login_table="login";	
	const anagrafica_login_table="login_ana";	
	private $role; 
	var $token; 
	var $salt="";
	var $idlog;
	var $idanagrafica;
	var $loginTableName; // it is the name of the login table ... that contains at least username password token token_timestamp .
	var $language;

	function __construct($type=myAuthType::STAFF_LOGIN) {
		if ( $type==myAuthType::STAFF_LOGIN) {
			$this->loginTableName= self::ammministrative_login_table;
		} else { 
			$this->loginTableName= self::anagrafica_login_table;
		}

	}


	function ammLogin ($username, $password)  {
	// this function performs a login check for an amministrative login
	// it will return true on success and false on any other case 
	// the function will set the appropriate value in both token
	// and role in session and the token value in db
		global $mysqli;
		$retVal=FALSE;
		$username=$username;
		$dummy=sha1($password.$this->salt);
		$query="select * from ".$this->loginTableName." where username='".$username."' AND password = '".$dummy."'"; 
		if(!$r = $mysqli->query($query)){
		    die('There was an error running the query [' . $mysqli->error . ']');
		}
		

		if($r->num_rows == 1){   	
			$row = $r->fetch_assoc();
			$role=$row['role'];
			$idlog=$row['idlog'];
			$language=$row['lingua'];
			$tokenvalue=md5($username.date("YmdH:i:s"));
			$updquery="update ".$this->loginTableName." set token='".$tokenvalue."', token_timestamp=now() where username='".$username."' AND password = '".$dummy."'" ;
			if(!$r1 = $mysqli->query($updquery)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			$_SESSION['language']=$language;	
			$_SESSION['token']=$tokenvalue;	
			$_SESSION['role']=$role;
			$_SESSION['username']=$username;
			$_SESSION['idlog']=$idlog;
			$retVal=TRUE;
		}
		
		return $retVal;

	} 


	function Login ()  {


	}
	function sessionTimeout () {
		global $_SESSION;
	// returns true on expired session 
	// false if it is still valid 
		$retVal=0;
		if (isset ($_SESSION['LAST_ACTIVITY'])){
			$dummy=(time() - $_SESSION['LAST_ACTIVITY']);
			if ($dummy > SESSION_TIMEOUT) {
				$retVal=1;
			}
			$_SESSION['LAST_ACTIVITY']=time();
		} else  {
			$_SESSION['LAST_ACTIVITY']=time();
		}	
		
		return ($retVal);
	}

	function deleteToken() {
		global $_SESSION;
		global $mysqli;
		if (isset($_SESSION['token'])) {
			$updquery="update ".$this->loginTableName." set token='no more valid' where token='".$_SESSION['token']."'";		
			if(!$r1 = $mysqli->query($updquery)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			

		}
	}
			
	function loginCheck()  {
	// this function will check that the user has correctly performed
	// the login and, that the permission is not expired

	// it will return true on a valid login and, false in any other case
	// thi
		global $mysqli;
		global $_SESSION;
		$retVal=FALSE;

		if(isset($_SESSION['token'])){
			$token = $_SESSION['token'];
			$query = "select token,role,idlog from ".$this->loginTableName." where ".$this->loginTableName.".token='".$token."'";
			if(!$r = $mysqli->query($query)){
			    die('There was an error running the query [' . $mysqli->error . ']');
			}
			if($r->num_rows == 1){   	
			// hereby can be put some paranoid check on role and token 
				$row = $r->fetch_assoc();
				$len=strlen($token);
				$len1=strlen($row['token']);
				$this->idlog=$row['idlog'];
				if ($len==$len1) {	
					if (strncmp ($token, $row['token'],$len)==0) {
						$retVal=TRUE;

						$this->role=$row['role'];
						
					} else {
						//do nothing
					}
				} else  {
					// do something
				}
			} else {
				// maybe we can make something... 
			}
			$r->free();
		return ($retVal);
		}
	return (FALSE);
	}



	function showAuthForm ($authscriptname) {
		$signinForm=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/include/signinForm.html'); 
		$signinForm=str_replace("--LOGINACTION--",$authscriptname,$signinForm);

		echo $signinForm;
	}
	function showAuthPage ($scriptname) {
		$signinForm=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/include/auth_ok.html'); 
		$signinForm=str_replace("--URLDARICARICARE--",$authscriptname,$signinForm);
		$signinForm=str_replace("--LOGINACCEPTED--",__LOGINACCEPTED__,$signinForm);
		echo $signinForm;
	}
	
	function changePassword($password) {
		$dummy=sha1($password.$this->salt);
		$updquery="update ".$this->loginTableName." set password='".$dummy."' where idlog='".$this->idlog."'";
		if(!$r1 = $mysqli->query($updquery)){
                            die('There was an error running the query [' . $mysqli->error . ']');
                }
		$r1->free(); 
	}		
	function changePasswordFor($password,$idlog) {
		global $mysqli;
		$dummy=sha1($password.$this->salt);
		$updquery="update ".$this->loginTableName." set password='".$dummy."' where idlog='".$idlog."'";
		if(!$r1 = $mysqli->query($updquery)){
                            die('There was an error running the query [' . $mysqli->error . ']');
                }
	}		

	public static function generatePassword($length=10) {
		return(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#!?+-;:') , 0 , $length ));
	}
	public function getRole(){
		return($this->role);
	}
	public function isRole ($roleToBeChecked) {
		$retVal=FALSE; 
		if ($this->role >= $roleToBeChecked)  {
			$retVal=TRUE;
		}
		return ($retVal);
	}
	public function returnNoRoleMessage()  {
		global $_SERVER;
		$message= file_get_contents($_SERVER['DOCUMENT_ROOT'].'/include/norole.html');
		$message=str_replace("--URLDARICARICARE--",$_SERVER['PHP_SELF'],$message);
		$message=str_replace("--LOGINNOROLE--",__LOGINNOROLE__,$message);
		$message=str_replace("--NOROLEBUTTON--",__NOROLEBUTTON__,$message);
		
		return ($message); 
	}

}
		
