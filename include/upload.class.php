<?PHP
/*  
        (c) Copyright 2015  G.Vieri https://github.com/gvieri
        All Rights reserved
        this program is released both under Apache License 2.0 and GPL v 2.0

*/

require_once($_SERVER['DOCUMENT_ROOT']."/include/messages.php");


class upload  extends Exception {


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

	

		public function isAcceptable() {
			global $_FILES;
					
			if ($_FILES["file"]["size"] >self::maxFileSize ) {
//				throw new Exception ("file di dimensioni eccessive", 1 );
				return (1);
			}
			foreach ($this->extensionsAllowed  as $ext ) {
				if ($_FILES["file"]["type"]== $ext) {
					return (0) ; 
				}
			}
			return (1);
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

//			$ret = "<form action=\"ana_upload.php?action=upload\" method=\"post\" "
			$ret = "<form action=\"".$_SERVER['PHP_SELF']."?action=upload\" method=\"post\" "
				."enctype=\"multipart/form-data\">"
				."<div class=\"container\">"
				."<label for=\"file\">Filename:</label>"
				."<div class=\"fileUpload btn btn-primary\">"
				."<input type=\"file\" name=\"file\" id=\"files[]\" class=\"upload\"/>"
				."</div>"
				."<br />"
				."<input type=\"submit\" name=\"submit\" value=\"".__CARICA__."\" />"
				."</div>"
				."</form>"
				."";
			return ($ret) ;
		}


}
?>
