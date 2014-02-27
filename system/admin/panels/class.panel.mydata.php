<?
@session_start();

class mydata extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		if( !isset( $this->userPrivilege['mydata_edit'] )){
			die('access denied');
		}
		
		#compile users array
		$res = $this->db->prepare("SELECT * FROM `adm_users` WHERE `uid`=:uid");
		$res->bindValue(':uid', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']);
		$res->execute();
		$usersArray = $res->fetch();
		$this->template->assign('user', $usersArray ); 
 
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.mydata.tpl');
	}
	
	
	public function save( $post )
	{
		global $validate;
		global $auth;

		$uid = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'];
		
		switch( $post['action'] )
		{
			#edit user
			case 'editUser' :
				$this->log('Мой пользователь', 'Изменение личных данных' );
			
				$validate->addToValidate('login', $post['login'], 'login');
				$validate->addToValidate('pass', $post['pass'], 'password');
				$validate->addToValidate('name', $post['name'], 'name');
				$validate->addToValidate('email', $post['email'], 'email');
				$validRes = $validate->validate();
				if( !$validRes ) die( $validate->error );
				
				$res = $this->db->prepare("SELECT `login`, `pass` FROM `adm_users` WHERE `uid`=:uid LIMIT 1");
				$res->bindValue(':uid', $uid);
				$res->execute();
				$tmpArray = $res->fetch();
				$userPass = $tmpArray['pass'];
				
				if( $userPass!=$post['pass'] )
				{
					$pass = $auth->crypt( $post['login'], $post['pass'] );
				}else{
					$pass = $post['pass'];
				}
				
				#check login
				$res = $this->db->prepare("SELECT `login` FROM `adm_users` WHERE `login`=:login AND `uid`<>:uid");
				$res->bindValue(':login', $post['login']);
				$res->bindValue(':uid', $uid);
				$res->execute();
				$ret = $res->fetchAll();
				
				if( !empty($ret) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['login'] = 'Данный логин уже занят';
					die( json_encode( $tmpArray ) );
				}
				
				$res = $this->db->prepare("UPDATE `adm_users` SET `name`=:name, `email`=:email, `login`=:login, `pass`=:pass WHERE `uid`=:uid ");
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':email', $post['email']);
				$res->bindValue(':login', $post['login']);
				$res->bindValue(':pass', $pass);
				$res->bindValue(':uid', $uid);
				$res->execute();
				die( $validate->error );
			break;

		}
	}
	
	

	
}
?>