<?
@session_start();

class parts extends Get{

	
	public function __construct()
	{
		//parent::__construct( );
		parent::init();
	}
	

	public function load()
	{
		#compile partitions menu
		$this->template->assign('partitions', $this->core->mmenuArray);
		if( $this->config->mmenu['createSubSections'] ) $this->template->assign('createSubSections', '1'); else $this->template->assign('createSubSections', '0'); 
		if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] ) ) $this->template->assign('active', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id']);
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.parts.tpl');
	}
	
	public function clearDir( $dir, $suicide=false ) 
	{
		if (is_dir($dir)) 
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..") 
				{
					if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			if( $suicide) rmdir($dir); 
		}
	}
	
	public function save( $post )
	{
		global $validate;
	
		switch( $post['action'] )
		{
			#return template for adding page
			case 'addPart':
				if( !isset( $this->userPrivilege['partition_create'] )){
					die('access denied');
				}				
				
				#compile languages
				$langsArray = array(
					'ru' => 'Русский',
					'en' => 'Английский',
					'de' => 'Немецкий',
					'fr' => 'Французский'
				);
				$this->template->assign('langsArray', $langsArray );
				
				
				#compile pragma array
				$this->template->assign('pragmaArray', array(
					'null'		=>	'Не отправлять',
					'empty'		=>	'Отправлять пустой',
					'no-cache'	=>	'no-cache',
					'public'	=>	'public'
				));		
				
				#compile cache-control array
				$this->template->assign('cacheControlArray', array(
					'null'			=>	'Не отправлять',
					'empty'			=>	'Отправлять пустой',
					'no-cache'		=>	'no-cache',
					'a-no-cache'	=>	'no-store, no-cache',
					'b-no-cache'	=>	'no-store, no-cache, must-revalidate',
					'c-no-cache'	=>	'no-store, no-cache, must-revalidate, max-age=o',
					'd-no-cache'	=>	'no-store, no-cache, max-age=o'
				));				
				
				#search all page template files
				$templatFileArray = array();
				$dirArray = scandir( $this->root.'/tpl/' );
				foreach($dirArray as $file)
				{
					if( $file=='.' || $file=='..' ) continue;
					if( preg_match('/(^page\.)([0-9a-zA-Z]+)(\.tpl$)/', $file, $mask)  )
					{
						$templatFileArray[ $mask[2] ] = $mask[2]; 
					}			
				}
				#compile template file array
				if( count( $templatFileArray) >1 ) $this->template->assign('templatFileArray', $templatFileArray);

				
				#compile pids
				$this->template->assign('pidsArray', $this->core->mmenuArray );
				
				
				
				
				#compile new part data array
				$pid = ( $post['pid']>1 ) ? $post['pid'] : '0';
				
				if( $pid!='0' && !$this->config->mmenu['createSubSections'] ){
					die('access denied');
				}
				
				
				$dataArray = array(
					'pid'					=>		$pid,
					'link'					=>		'new_page',
					'name'					=>		'Новый раздел',
					'quick_desc'			=>		'',
					'in_menu'				=>		'1',
					'off'					=>		'0',
					'target'				=>		'_self',
					'skype_block'			=>		'1',
					'meta_lang'				=>		'ru',
					'meta_title'			=>		'',
					'meta_keywords'			=>		'',
					'meta_description'		=>		'',
					'extra_meta'			=>		'',
					'meta_robots_all'		=>		'',
					'meta_robots_noindex'	=>		'',
					'meta_robots_nofollow'	=>		'',
					'meta_robots_noarchive'	=>		'',
					'template'				=>		'default'
				);

				$date = new DateTime();
				$dataArray['create_date'] = $date->format("d.m.Y H:i:s");
						
				$date = new DateTime();
				$date->modify("+1 month");
				$dataArray['expires_date'] = $date->format("d.m.Y H:i:s");
		
				
				$date = new DateTime();
				$dataArray['edit_date'] = $date->format("d.m.Y H:i:s");
				
				
				$this->template->assign('part', $dataArray );
				
				#render
				$this->template->display('panel.parts.add.tpl');
			break;
			
			#add page
			case 'addPartSave':
				if( !isset( $this->userPrivilege['partition_create'] )){
					die('access denied');
				}
				
				$validate->empty = 'Введите имя страницы';
				$validate->protectedLink = 'Данное значение зарезервировано системой и не может быть использовано';

				#if is main page then pid is zero
				$pid = (isset( $post['partId'] ) && $post['partId']=='1') ? '0' : $post['pid'];
				if( isset($post['partId']) && $pid==$post['partId'] ) $pid = '0';
				
				#if is main page then link is empty
				if( isset( $post['partId']) && $post['partId']=='1' )
				{		
					$post['link'] = '';
				}else{
				#else add link in to validator
					$validate->addToValidate('link', $post['link'], 'link');
				}
				
				$validate->addToValidate('name', $post['name'], 'notnull');
				
				$validRes = $validate->validate();
				if(!$validRes) die( $validate->error );
				
				#check link
				$res = $this->db->prepare("SELECT `id`, `name` FROM `parts` WHERE `link`=:link AND `pid`=:pid  ");
				$res->bindValue(':link', $post['link']);
				$res->bindValue(':pid', $post['pid']);
				$res->execute();
				$resArray = $res->fetchAll();
				if( !empty( $resArray ) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['link'] = 'Данный адрес раздела уже используется. Задайте другой линк для страницы';
					
					die( json_encode($tmpArray) );
				}
				
				$this->log('Разделы', 'Создание раздела "'.$post['name'].'"' );
				
				
				if( $post['pid'] !='0' )
				{
					$res = $this->db->prepare(" SELECT `url` FROM `parts` WHERE `id`=:id LIMIT 1 ");
					$res->bindValue(':id', $post['pid']);
					$res->execute();
					$resArray = $res->fetch();
					$url = $resArray['url'].$post['link'].'/';
				
				}else{
					$url = '/'.$post['link'].'/';
				}

				$date = new DateTime();
				$create_date = $date->format("Y-m-d H:i:s");
								
				if( isset($post['expires_date']) )
				{
					$date = new DateTime( $post['expires_date']);
					$expires_date = $date->format("Y-m-d H:i:s");
				}else{
					$expires_date = date("Y-m-d H:i:s");
				}

				
				if( isset($post['edit_date']) )
				{
					$date = new DateTime( $post['edit_date']);
					$edit_date = $date->format("Y-m-d H:i:s");
				}else{
					$edit_date = date("Y-m-d H:i:s");
				}

				
				$res = $this->db->prepare("
				INSERT INTO
					`parts` 
				SET 
					`link`=:link,
					`url`=:url,
					`pid`=:pid,
					`name`=:name,
					`quick_desc`=:quick_desc,
					`edit_date`=:edit_date,
					`create_date`=:create_date,
					`expires_date`=:expires_date
				");
				$res->bindValue(':link', $post['link']);
				$res->bindValue(':url', $url);
				$res->bindValue(':pid', $pid);
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':quick_desc', $post['quick_desc']);
				$res->bindValue(':edit_date', $edit_date);
				$res->bindValue(':create_date', $create_date);
				$res->bindValue(':expires_date', $expires_date);
				$res->execute();

				$lastInsertId = $this->db->lastInsertId();

				#change current page id
				$this->pageId = $lastInsertId;
				$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] = $lastInsertId;

				$tmpArray = json_decode( $validate->error, true );
				$tmpArray['pageId'] = $lastInsertId;
				$validate->error = json_encode( $tmpArray );
				
				die( $validate->error );
			break;
			
			case 'removePart':
				if( !isset( $this->userPrivilege['partition_remove'] )){
					die('access denied');
				}
				#remove page
				$this->log('Разделы', 'Удаление раздела', $post['id'] );
				
				#if is main page then break
				if( $post['id']=='1' ) break;
				
				#add to ids array this page id
				$id_arr = array( $post['id'] => $post['id'] );
				
				#get first childrens of this page
				$res = $this->db->prepare("SELECT `id` FROM `parts` WHERE  `pid`=:pid ");
				$res->bindValue(':pid', $post['id']);
				$res->execute();
				$resArray = $res->fetchAll();

				if( !empty($resArray) )
				{
					foreach( $resArray as $row )
					{
						$id_arr[ $row['id'] ] = $row['id'];
					}
				}
				
				
				
				#results array
				$resArray = array('0'=>'0');
				
				#just fuse
				$fuse = 0;
				
				#go recursion
				while( !empty($resArray) )
				{
					$fuse++;
					
					#if cicle more 5000 step then break
					if( $fuse > 5000 ) break;
					
					#compile query
					$in = implode( ',', $id_arr );
					$res = $this->db->prepare( "SELECT `id` FROM `parts` WHERE `pid` IN (".$in.") ");
					$res->execute();
					$resArray = $res->fetchAll();
					
					#if result not empty then add to ids array id
					if( !empty($resArray) )
					{
						foreach( $resArray as $rows )
						{
							$id_arr[ $rows['id'] ] = $rows['id'];
						}
					}
				}
				
				#get modules of this pages
				$in = implode( ',', $id_arr );
				$res = $this->db->prepare( "SELECT `mod_name`, `id` FROM `modules` WHERE `page_id` IN (".$in.") " );
				$res->execute();
				$resArray = $res->fetchAll();
				
				#if having modules
				if( !empty( $resArray ) )
				{
					foreach( $resArray as $modDataArray )
					{
						#sent module about delete
						$modName = $modDataArray['mod_name'];
						$modId = $modDataArray['id'];
						$pageId = $modDataArray['page_id'];
						include_once $this->root.$this->modDir.$modName.'/back/mod.php';
						eval(' $mod_'.$modName.' = new Mod'.ucfirst($modName).'(); ');
						eval(' $mod_'.$modName.' ->delete( "'.$modId.'", "'.$pageId.'" ); ');
					}
				}

				#remove pages
				$dres = $this->db->query(" DELETE FROM `parts` WHERE `id` IN (".$in.")  AND `id`<>'1' " );
				
				#remove modules attach
				$dres = $this->db->query(" DELETE FROM `modules` WHERE `page_id` IN (".$in.") " );
				
				#remove folders
				foreach( $id_arr as $pageId )
				{
					$this->clearDir($this->root.'/files/images/pages/'.$pageId.'/', true);
				}
				
				if( $this->pageId==$post['id'] )
				{
					$this->pageId = '1';
					$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] = '1';
				}
				

				
			break;
			
			case 'rangeChange':
				if( !isset( $this->userPrivilege['partition_range'] )){
					die('access denied');
				}
				$this->log('Разделы', 'Изменение порядка следования разделов');
			
				unset($post['action']);
				
				$res = $this->db->prepare(" UPDATE `parts` SET `page_range`=:page_range WHERE `id`=:id");
				foreach($post as $page_range => $id)
				{
					$res->bindValue(':page_range', $page_range);
					$res->bindValue(':id', $id);
					$res->execute();
				} 
			break;
			
		}
	}
}
?>