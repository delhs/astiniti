<?
@session_start();

class users extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		if( !isset( $this->userPrivilege['users_edit'] )){
			die('access denied');
		}
		
		#compile users array
		$res = $this->db->prepare("SELECT * FROM `adm_users` ORDER BY `reg_date`  DESC");
		$res->execute();
		$resArray = $res->fetchAll();
		
		#compile users privilege array 
		foreach($resArray as $dataArray)
		{
			
			$usersArray[ $dataArray['id'] ] = $dataArray;
			$privilegeArray = unserialize($dataArray['privilege']);
			$usersPrivilegiesArray[ $dataArray['id'] ] = array();
			//print_r($privilegeArray);
			if( !empty( $privilegeArray) )
			{
				foreach( $privilegeArray as $privilegeName )
				{
					$usersPrivilegiesArray[ $dataArray['id'] ][$privilegeName] = $privilegeName;
				}
			}
		}

		#compile users privilege array template
		$this->template->assign('usersPrivilegiesArray', $usersPrivilegiesArray ); 
				
		#compile users array template
		$this->template->assign('usersArray', $usersArray ); 
		
		#compile myuid template
		$this->template->assign('myuid', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] ); 
		
		
		
		#compile all privileges array
		$res = $this->db->prepare("SELECT `name`, `descr` FROM `adm_privileges`");
		$res->execute();
		$tmpArray = $res->fetchAll();
		foreach( $tmpArray as $dataArray)
		{
			if( isset( $privilegesArray[ $dataArray['name'] ]['id'] ) ) $privilegesArray[ $dataArray['name'] ]['id'] = $dataArray['id'];
			$privilegesArray[ $dataArray['name'] ]['name'] = $dataArray['name'];
			$privilegesArray[ $dataArray['name'] ]['descr'] = $dataArray['descr'];
		}
		
		#compile all privileges array template
		$this->template->assign('privilegesArray', $privilegesArray );

		#compile groups array
		$res = $this->db->prepare("SELECT `id`, `name`, `privilege` FROM `adm_users_groups`");
		$res->execute();
		$usersGroupsArray = $res->fetchAll();
	
		#compile privileges array for groups
		foreach( $usersGroupsArray as $index => $dataArray )
		{
			$privilegesArrayA = $privilegesArray;
			$tempArray = unserialize($dataArray['privilege']);
			if(!empty( $tempArray ))
			{
				foreach( $tempArray as $privilegeName )
				{
					if( isset( $privilegesArrayA[ $privilegeName ] ) )
					{
						$privilegesArrayA[ $privilegeName ]['checked'] = 'checked';
					}
				}
			}
			$usersGroupsArrayA[ $dataArray['id'] ]['id'] = $dataArray['id'];
			$usersGroupsArrayA[ $dataArray['id'] ]['name'] = $dataArray['name'];
			$usersGroupsArrayA[ $dataArray['id'] ]['privileges'] = $privilegesArrayA;
		}
		#compile privileges array for groups template
		$this->template->assign('usersGroupsArray', $usersGroupsArrayA ); 
			
		if( !isset( $this->post['action'] ) )
		{
			$this->template->clear_compiled_tpl('panel.users.view.tpl');
			$this->template->display('panel.users.view.tpl');
			return;
		}
		
		switch( $this->post['action'] )
		{
			case 'users' :
				$this->template->clear_compiled_tpl('panel.users.view.tpl');	
				$this->template->display('panel.users.view.tpl');	
			break;			
			
			case 'adduser' :
				$this->template->display('panel.users.adduser.tpl');	
			break;
						
			case 'edituser' :
				$this->template->assign('editUserId',  $this->post['editedUserId']);
				$this->template->assign('editUserPrivilegeArray',  $usersPrivilegiesArray[ $this->post['editedUserId'] ]);
				
				$this->template->clear_compiled_tpl('panel.users.edituser.tpl');
				$this->template->display('panel.users.edituser.tpl');	
			break;
			
			case 'groups' :
				$this->template->clear_compiled_tpl('panel.users.groups.tpl');
				$this->template->display('panel.users.groups.tpl');	
			break;	
			
			case 'addgroup' :
				$this->template->display('panel.users.addgroup.tpl');	
			break;	
			
			case 'editgroup' :
				$this->template->assign('editedGroupId', $this->post['editedGroupId']);
				$this->template->display('panel.users.editgroup.tpl');	
			break;	
		}
		
		
		return;
		
		
	/* 	#compile users array
		$res = $this->db->prepare("SELECT * FROM `adm_users`");
		$res->execute();
		$usersArray = $res->fetchAll();
		$this->template->assign('usersArray', $usersArray ); 

		#compile all privileges array
 		$res = $this->db->prepare("SELECT `name`, `descr` FROM `adm_privileges`");
		$res->execute();
		$tmpArray = $res->fetchAll();
		foreach( $tmpArray as $dataArray)
		{
			$privilegesArray[ $dataArray['name'] ]['id'] = $dataArray['id'];
			$privilegesArray[ $dataArray['name'] ]['name'] = $dataArray['name'];
			$privilegesArray[ $dataArray['name'] ]['descr'] = $dataArray['descr'];
		}
		$this->template->assign('privilegesArray', $privilegesArray );
		
		
		#compile groups array
 		$res = $this->db->prepare("SELECT `id`, `name`, `privilege` FROM `adm_users_groups`");
		$res->execute();
		$usersGroupsArray = $res->fetchAll();

		#compile privileges array for groups
		foreach( $usersGroupsArray as $index => $dataArray )
		{
			$privilegesArrayA = $privilegesArray;
			$tempArray = unserialize($dataArray['privilege']);
			if(!empty( $tempArray ))
			{
				foreach( $tempArray as $privilegeName )
				{
					if( isset( $privilegesArrayA[ $privilegeName ] ) )
					{
						$privilegesArrayA[ $privilegeName ]['checked'] = 'checked';
					}
				}
			}
			$usersGroupsArrayA[ $dataArray['id'] ]['id'] = $dataArray['id'];
			$usersGroupsArrayA[ $dataArray['id'] ]['name'] = $dataArray['name'];
			$usersGroupsArrayA[ $dataArray['id'] ]['privileges'] = $privilegesArrayA;
		}
		$this->template->assign('usersGroupsArray', $usersGroupsArrayA );  */
	}
		
	public function render()
	{
		#display template
		//$this->template->display('panel.users.view.tpl');
	}
	
	
	public function save( $post )
	{
		global $validate;
		global $auth;
		
		if( !isset( $this->userPrivilege['users_edit'] )){
			die('access denied');
		}
		
		switch( $post['action'] )
		{
			#create new user
			case 'createUser' : 
				$validate->addToValidate('login', $post['login'], 'login');
				$validate->addToValidate('pass', $post['pass'], 'pass');
				$validate->addToValidate('name', $post['name'], 'name');
				$validate->addToValidate('email', $post['email'], 'email');
				$validRes = $validate->validate();
				if( !$validRes ) die( $validate->error );
				
				if( $auth->check($post['login'], $post['pass'] )!=NULL )
				{
					$tmpArray['validate'] = 'error';
					$tmpArray['login'] = 'Этот логин уже занят';
					die( json_encode( $tmpArray ) );
				}
				
				#compile uid
				$uid = md5( uniqid() );
				
				#crypt pass
				$pass = $auth->crypt( $post['login'], $post['pass']  );
				
				#compile privilege and group id
				if( !isset($post['privilege']) ) $post['privilege'] = array();
				
				
				if( $post['group']=='0' )
				{
					$group_id = '0';
				}else{
					$post['privilege'] = array();
					$group_id = $post['group'];					
				}
				$privilege = serialize( $post['privilege'] );
			
				#compile avow
				$avow = (isset( $post['avow'] )) ? '1' : '0';
			
				#compile user registry date
				$reg_date = date("Y-m-d");

				$this->log('Учетные записи', 'Создание учетной записи "'.$post['name'].'" с логином "'.$post['login'].'"');
				
				$res = $this->db->prepare("INSERT INTO `adm_users` SET `uid`=:uid, `name`=:name, `email`=:email, `login`=:login, `pass`=:pass, `privilege`=:privilege, `group_id`=:group_id, `avow`=:avow, `reg_date`=:reg_date ");
				$res->bindValue(':uid', $uid);
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':email', $post['email']);
				$res->bindValue(':login', $post['login']);
				$res->bindValue(':pass', $pass);
				$res->bindValue(':privilege', $privilege);
				$res->bindValue(':group_id', $group_id);
				$res->bindValue(':avow', $avow);
				$res->bindValue(':reg_date', $reg_date);
				$res->execute();
				die( $validate->error );
			break;
			
			#change privileges of users groups
			case 'groupListChange' :
			
				$res = $this->db->prepare("UPDATE `adm_users_groups` SET `privilege`=:privilege WHERE `id`=:id LIMIT 1");
				
				foreach( $post['privilege'] as $groupId => $privilegeArray )
				{
					$res->bindValue(':privilege', serialize( $privilegeArray ));
					$res->bindValue(':id', $groupId);
					$res->execute();
					
				
					$res = $this->db->prepare("SELECT `name` FROM `adm_users_groups` WHERE `id`=:id LIMIT 1");
					$res->bindValue(':id', $groupId);
					$res->execute();
					$resArray = $res->fetch();
					$this->log('Учетные записи', 'Изменение списка привилегий для группы "'.$resArray['name'].'"');
				}
			break;
			
			#delete user
			case 'deleteUser' :
			
				$res = $this->db->prepare("SELECT `name` FROM `adm_users` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				$resArray = $res->fetch();
				$this->log('Учетные записи', 'Удаление учетной записи "'.$resArray['name'].'"');
			
				$res = $this->db->prepare("DELETE FROM `adm_users` WHERE `id`=:id AND `super_admin`=:super_admin LIMIT 1");
				$res->bindValue(':id', $post['userId']);
				$res->bindValue(':super_admin', '0' );
				$res->execute();
			break;
			
			#delete group
			case 'deleteGroup' :
				
				$res = $this->db->prepare("SELECT `name` FROM `adm_users_groups` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['groupId']);
				$res->execute();
				$resArray = $res->fetch();
				$this->log('Учетные записи', 'Удаление группы учетных записей "'.$resArray['name'].'"');
				
				$res = $this->db->prepare("DELETE FROM `adm_users_groups` WHERE `id`=:id LIMIT 1 ");
				$res->bindValue(':id', $post['groupId']);
				$res->execute();
				
				$res = $this->db->prepare("UPDATE `adm_users` SET `group_id`=:new_group_id WHERE `group_id`=:old_group_id ");
				$res->bindValue(':new_group_id', '0');
				$res->bindValue(':old_group_id', $post['groupId']);
				$res->execute();
			break;	
			
			#change group for user
			case 'changeGroup' :
				$res = $this->db->prepare("SELECT `name` FROM `adm_users` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				$resArray = $res->fetch();
				$this->log('Учетные записи', 'Изменение группы для учетной записи "'.$resArray['name'].'"');
				
				
				$res = $this->db->prepare("UPDATE `adm_users` SET `group_id`=:group_id WHERE `id`=:id LIMIT 1");
				$res->bindValue(':group_id', $post['groupId']);
				$res->bindValue(':id', $post['userId']);
				$res->execute();
			break;
			
			#return user data
			case 'getUserData' :
				$res = $this->db->prepare("SELECT *  FROM `adm_users` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				$tmpArray = $res->fetchAll();
				$retArray = array();
				foreach( $tmpArray[0] as $field => $value )
				{
					if( !is_numeric($field) ) $retArray[ $field ] = $value;
				}
				$retArray['privilege'] = unserialize( $retArray['privilege'] );
				die( json_encode( $retArray ) );
			break;
			
			#edit user
			case 'editUser' :
				$validate->addToValidate('login', $post['login'], 'login');
				$validate->addToValidate('pass', $post['pass'], 'pass');
				$validate->addToValidate('name', $post['name'], 'name');
				$validate->addToValidate('email', $post['email'], 'email');
				$validRes = $validate->validate();
				if( !$validRes ) die( $validate->error );
				
				$res = $this->db->prepare("SELECT `login`, `pass` FROM `adm_users` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['userId']);
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
				$res = $this->db->prepare("SELECT `login` FROM `adm_users` WHERE `login`=:login AND `id`<>:id");
				$res->bindValue(':login', $post['login']);
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				$ret = $res->fetchAll();
				
				if( !empty($ret) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['login'] = 'Данный логин уже занят';
					die( json_encode( $tmpArray ) );
				}
				
				#compile privilege and group id
				if( !isset($post['privilege']) ) $post['privilege'] = array();
				
				
				if( !isset($post['group']) || $post['group']=='0' )
				{
					$group_id = '0';
				}else{
					$post['privilege'] = array();
					$group_id = $post['group'];					
				}
				$privilege = serialize( $post['privilege'] );
			
				#compile avow
				$avow = (isset( $post['avow'] )) ? '1' : '0';
				
				#if is super admin account
				if($post['userId']=='1') $avow = '1';
				
				
				$res = $this->db->prepare("SELECT `name` FROM `adm_users` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				$resArray = $res->fetch();
				$this->log('Учетные записи', 'Изменение настроек учетной записи "'.$resArray['name'].'"');
				
				
				$res = $this->db->prepare("UPDATE `adm_users` SET `name`=:name, `email`=:email, `login`=:login, `pass`=:pass, `privilege`=:privilege, `group_id`=:group_id, `avow`=:avow WHERE `id`=:id ");
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':email', $post['email']);
				$res->bindValue(':login', $post['login']);
				$res->bindValue(':pass', $pass);
				$res->bindValue(':privilege', $privilege);
				$res->bindValue(':group_id', $group_id);
				$res->bindValue(':avow', $avow);
				$res->bindValue(':id', $post['userId']);
				$res->execute();
				die( $validate->error );
			break;
			
			#create group
			case 'groupCreate' :
				$validate->addToValidate('name', $post['name'], 'notnull');
				$validRes = $validate->validate();
				if( $validRes )
				{
					$this->log('Учетные записи', 'Создание группы учетных записей "'.$post['name'].'"');
					
					if( !isset( $post['privilege'] ) ) $post['privilege'] = array();
					
					$res=$this->db->prepare("INSERT INTO `adm_users_groups` SET `name`=:name, `privilege`=:privilege ");
					$res->bindValue(':name', $post['name']);
					$res->bindValue(':privilege', serialize( $post['privilege'] ));
					$res->execute();
				}
				die( $validate->error );
			break;
			
			#edit group
			case 'getGroupData' :
				$res = $this->db->prepare("SELECT * FROM `adm_users_groups` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['groupId']);
				$res->execute();
				$tmpArray = $res->fetchAll();
				$retArray = array();
				foreach( $tmpArray[0] as $field => $value )
				{
					if( !is_numeric($field) ) $retArray[ $field ] = $value;
				}
				$retArray['privilege'] = unserialize( $retArray['privilege'] );
				die( json_encode( $retArray ) );
			break;
			
			#edit group
			case 'editGroup' :
				$validate->addToValidate('name', $post['name'], 'notnull');
				$validRes = $validate->validate();
				if( !$validRes ) die( $validate->error );
				
				$this->log('Учетные записи', 'Изменение настроек группы учетных записей "'.$post['name'].'"');
				
				#compile privileges
				if( !isset($post['privilege']) ) $post['privilege'] = array();
				$privilege = serialize( $post['privilege'] );
				$res = $this->db->prepare("UPDATE `adm_users_groups` SET `name`=:name, `privilege`=:privilege WHERE `id`=:id ");
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':privilege', $privilege);
				$res->bindValue(':id', $post['groupId']);
				$res->execute();
				die( $validate->error );
			break;
		}
	}
	
	

	
}
?>