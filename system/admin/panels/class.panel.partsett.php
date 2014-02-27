<?
@session_start();

class partsett extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	
	
	public function load()
	{
		global $config;

		$res = $this->db->prepare("SELECT * FROM `parts` WHERE `id`=:id LIMIT 1");
		$res->bindValue(':id', $this->pageId);
		$res->execute();
		$dataArray = $res->fetch();
		
		#check exist icons files
		
		$dataArray['icon'] = ( $dataArray['icon']!='' && file_exists( $this->root.'/files/images/pages/'.$this->pageId.'/'.$dataArray['icon'] ) ) ? '/files/images/pages/'.$this->pageId.'/'.$dataArray['icon'] : '';
		
		$date = new DateTime( $dataArray['expires_date'] );
		$dataArray['expires_date'] = $date->format("d.m.Y H:i:s");
		
		$date = new DateTime( $dataArray['edit_date'] );
		$dataArray['edit_date'] = $date->format("d.m.Y H:i:s");
		
		#compile icons array
		$this->template->assign('part', $dataArray );
		
		#compile language array
		$langsArray = array(
			'ru' => 'Русский',
			'en' => 'Английский',
			'de' => 'Немецкий',
			'fr' => 'Французский'
		);
		
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

		 
		#compile template site host
		$this->template->assign('hostname', $_SERVER['HTTP_HOST']);
				 
		#compile template site protocol
		$this->template->assign('protocol', $config->protocol);
		
		#compile template language array
		$this->template->assign('langsArray', $langsArray );
		
		#compile pids
		$this->template->assign('pidsArray', $this->core->mmenuArray );
		
		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.partsset.tpl');
	}
	
	

	
	public function save( $post )
	{
		global $validate;
		global $config;

		if( !isset( $this->userPrivilege['partition_edit'] )){
			die('access denied');
		}
		
		switch( $post['action'] )
		{
			case 'editPart' :
				
				$validate->emptyName = 'Введите имя страницы';
				$validate->protectedLink = 'Данное значение зарезервировано системой и не может быть использовано';
				
				#if is main page then pid is zero
				$pid = ($post['partId']=='1') ? '0' : $post['pid'];
				if( $pid==$post['partId'] ) $pid = '0';
				
				#if is main page then link is empty
				if( $post['partId']=='1' )
				{		
					$post['link'] = '';
				}else{
				#else add link in to validator
					$validate->addToValidate('link', $post['link'], 'link');
				}
				$validate->addToValidate('name', $post['name'], 'notnull');
				
				$validRes = $validate->validate();
				
				if(!$validRes) die( $validate->error );
				
				#template check
				$template = (isset($post['template'])) ? $post['template'] : 'default';				

				#check link
				$pid = ( isset($post['pid']) ) ? $post['pid'] : 0;
				$res = $this->db->prepare("SELECT `id`, `name` FROM `parts` WHERE `link`=:link AND `id`<>:id AND `pid`=:pid  ");
				$res->bindValue(':link', $post['link']);
				$res->bindValue(':id', $post['partId']);
				$res->bindValue(':pid', $pid);
				$res->execute();
				$resArray = $res->fetchAll();
				if( !empty( $resArray ) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['link'] = 'Данный адрес раздела уже используется. Задайте другой линк для страницы';
					die( json_encode($tmpArray) );
				}

				#get current page data
				$res = $this->db->prepare("SELECT `url`, `name`, `icon` FROM `parts` WHERE  `id`=:id ");
				$res->bindValue(':id', $post['partId']);
				$res->execute();
				$resArray = $res->fetch();
				$url = $resArray['url'];
				$oldName = $resArray['name'];
 				$partIcon = $resArray['icon'];
				$pattern = $url;
				$pattern = preg_replace('/\\//','\\\/',$pattern);
				$urlArr = array();
				$urlArr = explode('/', $url );
				unset( $urlArr[0] );
				unset( $urlArr[ count($urlArr) ] );
				$urlArr[count($urlArr)] = $post['link'];
				$url = '/';
				
				$lastUrl = $resArray['url'];
				
				
				foreach($urlArr as $k => $v)
				{
					$url .= $v.'/';
					if( $post[ 'partId' ]==1 ) $url = '/';
				};
				
				if( $post['partIcon'] == '' ) $post['partIcon'] = $partIcon;
				


				#save icons
				if( $post['partIcon']!='' &&  $post['partIcon']!='remove' && $post['partIcon'] != $partIcon)
				{
					if( !is_dir( $this->root.'/files/images/pages/'.$post['partId'].'/' ) ) mkdir( $this->root.'/files/images/pages/'.$post['partId'].'/', 0777, true );
					
					#remove old icon
					if( $partIcon!='' && file_exists( $this->root.'/files/images/pages/'.$post['partId'].'/'.$partIcon  ) ) unlink( $this->root.'/files/images/pages/'.$post['partId'].'/'.$partIcon);
					
					#get file external
					$tmpArr = explode(".", basename($post['partIcon']));
					$fileExternal = strtolower(array_pop($tmpArr)); 
					unset($tmpArr);
					
					#set output full filename
					$outputName = uniqid().'.'.$fileExternal;
					
					#crop icon
					$this->img->source = $this->root.$post['partIcon'];
					$this->img->output = $this->root.'/files/images/pages/'.$post['partId'].'/'.$outputName;
					$this->img->crop( $post['x'], $post['y'], $post['w'], $post['h'], true ) or die( $this->img->errorReport."-".$this->root.$post['partIcon'] );
					
					$this->img->source = $this->img->output;
					$this->img->resize( 40, 40, true );
					
					$post['partIcon'] = basename( $this->img->output );
				}	
				

				$meta_robots_all = isset( $post['meta_robots_all'] ) ? '1' : '0';
				$meta_robots_noindex = isset( $post['meta_robots_noindex'] ) ? '1' : '0';
				$meta_robots_nofollow = isset( $post['meta_robots_nofollow'] ) ? '1' : '0';
				$meta_robots_noarchive = isset( $post['meta_robots_noarchive'] ) ? '1' : '0';
				
				$date = new DateTime( $post['expires_date']);
				$expires_date = $date->format("Y-m-d H:i:s");
				
				$date = new DateTime( $post['edit_date']);
				$edit_date = $date->format("Y-m-d H:i:s");
				
				if( $oldName==$post['name'] )
				{
					$this->log('Настройки раздела', 'Изменение настроек раздела "'.$oldName.'"', $this->pageId);
				}else{
					$this->log('Настройки раздела', 'Изменение настроек раздела "'.$oldName.'". Новое имя раздела - "'.$post['name'].'"', $this->pageId);
				}
				
				
				#save page data
				$res = $this->db->prepare("
				UPDATE
					`parts` 
				SET 
					`link`=:link,
					`url`=:url,
					`pid`=:pid,
					`name`=:name,
					`quick_desc`=:quick_desc,
					`in_menu`=:in_menu,
					`off`=:off,
					`target`=:target,
					`skype_block`=:skype_block,
					`meta_lang`=:meta_lang,
					`meta_title`=:meta_title,
					`meta_keywords`=:meta_keywords,
					`meta_description`=:meta_description,
					`extra_meta`=:extra_meta,
					`meta_robots_all`=:meta_robots_all,
					`meta_robots_noindex`=:meta_robots_noindex,
					`meta_robots_nofollow`=:meta_robots_nofollow,
					`meta_robots_noarchive`=:meta_robots_noarchive,
					`template`=:template,
					`icon`=:icon,
					`edit_date`=:edit_date,
					`expires_date`=:expires_date,
					`pragma`=:pragma,
					`cache_control`=:cache_control
				WHERE 
					`id`=:id 
				LIMIT 1 
				");
				
				$res->bindValue(':link', $post['link']);
				$res->bindValue(':url', $url);
				$res->bindValue(':pid', $pid);
				$res->bindValue(':name', $post['name']);
				$res->bindValue(':quick_desc', $post['quick_desc']);
				$res->bindValue(':in_menu', $post['in_menu']);
				$res->bindValue(':off', $post['off']);
				$res->bindValue(':target', $post['target']);
				$res->bindValue(':skype_block', $post['skype_block']);
				$res->bindValue(':meta_lang', $post['meta_lang']);
				$res->bindValue(':meta_title', $post['meta_title']);
				$res->bindValue(':meta_keywords', $post['meta_keywords']);
				$res->bindValue(':meta_description', $post['meta_description']);
				$res->bindValue(':extra_meta', $post['extra_meta']);
				$res->bindValue(':meta_robots_all', $meta_robots_all);
				$res->bindValue(':meta_robots_noindex', $meta_robots_noindex);
				$res->bindValue(':meta_robots_nofollow', $meta_robots_nofollow);
				$res->bindValue(':meta_robots_noarchive', $meta_robots_noarchive);
				$res->bindValue(':template', $template);
				$res->bindValue(':icon', $post['partIcon']);
				$res->bindValue(':edit_date', $edit_date);
				$res->bindValue(':expires_date', $expires_date);
				$res->bindValue(':pragma', $post['pragma']);
				$res->bindValue(':cache_control', $post['cache_control']);
				$res->bindValue(':id', $post['partId']);
				$res->execute();
				
				
				
				#get first childrens of this page
				$res = $this->db->prepare("SELECT `id`, `url` FROM `parts` WHERE `pid`=:pid ");
				$res->bindValue(':pid', $post['partId']);
				$res->execute();
				$resArray = $res->fetchAll();
				
				if( !empty($resArray) )
				{
					foreach( $resArray as $row )
					{
						$id_arr[ $row['id'] ] = $row['url'];
						$in_arr[ $row['id'] ] = $row['id'];
						$in_arr2[ $row['id'] ] = $row['id'];
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
					if( $fuse > 15 ) break;
					
					#compile query
					if( empty( $in_arr ) ) continue;
					
					$in = implode( ',', $in_arr );

					$res = $this->db->prepare( "SELECT `id`, `url` FROM `parts` WHERE `pid` IN (".$in.") ");
					$res->execute();
					$resArray = $res->fetchAll();
					
					$in_arr = array();
					
					#if result not empty then add to ids array id
					if( !empty($resArray) )
					{
						foreach( $resArray as $row )
						{
							//echo 'step'.$fuse;
							$id_arr[ $row['id'] ] = $row['url'];
							$in_arr[ $row['id'] ] = $row['id'];
							
						}
					}
					
				}
				

				$lastUrl = preg_replace('/\//','\/',$lastUrl);

				#update childrens url
				if( !empty($id_arr) )
				{
					$query = "INSERT INTO `parts` (`id`, `url`) VALUES ";
					foreach( $id_arr as $id => $dataUrl )
					{
						$newUrl = preg_replace('/^'.$lastUrl.'/', $url, $dataUrl);
						$query .= "('".$id."', '".$newUrl."'),";
					}
					$query = substr($query, 0, -1);
					$query .= "ON DUPLICATE KEY UPDATE `id`=VALUES(`id`), `url`=VALUES(`url`)";
					$this->db->query( $query );
					
				}		

				die( $validate->error );
			break;

		}

	}
	

	public function uploadFile( $files, $post )
	{
		#check error
		if( !isset($post['action']) || $post['action'] !=="uploadIcon" ) return "Неизвестная ошибка"; 
		
		#check filesize
		if( $files['icon']['size'] > (3*1024*1024) ) return "Размер файла превышает 3 МБ";
		
		#check file
		if( !$this->img->isImg( $files['icon']['tmp_name'] ) ) return "Данный файл не является изображением, либо изображение повреждено";
		
		#resize icon
		$this->img->source = $files['icon']['tmp_name'];
		$this->img->output = $this->root.'/system/admin/temp/'.'_'.$files['icon']['name'];
		if ( $this->img->resize( 335, null, true) )
		{
			#unlink temporary file
			unlink( $files['icon']['tmp_name'] );
			
			#try to rename file
			if( !rename( $this->root.'/system/admin/temp/'.'_'.$files['icon']['name'], $files['icon']['tmp_name'] ))
			{
				#try to rename file else
				rename( $this->root.'/system/admin/temp/'.'_'.$files['icon']['name'], $files['icon']['tmp_name'] );
			}
		#if error	
		}else{
			return "При загрузке файла произошла ошибка.";
		}
			
		#return success result
		return "";	
	}
	
}
?>