<?
class ModCatalogitems extends Mod{

	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		
		#If you want to prevent loading of content
		//$this->core->blockContent = true;		
		
		#If you want to prevent loading of modules
		//$this->core->blockModules = true;
		$res = $this->db->prepare("SELECT * FROM `mod_catalogitems_items` WHERE `mod_id`=:mod_id");
		$res->bindValue(':mod_id', $this->modId, PDO::PARAM_INT);
		$res->execute();
		$resArray = $res->fetchAll();

		if( !empty($resArray) )
		{
			$itemsIdArray = array();
			foreach($resArray as $value)
			{
				$itemsIdArray[] = (int)$value['item_id'];
			}

			$catalog = new PlugCatalog();
			$catalog->loadSettings();
			$itemsArray = $catalog->getItems(0, 100, array( $catalog->marker['filterItemMarker'] =>$itemsIdArray));
			if(!empty($itemsArray))
			{
				$this->template->assign('catalogSettings', $catalog->catalogSettings);
				$this->template->assign('itemsArray', $itemsArray['itemsArray']);
			}
		}
	}
	
	public function render()
	{
		#paste your code here for view plugin
		
		$this->template->display('default.tpl');
		
	}
	
	
	public function ajax( $postArray = array() )
	{
		#paste your code here for ajax response
		echo 'I got an ajax response';
		var_dump($postArray);
	}

	public function downloadFile( $filename = '' )
	{
		//$filename = $this->root.'/files/file.txt';
		//$this->core->downloadFile( $filename, 'display file name.txt');
		return;
	}

	#get folder name by id
	#@param $id - for example, a item ID
	public function getFrolderById($id)
	{
		return intval($id / 50);
	}
	
}

?>