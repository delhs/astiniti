<?
@session_start();

class redirect extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		if( !isset( $this->userPrivilege['redirect'] )){
			die('access denied');
		}
		
		$res = $this->db->prepare("SELECT * FROM `redirects`");
		$res->execute();
		$resArray = $res->fetchAll();
		$this->template->assign('redirectArray', $resArray);
		
		$this->template->assign('hostname', $_SERVER['HTTP_HOST']);
		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.redirect.tpl');
	}
	
	
	public function save( $post )
	{
		global $validate;
		
		if( !isset( $this->userPrivilege['redirect'] )){
			die('access denied');
		}
		
		
		if(isset($post['empty']))
		{
			$this->db->query("TRUNCATE TABLE `redirects`");
			$this->log('Перенаправления', 'Очистка списка перенаправлений', $this->pageId);
			return;
		}
		
		$validate->emptyUrl = 'Укажите URL адрес перенаправления';
		$validate->empty = 'Укажите URL адрес c которого должно быть выполнено перенаправление';
		
		$query = " INSERT INTO `redirects` (`from_url`, `to_url`) VALUES ";
		foreach( $post['from'] as $index => $fromUrl )
		{
			#add to validatot
			$validate->addToValidate('from['.$index.']', $fromUrl, 'notnull' );
			$validate->addToValidate('to['.$index.']', $post['to'][$index], 'url' );

			$fromUrl = preg_replace('/^http[s]{0,1}\:\/\//', '', $fromUrl);
			$fromUrl = preg_replace('/^www\./', '', $fromUrl);
			$fromUrl = preg_replace('/^'.$_SERVER['HTTP_HOST'].'\//', '/', $fromUrl);
			$fromUrl = '/'.$fromUrl;
			$fromUrl = preg_replace('/^\/{1,}/', '/', $fromUrl);
			#compile query
			$query .= "('".$fromUrl."', '".$post['to'][ $index ]."'),";
		}
		$this->log('Перенаправления', 'Изменение списка перенаправлений', $this->pageId);
		#validate
		if( !$validate->validate() ) die( $validate->error );
		
		$query = substr( $query, 0, -1);
		
		$this->db->query("TRUNCATE TABLE `redirects`");
		$this->db->query( $query );
		
		die( $validate->error );
	}
	
	

	
}
?>