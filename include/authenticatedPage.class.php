<?PHP 

/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/



require_once("auth.class.php");


class authenticatedPage {

	private $payloadF;
	private $typeOfAuth;

	private $authObj;

	private $minimumRole;


	public function __construct ($nameOfFunction,$typeOfAuth=myAuthType::STAFF_LOGIN,$minimumRole=1) {
		$this->payloadF		=$nameOfFunction;
		$this->typeOfAuth	=$typeOfAuth;

	}

	public function callPayload($auth) {
		return (call_user_func($this->payloadF,$auth));

	}


	public function doAll() 	{
		global $mysqli;
		$res=FALSE; /// modifica del 20140405 per eleiminare un warning
	
		ini_set('session.cookie_httponly',true);
		ini_set('session.use_only_cookies',true);
		ini_set('session.cookie_httponly',1);
		ini_set('session.use_only_cookies',1);

		ob_start();
		if(!isset($_SESSION)){
			session_start();
		}
		session_regenerate_id();

		require_once($_SERVER['DOCUMENT_ROOT']."/include/conf.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/include/lib.inc.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/include/messages.IT.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/include/auth.class.php");
		$this->authObj= new myAuth($this->typeOfAuth);

		// verify if the user is already authenticated
		$sessFlag=$this->authObj->sessionTimeout() ;
		//echo "<hr> sessFlag=|".$sessFlag."|<hr>";

		if($sessFlag!=FALSE) {
			$this->authObj->deleteToken();
		}
		$authFlag=$this->authObj->loginCheck();
		//echo "<hr> authFlag=|".$authFlag."| <hr>";
		if ($authFlag==TRUE ) {
			if($this->authObj->isRole($this->minimumRole))  {
			$this->callPayload($this->authObj);
			} else {
				$this->authObj->deleteToken();
				echo $this->authObj->returnNoRoleMessage();
			}
		} else  {
			$this->authObj->deleteToken();
			if (isset($_REQUEST['loginform']) && ($_REQUEST['loginform']=="mylogin") ) {
				//try to log in
				$res=$this->authObj->ammLogin($_REQUEST['username'],$_REQUEST['password']);

		}
		if ($res==FALSE) {
			$script=basename($_SERVER["REQUEST_URI"]);
			echo $this->authObj->showAuthForm($script);
		} else {
			// start to work or, simply reload the page...
			echo $this->authObj->showAuthPage($script);

		}
	}
	ob_flush() ;
	} // end of function

}

