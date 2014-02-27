<?
class Config
{

	public $dbhost = '';
	
	public $dbuser = '';
	
	public $dbname = '';
	
	public $projectName = '';
	
	public $dbpassword = '';

	public $salt = '';
	
	public $pageOnlyNum = true;
	
	public $protectedLinks	= array( );
	
	public $compressCss = false;
	
	public $compressJs = false;
	
	public $debug = false;
	
	public $multylang = false;
	
	public $prefixes = array();
	
	public $urlPref = '';
	
	public $currentLang = '';
	
	public $multylang_bd = array();
	
	public $mobileVersionExist = '';
	
	public $mobileVersionOnSubdomain = '';
	
	public $mobileVersionSubdomainName = '';
	
	public $mobileVersionOtherDomainName = '';
	
	public $originalVersionDomainName = '';
	
	public $protocol = '';
	
	public $mmenu = array();
	
	public function __construct()
	{
	
		$this->setProps();
	
	}
	
	#return url prefix
	public function getUrlPref()
	{
		return ( $this->multylang ) ? '/'.$this->urlPref : '';
	}
	
	
	public function setProps()
	{
	
		include $_SERVER['DOCUMENT_ROOT'].'/system/config/config.cfg';
		
		$this->multylang = $config['multylang'];
		
		if( !$this->multylang )
		{
			$this->dbhost = $config['dbhost'];
			
			$this->dbuser = $config['dbuser'];
			
			$this->dbname = $config['dbname'];
			
			$this->dbpassword = $config['dbpassword'];
			
		}else{
			
			foreach( $config['multylang_bd'] as $ps => $arr )
			{
				$this->prefixes[] = $ps;

				$this->multylang_bd[ $ps ] = $config[ 'multylang_bd' ][ $ps ];
			}

			$tmpArr = explode('/',$_SERVER['REQUEST_URI']);

			$this->urlPref = ( !empty( $tmpArr[1] )  && array_search( $tmpArr[1],$this->prefixes ) !==false ) ? $tmpArr[1] : $this->prefixes[0];

			$this->currentLang = $this->urlPref;
			
			$this->dbhost = $config['multylang_bd'][ $this->urlPref ]['dbhost'];
			
			$this->dbuser = $config['multylang_bd'][ $this->urlPref ]['dbuser'];
			
			$this->dbname = $config['multylang_bd'][ $this->urlPref ]['dbname'];
			
			$this->dbpassword = $config['multylang_bd'][ $this->urlPref ]['dbpassword'];

		}	

		
		$this->salt = $config['salt'];
		
		$this->projectName = $config['projectName'];

		$this->protectedLinks = $config['protectedLinks'];
		
		$this->protocol = $config['protocol'];
		
		$this->debug = $config['debug'];
		
		$this->mmenu = $config['mmenu'];
		

		
		
		$this->mobileVersionExist = $config['mobileVersionExist'];
	
		$this->mobileVersionOnSubdomain = $config['mobileVersionOnSubdomain'];
	
		$this->mobileVersionSubdomainName = $config['mobileVersionSubdomainName'];
	
		$this->mobileVersionOtherDomainName = $config['mobileVersionOtherDomainName'];
		
		$this->originalVersionDomainName = $config['originalVersionDomainName'];
		
		unset( $config );
	
	}
	
	
	

}
?>