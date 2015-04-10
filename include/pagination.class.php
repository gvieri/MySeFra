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
require_once($_SERVER['DOCUMENT_ROOT']."/include/authenticatedPage.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/simpleCrypt.class.php");

/// questa classe prepara un widget di paginazione con alcune pagine elencate,
/// ritorno indietro 
/// indietro 
/// avanti 
/// vai alla fine 
/// il comando e' aggiunto alla fine della url come &page=[xxxx] con xx numero intero... 
///
class pagination {
	private $numOfRecords; 
	private $rowForPage; 
	private $actualPosition;
	private $url;
	private $urlParameter;
	function __construct($numOfRecords, $rowForPage, $actualPosition)  {

		if($actualPosition < 0 )  { $actualPosition = 0; }
		if($actualPosition > $numOfRecords )  { $actualPosition = $numOfRecords-$rowForPage; }

		$this->numOfRecords	=$numOfRecords;
		$this->rowForPage	=$rowForPage;
		$this->actualPosition	=$actualPosition;
		$this->url		=$_SERVER['PHP_SELF']."?";
		$this->urlParameter	="";

	}

	public function addParameter($par) {
		$this->urlParameter.=$par."&";
	}		
	public function display() {
		$totalPages= $this->numOfRecords/ $this->rowForPage;
		$actualPage= $this->actualPosition/ $this->rowForPage;

		$prevPageVal=$this->actualPosition-$this->rowForPage;
		$prevPageVal=($prevPageVal<0 ? 0: $prevPageVal);	
	
		$nextPageVal=$this->actualPosition+$this->rowForPage;
		$nextPageVal=($nextPageVal>($totalPages*$this->rowForPage)? $totalPages*$this->rowForPage-$this->rowForPage: $nextPageVal);	
		$lastPage=$this->numOfRecords-$this->rowForPage;
		$url=$this->url.$this->urlParameter;
		$start="<li> <a href='".$url."page=0'>&#12298; </a></li>"
		."<li>  <a href='".$url."page=".$prevPageVal."'>&#12296; </a></li>";
		$end	="<li> <a href='".$url."page=".$nextPageVal."'>&#12297;</a></li>"
		."<li>  <a href='".$url."page=".$lastPage."'>&#12299;</a></li>";
		$pagesString="";
		if ($totalPages >11 )  {
			if($actualPage<6) {
				$startpage=0;
				$endpage=11;
			} else if ($actualPage>($totalPages-6)) {
				$startpage=$totalPages-11;
				$endpage=$totalPages;
			} else {
				$startpage=$totalPages-5;
				$endpage=$totalPages+5;
			}		
			for($i=$startpage;$i<$endpage;$i++) {
				$dest=$i*$this->rowForPage;
				$pagesString.="<li><a href='".$url."page=".$dest."'>$i </a></li>";

			}				



		} else { 
			for($i=0;$i<$totalPages;$i++) {
				$dest=$i*$this-rowForPage;
				$pagesString.="<li><a href='".$url."page=".$dest."'>$i </a></li>";

			}				
		}
		$pagesString=$start.$pagesString.$end;
		/// leggi il template
			$cont=file_get_contents('pagination.html');


		/// sostituisci i contenuti 
			$cont=str_replace("<!--LIST_ITEM-->",$pagesString,$cont);

		/// ritorna il valore
		return($cont);	
	}

	public function getLimitClause() {
		$ret="LIMIT ".$this->actualPosition.", ".$this->rowForPage." ";
		return ($ret);

	}
}
?>
