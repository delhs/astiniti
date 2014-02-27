<?
class ModCatalogitems extends Mod{

	public $modName = 'catalogitems';
	public $modNameRu = 'Лента товаров';
	
	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		#paste your code here
		ob_start();
		$this->ajax(array('action'=>'index_items_list'));
		$this->template->assign('welcomescreen', ob_get_clean());
		
	}
	
	public function render()
	{
		#paste your code here for view plugin
		
		$this->template->display('default.tpl');

	}
	
	public function install()
	{
		#mod_catalogitems_items
		$res = $this->db->query("SELECT count(*) FROM `mod_catalogitems_items`");
		if(!$res)
		{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `mod_catalogitems_items` (
				  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
				  `mod_id` int(11) NOT NULL COMMENT 'module id',
				  `item_id` int(11) NOT NULL COMMENT 'item id',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
			");
		}
	}

	#@param $moduleId - removed module id
	#@param $fromPageId - page from which the module is removed
	public function delete( $moduleId, $fromPageId )
	{
		$res = $this->db->prepare("DELETE FROM `mod_catalogitems_items` WHERE `mod_id`=:mod_id");
		$res->bindValue(':mod_id', $moduleId);
		$res->execute();
	}
	
	public function ajax( $postArray = array() )
	{
		#paste your code here for ajax response
		switch( $postArray['action'] )
		{
			case 'index_items_list':
				$res = $this->db->prepare("SELECT * FROM `mod_catalogitems_items` WHERE `mod_id`=:mod_id");
				$res->bindValue(':mod_id', $this->modId);
				$res->execute();
				$resArray = $res->fetchAll();



				if( !empty($resArray) )
				{
					$itemsIdArray = array();
					foreach($resArray as $value)
					{
						$itemsIdArray[] = $value['item_id'];
					}

					$catalog = new PlugCatalog();
					$itemsArray = $catalog->getItems( $itemsIdArray );
					if(!empty($itemsArray))	$this->template->assign('itemsArray', $itemsArray);
				}

				$this->template->display('index_items_list.tpl');
			break;

			#get all data for analog window
			case 'items_splash_tpl':
				$catalog = new PlugCatalog();
				$catalog->loadCatList();
				$this->template->assign('categories', $catalog->catList);
				$this->template->display('cat_list.tpl');
			break;

			#save data
			case 'saveItemsList':
				
				$res = $this->db->prepare("DELETE FROM `mod_catalogitems_items` WHERE `mod_id`=:mod_id");
				$res->bindValue(':mod_id', $this->modId);
				$res->execute();

				if( empty($postArray['items']) ) return;

				$queryValuesArray = array();
				foreach( $postArray['items'] as $itemId )
				{
					$queryValuesArray[] = "('".$this->modId."', '".$itemId."')";
				}

				$this->db->query("
					INSERT INTO 
						`mod_catalogitems_items`
					(`mod_id`, `item_id`)
					VALUES
					".implode(', ',$queryValuesArray)."
				");

			break;
		}
	}

	#get folder name by id
	#@param $id - for example, a item ID
	public function getFolderById($id)
	{
		return intval($id / 50);
	}
}

?>