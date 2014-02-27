<?
	class PlugCatalog extends Plug{
	
	
		
		public $marker = array(

			#category link url marker. Add this link into "protectedLinks" section array "config.cfg" file from "/config/config.cfg"
			'catMarker'					=>	'cat',
			
			#item link url marker
			'itemMarker'				=>	'item',

			#brand link url marker
			'brandMarker'				=>	'brand',

			#brands page url marker
			'brandsMarker'				=>	'brands',

			#compare page url marker
			'compareMarker'				=>	'compare',

			#cart page url marker
			'cartMarker'				=>	'cart',

			#filter link url marker
			'filterMarker'				=>	'filter',

			#QR code marker
			'qrCodeMarker'				=>	'qrcode',

			#filter brand marker
			'filterBrandMarker'			=>	'b',

			#filter price marker
			'filterPriceMarker'			=>	'p',

			#filter price marker
			'filterPriceRangeMarker'	=>	'pr',

			#filter categories marker
			'filterCatMarker'			=>	'c',

			#filter item marker
			'filterItemMarker'			=>	'i',

			#filter type marker
			'filterTypeMarker'			=>	't',

			#filter type marker
			'filterAttrMarker'			=>	'attr',

			#filter is_new marker
			'filterIsNewMarker'			=>	'new',

			#filter is_sale marker
			'filterIsSaleMarker'		=>	'sale',

			#filter is_hot marker
			'filterIsBestMarker'		=>	'best',

			#filter is_markdown marker
			'filterIsMarkdownMarker'	=>	'markdown'
		);

		#brands images sizes
		public $categoryLogosSizer = array(
			0	=> array(400, 400), //main image
			1	=> array(200, 200),
			2	=> array(100, 100)	//small image for list of cats
		);

		#brands images sizes
		public $brandLogosSizer = array(
			0	=> array(400, 200),
			1	=> array(200, 100),
			2	=> array(100, 50)	//small image for list of brands
		);	
		
		#items images sizes
		public $itemLogosSizer = array(
			0	=> array(800, 800),
			1	=> array(200, 200),
			2	=> array(100, 100),
			3	=> array(50, 50)	//small image for list of items
		);	
	
		#path to item images
		public $itemImagesPath = '/files/images/catalog/items/';
			
		#path to item other images
		public $itemImagesOtherPath = '/files/images/catalog/items/other/';
					
		#path to brand images
		public $brandImagesPath = '/files/images/catalog/brands/';
		
		#path to brand images
		public $categoryImagesPath = '/files/images/catalog/categories/';

		#catalog all settings array
		public $catalogSettings = array();
		
		#curent category id
		public $currentCatId = 0;
		
		#current category uri
		public $currentCatUri = '';
		
		#curent item id
		public $currentItemId = 0;
		
		#curent item uri
		public $currentItemUri = '';
		
		#curent brand id
		public $currentBrandId = 0;
		
		#curent brand uri
		public $currentBrandUri = '';
		

		
		#filter array
		public $filterArray = array();
		
		#template file name
		private $tpl = '';
		
		
		#categories list array (after method loadCatList())
		public $catList = array();

		#comments list array (after method loadItemComments())
		public $itemCommentsArray = array();



		public function __construct()
		{
			parent::__construct();
		}
		
		public function start()
		{
			$this->loadSettings();
			$this->route();
		}
		
		#load all catalog settings
		public function loadSettings()
		{
			#load catalog settings
			$res = $this->db->query("
				SELECT 
					`s`.`catalog_page_id`,
					`s`.`currency_quick`,
					`s`.`currency_symbol`,
					`s`.`currency_nom`,
					`s`.`currency_acc`,
					`s`.`currency_nomp`,
					`s`.`item_nom`,
					`s`.`item_acc`,
					`s`.`item_nomp`,
					`s`.`show_comments`,
					`p`.`url` AS `catalog_page_url`
				FROM
					`plug_catalog_sett` AS `s`
				LEFT JOIN
					`parts` AS `p`
				ON 
					`p`.`id` = `s`.`catalog_page_id`
			");
			if(!$res) die("Failed to load catalog settings");
			$resArray = $res->fetch();

			foreach( $resArray as $index => $value )
			{

				if( !is_numeric( $index ) ) $this->catalogSettings[ $index ] = $value;
			}

			$this->catalogSettings['comparePage'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['compareMarker'].'/';
			$this->catalogSettings['cartPage'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['cartMarker'].'/';
			$this->catalogSettings['brandsPage'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandsMarker'].'/';
			$this->catalogSettings['filterMarker'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['filterMarker'].'/';

			$this->template->assign("settings", json_encode( $this->catalogSettings ));
		}
		
		#catalog routing
		public function route()
		{
			#check the page id and catalog display page id
			if( $this->core->pageId != $this->catalogSettings['catalog_page_id'])
			{
				
				if( array_search($this->marker['catMarker'], $this->core->requestUriArray)!==false )
				{
					$this->core->error404();
					return;
				}
				
				return;
			}

			#if url has "..../cat/...."
			if( array_search( $this->marker['catMarker'], $this->core->requestUriArray)!==false )
			{

				//check to admin edited
				if( $this->core->editedFromAdmin )
				{
					$i = array_search( 'edited', $this->core->requestUriArray);
					if( $i!==false )
					{
						$a = $this->core->requestUriArray;
						foreach($a as $ia => $link)
						{
							if( $ia==$i || $ia>$i ) unset( $this->core->requestUriArray[ $ia ] );
						}
					}
				}
				
				#if url has "..../item/...." then load item page
				if( array_search( $this->marker['itemMarker'], $this->core->requestUriArray)!==false )
				{
					$this->loadItem();
				}
				#if url has "..../qrcode/...." then return qr image
				elseif( array_search( $this->marker['qrCodeMarker'], $this->core->requestUriArray)!==false )
				{
					$this->loadQrCode();
				}
				#if url has "..../brand/...." then load brand page or category if url not has "..../brand/...."
				elseif( array_search( $this->marker['brandMarker'], $this->core->requestUriArray)!==false )
				{
					$this->loadBrand();
				}
				elseif( array_search( $this->marker['brandsMarker'], $this->core->requestUriArray)!==false )
				{
					$this->brandsPage();
				}
				elseif( array_search( $this->marker['compareMarker'], $this->core->requestUriArray)!==false )
				{
					$this->comparePage();
				}				
				elseif( array_search( $this->marker['cartMarker'], $this->core->requestUriArray)!==false )
				{
					$this->cartPage();
				}
				#if url has "..../filter/...." then
				elseif( array_search( $this->marker['filterMarker'], $this->core->requestUriArray)!==false ){
					$index = array_search( $this->marker['filterMarker'], $this->core->requestUriArray);
					
					#if before "filter" url has not "cat" marker
					if( $this->core->requestUriArray[ $index - 1 ] != $this->marker['catMarker']  )
					{
						unset( $this->core->requestUriArray[ $index ] );
						#die('фильтр по чему-либо');
						$this->route();
					#if before "filter" url has "cat" marker	
					}else{
						#if after filter empty string then 404
						if(  $this->core->requestUriArray[ $index + 1 ] == "" )
						{

							$this->core->error404();
							return;
						}
						
						$massIndex = 'undefined';
						
						foreach( $this->core->requestUriArray as $i => $string )
						{
							if( $i < $index + 1 ) continue;
							#compile filter array
							foreach( $this->marker as $filterMarkerName => $mark )
							{
								if( $string == $mark ) $massIndex = $mark;
							}
							if( $string != '' && $string != $massIndex ) $this->filterArray[ $massIndex ][] = $string;
						}

						if( isset( $this->filterArray[ $this->marker['filterAttrMarker'] ] ) )
						{
							$attr = array();
							$i = 1;
							$typeId = 0;
							$attrId = 0;

							foreach( $this->filterArray[ $this->marker['filterAttrMarker'] ] as $attrData )
							{

								switch( $i )
								{
									
									case 2: $typeId = $attrData; break;
									case 3: $attrId = $attrData;  break;
									case 4: $attr[ $attrId ] = $attrData; $i = 0; break;
								}

								$i++;
							}

							$this->filterArray[ $this->marker['filterAttrMarker'] ] = $attr;
						}

						
						unset( $this->core->requestUriArray[ $index - 1 ] );
						$this->route();
					}
				}else{
					#load category
					$this->loadCategory();
				}
			#show main catalog page	if url not has "..../cat/...."
			}else{
				$this->mainPage();
			}
		}
		
		#return qr code
		public function loadQrCode()
		{
			
			#right link
			//.....cat/qrcode/12/M/8/3/FFCCCC/000000
			#get item id
			$startIndex = array_search($this->marker['qrCodeMarker'], $this->core->requestUriArray) + 1;
			$endIndex = count($this->core->requestUriArray) - 2;
			$qrUrlRequest = '';

			foreach( $this->core->requestUriArray as $key => $value )
			{
				if( $key<$startIndex ) continue;
				$qrUrlRequest .= '/'.$value;
			}
			
			if( $qrUrlRequest=='' || $qrUrlRequest=='/' )
			{
				$this->core->error404();
			}

			$tmpArray = explode('/', $qrUrlRequest);
			
			$dataArray['params'] = array(
				'errorCorrectionLevel'	=>	'L',
				'matrixPointSize'		=>	'4',
				'whiteBorder'			=>	'1',
				'backgroundColor'		=>	0xFFFFFF,
				'foregroundColor'		=>	0x000000
			);


			foreach( $tmpArray as $index => $value )
			{
				if( $value=='' ) continue;

				if( $index==1 && is_numeric($value) )
				{
					$dataArray['id'] = $value;
				}
				elseif( $index==2 && in_array($value, array('L','M','Q','H'))  )
				{
					$dataArray['params']['errorCorrectionLevel'] = $value;
				}
				elseif( $index==3 && $value>0 && $value<11 )
				{
					$dataArray['params']['matrixPointSize'] = (int)$value;
				}
				elseif( $index==4 && $value>0 && $value<11 )
				{
					$dataArray['params']['whiteBorder'] = (int)$value;
				}
				elseif( $index==5 && preg_match('/^[0-9a-fA-F]{6}+$/', $value) )
				{
					$dataArray['params']['backgroundColor'] = hexdec($value);
				}
				elseif( $index==6 && preg_match('/^[0-9a-fA-F]{6}+$/', $value) )
				{
					$dataArray['params']['foregroundColor'] = hexdec($value);
				}
			}

			#get item data
			$res = $this->db->prepare("SELECT `name`, `price` FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
			$res->bindValue(':id', $dataArray['id'], PDO::PARAM_INT);
			$res->execute();
			$resArray = $res->fetch();
			if( !$resArray )
			{
				$this->core->error404();
				return;
			}

			$qrText = $resArray['name'].'. Цена: '.$resArray['price'].' '. $this->catalogSettings['currency_quick'].'. "'.$this->core->config->projectName.'". www.'.$_SERVER['HTTP_HOST'];

			#compile QR code
			include_once dirname(__FILE__).'/lib/qrcode/qrlib.php';
			QRcode::png($qrText, false, $dataArray['params']['errorCorrectionLevel'], $dataArray['params']['matrixPointSize'], $dataArray['params']['whiteBorder'], false, $dataArray['params']['backgroundColor'], $dataArray['params']['foregroundColor']);
			die();
		}

		#compile main catalog page
		public function mainPage()
		{
			
			#get currentpage number
			if( $index = array_search('page', $this->core->requestUriArray) ){
				#get current page
				$page = (int)$this->core->requestUriArray[ $index + 1 ];
			}			
			if( !isset( $page ) ) $page=1;
			
			#set tpl page
			$this->tpl = 'main';
			
			#get items data
			$itemsArray = $this->getItems( $page, 10, $this->filterArray); 
			

			if( isset( $this->filterArray[ $this->marker['filterCatMarker'] ][0] ) )
			{
				$brandsArray = $this->getBrands($this->filterArray[ $this->marker['filterCatMarker'] ]);
				//var_dump($brandsArray);
			}else{
				$brandsArray = ($this->currentCatId!=0) ? $this->getBrands( array($this->currentCatId) ) : $this->getBrands();
			}

			#get brands data
			//$brandsArray = ($this->currentCatId!=0) ? $this->getBrands( array($this->currentCatId) ) : $this->getBrands();

			#get categories data
			$this->loadCatList( $this->currentCatId );

			#get types data
			$typesArray = $this->getTypes();

			#get attributes for filter
			$attributesArray = $this->getAttributes();

			#compile items template

			if(!empty( $itemsArray['itemsArray'] ))
			{
				if( !empty( $itemsArray['navArray'] ) )
				{
					if(!empty($this->filterArray))
					{
						if( preg_match('/\\/page\\/([0-9]+)(.*)/', $this->core->requestUri) )
						{
							foreach( $itemsArray['navArray'] as &$data )
							{
								$data['href'] = preg_replace('/\\/page\\/([0-9]+)(.*)/', '/page/'.$data['num'].'$2', $this->core->requestUri);
							}
						}else{
							$pattern = preg_replace('/\//', '\/', $this->catalogSettings['catalog_page_url']).'(.*)';
							foreach( $itemsArray['navArray'] as &$data )
							{
								$data['href'] = preg_replace('/'.$pattern.'/', $this->catalogSettings['catalog_page_url'].'page/'.$data['num'].'/$1', $this->core->requestUri);
							}	
						}
				
					}

					$this->template->assign('navArray', $itemsArray['navArray']);
				}
				
				$this->template->assign('countonpage', $itemsArray['allCount']);
				$this->template->assign('items', $itemsArray['itemsArray']);	
			}
			
			#compile categories template
			if( !empty( $this->catList ) ) $this->template->assign('categories', $this->catList);


			#load full categories list
			$this->loadFullCatList();

			#assign categories list
			$this->template->assign('fullCatList', $this->catList);

			#get all brands
			$allBrandsArray = $this->getFullBrands();

			#assign all brands list
			if( !empty($allBrandsArray) )$this->template->assign('fullBrandsList', $allBrandsArray);

			#compile brands template
			if( !empty( $brandsArray ) )
			{
				$this->template->assign('brandsArray', $brandsArray);
				$this->template->assign('brandsCount', count($brandsArray));
			}

			#comple types template
			if( !empty( $typesArray ) )
			{
				#assign catalog types
				$this->template->assign('typesArray', $typesArray);
				$this->template->assign('typesCount', count($typesArray));
			}

			if( isset($_COOKIE['catalog_vm']) ) $this->template->assign('viewMode', $_COOKIE['catalog_vm']);

			#assign catalog current category id
			$this->catalogSettings['current_cat_id'] = $this->currentCatId;

			#assign catalog current item id
			$this->catalogSettings['current_item_id'] = $this->currentItemId;

			#assign catalog settings
			$this->template->assign('catalog', $this->catalogSettings);

			#assign catalog filter
			if( !empty($this->filterArray) )$this->template->assign('filterArray', $this->filterArray);

			#assign attributes
			$this->template->assign('attributesArray', $attributesArray);

		}	
		
		public function brandsPage()
		{
			
			if( !$this->core->editedFromAdmin )
			{
				$index = array_search( $this->marker['brandsMarker'], $this->core->requestUriArray);
	
				if( !isset( $this->core->requestUriArray[ $index+1 ] ) || $this->core->requestUriArray[ $index+1 ] != "" )
				{
					$this->core->error404();
					return;	
				}
			}


			$res = $this->db->query("
				SELECT 
					`b`.`id`,
					`b`.`name`,
					`b`.`link`,
					`b`.`brand_logo`
				FROM
					`plug_catalog_brands` AS `b`
				ORDER BY
					`b`.`name`
			");
			$resArray = $res->fetchAll();
			if( !empty( $resArray ) )
			{
				$brandsArray = array();
				
				foreach( $resArray as &$brand )
				{
					#get first letter
					$letter = mb_substr($brand['name'], 0, 1,"UTF-8");
					
					#set full url
					$brand['url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$brand['link'];

					#compile brand logo
					if( $brand['brand_logo'] != "" ) $brand['brand_logo'] = $this->brandImagesPath.$this->getFolderById( $brand['id']).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$brand['brand_logo'];

					#set data
					$brandsArray[ $letter ][ $brand['id'] ] = $brand;
				}

				$this->template->assign('brandsArray', $brandsArray);
			}


			$this->core->pageBufferArray['title'] = 'Производители';
			$this->core->pageBufferArray['description'] = '';
			$this->core->pageBufferArray['keywords'] = '';
			$this->core->pageBufferArray['h1'] = '';
			$this->core->pageBufferArray['title'] = ( $this->core->pageBufferArray['invert_title_prefix']=='0' ) ? $this->core->pageBufferArray['global_meta_title_prefix'].$this->core->pageBufferArray['title'] : $this->core->pageBufferArray['title'].$this->core->pageBufferArray['global_meta_title_prefix'];

			#assign catalog current category id
			$this->catalogSettings['current_cat_id'] = $this->currentCatId;

			#assign catalog current item id
			$this->catalogSettings['current_item_id'] = $this->currentItemId;

			#compile breadcrumbs
			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandsMarker'],
				'name'	=>	'Производители'
			);

			#set tpl page
			$this->tpl = 'brands';

		}
		
		public function comparePage()
		{
			
			if( !$this->core->editedFromAdmin ){
				$index = array_search( $this->marker['compareMarker'], $this->core->requestUriArray);
	
				if( !isset( $this->core->requestUriArray[ $index+1 ] ) || $this->core->requestUriArray[ $index+1 ] != "" )
				{
					$this->core->error404();
					return;	
				}
			}

			$this->core->pageBufferArray['title'] = 'Сравнение товаров';
			$this->core->pageBufferArray['description'] = '';
			$this->core->pageBufferArray['keywords'] = '';
			$this->core->pageBufferArray['h1'] = '';
			$this->core->pageBufferArray['title'] = ( $this->core->pageBufferArray['invert_title_prefix']=='0' ) ? $this->core->pageBufferArray['global_meta_title_prefix'].$this->core->pageBufferArray['title'] : $this->core->pageBufferArray['title'].$this->core->pageBufferArray['global_meta_title_prefix'];

			#set tpl page
			$this->tpl = 'compare';

			#check the cookie
			if( !isset( $_COOKIE['catalog_compare'] ) )
			{
				$this->template->assign('empty', 'empty');
				return;
			}

			#compile compare array from cookie
			$compareArray = json_decode( $_COOKIE['catalog_compare'], true );

			#check the compare array
			if( empty( $compareArray ) )
			{
				$this->template->assign('empty', 'empty');
				return;
			}

			#set vars
			$compareItemIds = array();

			#compile items ids array
			foreach( $compareArray as $id => $count)
			{
				$compareItemIds[] = $id;
			}

			#get items
			$compareItems = $this->getItems(0, 100, array( $this->marker['filterItemMarker'] =>$compareItemIds));

			#reset vars
			$compareArray = array();

			#compile compare array
			foreach(  $compareItems['itemsArray'] as $itemId => $itemData )
			{
				$compareArray[ $itemData['type_id'] ]['items'][ $itemData['id'] ] = $itemData;
				if(isset($itemData['attributes'])) $compareArray[ $itemData['type_id'] ]['attr_list'] = $itemData['attributes'];
			}

			#get types
			$typesArray = $this->getTypes();
			foreach( $typesArray as $type )
			{
				if( isset( $compareArray[ $type['id'] ] ) ) $compareArray[ $type['id'] ]['type_name'] = $type['name'];
			}

			$this->template->assign('catalog', $this->catalogSettings);

			$this->template->assign('compareArray', $compareArray);

		}	

		public function cartPage()
		{
			
			if( !$this->core->editedFromAdmin )
			{
				$index = array_search( $this->marker['cartMarker'], $this->core->requestUriArray);
	
				if( !isset( $this->core->requestUriArray[ $index+1 ] ) || $this->core->requestUriArray[ $index+1 ] != "" )
				{
					$this->core->error404();
					return;	
				}
			}

			$this->core->pageBufferArray['title'] = 'Корзина';
			$this->core->pageBufferArray['description'] = '';
			$this->core->pageBufferArray['keywords'] = '';
			$this->core->pageBufferArray['h1'] = '';
			$this->core->pageBufferArray['title'] = ( $this->core->pageBufferArray['invert_title_prefix']=='0' ) ? $this->core->pageBufferArray['global_meta_title_prefix'].$this->core->pageBufferArray['title'] : $this->core->pageBufferArray['title'].$this->core->pageBufferArray['global_meta_title_prefix'];


			#if cart is empty
			if( !isset( $_COOKIE['catalog_cart'] ))
			{
				#set empty tpl marker
				$this->template->assign('empty', 'empty');
				
				#set tpl page
				$this->tpl = 'cart';
				return;
			}

			#set cart array from cookies data
			$cartArray = json_decode( $_COOKIE['catalog_cart'], true );
			
			#if cart is empty
			if( empty( $cartArray ))
			{

				#set empty tpl marker
				$this->template->assign('empty', 'empty');
				
				#set tpl page
				$this->tpl = 'cart';
				return;
			}

			#define ids array
			$itemsIds = array();
			
			#compile ids array
			foreach( $cartArray as $id => $count )
			{
				$itemsIds[] = $id;
			}

			#get items
			$itemsArray = $this->getItems(0, 100, array( $this->marker['filterItemMarker'] =>$itemsIds));

			#set items count in cart
			foreach( $cartArray as $id => $count )
			{
				if(isset($itemsArray['itemsArray'][ $id ]))
				{

					$itemsArray['itemsArray'][ $id ]['count'] = $count;
					$itemsArray['itemsArray'][ $id ]['cost'] = number_format( $count * $itemsArray['itemsArray'][ $id ]['price'], 2, '.', ' ');
					$itemsArray['itemsArray'][ $id ]['price'] = number_format( $itemsArray['itemsArray'][ $id ]['price'], 2, '.', ' ');
				}

			}

			$this->template->assign('catalog', $this->catalogSettings);

			$date = strtotime("+2 day");

			$this->template->assign('deliveryDate', date("d.m.Y", $date));

			#assign template items data
			$this->template->assign('itemsArray', $itemsArray);

			$this->template->clear_compiled_tpl("cartPage.tpl");
			#set tpl page
			$this->tpl = 'cart';

		}

		#return array of items format	 array(
		#									'count'			=>	all items count
		#									'navArray'		=>	peganation array
		#									'itemsArray'	=>	items array
		#								)
		#@param currentPage - current page (integer)
		#@param countOnPage - how many items view on one page (integer)
		#@param itemsFilterArray - filter format	array(
		#												'b'		=>	array( ID, ID, ID, ..., ID ),
		#												'c'	=>	array( ID, ID, ID, ..., ID )
		#											) (array)
		public function getItems( $currentPage=0, $countOnPage=2, $itemsFilterArray = array() )
		{
			
			if(empty($this->catalogSettings)) $this->loadSettings();

			#set return array
			$retArray = array();

			#set filter default string
			$filterMainParamsArray = array('1=1');

			#set default price string
			$priceFilterStr = "ASC";
			
			#prepare itemsFilterArray
			if( !empty( $itemsFilterArray ) )
			{
				
				#item id
				if( isset( $itemsFilterArray[ $this->marker['filterItemMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterItemMarker'] ] ) )
				{
					
					$filterMainParamsArray[] = "`i`.`id` IN (".implode(',', $itemsFilterArray[ $this->marker['filterItemMarker'] ]).")";
				}	

				#type id
				if( isset( $itemsFilterArray[ $this->marker['filterTypeMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterTypeMarker'] ] ) )
				{
					$filterMainParamsArray[] = "`i`.`type_id` IN (".implode(',', $itemsFilterArray[ $this->marker['filterTypeMarker'] ]).")";
				}	

				#brands
				if( isset( $itemsFilterArray[ $this->marker['filterBrandMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterBrandMarker'] ] ) )
				{
					$filterMainParamsArray[] = "`i`.`brand_id` IN (".implode(',', $itemsFilterArray[ $this->marker['filterBrandMarker'] ]).")";
				}	

				#categories
				if( isset( $itemsFilterArray[ $this->marker['filterCatMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterCatMarker'] ]  ) )
				{

					$filterMainParamsArray[] = "`i`.`cat_id` IN (".implode(',', $itemsFilterArray[ $this->marker['filterCatMarker'] ]).")";
				}

				#price
				if( isset( $itemsFilterArray[ $this->marker['filterPriceMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterPriceMarker'] ] ) )
				{
					if(  $itemsFilterArray[ $this->marker['filterPriceMarker'] ][0] == '1' ) $priceFilterStr = "DESC";
				}	
				
				#price range
				if( isset( $itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ] ) )
				{
					if(
						!isset($itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][0]) 
						||
						!isset($itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][1]) 
						|| 
						!is_numeric($itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][0]) 
						||
						!is_numeric($itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][1]) 
						||
						$itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][0] > $itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][1]
					) 
					{
						$this->core->error404();
					}
						
					$filterMainParamsArray[] = "`i`.`price`>= '".(int)$itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][0]."' AND `i`.`price` <= '".(int)$itemsFilterArray[ $this->marker['filterPriceRangeMarker'] ][1]."'";
				}

				#is_new
 				//if( isset( $itemsFilterArray[ $this->marker['filterIsNewMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterIsNewMarker'] ] ) )
				//{
				//	$isNewFilterStr = "`i`.`is_new` = '".intval( $itemsFilterArray[ $this->marker['filterIsNewMarker'] ][0] )."'";
				//}	
				//
				//#is_sale
				//if( isset( $itemsFilterArray[ $this->marker['filterIsSaleMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterIsSaleMarker'] ] ) )
				//{
				//	$isSaleFilterStr = "`i`.`is_sale` = '".intval( $itemsFilterArray[ $this->marker['filterIsSaleMarker'] ][0] )."'";
				//}	
				//
				//#is_best
				//if( isset( $itemsFilterArray[ $this->marker['filterIsBestMarker'] ] ) && !empty( $itemsFilterArray[ $this->marker['filterIsBestMarker'] ] ) )
				//{
				//	$isBestFilterStr = "`i`.`is_best` = '".intval( $itemsFilterArray[ $this->marker['filterIsBestMarker'] ][0] )."'";
				//} 
			}
			
			
			
			#items count on page
			$count = $countOnPage;
			
			#get total lines in DB
			$res = $this->db->query("
				SELECT count(*) FROM `plug_catalog_items` AS `i`	
				WHERE
					`i`.`disabled`='0'
				AND
					`i`.`hide_in_list`='0'
				AND
					`i`.`cat_id`<>'0'
				AND 
					".implode(' AND ', $filterMainParamsArray)."
				ORDER BY
					`i`.`price` ".$priceFilterStr."
			");
	
		
			if( $res === false )
			{
				$this->core->error404();
				return;
			}
			
			$resArray = $res->fetch();
			
			#total lines in DB
			$total = intval( ($resArray[0] - 1) / $count) + 1; 
			
			#set current page
			$page = intval($currentPage); 
			
			if(empty($page) or $page < 0) $page = 1;

			#if page not exists
			if($page > $total || $page == 0) 
			{
				$this->core->error404();
				die();
			}
			
			#set start line
			$start = $page * $count - $count;

			$res = $this->db->prepare("
				SELECT 
					`i`.`id`,
					`i`.`external_id`,
					`i`.`articul`,
					`i`.`name`,
					`i`.`item_quick_desc`,
					`i`.`hide_in_list`,
					`i`.`discount`,
					`i`.`disabled`,
					`i`.`link`,
					`i`.`cat_id`,
					`i`.`type_id`,
					`i`.`in_stock`,
					`i`.`is_sale`,
					`i`.`is_new`,
					`i`.`is_best`,
					`i`.`is_markdown`,
					`i`.`count`,
					`i`.`item_logo`,
					`i`.`price`,
					`i`.`old_price`,
					`i`.`create_date`,
					`i`.`update_date`,
					`i`.`meta_title`,
					`b`.`name` AS `brand_name`,
					`b`.`brand_logo`,
					`b`.`link` AS `brand_link`,
					`c`.`name` AS `category_name`,
					`c`.`url` AS `category_url`
				FROM 
					`plug_catalog_items` AS `i`
				LEFT JOIN
					`plug_catalog_brands` AS `b`
				ON
					`b`.`id` = `i`.`brand_id`
				LEFT JOIN
					`plug_catalog_cat` AS `c`
				ON
					`c`.`id` = `i`.`cat_id`
				WHERE
					`i`.`disabled`=:disabled
				AND
					`i`.`hide_in_list`=:hide_in_list
				AND
					`i`.`cat_id`<>:cat_id
				AND 
					".implode(' AND ', $filterMainParamsArray)."

				ORDER BY
					`i`.`price` ".$priceFilterStr."
				LIMIT
					:start,:num
			");

			$res->bindValue(':disabled', '0', PDO::PARAM_STR);
			$res->bindValue(':hide_in_list', '0', PDO::PARAM_STR);
			$res->bindValue(':cat_id', '0', PDO::PARAM_STR);
			$res->bindValue(':start', $start, PDO::PARAM_INT);
			$res->bindValue(':num', $count, PDO::PARAM_INT);
			$res->execute();

			$resArray = $res->fetchAll();
			
			#array of items id
			$itemsIdArray = array();
			
			foreach( $resArray as &$dataArray)
			{
				#set full URL to item
				$dataArray['full_item_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$dataArray['category_url'].$this->marker['itemMarker'].'/'.$dataArray['link'];
				
				#set full URL to category
				$dataArray['full_cat_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$dataArray['category_url'];
								
				#set full URL to brand
				$dataArray['full_brand_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$dataArray['brand_link'];
				
				#set brand logo url
				if( $dataArray['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById( $dataArray['id'] ).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'])) $dataArray['brand_full_logo_src'] = $this->brandImagesPath.$this->getFolderById( $dataArray['id']).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'];


				#set image to item if image exist
				if( $dataArray['item_logo']!='' ) $dataArray['item_logo'] = $this->itemImagesPath.$this->getFolderById( $dataArray['id'] ).'/'.$this->itemLogosSizer[1][0].'x'.$this->itemLogosSizer[1][1].'/'.$dataArray['item_logo'];
				

				#set QR code
				$dataArray['qrcode'] =$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['qrCodeMarker'].'/'.$dataArray['id'];


				#items identificators array
				$itemsIdArray[] = $dataArray['id'];
			}

			#declare items array
			$itemsArray = array();
			
			#restruct items array to format [ id ] = array()
			foreach( $resArray as $DataArray )
			{
				$itemsArray[ $DataArray['id'] ] =  $DataArray;
			}
			
			#get items attributes
			$res = $this->db->query("
				SELECT 
					`item_attr_vals`.`item_id`,
					`item_attr_vals`.`attr_id`,
					`item_attr_vals`.`value_id`,
					`types_vals`.`type_id`,
					`types_vals`.`value` AS `attribute_value`,
					`types_attr`.`name` AS `attribute_name`,
					`types_attr`.`units` AS `attribute_units`,
					`types_attr`.`in_list`
				FROM 
					`plug_catalog_item_attr_vals` AS `item_attr_vals` 
				LEFT JOIN 
					`plug_catalog_types_vals` AS `types_vals`
				ON
					`types_vals`.`id`=`item_attr_vals`.`value_id`
				LEFT JOIN 
					`plug_catalog_types_attr` AS `types_attr`
				ON 
					`types_attr`.`id`=`item_attr_vals`.`attr_id` 
				WHERE 
					`item_attr_vals`.`item_id` IN(".implode(',', $itemsIdArray).")
				ORDER BY
					`types_attr`.`name`
			");

			if( $res!==false ) $resArray = $res->fetchAll();
			
			if( !empty($resArray) )
			{
				
				#if not exists attributes filter
				foreach( $resArray as $dataArray )
				{
					$itemsArray[ $dataArray['item_id'] ]['attributes'][ $dataArray['attr_id'] ] = $dataArray;
				}

			}
				
			#attributes filter
			if( isset( $itemsFilterArray[ $this->marker['filterAttrMarker'] ] ) )
			{
				foreach( $itemsArray as $itemId => $data )
				{
					foreach( $itemsFilterArray[ $this->marker['filterAttrMarker'] ] as $attrId => $valueId )
					{	
						if( !isset( $data['attributes'][ (int)$attrId ] ) || $data['attributes'][ (int)$attrId ]['value_id']!=(int)$valueId)
						{
							$total--;
							unset( $itemsArray[ $itemId ] );
						}
					}
				}
			}




			#get navArray
			$navArray = array();
			
			$toLeft = 5;
			$toRight = 5;
			
			#set pagers
			for( $i=1;$i<($total+1);$i++ ) 
			{
				if(  $page - $toLeft < $i && $page + $toRight > $i ) 
				{
					$navArray[] = array( 'num'=> $i, 'title'=>'На страницу '.$i, 'text'=>$i,'class'=>( $i==$page ) ? 'act' : '', 'href'=>$this->catalogSettings['catalog_page_url'].'page/'.$i.'/');
				}
			}
			
			#set arrows
			if( $page!=1 ) array_unshift( $navArray, array(	
				'num'=> 1, 
				'title'=>'К первой странице', 
				'text'=> '&laquo;', 
				'class'=> 'start',
				'href'=>$this->catalogSettings['catalog_page_url'].'page/1/' 
				), array( 
					'num'=> $page-1, 
					'title'=>'Назад', 
					'text'=> '&larr;', 
					'class'=> 'back', 
					'href'=>$this->catalogSettings['catalog_page_url'].'page/'.($page-1).'/' 
					) 
			);
			
			if( $page!=$total ) array_push( $navArray,  array(
				'num'=> $page+1,
				'title'=>'Вперед',
				'text'=> '&rarr;',
				'class'=> 'next',
				'href'=>$this->catalogSettings['catalog_page_url'].'page/'.($page+1).'/'
				),array(
					'num'=> $total, 
					'title'=>'К последней странице', 
					'text'=> '&raquo;',
					'class'=> 'end', 
					'href'=>$this->catalogSettings['catalog_page_url'].'page/'.$total.'/'
					) 
			);
	
			#compile return data about items count
			$retArray['allCount'] = 	$count;
			
			#compile return data about peganation if is exist
			if( count( $navArray )>1 ) $retArray['navArray'] = 	$navArray;	
			
			#compile return data about items
			$retArray['itemsArray'] = $itemsArray;	

			#return all data as array
			return $retArray;	
		}
		
		#return all items attributes
		public function getAttributes()
		{
			#get attributes
			$res = $this->db->prepare("
				SELECT 
					`a`.`id` AS `attr_id`,
					`a`.`type_id`,
					`a`.`name` AS `attr_name`,
					`a`.`units` AS `attr_units`
				FROM
					`plug_catalog_types_attr` AS `a`
				WHERE
					`a`.`in_filter`=:in_filter
			");
			$res->bindValue(':in_filter', '1', PDO::PARAM_STR);
			$res->execute();
			$resArray = $res->fetchAll();
			
			if( empty( $resArray ) ) return array();

			$attrArray = array();
			$attrIdsArray = array();
			foreach( $resArray as $attrData )
			{
				$attrArray[ $attrData['type_id'] ][ $attrData['attr_id'] ]['name'] = $attrData['attr_name'];
				$attrArray[ $attrData['type_id'] ][ $attrData['attr_id'] ]['units'] = $attrData['attr_units'];
				$attrIdsArray[] = $attrData['attr_id'];
			}

			$res = $this->db->query("
				SELECT
					`v`.`id` AS `value_id`,
					`v`.`type_id`,
					`v`.`attr_id` AS `value_attr_id`,
					`v`.`value`
				FROM
					`plug_catalog_types_vals` AS `v`
				WHERE
					`v`.`attr_id` IN (".implode(',', $attrIdsArray).")
			");
			$AttrvaluesArray = $res->fetchAll();
			
			if( empty( $AttrvaluesArray ) ) return array();
			
			foreach($AttrvaluesArray as $dataValue)
			{
				$attrArray[ $dataValue['type_id'] ][ $dataValue['value_attr_id'] ]['values'][ $dataValue['value_id'] ] = $dataValue['value'];
			}
			
			return $attrArray;
		}

		#load category list and place to $this->catList array
		public function loadCatList( $categoryPid = 0 )
		{
			#load data
			$res = $this->db->prepare("
				SELECT 
					`c`.`id`, 
					`c`.`pid`, 
					`c`.`cat_logo`, 
					`c`.`name`, 
					`c`.`url`, 
					`c`.`cat_range`,
					`c`.`hide`, 
					`c`.`off` 
				FROM 
					`plug_catalog_cat` AS `c`
				WHERE
					`c`.`hide`=:hide
				AND
					`c`.`off`=:off
				AND
					`c`.`pid`=:pid
				ORDER BY 
					`c`.`pid` DESC ,`c`.`cat_range`
			");

			$res->bindValue(':hide', '0', PDO::PARAM_STR);
			$res->bindValue(':off', '0', PDO::PARAM_STR);
			$res->bindValue(':pid', $categoryPid, PDO::PARAM_STR);
			$res->execute();			
			
			if(!$res) return;

			$tableArray = $res->fetchAll();
			
			if( empty($tableArray) )
			{
				return;
			}
			
			#compile tree array
			foreach ($tableArray as $row)
			{	
	
				$tree[$row['id']] = array( 
					'id'=>$row['id'],
					'pid'=>$row['pid'],
					'name'=>$row['name'], 
					'cat_logo'=>$row['cat_logo'], 
					'url'=>$row['url'],
					'full_url'=> $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$row['url'],
					'cat_range'=>$row['cat_range'],
					'hide'=>$row['hide'],
					'off'=>$row['off']
				);
				
				if( $tree[$row['id']]['cat_logo']!="" && file_exists($this->root.$this->categoryImagesPath.$this->getFolderById( $tree[$row['id']]['id'] ).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$tree[$row['id']]['cat_logo'])) $tree[$row['id']]['full_logo_src'] = $this->categoryImagesPath.$this->getFolderById( $tree[$row['id']]['id']).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$tree[$row['id']]['cat_logo'];


			} 
			
			#paste childs items to parent
			foreach($tree as $id => $arr)
			{
				if( $arr['pid']!=0 )
				{
					#paste childs items to parent
					//$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
					//unset( $tree[ $id ] );
				}
				if ($arr['pid']==0) continue;
			}
			
			$this->catList = $tree;

		}


		#load category list and place to $this->catList array
		public function loadFullCatList()
		{
			#load data
			$res = $this->db->prepare("
				SELECT 
					`c`.`id`, 
					`c`.`pid`, 
					`c`.`cat_logo`, 
					`c`.`name`, 
					`c`.`url`, 
					`c`.`cat_range`,
					`c`.`hide`, 
					`c`.`off` 
				FROM 
					`plug_catalog_cat` AS `c`
				WHERE
					`c`.`hide`=:hide
				AND
					`c`.`off`=:off
				ORDER BY 
					`c`.`pid` DESC ,`c`.`cat_range`
			");

			$res->bindValue(':hide', '0', PDO::PARAM_STR);
			$res->bindValue(':off', '0', PDO::PARAM_STR);
			$res->execute();			
			
			if(!$res) return;

			$tableArray = $res->fetchAll();
			
			if( empty($tableArray) )
			{
				return;
			}
			
			#compile tree array
			foreach ($tableArray as $row)
			{	
	
				$tree[$row['id']] = array( 
					'id'=>$row['id'],
					'pid'=>$row['pid'],
					'name'=>$row['name'], 
					'cat_logo'=>$row['cat_logo'], 
					'url'=>$row['url'],
					'full_url'=> $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$row['url'],
					'cat_range'=>$row['cat_range'],
					'hide'=>$row['hide'],
					'off'=>$row['off']
				);
				
				if( $tree[$row['id']]['cat_logo']!="" && file_exists($this->root.$this->categoryImagesPath.$this->getFolderById( $tree[$row['id']]['id'] ).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$tree[$row['id']]['cat_logo'])) $tree[$row['id']]['full_logo_src'] = $this->categoryImagesPath.$this->getFolderById( $tree[$row['id']]['id']).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$tree[$row['id']]['cat_logo'];


			} 
			
			#paste childs items to parent
			foreach($tree as $id => $arr)
			{
				if( $arr['pid']!=0 )
				{
					#paste childs items to parent
					$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
					unset( $tree[ $id ] );
				}
				if ($arr['pid']==0) continue;
			}
			
			$this->catList = $tree;

		}
		
		public function getCategories()
		{
			$res = $this->db->prepare("
				SELECT 
					`c`.`name`,
					`c`.`url`
				FROM 
					`plug_catalog_cat` AS `c`
				WHERE
					`c`.`hide`=:hide
				AND
					`c`.`off`=:off
			");
			$res->bindValue(':hide', '0', PDO::PARAM_STR);
			$res->bindValue(':off', '0', PDO::PARAM_STR);
			$res->execute();
			
			$resArray = $res->fetchAll();
			if( !empty($resArray) )
			{
				foreach( $resArray as &$dataArray )
				{
					$dataArray['full_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$dataArray['url'];
				}
				return $resArray;
				
			}else{
				return array();
			}			
		}

		function getFullBrands()
		{

			$res = $this->db->prepare("
				SELECT 
					`b`.`id`,
					`b`.`name`,
					`b`.`brand_logo`,
					`b`.`link`
				FROM 
					`plug_catalog_brands` AS `b`
				WHERE
					`b`.`hide_in_list`=:hide_in_list
				AND
					`b`.`disabled`=:disabled
				ORDER BY
					`b`.`brand_range`
			");
			$res->bindValue(':hide_in_list', '0', PDO::PARAM_STR);
			$res->bindValue(':disabled', '0', PDO::PARAM_STR);
			$res->execute();
			
			$resArray = $res->fetchAll();

			if( !empty($resArray) )
			{
				foreach( $resArray as &$dataArray )
				{
					#compale logo url
					if( $dataArray['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById( $dataArray['id'] ).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'])) $dataArray['full_logo_src'] = $this->brandImagesPath.$this->getFolderById( $dataArray['id']).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'];
					$dataArray['full_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$dataArray['link'];
				}
				return $resArray;
			}else{
				return array();
			}			
		}

		
		#get catalog brands
		function getBrands( $categories = array() )
		{

			if( !empty( $categories ) )
			{
				$res = $this->db->prepare("
					SELECT 
						`b`.`id`,
						`b`.`name`,
						`b`.`brand_logo`,
						`b`.`link`
					FROM 
						`plug_catalog_items` AS `i`
					LEFT JOIN 
						`plug_catalog_brands` AS `b` 
					ON 
						`i`.`brand_id`=`b`.`id`
					WHERE
						`i`.`cat_id` IN(".implode(',', $categories).")
					AND
						`b`.`hide_in_list`=:hide_in_list
					AND
						`b`.`disabled`=:disabled
					GROUP BY
						`i`.`brand_id` 
					ORDER BY
						`b`.`brand_range`
				");
				$res->bindValue(':hide_in_list', '0', PDO::PARAM_STR);
				$res->bindValue(':disabled', '0', PDO::PARAM_STR);
				$res->execute();
			}else{

				$res = $this->db->prepare("
					SELECT 
						`b`.`id`,
						`b`.`name`,
						`b`.`brand_logo`,
						`b`.`link`
					FROM 
						`plug_catalog_brands` AS `b`
					WHERE
						`b`.`hide_in_list`=:hide_in_list
					AND
						`b`.`disabled`=:disabled
					ORDER BY
						`b`.`brand_range`
				");
				$res->bindValue(':hide_in_list', '0', PDO::PARAM_STR);
				$res->bindValue(':disabled', '0', PDO::PARAM_STR);
				$res->execute();
			}
			$resArray = $res->fetchAll();

			if( !empty($resArray) )
			{
				foreach( $resArray as &$dataArray )
				{
					#compale logo url
					if( $dataArray['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById( $dataArray['id'] ).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'])) $dataArray['full_logo_src'] = $this->brandImagesPath.$this->getFolderById( $dataArray['id']).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$dataArray['brand_logo'];
					$dataArray['full_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$dataArray['link'];
				}
				return $resArray;
			}else{
				return array();
			}			
		}
		
		public function getTypes()
		{
			$res = $this->db->prepare("
				SELECT 
					`t`.`id`,
					`t`.`name`
				FROM 
					`plug_catalog_types` AS `t`
				ORDER BY
					`t`.`name`
			");
			$res->execute();
			
			$resArray = $res->fetchAll();
			if( !empty($resArray) )
			{
				return $resArray;
			}else{
				return array();
			}		
		}

		#compile category
		public function loadCategory()
		{
			#get category uri
			$startIndex = array_search($this->marker['catMarker'], $this->core->requestUriArray) + 1;
			$endIndex = count($this->core->requestUriArray) - 2;
			
			$this->currentCatUri = '';
			
			foreach( $this->core->requestUriArray as $key => $value )
			{
				if( $key<$startIndex ) continue;
				$this->currentCatUri .= '/'.$value;
			}

			if( $this->core->editedFromAdmin )
			{
				$this->currentCatUri .= '/';
				$this->currentCatUri = preg_replace('/\/{1,}$/', '/', $this->currentCatUri);
			}


			#check category exist and load data
			$res = $this->db->prepare("SELECT * FROM `plug_catalog_cat` WHERE `url`=:url LIMIT 1");
			$res->bindValue(':url', $this->currentCatUri, PDO::PARAM_STR);
			$res->execute();
			$resArray = $res->fetch();
			if( !$resArray )
			{
				$this->core->error404();
				return;
			}
			

			#compile breadcrumbs
			$tmp = $resArray['pid'];
			while( $tmp!=0 )
			{
				$res = $this->db->prepare("SELECT `id`, `pid`, `name`, `url` FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $tmp);
				$res->execute();
				$tmpArray = $res->fetch();
				if( !$resArray ) break;
				$this->core->pageBufferArray['breadcrumbs'][] = array(
					'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$tmpArray['url'],
					'name'	=>	$tmpArray['name']
				);
				$tmp = $tmpArray['pid'];
			}
			if( isset($tmp) ) unset($tmp);
			if( isset($tmpArray) ) unset($tmpArray);

			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$resArray['url'],
				'name'	=>	$resArray['name']
			);
				
			#set current category ID
			$this->currentCatId = $resArray['id'];
			$this->template->assign('category', $resArray);
			$this->filterArray[ $this->marker['filterCatMarker'] ] = array( $this->currentCatId );
			$this->mainPage();
			return;
		}		
		
		#load item comments
		public function loadItemComments( $itemId, $dateFormat="d F Y" )
		{
			#load data
			$query = "SELECT * FROM `plug_catalog_comments` WHERE `item_id`=:item_id ORDER BY `pid` DESC, `comment_date`, `comment_time`";
			$res = $this->db->prepare( $query );
			$res->bindValue(':item_id', $itemId, PDO::PARAM_STR);
			$res->execute();
			
			$tableArray = $res->fetchAll();
	
			#compile tree array
 			foreach ($tableArray as $row)
			{	
				$tree[$row['id']] = array( 
					'id'			=>	$row['id'],
					'pid'			=>	$row['pid'],
					'user_name'		=>	$row['user_name'], 
					'comment_text'	=>	($row['remove']=='0' && $row['hide']=='0') ? $this->core->bbcodeToHtml( $row['comment_text'] ) : '', 
					'comment_date'	=>	$this->core->dateToRus( "j F Y", strtotime( $row['comment_date'] ) ),
					'comment_time'	=>	substr($row['comment_time'], 0, -3),
					'remove'		=>	$row['remove'],
					'hide'			=>	$row['hide']
				);
			} 
			
			if( !isset($tree) ) return;

			#paste childs items to parent
			foreach($tree as $id => $arr)
			{
				if($arr['hide']!='0') continue;
				if( $arr['pid']!=0 )
				{
					$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
					unset( $tree[ $id ] );
				}
			}
			

			$this->itemCommentsArray = $tree;

		}

		#compile item
		public function loadItem()
		{
			#get item uri
			$startIndex = array_search($this->marker['itemMarker'], $this->core->requestUriArray) + 1;
			$endIndex = count($this->core->requestUriArray) - 2;
			
			$this->currentItemUri = '';
			
			foreach( $this->core->requestUriArray as $key => $value )
			{
				if( $key<$startIndex ) continue;
				$this->currentItemUri .= '/'.$value;
			}
			$this->currentItemUri = preg_replace('/\//', '', $this->currentItemUri);
			
			
			#get category uri
			$startIndex = array_search($this->marker['catMarker'], $this->core->requestUriArray) + 1;
			$endIndex = count($this->core->requestUriArray) - 2;
			
			$this->currentCatUri = '';
			
			foreach( $this->core->requestUriArray as $key => $value )
			{
				if( $key<$startIndex ) continue;
				$this->currentCatUri .= '/'.$value;
			}					
			
			if( $this->core->editedFromAdmin )
			{
				$this->currentCatUri .= '/';
				$this->currentCatUri = preg_replace('/\/{1,}$/', '/', $this->currentCatUri);
			}

			$this->currentCatUri = str_replace($this->marker['itemMarker']."/".$this->currentItemUri."/", "", $this->currentCatUri);
			
			#get category id and category name
			$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_cat` WHERE `url`=:url LIMIT 1");
			$res->bindValue(':url', $this->currentCatUri, PDO::PARAM_STR);
			$res->execute();
			$resArray = $res->fetch();
			if( !$resArray )
			{
				$this->core->error404();
				return;
			}
			$this->currentCatId = $resArray['id'];
			$caregoryName = $resArray['name'];
			

			#check item to exist and load data
			$res = $this->db->prepare("
				SELECT 
					`i`.*,
					`b`.`name` AS `brand_name`,
					`b`.`link` AS `brand_link`,
					`b`.`brand_logo`
				FROM 
					`plug_catalog_items` AS `i` 
				LEFT JOIN
					`plug_catalog_brands` AS `b`
				ON 
					`b`.`id`=`i`.`brand_id`
				WHERE 
					`i`.`link`=:link 
				AND 
					`i`.`cat_id`=:cat_id
				AND 
					`i`.`disabled`=:disabled
				LIMIT 1
			");
			$res->bindValue(':link', $this->currentItemUri, PDO::PARAM_STR);
			$res->bindValue(':cat_id', $this->currentCatId, PDO::PARAM_INT);
			$res->bindValue(':disabled', '0', PDO::PARAM_STR);
			$res->execute();
			$itemArray = $res->fetch();
			if( !$itemArray )
			{
				
				$this->core->error404();
				return;
			}

			#get item attributes
			$res = $this->db->prepare("
				SELECT 
					`item_attr_vals`.`item_id`,
					`item_attr_vals`.`attr_id`,
					`item_attr_vals`.`value_id`,
					`types_vals`.`type_id`,
					`types_vals`.`value` AS `attribute_value`,
					`types_attr`.`name` AS `attribute_name`,
					`types_attr`.`units` AS `attribute_units`,
					`types_attr`.`in_list`
				FROM 
					`plug_catalog_item_attr_vals` AS `item_attr_vals` 
				LEFT JOIN 
					`plug_catalog_types_vals` AS `types_vals`
				ON
					`types_vals`.`id`=`item_attr_vals`.`value_id`
				LEFT JOIN 
					`plug_catalog_types_attr` AS `types_attr`
				ON 
					`types_attr`.`id`=`item_attr_vals`.`attr_id` 
				WHERE 
					`item_attr_vals`.`item_id` =:item_id
			");
			$res->bindValue(':item_id', $itemArray['id'], PDO::PARAM_INT);
			$res->execute();
			$attrArray = array();
			if( $res!==false ) $attrArray = $res->fetchAll();
	

			$itemArray['qrcode'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['qrCodeMarker'].'/'.$itemArray['id'];

			
			if( $itemArray['item_logo']!="" ) $itemArray['full_logo_src'] = $this->itemImagesPath.$this->getFolderById( $itemArray['id'] ).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$itemArray['item_logo'];

			#get other photos
			$res = $this->db->prepare("
				SELECT
					`img`.`filename`
				FROM
					`plug_catalog_img` AS `img`
				WHERE
					`img`.`item_id`=:item_id
				ORDER BY
					`img`.`img_range`
			");
			$res->bindValue(':item_id', $itemArray['id'], PDO::PARAM_INT);
			$res->execute();
			$imgArray = $res->fetchAll();
			if( !empty( $imgArray ) )
			{
				foreach( $imgArray as &$img )
				{
					$img['filename'] = $this->itemImagesOtherPath.$this->getFolderById( $itemArray['id'] ).'/'.$img['filename'];
				}
				$this->template->assign('itemImages', $imgArray);
			}
			

			#get analogs
			$res = $this->db->prepare("
				SELECT 
					`a`.`analog_id`,
					`i`.`name`,
					`i`.`price`,
					`i`.`item_logo`,
					`i`.`link`,
					`i`.`cat_id`,
					`c`.`url` AS `cat_url`
				FROM
					`plug_catalog_item_analogs` AS `a`
				LEFT JOIN
					`plug_catalog_items` AS `i`
				ON 
					`i`.`id`=`a`.`analog_id`
				LEFT JOIN
					`plug_catalog_cat` AS `c`
				ON
					`i`.`cat_id`=`c`.`id`
				WHERE 
					`a`.`item_id`=:item_id
			");
			$res->bindValue(':item_id', $itemArray['id'], PDO::PARAM_INT);
			$res->execute();
			$analogsArray = array();
			$resArray = $res->fetchAll();

			if( !empty( $resArray ) )
			{
				foreach( $resArray as $analog )
				{
					$analogsArray[ $analog['analog_id'] ] = $analog;
					if( $analog['item_logo']!='' )
					{
						$analogsArray[ $analog['analog_id'] ]['item_logo'] = $this->itemImagesPath.$this->getFolderById( $analog['analog_id'] ).'/'.$this->itemLogosSizer[1][0].'x'.$this->itemLogosSizer[1][1].'/'.$analog['item_logo'];
					}else{
						$analogsArray[ $analog['analog_id'] ]['item_logo'] = '';
					}
					$analogsArray[ $analog['analog_id'] ]['href'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$analog['cat_url'].$this->marker['itemMarker'].'/'.$analog['link'];
				}
			}

			#get accompanying
			$res = $this->db->prepare("
				SELECT 
					`a`.`accompanying_id`,
					`i`.`name`,
					`i`.`price`,
					`i`.`item_logo`,
					`i`.`link`,
					`i`.`cat_id`,
					`c`.`url` AS `cat_url`
				FROM
					`plug_catalog_item_accompanying` AS `a`
				LEFT JOIN
					`plug_catalog_items` AS `i`
				ON 
					`i`.`id`=`a`.`accompanying_id`
				LEFT JOIN
					`plug_catalog_cat` AS `c`
				ON
					`i`.`cat_id`=`c`.`id`
				WHERE 
					`a`.`item_id`=:item_id
			");
			$res->bindValue(':item_id', $itemArray['id'], PDO::PARAM_INT);
			$res->execute();
			$accompanyingArray = array();
			$resArray = $res->fetchAll();

			if( !empty( $resArray ) )
			{
				foreach( $resArray as $accompanying )
				{
					$accompanyingArray[ $accompanying['accompanying_id'] ] = $accompanying;
					if( $accompanying['item_logo']!='' )
					{
						$accompanyingArray[ $accompanying['accompanying_id'] ]['item_logo'] = $this->itemImagesPath.$this->getFolderById( $accompanying['accompanying_id'] ).'/'.$this->itemLogosSizer[1][0].'x'.$this->itemLogosSizer[1][1].'/'.$accompanying['item_logo'];
					}else{
						$accompanyingArray[ $accompanying['accompanying_id'] ]['item_logo'] = '';
					}
					$accompanyingArray[ $accompanying['accompanying_id'] ]['href'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$accompanying['cat_url'].$this->marker['itemMarker'].'/'.$accompanying['link'];
				}
			}


			#compile accompanying template
			if( !empty( $accompanyingArray ) ) $this->template->assign('accompanyingArray', $accompanyingArray);

			#compile analogs template
			if( !empty( $analogsArray ) ) $this->template->assign('analogsArray', $analogsArray);

			#compile brand logo
			if( $itemArray['brand_logo'] != "" ) $itemArray['brand_logo'] = $this->brandImagesPath.$this->getFolderById( $itemArray['brand_id']).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$itemArray['brand_logo'];
			
			
			#compile brand url
			$itemArray['brand_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$itemArray['brand_link'];
			
			
			#compile currency
			$itemArray['currency'] = $this->core->declOfNum( $itemArray['price'], array( $this->catalogSettings['currency_nom'], $this->catalogSettings['currency_acc'], $this->catalogSettings['currency_nomp'] ) );			
			
			#restruct price
			if( preg_match( '/\.00$/', $itemArray['price'] ) ) $itemArray['price'] = number_format( $itemArray['price'], 0, '', ' ' );
			else $itemArray['price'] = number_format( $itemArray['price'], 2, '.', ' ' );
			
			#set page meta title
			$this->core->pageBufferArray['title'] = ($itemArray['meta_title'] != "") ? $itemArray['meta_title'] : $itemArray['name'];
			$this->core->pageBufferArray['title'] = ( $this->core->pageBufferArray['invert_title_prefix']=='0' ) ? $this->core->pageBufferArray['global_meta_title_prefix'].$this->core->pageBufferArray['title'] : $this->core->pageBufferArray['title'].$this->core->pageBufferArray['global_meta_title_prefix'];
			$this->core->pageBufferArray['keywords'] = $itemArray['meta_keywords'];
			$this->core->pageBufferArray['description'] = $itemArray['meta_description'];
			
			#compile template
			$this->template->assign('catalog', $this->catalogSettings);			
			
			#compile attributes
			if( !empty( $attrArray ) ) $this->template->assign('attrArray', $attrArray);


			#check the show comments
			if($this->catalogSettings['show_comments']=='1')
			{
				#load item comments
				$this->loadItemComments( $itemArray['id'] );
	
				#compile item comments
				if(!empty($this->itemCommentsArray))
				{
					$this->template->assign('itemCommentsArray', $this->itemCommentsArray);
				}
			}

			#compile breadcrumbs
			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$this->currentCatUri,
				'name'	=>	$caregoryName
			);

			#compile breadcrumbs
			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$this->currentCatUri.$this->marker['itemMarker'].'/'.$itemArray['link'],
				'name'	=>	$itemArray['name']
			);

			#headers
			foreach( $this->core->headersArray as &$value )
			{
				if( preg_match('/^Last-Modified/', $value) )
				{
					$date = new DateTime( $itemArray['update_date']." ".$itemArray['update_time'] );
					$value = "Last-Modified:".$date->format("D, d M Y H:i:s")." GMT";
					unset($date);
				}

				if( preg_match('/^Expires/', $value) )
				{
					$date = new DateTime( $itemArray['update_date']." ".$itemArray['update_time'] );
					$date->modify('+1 month');
					$value = "Expires:".$date->format("D, d M Y H:i:s")." GMT";
					unset($date);
				}	
			}

			#assign catalog current category id
			$this->catalogSettings['current_cat_id'] = $this->currentCatId;

			#assign catalog current item id
			$this->catalogSettings['current_item_id'] = $this->currentItemId;
			
			#compile template
			$this->template->assign('item', $itemArray);

			#set template to render
			$this->tpl = 'item';


		}

		#compile brand
		public function loadBrand()
		{
			
			#get category uri
			$startIndex = array_search($this->marker['brandMarker'], $this->core->requestUriArray) + 1;
			$endIndex = count($this->core->requestUriArray) - 2;
			
			#set uri
			$this->currentBrandUri = '';
			
			foreach( $this->core->requestUriArray as $key => $value )
			{
				if( $key<$startIndex ) continue;
				$this->currentBrandUri .= '/'.$value;
			}
			$this->currentBrandUri = preg_replace('/\//', '', $this->currentBrandUri);
			
			#check brand exists and load data
			$res = $this->db->prepare("SELECT * FROM `plug_catalog_brands` WHERE `link`=:link LIMIT 1");
			$res->bindValue(':link', $this->currentBrandUri, PDO::PARAM_STR);
			$res->execute();
			$resArray = $res->fetch();
			if( !$resArray )
			{
				$this->core->error404();
				return;
			}


			#compile breadcrumbs
			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandsMarker'],
				'name'	=>	'Производители'
			);

			$this->core->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['brandMarker'].'/'.$resArray['link'],
				'name'	=>	$resArray['name']
			);

			#compale logo url
			if( $resArray['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById( $resArray['id'] ).'/'.$this->brandLogosSizer[0][0].'x'.$this->brandLogosSizer[0][1].'/'.$resArray['brand_logo'])) $resArray['full_logo_src'] = $this->brandImagesPath.$this->getFolderById( $resArray['id']).'/'.$this->brandLogosSizer[0][0].'x'.$this->brandLogosSizer[0][1].'/'.$resArray['brand_logo'];

			#compile brand template
			$this->template->assign('brand', $resArray);
			




			#get categories of this brand
			$res = $this->db->prepare("
				SELECT
					`c`.`name` AS `cat_name`,
					`c`.`id` AS `cat_id`
				FROM
					`plug_catalog_items` AS `i`
				LEFT JOIN
					`plug_catalog_cat` AS `c`
				ON
					`c`.`id`=`i`.`cat_id`
				WHERE
					`i`.`brand_id`=:brand_id
				GROUP BY 
					`c`.`name`

			");
			$res->bindValue(':brand_id', $resArray['id'], PDO::PARAM_INT);
			$res->execute();
			$catArray = $res->fetchAll();
			$tmpArray = array();

			if( !empty($catArray) )
			{
				#categories ids array
				$catIdsArray = array();
				
				#restruct categories array
				foreach( $catArray as $data )
				{
					$tmpArray[ $data['cat_id'] ] = $data;
					$catIdsArray[] = $data['cat_id'];
				}

				#reset categories array
				$catArray = $tmpArray;
				
				#free mem
				unset( $tmpArray );

				$res = $this->db->prepare("
					SELECT
						count( `i`.`id` ) AS `count`,
						`i`.`cat_id`,
						`t`.`name` AS `type_name`,
						`t`.`id` AS `type_id`
					FROM
						`plug_catalog_items` AS `i`
					LEFT JOIN
						`plug_catalog_types` AS `t`
					ON
						`t`.`id`=`i`.`type_id`
					WHERE
						`i`.`brand_id`=:brand_id
					AND 
						`i`.`cat_id` IN (".implode(',', $catIdsArray).")
					GROUP BY
						`i`.`type_id`
				");
				$res->bindValue(':brand_id', $resArray['id'], PDO::PARAM_INT);
				$res->execute();
				$typesArray = $res->fetchAll();

				#free mem
				unset( $catIdsArray );

				#add in categories array type array data
				if( !empty($typesArray) )
				{
					foreach( $typesArray as &$data )
					{
						$data['url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].'/'.$this->marker['filterMarker'].'/'.$this->marker['filterTypeMarker'].'/'.$data['type_id'].'/'.$this->marker['filterBrandMarker'].'/'.$resArray['id'].'/';
						$catArray[ $data['cat_id'] ]['types'][] = $data;
					}
				}

				$this->template->assign('brandCatList', $catArray);
			}

			#set template to render
			$this->tpl = 'brand';
		}
		
		#view
		public function render()
		{
			#tpl is not defined then return
			if( $this->tpl=='' ) return;
			
			#choose template file name
			switch( $this->tpl )
			{
				case 'main':
					$this->template->display('mainPage.tpl');
				break;	
				
				case 'item':
					$this->template->display('itemPage.tpl');
				break;	
				
				case 'brand':
					$this->template->display('brandPage.tpl');
				break;

				case 'brands':
					$this->template->display('brandsPage.tpl');
				break;

				case 'compare':
					$this->template->display('comparePage.tpl');
				break;

				case 'cart':
					$this->template->display('cartPage.tpl');
				break;
			}
		}
		
		#ajax
		public function ajax( $postArray = array() )
		{
			if( !isset( $postArray['action'] ) ) return;

			switch( $postArray['action'] )
			{
				#save order
				case 'saveOrder':

					#check data
					$postArray['city'] = (isset($postArray['city'])) ? $postArray['city'] : '0';
					$postArray['region'] = (isset($postArray['region'])) ? $postArray['region'] : '0';

					#trim data
					$postArray['phone'] = mb_substr($postArray['phone'], 0, 17);
					$postArray['name'] = mb_substr($postArray['name'], 0, 49);
					$postArray['email'] = mb_substr($postArray['email'], 0, 256);
					$postArray['comment'] = mb_substr($postArray['comment'], 0, 3000);
					$postArray['street'] = mb_substr($postArray['street'], 0, 50);
					$postArray['build'] = mb_substr($postArray['build'], 0, 4);
					$postArray['liter'] = mb_substr($postArray['liter'], 0, 2);
					$postArray['entrance'] = mb_substr($postArray['entrance'], 0, 1);
					$postArray['floor'] = mb_substr($postArray['floor'], 0, 2);
					$postArray['room'] = mb_substr($postArray['room'], 0, 5);

					#prepare phone
					$postArray['phone'] = preg_replace('/[^0-9]/', '', $postArray['phone']);

					#validate data
					$validate = new Validate();
					$validate->addToValidate('name', $postArray['name'], 'name');
					$validate->addToValidate('phone', $postArray['phone'], 'phone');
					
					$valid = $validate->validate();

					#compile and save data
					if($valid)
					{
						#set vars
						$order = array(
							'order_date'		=> date("Y-m-d"),
							'order_time'		=> date("H:i:s"),
							'order_cost'		=> (float)'0.00',
							'deliverySelf'		=> (bool)(isset($postArray['order_delivery_self'])) ? true : false,
							'order_comment'		=> $postArray['comment'],
							'order_items_count'	=> (int)0,
							
							'order_table'		=>	'<table border="1" cellpadding="20" cellspacing="0">
														<thead>
															<tr>
																<td>ID в каталоге</td>
																<td>ID в 1C</td>
																<td>Артикул</td>
																<td>Название</td>
																<td>Цена</td>
																<td>Количество</td>
															</tr>
														</thead>
														<tbody>%order_table_rows%</tbody>
													</table>
													',
							'order_table_rows'	=> '',
							
							'order_client_table'		=>	'<table border="1" cellpadding="20" cellspacing="0">
														<thead>
															<tr>
																<td>Артикул</td>
																<td>Название</td>
																<td>Цена</td>
																<td>Количество</td>
															</tr>
														</thead>
														<tbody>%order_client_table_rows%</tbody>
													</table>
													',
							'order_client_table_rows'	=> ''
						);



						#get items data
						$itemsIdsArray = array();
						foreach( $postArray['items'] as $itemId => $itemCount )
						{
							$itemsIdsArray[] = $itemId;
						}
						$itemsArray = $this->getItems(0, 100000, array( $this->marker['filterItemMarker'] =>$itemsIdsArray));
						unset( $itemsIdsArray );

						#calculate orderCost
						foreach( $itemsArray['itemsArray'] as $itemId => $itemData )
						{
							$order['order_cost'] = $order['order_cost'] + ( (float)($itemData['price'] * $postArray['items'][ $itemId ]) );
							$order['order_items_count'] = ((int)$order['order_items_count'] + (int)$postArray['items'][ $itemId ]);
							$order['order_table_rows'] .= '
							<tr>
								<td>'.$itemData['id'].'</td>
								<td>'.$itemData['external_id'].'</td>
								<td>'.$itemData['articul'].'</td>
								<td>'.$itemData['name'].'</td>
								<td>'.$itemData['price'].'</td>
								<td>'.$postArray['items'][ $itemId ].'</td>
							</tr>';
							$order['order_client_table_rows'] .= '
							<tr>
								<td>'.$itemData['articul'].'</td>
								<td>'.$itemData['name'].'</td>
								<td>'.$itemData['price'].'</td>
								<td>'.$postArray['items'][ $itemId ].'</td>
							</tr>';
						}
						$order['order_cost'] = number_format($order['order_cost'], 2, '.', '');

						#create order
						$res = $this->db->prepare("
							INSERT INTO 
								`plug_catalog_orders`
							SET
								`order_date`=:order_date,
								`order_time`=:order_time,
								`order_cost`=:order_cost,
								`order_delivery_self`=:order_delivery_self,
								`order_comment`=:order_comment
						");
						$res->bindValue(':order_date', $order['order_date']);
						$res->bindValue(':order_time', $order['order_time']);
						$res->bindValue(':order_cost', $order['order_cost']);
						$res->bindValue(':order_delivery_self', ($order['deliverySelf']) ? '1' : '0');
						$res->bindValue(':order_comment', $order['order_comment']);
						$res->execute();
						$order['order_id'] = $this->db->lastInsertId();

						#create order items data
						$queryArray = array();
						foreach( $itemsArray['itemsArray'] as $itemId => $itemData )
						{
							if($itemData['external_id']=='') $itemData['external_id'] = '0';
							$queryArray[] = "('".$order['order_id']."', '".addslashes($itemData['external_id'])."', '".addslashes($itemData['articul'])."', '".$itemId."', '".addslashes($itemData['name'])."', '".($itemData['price'])."', '".$postArray['items'][ $itemId ]."')";
						}

						$this->db->query("
							INSERT INTO 
								`plug_catalog_orders_items`
								(`order_id`, `item_external_id`, `item_articul`, `item_id`, `item_name`, `item_price`, `item_count`)
							VALUES
								".implode(',', $queryArray)."
						");

						#create order user data
						$res = $this->db->prepare("
							INSERT INTO 
								`plug_catalog_orders_users`
							SET
								`order_id`=:order_id,
								`user_name`=:user_name,
								`user_email`=:user_email,
								`user_phone`=:user_phone,
								`region_id`=:region_id,
								`city_id`=:city_id,
								`address_street`=:address_street,
								`address_build`=:address_build,
								`address_liter`=:address_liter,
								`address_entrance`=:address_entrance,
								`address_floor`=:address_floor,
								`address_room`=:address_room
						");
						$res->bindValue(':order_id', $order['order_id']);
						$res->bindValue(':user_name', $postArray['name']);
						$res->bindValue(':user_email', $postArray['email']);
						$res->bindValue(':user_phone', $postArray['phone']);
						$res->bindValue(':region_id', (int)$postArray['region']);
						$res->bindValue(':city_id', (int)$postArray['city']);
						$res->bindValue(':address_street', $postArray['street']);
						$res->bindValue(':address_build', $postArray['build']);
						$res->bindValue(':address_liter', $postArray['liter']);
						$res->bindValue(':address_entrance', $postArray['entrance']);
						$res->bindValue(':address_floor', $postArray['floor']);
						$res->bindValue(':address_room', $postArray['room']);
						$res->execute();


						#send email notice for administrator
						$res = $this->db->query("SELECT * FROM `plug_catalog_sett` WHERE `id`='1' LIMIT 1");
						$settArray = $res->fetch();

						if( $settArray['order_email_to_address']!='' )
						{
							#replace pseudos
							$order['order_table'] = str_replace('%order_table_rows%', $order['order_table_rows'], $order['order_table']);

							$orderEmailTemplate = $settArray['order_email_template'];
							$orderEmailTemplate = str_replace('%name%', $postArray['name'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%phone%', $postArray['phone'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%count%', $order['order_items_count'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%cost%', $order['order_cost'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%id%', $order['order_id'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%ordertable%', $order['order_table'], $orderEmailTemplate);
							if( $postArray['email']!='' ) $orderEmailTemplate = str_replace('%email%', $postArray['email'], $orderEmailTemplate);

							#send mail to admin
							$mailer = new PHPMailer();
							$mailer->CharSet = 'utf-8';
							$mailer->Subject  =  $settArray['order_email_subject'];
							$mailer->FromName =  $settArray['order_email_from_name'];
							$mailer->From = $settArray['order_email_from_email'];
							$mailer->AddAddress(  $settArray['order_email_to_address'],  $settArray['order_email_to_name']);
							$mailer->Body =  $orderEmailTemplate;
							$mailer->IsHTML(true);
							$mailer->Send();
							unset($mailer);
						}


						#send email to client
						if( $postArray['email']!='' )
						{
							#replace pseudos
							$order['order_client_table'] = str_replace('%order_client_table_rows%', $order['order_client_table_rows'], $order['order_client_table']);

							$orderEmailTemplate = $settArray['client_email_template'];
							$orderEmailTemplate = str_replace('%name%', $postArray['name'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%phone%', $postArray['phone'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%count%', $order['order_items_count'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%cost%', $order['order_cost'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%id%', $order['order_id'], $orderEmailTemplate);
							$orderEmailTemplate = str_replace('%ordertable%', $order['order_client_table'], $orderEmailTemplate);
							if( $postArray['email']!='' ) $orderEmailTemplate = str_replace('%email%', $postArray['email'], $orderEmailTemplate);
							
							#send mail to admin
							$mailer = new PHPMailer();
							$mailer->CharSet = 'utf-8';
							$mailer->Subject  =  $settArray['client_email_subject'];
							$mailer->FromName =  $settArray['client_from_name'];
							$mailer->From = $settArray['client_email_from'];
							$mailer->AddAddress(  $postArray['email'],  $postArray['name']);
							$mailer->Body =  $orderEmailTemplate;
							$mailer->IsHTML(true);
							$mailer->Send();
							unset($mailer);
						}


						#get message text
						$res  = $this->db->query("SELECT `word_value` FROM `words` WHERE `word_key`='plug_catalog_saveorder_ok' LIMIT 1 ");
						$resArray = $res->fetch();
						if( !empty($resArray) )
						{
							$array = json_decode($validate->error, true);
							$array['message'] = $resArray['word_value'];
							$validate->error = json_encode( $array );
						}
					}

					die( $validate->error );
				break;


				#get oferta html
				case 'getOferta':
					$res = $this->db->query("SELECT `oferta_html` FROM `plug_catalog_sett` WHERE `id`=1 LIMIT 1");
					if(!$res) die("");
					$resArray = $res->fetch();
					die( $resArray['oferta_html'] );
				break;

				#get region
				case 'getRegions':

					$res = $this->db->prepare("SELECT `region_id`, `region_name` FROM `geo_regions` WHERE `country_id`=:country_id");
					//3159 - this is Russia country ID
					$res->bindValue(':country_id', '3159', PDO::PARAM_STR);
					$res->execute();
					if($res) $resArray = $res->fetchAll();
					
					$responseArray = array();
					if(!empty($resArray))
					{
						foreach($resArray as $primaryIndex => $dataArray)
						{
							foreach($dataArray as $index => $value)
							{
								if( !is_numeric($index)) $responseArray[$primaryIndex][$index] = $value;
							}
						}

					}

					die( json_encode($responseArray) );
				break;

				#get cities
				case 'getCities':

					$resArray = array();
					$res = $this->db->prepare("SELECT `city_id`, `city_name` FROM `geo_cities` WHERE `country_id`=:country_id AND `region_id`=:region_id");
					//3159 - this is Russia country ID
					$res->bindValue(':country_id', '3159', PDO::PARAM_STR);
					$res->bindValue(':region_id', (int)$postArray['region_id'], PDO::PARAM_STR);
					$res->execute();
					if($res) $resArray = $res->fetchAll();
					if(!empty($resArray))
					{
						foreach($resArray as $primaryIndex => $dataArray)
						{
							foreach($dataArray as $index => $value)
							{
								if( is_numeric($index)) unset( $resArray[$primaryIndex][$index] );
							}
						}

					}

					die( json_encode($resArray) );
				break;
				#get all catr data
				case 'getCartFullData':
					#get items card
					$itemsArray = isset( $_COOKIE['catalog_cart'] ) ? json_decode( $_COOKIE['catalog_cart'], true ) : array();

					#for json encode
					$resultArray = array();
					
					if( !empty( $itemsArray ) ) 
					{
						#for implode
						$itemsIdArray = array();
						
						#set array for implode
						foreach( $itemsArray as $id => $count )
						{
							$itemsIdArray[] = $id;
						}

						#get data
						$res = $this->db->query("
							SELECT
								`i`.`id`,
								`i`.`price`,
								`i`.`cat_id`,
								`i`.`name`,
								`i`.`item_logo`,
								`i`.`link`,
								`c`.`url` AS `category_url`
							FROM
								`plug_catalog_items` AS `i`
							LEFT JOIN
								`plug_catalog_cat` AS `c`
							ON
								`i`.`cat_id`=`c`.`id`
							WHERE 
								`i`.`id` IN(".implode(',', $itemsIdArray).")
							AND
								`i`.`disabled`='0'
						");
						$resultArray = array();

						if( !$res ) die( json_encode( $resultArray ) );
						$resArray = $res->fetchAll();

						
						#compile result array for json encode
						if( !empty( $resArray ) )
						{
							$this->loadSettings();

							foreach( $resArray as $item )
							{
								#item count
								$resultArray[ $item['id'] ] ['count'] = $itemsArray[ $item['id'] ];
								
								#item price
								$resultArray[ $item['id'] ] ['price'] = $item['price'];
								
								#item name
								$resultArray[ $item['id'] ] ['name'] = $item['name'];
								
								#item summ (item price * item count)
								$resultArray[ $item['id'] ] ['cost'] = number_format((float)$item['price'] * (int)$itemsArray[ $item['id'] ], 2, '.', '');

								#item image full url
								if( $item['item_logo']!='' )
								{
									$resultArray[ $item['id'] ] ['image'] = $this->itemImagesPath.$this->getFolderById( $item['id'] ).'/'.$this->itemLogosSizer[1][0].'x'.$this->itemLogosSizer[1][1].'/'.$item['item_logo'];
								}else{
									$resultArray[ $item['id'] ] ['image'] = '';
								}

								#item full url
								$resultArray[ $item['id'] ]['full_item_url'] = $this->catalogSettings['catalog_page_url'].$this->marker['catMarker'].$item['category_url'].$this->marker['itemMarker'].'/'.$item['link'];
							}
						}
					}

					#send response
					die( json_encode( $resultArray ) );

				break;

				case 'getSettings':
					$this->loadSettings();
					die( json_encode( $this->catalogSettings ) );
				break;

				#send comment for item
				case 'sendComment':

					$validate = new Validate();
					
					$validate->captcha = (isset($_SESSION["captcha"]["catalogComments"])) ? $_SESSION["captcha"]["catalogComments"] : uniqid();

					$validate->addToValidate('username', $postArray['username'], 'name');
					$validate->addToValidate('captcha', $postArray['captcha'], 'captcha');

					if( !$validate->validate() ) die( $validate->error );
					
    				#set saved data array
    				$dataArray = array(
    					'item_id'		=> $postArray['item_id'],
    					'pid'			=> '0',
    					'comment_time'	=> date("H:i:s"),
    					'comment_date'	=> date("Y-m-d"),
    					'hide'			=> '0',
    					'remove'		=> '0',
    					'user_name'		=> mb_substr($postArray['username'], 0, 49),
    					'comment_text'	=> ( mb_strlen( $postArray['text'] ) > 10000 ) ? mb_substr($postArray['text'], 0, 10000 ).'... [replace]Комментарий был урезан[/replace]' : $postArray['text']
    				);

    				#reply
    				if( isset( $postArray['reply'] ) && !empty($postArray['reply']) )
    				{
    					if( count($postArray['reply']) == 1 )
    					{
    						foreach( $postArray['reply'] as $id => $name) $dataArray['pid'] = $id;
    					}else{
    						$replyListArray = array();
    						foreach( $postArray['reply'] as $id => $name)
    						{
    							$replyListArray[] = "[b]".$name."[/b]";
    						}

    						$dataArray['comment_text'] = "[reply]".implode(', ', $replyListArray)."[/reply]".$dataArray['comment_text'];

    					}
    				}

    				$res = $this->db->prepare("
    					INSERT INTO 
    						`plug_catalog_comments` 
    					SET
    						`item_id`=:item_id,
    						`pid`=:pid,
    						`comment_date`=:comment_date,
    						`comment_time`=:comment_time,
    						`hide`=:hide,
    						`remove`=:remove,
    						`user_name`=:user_name,
    						`comment_text`=:comment_text
  					");
  					$res->bindValue(':item_id', $dataArray['item_id'], PDO::PARAM_STR);
  					$res->bindValue(':pid', $dataArray['pid'], PDO::PARAM_STR);
  					$res->bindValue(':comment_date', $dataArray['comment_date'], PDO::PARAM_STR);
  					$res->bindValue(':comment_time', $dataArray['comment_time'], PDO::PARAM_STR);
  					$res->bindValue(':hide', $dataArray['hide'], PDO::PARAM_STR);
  					$res->bindValue(':remove', $dataArray['remove'], PDO::PARAM_STR);
  					$res->bindValue(':user_name', $dataArray['user_name'], PDO::PARAM_STR);
  					$res->bindValue(':comment_text', $dataArray['comment_text'], PDO::PARAM_STR);

  					$res->execute();

					die( $validate->error );
				break;

			}

			die();

		}


		public function uploadFile( $files, $post )
		{
			//#check filesize
			//if( $files['upload']['size'] > (3*1024*1024) ) return "Max filesize 3 МБ";
			return "";
		}

		public function downloadFile( $filename = '' )
		{
			if( $filename == 'xmlman' )
			{
				$filename = $this->root.'/plug/catalog/back/manual/xml_manual_gjdf6u32bffb387fg23fds.xml';
				$this->core->downloadFile( $filename, 'XML-catalog-import-manual.xml');
			}

			return;
		}

		#get folder name by id
		public function getFolderById($id)
		{
			return intval($id / 50);
		}	
		
	}

?>