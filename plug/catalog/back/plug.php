<?
	class PlugCatalog extends Plug{
		
		#catalog plugin name
		public $plugName = 'catalog';
		
		#catalog display name
		public $plugNameRu = 'Каталог';
		
		#brands images sizes
		public $categoryLogosSizer = array(
			0	=> array(400, 400), //main image
			1	=> array(200, 200),
			2	=> array(100, 100)	//small image for list of cats
		);

		#brands images sizes
		public $brandLogosSizer = array(
			0	=> array(400, 200),//main image
			1	=> array(200, 100),
			2	=> array(100, 50)	//small image for list of brands
		);	
		
		#items images sizes
		public $itemLogosSizer = array(
			0	=> array(800, 800), //main image
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
		


		#order statuses
		public $orderStatuses = array(
			0	=>	'Не подтвержден',	//only first position in this list
			1	=>	'Подтвержден',
			2	=>	'Оплачено',
			3	=>	'Отправлено в достаку',
			4	=>	'Самовывоз',
			5	=>	'Доставлено',
			6	=>	'Возврат',
			7	=>	'Закрыт'
		);
		
		#categories list array (after method loadCatList())
		public $catList = array();
		
		#catalog all settings array
		public $catalogSettings = array();
		
		#after getCommentsList method
		public $itemCommentsArray = array();

		public function __construct()
		{
			parent::__construct();
		}
		
		#commint this method if not needed menu item
		public function registerMmenuItem()
		{
			return array(
				'color'			=>	'orange',
				'dataPanel'		=>	'plugins',
				'dataAction'	=>	$this->plugName,
				'name'			=>	$this->plugNameRu
			);
		}
		
		#load all catalog settings
		private function loadSettings()
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
		}

		#load category list and place to $this->catList array
		public function loadCatList()
		{
			$flag = false;
			#load data
			$query = "SELECT `id`, `pid`, `name`, `url`, `cat_range`, `hide`, `off` FROM `plug_catalog_cat` ORDER BY `pid` DESC ,`cat_range`";
			$res = $this->db->prepare( $query );
			$res->execute();
			
			if(!$res) { $this->errorEngine("Failed to load categories"); }
			
			$tableArray = $res->fetchAll();
			if( empty($tableArray) )
			{
				$this->template->assign('cat_not_exists','empty');
				return;
			}
			
			#compile tree array
			foreach ($tableArray as $row)
			{	
	
				$tree[$row['id']] = array( 
					'id'=>$row['id'],
					'pid'=>$row['pid'],
					'name'=>$row['name'], 
					'url'=>$row['url'],
					'cat_range'=>$row['cat_range'],
					'hide'=>$row['hide'],
					'off'=>$row['off']
				);
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
		
		#set first category is active
		public function setFirstCatIsActive()
		{
			$res = $this->db->query("SELECT `id` FROM `plug_catalog_cat` WHERE `pid`='0' ORDER BY `cat_range` LIMIT 1 ");
			if( $res )
			{
				$res = $res->fetch();
				if( !empty( $res ) )
				{
					$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] = $res[0]['id'];
				}
			}
		}
		
		
		public function start()
		{
			
			#set carrent category if is not set
			if( !isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] ) ) $this->setFirstCatIsActive();
			
			#view catalog categories list
			ob_start();
			$this->ajax(array('action'=>'cat_list'));
			$this->template->assign('welcomescreen', ob_get_clean());
		}
		
		public function render()
		{
			#paste your code here for view plugin
			$this->template->display('default.tpl');
		}
		
		public function install()
		{
			#installing plugin
			
			#plug_catalog_cat
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_cat`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_cat` (
					  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'category id',
					  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'category parent id',
					  `name` varchar(255) NOT NULL COMMENT 'category name',
					  `cat_logo` varchar(255) NOT NULL COMMENT 'category logo filename',
					  `link` varchar(255) NOT NULL COMMENT 'category link',
					  `url` varchar(255) NOT NULL COMMENT 'category full url',
					  `hide` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'category hide from categories list',
					  `off` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'category not worked',
					  `cat_range` int(11) NOT NULL DEFAULT '999' COMMENT 'category range',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				");
			}
			
			#plug_catalog_items
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_items`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_items` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `external_id` varchar(255) NOT NULL COMMENT 'id from 1C',
					  `articul` varchar(255) NOT NULL COMMENT 'item articul',
					  `name` varchar(255) NOT NULL COMMENT 'item name',
					  `meta_title` varchar(255) NOT NULL COMMENT 'page meta tag title',
					  `meta_keywords` text NOT NULL COMMENT 'page meta tag keywords',
					  `meta_description` text NOT NULL COMMENT 'page meta tag description',
					  `extra_meta` text NOT NULL COMMENT 'page other meta data',
					  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'item price',
					  `old_price` decimal(10,2) NOT NULL DEFAULT '0.00',
					  `discount` int(11) NOT NULL DEFAULT '0' COMMENT 'percentage discount',
					  `discount_calc_auto` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'automatic calculation of discounts',
					  `item_quick_desc` text NOT NULL COMMENT 'item quick description',
					  `item_desc` text NOT NULL COMMENT 'item full description',
					  `hide_in_list` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'hide item from items list',
					  `disabled` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'disabled item',
					  `link` varchar(255) NOT NULL COMMENT 'item link',
					  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT 'item type id from plug_catalog_types',
					  `cat_id` int(11) NOT NULL DEFAULT '0' COMMENT 'category id from plug_catalog_cat',
					  `brand_id` int(11) NOT NULL DEFAULT '0' COMMENT 'brand id from plug_catalog_brands',
					  `in_stock` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT 'item is in stock',
					  `is_sale` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'item is sale',
					  `is_new` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'item is new',
					  `is_best` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'item is best',
					  `is_markdown` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'item is markdown',
					  `create_date` date NOT NULL COMMENT 'date of item create',
					  `create_time` time NOT NULL COMMENT 'time of item create',
					  `update_date` date NOT NULL COMMENT 'date of item last update',
					  `update_time` time NOT NULL COMMENT 'time of item last update',
					  `raiting` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0' COMMENT 'item raiting',
					  `count` int(11) NOT NULL COMMENT 'item count',
					  `delivery_city` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'delivery in the city',
					  `delivery_region` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'delivery in the region',
					  `delivery_out_region` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'delivery to other regions',
					  `pay_cash_person` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'cash payment in person',
					  `pay_card_person` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'cards payment in person',
					  `pay_card_web` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'cards payment in web',
					  `pay_web_money` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'payment by web money',
					  `pay_entity` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Payment entity',
					  `item_logo` varchar(255) NOT NULL,
					  `item_range` int(11) NOT NULL DEFAULT '999' COMMENT 'item range',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				");
			}
			
			#plug_catalog_brands
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_brands`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_brands` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `external_id` varchar(255) NOT NULL COMMENT 'brand ID for 1C',
					  `link` varchar(255) NOT NULL COMMENT 'brand link',
					  `offsite` varchar(255) NOT NULL COMMENT 'brand official site',
					  `name` varchar(255) NOT NULL COMMENT 'brand name',
					  `brand_quick_desc` text NOT NULL COMMENT 'quick description for brand',
					  `brand_descr` text NOT NULL COMMENT 'brand description',
					  `brand_logo` varchar(255) NOT NULL COMMENT 'logo filename name for brand',
					  `meta_title` varchar(255) NOT NULL COMMENT 'meta tag TITLE for brand page',
					  `meta_keywords` text NOT NULL COMMENT 'meta tag KEYWORDS for brand page',
					  `meta_description` text NOT NULL COMMENT 'meta tag DESCRIPTION for brand page',
					  `extra_meta` text NOT NULL COMMENT 'other meta data',
					  `hide_in_list` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'hidden brand from brands list',
					  `disabled` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'disabled brand',
					  `brand_range` int(11) NOT NULL DEFAULT '999' COMMENT 'range of brand list',
					  `create_date` date NOT NULL COMMENT 'date of brand create',
					  `create_time` time NOT NULL COMMENT 'time of brand create',
					  `update_date` date NOT NULL COMMENT 'date of brand last update',
					  `update_time` time NOT NULL COMMENT 'time of brand last update',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				");
			}
			
			#plug_catalog_sett
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_sett`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_sett` (
					  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					  `catalog_page_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Id page that displays catalog',
					  `currency_quick` varchar(255) NOT NULL DEFAULT 'руб' COMMENT 'shorthand for a currency',
					  `currency_symbol` varchar(255) NOT NULL DEFAULT 'р' COMMENT 'currency symbol',
					  `currency_nom` varchar(255) NOT NULL DEFAULT 'рубль' COMMENT 'currency in the nominative case',
					  `currency_acc` varchar(255) NOT NULL DEFAULT 'рубля' COMMENT 'currency in the accusative',
					  `currency_nomp` varchar(255) NOT NULL DEFAULT 'рублей' COMMENT 'currency in the nominative plural',
					  `item_nom` varchar(255) NOT NULL DEFAULT 'товар' COMMENT 'item in the nominative case',
					  `item_acc` varchar(255) NOT NULL DEFAULT 'товара' COMMENT 'item in the accusative',
					  `item_nomp` varchar(255) NOT NULL DEFAULT 'товаров' COMMENT 'item in the nominative plural',
					  `oferta_html` text NOT NULL COMMENT 'oferta doc html',
					  `show_comments` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'show item comments',
					  `order_email_to_address` varchar(255) NOT NULL COMMENT 'admin email address of the new order',
					  `order_email_to_name` varchar(255) NOT NULL COMMENT 'admin email name of the new order',
					  `order_email_template` text NOT NULL COMMENT 'email template for admin of the new order',
					  `order_email_from_email` varchar(255) NOT NULL COMMENT 'email from addredd for admin of the new order',
					  `order_email_from_name` varchar(255) NOT NULL COMMENT 'email from name for admin of the new order',
					  `order_email_subject` varchar(255) NOT NULL COMMENT 'email subject for admin of the new order',
					  `client_email_template` text NOT NULL COMMENT 'email template to client about order',
					  `client_email_subject` varchar(255) NOT NULL COMMENT 'email subject to client about order',
					  `client_email_from` varchar(255) NOT NULL COMMENT 'email from address to client about order',
					  `client_from_name` varchar(255) NOT NULL COMMENT 'email from name to client about order',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
				");
				
				$this->db->query("
					INSERT INTO 
						`plug_catalog_sett` (`id`, `catalog_page_id`, `currency_quick`, `currency_symbol`, `currency_nom`, `currency_acc`, `currency_nomp`, `item_nom`, `item_acc`, `item_nomp`, `oferta_html`, `show_comments`, `order_email_to_address`, `order_email_to_name`, `order_email_template`, `order_email_from_email`, `order_email_from_name`, `order_email_subject`, `client_email_template`, `client_email_subject`, `client_email_from`, `client_from_name`) VALUES
						(1, 0, 'руб', 'р', 'рубль', 'рубля', 'рублей', 'товар', 'товара', 'товаров', '<p>\r\n	<span style=\"line-height: 1.45em; font-style: inherit; font-variant: inherit; font-weight: inherit;\">ООО \"ИНТЕРНЕТ-МАГАЗИН\", в лице интернет-магазина WWW.EXAMPLE.COM (далее ПРОДАВЕЦ), публикует настоящий договор, являющийся публичным договором-офертой в адрес как физических, так и юридических лиц (далее ПОКУПАТЕЛЬ) о нижеследующем:</span>\r\n</p>\r\n<h2>Статья 1. Предмет договора-оферты.</h2>\r\n<p>\r\n	1.1. ПРОДАВЕЦ обязуется передать в собственность ПОКУПАТЕЛЮ, а ПОКУПАТЕЛЬ обязуется оплатить и принять заказанные в интернет-магазине WWW.EXAMPLE.COM товары (далее ТОВАР).\r\n</p>\r\n<h2>Статья 2. Момент заключения договора.</h2>\r\n<p>\r\n	2.1. Текст данного Договора является публичной офертой (в соответствии со статьей 435 и частью 2 статьи 437 Гражданского кодекса РФ).\r\n</p>\r\n<p>\r\n	2.2. Факт оформления ЗАКАЗА ТОВАРА у ПРОДАВЦА как самостоятельно, так и через оператора, является безоговорочным принятием данного Договора, и ПОКУПАТЕЛЬ рассматривается как лицо, вступившее с ООО «ИНТЕРНЕТ МАГАЗИН» в договорные отношения.\r\n</p>\r\n<p>\r\n	2.3. Оформление ЗАКАЗА ТОВАРА и расчета осуществляется путем заказа ПОКУПАТЕЛЕМ в интернет-магазине WWW.EXAMPLE.COM.\r\n</p>\r\n<h2>Статья 3. Характеристики ТОВАРА.</h2>\r\n<p>\r\n	3.1. В связи с разными техническими характеристиками мониторов цвет ТОВАРА может отличаться от представленного на сайте.\r\n</p>\r\n<p>\r\n	3.2. Характеристики и внешний вид ТОВАРА могут отличаться от описанных на сайте.\r\n</p>\r\n<h2>Статья 4. Цена ТОВАРА.</h2>\r\n<p>\r\n	4.1. Цены в интернет-магазине указаны в валюте страны покупателя за единицу ТОВАРА.\r\n</p>\r\n<p>\r\n	4.2. Тарифы на оказание услуг по доставке, разгрузке, подъеме и сборке ТОВАРА указаны в интернет-магазине на каждый ТОВАР в зависимости от его характеристики.\r\n</p>\r\n<p>\r\n	4.3. Общая сумма ЗАКАЗА, которая в некоторых случаях (по желанию покупателя) может включать платную доставку и сборку ТОВАРА, указывается в разделе «Корзина» в строке «Итого».\r\n</p>\r\n<h2>Статья 5. Оплата ТОВАРА.</h2>\r\n<p>\r\n	5.1. При наличной форме оплаты ПОКУПАТЕЛЬ обязан уплатить ПРОДАВЦУ цену ТОВАРА в момент его передачи, а ПРОДАВЕЦ обязан предоставить ПОКУПАТЕЛЮ кассовый или товарный чек, или иной документ, подтверждающий оплату ТОВАРА.\r\n</p>\r\n<p>\r\n	5.2. При безналичной форме оплаты обязанность ПОКУПАТЕЛЯ по уплате цены ТОВАРА считается исполненной с момента зачисления соответствующих денежных средств в размере 100% (ста процентов) предоплаты на расчетный счет ПРОДАВЦА по реквизитам, указанным в п. 13 (Реквизиты магазина) настоящего ДОГОВОРА.\r\n</p>\r\n<p>\r\n	5.3. При безналичной форме оплаты просрочка уплаты ПОКУПАТЕЛЕМ цены ТОВАРА на срок свыше 5 (пяти) дней является существенным нарушением настоящего договора. В этом случае ПРОДАВЕЦ вправе в одностороннем порядке отказаться от исполнения настоящего договора, уведомив об этом ПОКУПАТЕЛЯ.\r\n</p>\r\n<p>\r\n	5.4. ТОВАРЫ поставляются ПОКУПАТЕЛЮ по ценам, наименованию, в количестве, соответствующем счету, оплаченному ПОКУПАТЕЛЕМ.\r\n</p>\r\n<h2>Статья 6. Доставка ТОВАРА.</h2>\r\n<p>\r\n	6.1. Доставка ТОВАРА ПОКУПАТЕЛЮ осуществляется адресу и в сроки, согласованные ПОКУПАТЕЛЕМ и менеджером ПРОДАВЦА при оформлении ЗАКАЗА, либо ПОКУПАТЕЛЬ самостоятельно забирает товар со склада ПРОДАВЦА по адресу, указанному в п. 13 (Реквизиты магазина) настоящего ДОГОВОРА.\r\n</p>\r\n<p>\r\n	6.2. Точная стоимость доставки ТОВАРА определяется менеджером ПРОДАВЦА при оформлении заказа и не может быть изменена после согласования ПОКУПАТЕЛЕМ.\r\n</p>\r\n<p>\r\n	6.3. Неявка ПОКУПАТЕЛЯ или не совершение иных необходимых действий для принятия ТОВАРА могут рассматриваться ПРОДАВЦОМ в качестве отказа ПОКУПАТЕЛЯ от исполнения ДОГОВОРА.\r\n</p>\r\n<h2>Статья 7. Гарантии на товар.</h2>\r\n<p>\r\n	7.1. На всю продукцию, продающуюся в Интернет-магазине WWW.EXAMPLE.COM, имеются все необходимые сертификаты качества и санитарно-гигиенические заключения.\r\n</p>\r\n<p>\r\n	7.2. Гарантийный срок эксплуатации на ТОВАР устанавливает производитель. Срок гарантии указывается в гарантийном талоне.\r\n</p>\r\n<h2>Статья 8. Права и обязанности сторон.</h2>\r\n<p>\r\n	8.1. ПРОДАВЕЦ обязуется:\r\n</p>\r\n<p>\r\n	8.1.1. Не разглашать любую частную информацию ПОКУПАТЕЛЯ и не предоставлять доступ к этой информации третьим лицам, за исключением случаев, предусмотренных Российским законодательством.\r\n</p>\r\n<p>\r\n	8.1.2. Предоставить ПОКУПАТЕЛЮ возможность получения бесплатных телефонных консультаций по телефонам, указанным на сайте магазина (WWW.EXAMPLE.COM). Объем консультаций ограничивается конкретными вопросами, связанными с выполнениями ЗАКАЗА.\r\n</p>\r\n<p>\r\n	8.1.3. ПРОДАВЕЦ оставляет за собой право изменять настоящий ДОГОВОР в одностороннем порядке до момента его заключения.\r\n</p>\r\n<p>\r\n	8.2. ПОКУПАТЕЛЬ обязуется:\r\n</p>\r\n<p>\r\n	8.2.1. До момента заключения ДОГОВОРА ознакомиться с содержанием договора-оферты, условиями оплаты и доставки на сайте магазина (WWW.EXAMPLE.COM).\r\n</p>\r\n<p>\r\n	8.2.2. Предоставлять достоверную информацию о себе (ФИО, контактные телефоны, адрес электронной почты) и реквизиты для доставки ТОВАРА.\r\n</p>\r\n<p>\r\n	8.2.3. Принять и оплатить ТОВАР в указанные в настоящем ДОГОВОРЕ сроки.\r\n</p>\r\n<h2>Статья 9. Ответственность сторон и разрешение споров.</h2>\r\n<p>\r\n	9.1. Стороны несут ответственность за неисполнение или ненадлежащее исполнение настоящего ДОГОВОРА в порядке, предусмотренном настоящим ДОГОВОРОМ и действующим законодательством РФ.\r\n</p>\r\n<p>\r\n	9.2. Продавец не несет ответственности за доставку ЗАКАЗА, если ПОКУПАТЕЛЕМ указан неправильный адрес доставки.\r\n</p>\r\n<p>\r\n	9.3. ПРОДАВЕЦ не несет ответственности, если ожидания ПОКУПАТЕЛЯ о потребительских свойствах ТОВАРА оказались не оправданны.\r\n</p>\r\n<p>\r\n	9.4. ПРОДАВЕЦ не несет ответственности за частичное или полное неисполнение обязательств по доставке ТОВАРА, если они являются следствием форс-мажорных обстоятельств.\r\n</p>\r\n<p>\r\n	9.5. ПОКУПАТЕЛЬ, оформляя ЗАКАЗ, несет ответственность за достоверность предоставляемой информации о себе, а так же подтверждает, что с условиями настоящего ДОГОВОРА ознакомлен и согласен.\r\n</p>\r\n<p>\r\n	9.6. Все споры и разногласия, возникающие при исполнении СТОРОНАМИ обязательств по настоящему Договору, решаются путем переговоров. В случае невозможности их устранения, СТОРОНЫ имеют право обратиться за судебной защитой своих интересов.\r\n</p>\r\n<h2>Статья 10. Возврат и обмен товара.</h2>\r\n<p>\r\n	10.1. Требование ПОКУПАТЕЛЯ об обмене либо о возврате ТОВАРА подлежит удовлетворению, если ТОВАР не был в употреблении, сохранены его потребительские свойства, сохранена и не нарушена упаковка, сохранены документы, подтверждающие факт покупки этого ТОВАРА в интернет-магазине WWW.EXAMPLE.COM.\r\n</p>\r\n<p>\r\n	10.2. Срок такого требования составляет 14 (четырнадцать) дней с момента передачи ТОВАРА ПОКУПАТЕЛЮ.\r\n</p>\r\n<p>\r\n	10.3. ПОКУПАТЕЛЬ компенсирует ПРОДАВЦУ необходимые транспортные расходы, понесенные в связи с организацией обмена или возврата ТОВАРА.\r\n</p>\r\n<h2>Статья 11. Форс-мажорные обстоятельства.</h2>\r\n<p>\r\n	11.1. Стороны освобождаются от ответственности за неисполнение или ненадлежащее исполнение обязательств по Договору на время действия непреодолимой силы. Под непреодолимой силой понимаются чрезвычайные и непреодолимые при данных условиях обстоятельства, препятствующие исполнению своих обязательств СТОРОНАМИ по настоящему Договору. К ним относятся стихийные явления (землетрясения, наводнения и т. п.), обстоятельства общественной жизни (военные действия, чрезвычайные положения, крупнейшие забастовки, эпидемии и т. п.), запретительные меры государственных органов (запрещение перевозок, валютные ограничения, международные санкции запрета на торговлю и т. п.). В течение этого времени СТОРОНЫ не имеют взаимных претензий, и каждая из СТОРОН принимает на себя свой риск последствия форс-мажорных обстоятельств.\r\n</p>\r\n<h2>Статья 12. Срок действия договора.</h2>\r\n<p>\r\n	12.1. Настоящий ДОГОВОР вступает в силу с момента обращения в ООО «ИНТЕРНЕТ МАГАЗИН» и оформления ЗАКАЗА, и заканчивается при полном исполнении обязательств СТОРОНАМИ.\r\n</p>\r\n<h2>Статья 13. Реквизиты интернет магазина.</h2>\r\n<p>\r\n	Разместите здесь ваши реквизиты.\r\n</p>', '1', 'email@site.ru', 'Администратору заказов', '<p>\r\n	  Новый заказ на сайте.\r\n</p>\r\n<p>\r\n	  ФИО клиента: <b style=\"font-style: inherit; font-variant: inherit;\">%name%</b>Телефон клиента: <strong style=\"font-style: inherit; font-variant: inherit;\">%phone%</strong>\r\n</p>\r\n<p>\r\n	  Перечень товаров:<br>\r\n	<strong>%ordertable%</strong>\r\n</p>\r\n<hr id=\"horizontalrule\">\r\n<p>\r\n	<span style=\"color: rgb(0, 0, 0);\">Всего товаров: <strong>%count%</strong> шт.<br>\r\n	  На сумму <strong>%cost%</strong> руб.<br>\r\n	</span>ID заказа <strong>%id%</strong>.\r\n</p><p></p><p>Письмо отправлено роботом и отвечать на него не надо</p>', 'noreply@site.ru', 'Система заказов сайта site.ru', 'Новый заказ на сайте.', '<p>\r\n	  Ваш заказ.\r\n</p>\r\n\r\n<p>\r\n	  Перечень товаров:<br>\r\n	<strong>%ordertable%</strong>\r\n</p>\r\n<hr id=\"horizontalrule\">\r\n<p>\r\n	<span style=\"color: rgb(0, 0, 0);\">Всего товаров: <strong>%count%</strong> шт.<br>\r\n	  На сумму <strong>%cost%</strong> руб.<br>\r\n	</span>ID заказа <strong>%id%</strong>.\r\n</p><p></p><p>Письмо отправлено роботом и отвечать на него не надо</p>', 'Ваш заказ с сайта site.ru', 'robot@site.ru', 'Система заказов сайта site.ru');
				");
			}
			
			#plug_catalog_sett
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_img`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_img` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`cat_id` int(11) NOT NULL COMMENT 'for categories images',
					`item_id` int(11) NOT NULL COMMENT 'for items images',
					`brand_id` int(11) NOT NULL COMMENT 'for brands images',
					`filename` varchar(255) NOT NULL COMMENT 'image filename',
					`img_range` int(11) NOT NULL COMMENT 'range',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}
			
			#plug_catalog_item_attr_vals
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_item_attr_vals`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_item_attr_vals` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`type_id` int(11) NOT NULL COMMENT 'type_id',
					`item_id` int(11) NOT NULL COMMENT 'item id',
					`attr_id` int(11) NOT NULL COMMENT 'attribute id',
					`value_id` int(11) NOT NULL COMMENT 'value id',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}	
			
			#plug_catalog_types
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_types`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_types` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`name` varchar(255) NOT NULL COMMENT 'type name',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}	
			
			#plug_catalog_types_attr
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_types_attr`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_types_attr` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`type_id` int(11) NOT NULL COMMENT 'type id',
					`name` text NOT NULL COMMENT 'value name',
					`units` varchar(255) NOT NULL COMMENT 'attribute units',
					`selector` enum('select','radiobutton','checkbox','string') NOT NULL COMMENT 'html selector type',
					`in_filter` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'show in filter',
					`in_list` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'show on items list',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}
			
			#plug_catalog_types_vals
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_types_vals`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_types_vals` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`type_id` int(11) NOT NULL COMMENT 'type id',
					`attr_id` int(11) NOT NULL COMMENT 'attribute id',
					`value` text NOT NULL COMMENT 'attribute value',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
				");
			}
			
			#plug_catalog_item_analogs
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_item_analogs`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_item_analogs` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`item_id` int(11) NOT NULL COMMENT 'item id',
					`analog_id` int(11) NOT NULL COMMENT 'analog id',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}	
			
			#plug_catalog_item_accompanying
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_item_accompanying`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_item_accompanying` (
					`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
					`item_id` int(11) NOT NULL COMMENT 'item id',
					`accompanying_id` int(11) NOT NULL COMMENT 'accompanying id',
					PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
			}

			#plug_catalog_comments
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_comments`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_comments` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `pid` int(11) NOT NULL DEFAULT '0' COMMENT 'parent id',
					  `item_id` int(11) NOT NULL COMMENT 'item id',
					  `user_name` varchar(255) NOT NULL COMMENT 'user name',
					  `comment_text` text NOT NULL COMMENT 'comment text',
					  `comment_date` date NOT NULL COMMENT 'date of comment',
					  `comment_time` time NOT NULL COMMENT 'time of comment',
					  `hide` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'hide this comment',
					  `remove` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'remove this comment',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
				");
			}

			#plug_catalog_orders
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_orders`");
			if(!$res)
			{
				$enum = array();
				foreach( $this->orderStatuses as $key => $value ) $enum[] = "'".$key."'";
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_orders` (
					  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'order id',
					  `order_date` date NOT NULL COMMENT 'order date',
					  `order_time` time NOT NULL COMMENT 'order time',
					  `order_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'order cost',
					  `order_delivery_self` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'order delivery',
					  `order_status` enum(".implode(', ', $enum).") NOT NULL DEFAULT '0' COMMENT 'order status',
					  `admin_user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'administrator account id',
					  `order_comment` text NOT NULL COMMENT 'comment of order',
					  `order_manager_comment` text NOT NULL COMMENT 'manager comment text',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;
				");
				unset($enum);
			}

			#plug_catalog_orders_items
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_orders_items`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_orders_items` (
					  `order_id` int(11) NOT NULL COMMENT 'order ID',
					  `item_external_id` varchar(255) NOT NULL,
					  `item_articul` varchar(255) NOT NULL,
					  `item_id` int(11) NOT NULL COMMENT 'item ID',
					  `item_name` varchar(255) NOT NULL COMMENT 'name of item',
					  `item_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'price of item',
					  `item_count` int(11) NOT NULL DEFAULT '1' COMMENT 'count of item',
					  UNIQUE KEY `order_id` (`order_id`,`item_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
				");
			}


			#plug_catalog_orders_users
			$res = $this->db->query("SELECT count(*) FROM `plug_catalog_orders_users`");
			if(!$res)
			{
				$this->db->query("
					CREATE TABLE IF NOT EXISTS `plug_catalog_orders_users` (
					  `order_id` int(11) NOT NULL COMMENT 'order ID',
					  `user_name` varchar(255) NOT NULL COMMENT 'username',
					  `user_email` varchar(255) NOT NULL COMMENT 'email of user',
					  `user_phone` varchar(255) NOT NULL COMMENT 'phone of user',
					  `region_id` int(11) NOT NULL COMMENT 'region ID',
					  `city_id` int(11) NOT NULL COMMENT 'city ID',
					  `address_street` varchar(255) NOT NULL COMMENT 'street',
					  `address_build` int(11) NOT NULL COMMENT 'build',
					  `address_liter` varchar(255) NOT NULL COMMENT 'liter of build',
					  `address_entrance` int(11) NOT NULL COMMENT 'entrance',
					  `address_floor` int(11) NOT NULL COMMENT 'floor',
					  `address_room` varchar(255) NOT NULL COMMENT 'room',
					  UNIQUE KEY `order_id` (`order_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
				");
			}
			


			#add new words
			$res = $this->db->query("SELECT `id` FROM `words` WHERE `word_key`='plug_catalog_saveorder_ok'");
			$resArray = $res->fetch();
			if(empty($resArray))
			{
				$this->db->query("
					INSERT INTO `words` SET `word_key`='plug_catalog_saveorder_ok', `word_value`='Спасибо. Ваша заявка принята.', `word_desc`='Каталог. Оформить заказ. Сообщение успешно принятой заявки.'
				");
			}



		}
		
		public function ajax( $postArray = array() )
		{
			if( !isset( $postArray['action'] ) ) die('ooops');
			
			switch( $postArray['action'] )
			{
				
				#view import template
				case 'import':
					$this->template->display('import.tpl');
				break;

				#set current active category ID
				case 'setActiveCategory': 
					$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] = $postArray['id'];
					die();
				break;
				
				#view template add categories
				case 'add_cat':
					$postArray['pid'] = ( isset( $postArray['pid'] ) ) ? $postArray['pid'] : 0;
					$this->template->assign("pid", $postArray['pid']);
					$this->template->display('add_cat.tpl');
				break;	
				
				#view template edit current category
				case 'edit_cat':
					$postArray['id'] = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'];

					$res = $this->db->prepare(" SELECT * FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1 ");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
					
					if( $resArray['cat_logo']!="" && file_exists($this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$resArray['cat_logo'])) $resArray['full_logo_src'] = $this->categoryImagesPath.$this->getFolderById	( $postArray['id']).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$resArray['cat_logo'];

					$this->template->assign('cat', $resArray);

					$this->template->assign('categoryLogosSizer', $this->categoryLogosSizer);
					$this->template->display('edit_cat.tpl');
				break;
				
				#view template categories list
				case 'cat_list':
					$this->loadCatList();
					if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id']) ) $this->template->assign('currentCatId', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id']);
					$this->template->assign('categories', $this->catList);
					$this->template->display('cat_list.tpl');
				break;
				
				#event change categories range
				case 'catRangeChange':
					unset( $postArray['action'] );
					
					$res = $this->db->prepare(" UPDATE `plug_catalog_cat` SET `cat_range`=:cat_range WHERE `id`=:id");
					foreach( $postArray as $cat_range => $id )
					{
						$res->bindValue(':cat_range', $cat_range);
						$res->bindValue(':id', $id);
						$res->execute();
					} 
				break;
				
				#add new category event
				case 'addCategory':
				
					#validate str set
					$this->validate->emptyLink = 'Укажите адрес категории';
					$this->validate->incorrectLink = 'Адрес категории может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
					
					#check link
					$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_cat` WHERE `link`=:link AND `pid`=:pid  ");
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':pid', $postArray['pid']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется. Задайте другой линк для категории';
						
						die( json_encode($tmpArray) );
					}					
					
					#compile url for new category
					if( $postArray['pid'] !='0' )
					{
						$res = $this->db->prepare(" SELECT `url` FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1 ");
						$res->bindValue(':id', $postArray['pid']);
						$res->execute();
						$resArray = $res->fetch();
						$url = $resArray['url'].$postArray['link'].'/';
					
					}else{
						$url = '/'.$postArray['link'].'/';
					}					
					
					#write to db
					$res = $this->db->prepare("INSERT INTO `plug_catalog_cat` SET `pid`=:pid, `name`=:name, `link`=:link, `url`=:url");
					$res->bindValue(':pid', $postArray['pid']);
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':url', $url);
					$res->execute();
					
					$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] = $this->db->lastInsertId();
					
					#log
					$this->core->log('Каталог', 'Создание категории "'.$postArray['name'].'"');

					#show result
					die($this->validate->error);			
					
				break;	
				
				#save edited category
				case 'editCategory':

					#validate str set
					$this->validate->emptyLink = 'Укажите адрес категории';
					$this->validate->incorrectLink = 'Адрес категории может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
					
					$postArray['id'] = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'];

					#check link
					$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_cat` WHERE `link`=:link AND `pid`=:pid AND `id`<>:id ");
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':pid', $postArray['pid']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется. Задайте другой линк для категории';
						
						die( json_encode($tmpArray) );
					}	
					





					#get current cat data
					$res = $this->db->prepare("SELECT `cat_logo` FROM `plug_catalog_cat` WHERE  `id`=:id ");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
					
					$cat_logo = $resArray['cat_logo'];
					if( $postArray['cat_logo'] == '' ) $postArray['cat_logo'] = $cat_logo;

					#save logo
					if( $postArray['cat_logo']!='' &&  $postArray['cat_logo']!='remove' && $postArray['cat_logo'] != $cat_logo)
					{

						foreach( $this->categoryLogosSizer as $size )
						{
							if( !is_dir( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/' ) ) mkdir( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/', 0777, true );
						
							if( $cat_logo !='' )
							{
								if( file_exists( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$cat_logo  ) ) unlink( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$cat_logo);
							}
						}

						#get file external
						$tmpArr = explode(".", basename($postArray['cat_logo']));
						$fileExternal = strtolower(array_pop($tmpArr)); 
						unset($tmpArr);
						
						#set output full filename
						$outputName = uniqid().'.'.$fileExternal;
			
						#crop logo
						$this->img->source = $this->root.$postArray['cat_logo'];
						$this->img->output = $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->categoryLogosSizer[0][0].'x'.$this->categoryLogosSizer[0][1].'/'.$outputName;
						$this->img->crop( $postArray['x'], $postArray['y'], $postArray['w'], $postArray['h'], true ) or die( $this->img->errorReport."-".$this->root.$postArray['cat_logo'] );
						
						#resampling logo
						foreach( $this->categoryLogosSizer as $index => $size )
						{
							if( $index==0 )
							{
								$this->img->source = $this->img->output;
								$this->img->resize( $size[0], $size[1], true );
							}else{
								$this->img->source = $this->img->output;
								$this->img->output = $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$outputName;
								$this->img->resize( $size[0], $size[1], true );	
							}
						}
						
						$postArray['cat_logo'] = basename( $this->img->output );
					}					
					
					#remove logo
					if( $postArray['cat_logo']=="remove" && $cat_logo != "" )
					{
						
						foreach( $this->categoryLogosSizer as $size )
						{
							if( file_exists( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$cat_logo  ) ) unlink( $this->root.$this->categoryImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$cat_logo);
						}

						$postArray['cat_logo'] = "";
					}


					#compile url for new category
					if( $postArray['pid'] !='0' )
					{
						$res = $this->db->prepare(" SELECT `url` FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1 ");
						$res->bindValue(':id', $postArray['pid']);
						$res->execute();
						$resArray = $res->fetch();
						$url = $resArray['url'].$postArray['link'].'/';
					
					}else{
						$url = '/'.$postArray['link'].'/';
					}	
					
					$hide = ( isset($postArray['hide']) ) ? '1' : '0';
					$off = ( isset($postArray['off']) ) ? '1' : '0';
					
					#write to db
					$res = $this->db->prepare("
						UPDATE 
							`plug_catalog_cat`
						SET 
							`pid`=:pid,
							`name`=:name, 
							`cat_logo`=:cat_logo, 
							`link`=:link, 
							`url`=:url,
							`hide`=:hide,
							`off`=:off
						WHERE 
							`id`=:id
						LIMIT 1
						");
					$res->bindValue(':pid', $postArray['pid']);
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':cat_logo', $postArray['cat_logo']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':url', $url);
					$res->bindValue(':hide', $hide);
					$res->bindValue(':off', $off);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();					
					
					#log
					$this->core->log('Каталог', 'Редактирование категории "'.$postArray['name'].'"');

					#show result
					die($this->validate->error);					
					
				break;				
				
				#event remove category
				case 'removeCategory':
				
					#add to ids array this category id
					$id_arr = array( $postArray['id'] => $postArray['id'] );

					#get first childrens of this category...
					$res = $this->db->prepare("SELECT `id` FROM `plug_catalog_cat` WHERE `pid`=:pid ");
					$res->bindValue(':pid', $postArray['id']);
					$res->execute();
					$resArray = $res->fetchAll();

					#...and append them id to id_arr
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
						#inc fuse
						$fuse++;
						
						#if cicle more 5000 steps then break
						if( $fuse > 5000 ) break;
						
						#compile query
						$in = implode( ',', $id_arr );
						$res = $this->db->prepare( "SELECT `id` FROM `plug_catalog_cat` WHERE `pid` IN (".$in.") ");
						$res->execute();
						$resArray = $res->fetchAll();
						
						#if result not empty then add to id_arr them id
						if( !empty($resArray) )
						{
							foreach( $resArray as $rows )
							{
								$id_arr[ $rows['id'] ] = $rows['id'];
							}
						}
					}
					
					$in = implode( ',', $id_arr );
					


					#get categories images and remove
					$res = $this->db->query("
						SELECT
							`id`,
							`cat_logo`
						FROM
							`plug_catalog_cat`
						WHERE 
							`id` IN (".$in.") 
					");
					$resArray = $res->fetchAll();
					
					foreach( $resArray as $cat )
					{
						$catLogoFileName = $cat['cat_logo'];

						#remove images
						foreach( $this->categoryLogosSizer as $size )
						{
							if( file_exists( $this->root.$this->categoryImagesPath.$this->getFolderById	( $cat['id'] ).'/'.$size[0].'x'.$size[1].'/'.$catLogoFileName ) )
							unlink ( $this->root.$this->categoryImagesPath.$this->getFolderById	( $cat['id'] ).'/'.$size[0].'x'.$size[1].'/'.$catLogoFileName );
						}
					}


					#get category name for log
					$lres = $this->db->prepare("SELECT `name` FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1");
					$lres->bindValue(':id', $postArray['id']);
					$lres->execute();
					$lresArray = $lres->fetch();

					#log
					$this->core->log('Каталог', 'Удаление категории "'.$lresArray['name'].'"');		


					#remove categories
					$this->db->query(" DELETE FROM `plug_catalog_cat` WHERE `id` IN (".$in.") " );
					
					#remove items
					if( !isset($postArray['removeOnlyCat'])) $this->db->query(" DELETE FROM `plug_catalog_items` WHERE `cat_id` IN (".$in.") " );
					
					if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] ) && isset( $id_arr[ $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['catalog']['cat_id'] ] ) ) $this->setFirstCatIsActive();
	
				break;		
				
				#view add brand tpl
				case 'add_brand':
					$this->template->display("add_brand.tpl");
				break;
				
				#view edit brand tpl
				case 'edit_brand':
					
					$res = $this->db->prepare("SELECT * FROM `plug_catalog_brands` WHERE `id`=:id LIMIT 1");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
				
					if( $resArray['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->brandLogosSizer[0][0].'x'.$this->brandLogosSizer[0][1].'/'.$resArray['brand_logo'])) $resArray['full_logo_src'] = $this->brandImagesPath.$this->getFolderById	( $postArray['id']).'/'.$this->brandLogosSizer[0][0].'x'.$this->brandLogosSizer[0][1].'/'.$resArray['brand_logo'];
					$this->template->assign('brandLogosSizer', $this->brandLogosSizer);
					$this->template->assign("brand", $resArray);
					$this->template->display("edit_brand.tpl");
				break;	
				
				#event change brands range
				case 'brandsRangeChange':
					unset( $postArray['action'] );
					
					$res = $this->db->prepare(" UPDATE `plug_catalog_brands` SET `brand_range`=:brand_range WHERE `id`=:id");
					foreach( $postArray as $brand_range => $id )
					{
						$res->bindValue(':brand_range', $brand_range);
						$res->bindValue(':id', $id);
						$res->execute();
					} 
				break;
				
				#add new brand event
				case 'addBrand':
				
					#validate str set
					$this->validate->emptyLink = 'Укажите адрес бренда';
					$this->validate->incorrectLink = 'Адрес бренда может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
					
					#check link
					$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_brands` WHERE `link`=:link ");
					$res->bindValue(':link', $postArray['link']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется. Задайте другой линк для бренда';
						
						die( json_encode($tmpArray) );
					}					
					
					#write to db
					$res = $this->db->prepare("
						INSERT INTO 
							`plug_catalog_brands` 
						SET 
							`name`=:name,
							`link`=:link,
							`create_date`=:create_date,
							`create_time`=:create_time,
							`update_date`=:update_date,
							`update_time`=:update_time,
							`brand_range`=:brand_range");
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':create_date', date("Y-m-d"));
					$res->bindValue(':create_time', date("H:i:s"));
					$res->bindValue(':update_date', date("Y-m-d"));
					$res->bindValue(':update_time', date("H:i:s"));
					$res->bindValue(':brand_range', '0');
					$res->execute();
					
					$this->core->log('Каталог', 'Создание бренда "'.$postArray['name'].'"');	

					#show result
					die($this->validate->error);			
					
				break;				
				
				#save edited brand
				case 'editBrand':
					
					#validate str set
					$this->validate->emptyLink = 'Укажите адрес страницы бренда';
					$this->validate->incorrectLink = 'Адрес страницы бренда может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
					
					#check link
					$res = $this->db->prepare("SELECT `id` FROM `plug_catalog_brands` WHERE `link`=:link AND `id`<>:id ");
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется. Задайте другой линк для бренда';
						
						die( json_encode($tmpArray) );
					}	
					
					#get current brand data
					$res = $this->db->prepare("SELECT `brand_logo` FROM `plug_catalog_brands` WHERE  `id`=:id ");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
					
					$brand_logo = $resArray['brand_logo'];
					if( $postArray['brand_logo'] == '' ) $postArray['brand_logo'] = $brand_logo;

					#save logo
					if( $postArray['brand_logo']!='' &&  $postArray['brand_logo']!='remove' && $postArray['brand_logo'] != $brand_logo)
					{

						foreach( $this->brandLogosSizer as $size )
						{
							if( !is_dir( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/' ) ) mkdir( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/', 0777, true );
						
							if( $brand_logo !='' )
							{
								if( file_exists( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brand_logo  ) ) unlink( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brand_logo);
							}
						}

						#get file external
						$tmpArr = explode(".", basename($postArray['brand_logo']));
						$fileExternal = strtolower(array_pop($tmpArr)); 
						unset($tmpArr);
						
						#set output full filename
						$outputName = uniqid().'.'.$fileExternal;
			
						#crop logo
						$this->img->source = $this->root.$postArray['brand_logo'];
						$this->img->output = $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->brandLogosSizer[0][0].'x'.$this->brandLogosSizer[0][1].'/'.$outputName;
						$this->img->crop( $postArray['x'], $postArray['y'], $postArray['w'], $postArray['h'], true ) or die( $this->img->errorReport."-".$this->root.$postArray['brand_logo'] );
						
						#resampling logo
						foreach( $this->brandLogosSizer as $index => $size )
						{
							if( $index==0 )
							{
								$this->img->source = $this->img->output;
								$this->img->resize( $size[0], $size[1], true );
							}else{
								$this->img->source = $this->img->output;
								$this->img->output = $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$outputName;
								$this->img->resize( $size[0], $size[1], true );	
							}
						}
						
						$postArray['brand_logo'] = basename( $this->img->output );
					}					
					
					#remove logo
					if( $postArray['brand_logo']=="remove" && $brand_logo != "" )
					{
						
						foreach( $this->brandLogosSizer as $size )
						{
							if( file_exists( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brand_logo  ) ) unlink( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brand_logo);
						}

						$postArray['brand_logo'] = "";
					}
					
					
					#visibility
					$postArray['hide_in_list'] = ( isset( $postArray['hide_in_list'] ) ) ? '1' : '0';
					$postArray['disabled'] = ( isset( $postArray['disabled'] ) ) ? '1' : '0';
					
					
					#write in DB
					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_brands`
						SET 
							`name`=:name,
							`link`=:link,
							`offsite`=:offsite,
							`brand_quick_desc`=:brand_quick_desc,
							`brand_descr`=:brand_descr,
							`brand_logo`=:brand_logo,
							`hide_in_list`=:hide_in_list,
							`disabled`=:disabled,
							`meta_title`=:meta_title,
							`meta_keywords`=:meta_keywords,
							`meta_description`=:meta_description,
							`extra_meta`=:extra_meta,
							`external_id`=:external_id
						WHERE 
							`id`=:id
						");
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':offsite', $postArray['offsite']);
					$res->bindValue(':brand_quick_desc', $postArray['brand_quick_desc']);
					$res->bindValue(':brand_descr', $postArray['brand_descr']);
					$res->bindValue(':brand_logo', $postArray['brand_logo']);
					$res->bindValue(':hide_in_list', $postArray['hide_in_list']);
					$res->bindValue(':disabled', $postArray['disabled']);
					$res->bindValue(':meta_title', $postArray['meta_title']);
					$res->bindValue(':meta_keywords', $postArray['meta_keywords']);
					$res->bindValue(':meta_description', $postArray['meta_description']);
					$res->bindValue(':extra_meta', $postArray['extra_meta']);
					$res->bindValue(':external_id', $postArray['external_id']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					
					$this->core->log('Каталог', 'Редактирование бренда "'.$postArray['name'].'"');

					die($this->validate->error);
					
				break;
				
				#set brand property
				case 'setBrandProperty':
				
					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_brands`
						SET
							`".$postArray['property']."`=:set
						WHERE 
							`id`=:id 
						LIMIT 1
					");
					$res->bindValue(':set', $postArray['set']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();

					$logRes = $this->db->prepare("SELECT `name` FROM `plug_catalog_brands` WHERE `id`=:id LIMIT 1");
					$logRes->bindValue(':id', $postArray['id']);
					$logRes->execute();
					$logResArray = $logRes->fetch();
					$this->core->log('Каталог', 'Редактирование бренда "'.$logResArray['name'].'"');

				break;
				
				#remove brand
				case 'removeBrand':
					#get brand image file name
					$res = $this->db->prepare("
						SELECT
							`brand_logo`
						FROM
							`plug_catalog_brands`
						WHERE 
							`id`=:id 
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
					$brandLogoFileName = $resArray['brand_logo'];
				
					#unset brand from items
					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_items`
						SET
							`brand_id`=0
						WHERE 
							`brand_id`=:id 
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					
					$logRes = $this->db->prepare("SELECT `name` FROM `plug_catalog_brands` WHERE `id`=:id LIMIT 1");
					$logRes->bindValue(':id', $postArray['id']);
					$logRes->execute();
					$logResArray = $logRes->fetch();
					$this->core->log('Каталог', 'Удаление бренда "'.$logResArray['name'].'"');

					#remove brand
					$res = $this->db->prepare("
						DELETE FROM
							`plug_catalog_brands`
						WHERE 
							`id`=:id
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					
					#remove images
					foreach( $this->brandLogosSizer as $size )
					{
						if( file_exists( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brandLogoFileName ) )
						unlink ( $this->root.$this->brandImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$brandLogoFileName );
					}	
					
				break;
				
				
				#view brands list
				case 'brands_list':
					
					$page = ( isset($postArray['page']) ) ? (int)$postArray['page'] : 1;
					$count = ( isset($postArray['countonpage']) ) ? (int)$postArray['countonpage'] : 20;
					
					#get total lines in DB
					$res = $this->db->query("SELECT count(*) FROM `plug_catalog_brands` ");
					$resArray = $res->fetch();					
					
					#total lines in DB
					$total = intval( ($resArray[0] - 1) / $count) + 1; 
					
					#set current page
					$page = intval($page); 
					if(empty($page) or $page < 0) $page = 1; 
					if($page > $total) $page = $total;
					
					#set start line
					$start = $page * $count - $count;
		
		
					$res = $this->db->prepare("
						SELECT 
							`b`.`id`,
							`b`.`external_id`,
							`b`.`link`,
							`b`.`name`,
							`b`.`brand_logo`,
							`b`.`hide_in_list`,
							`b`.`disabled`
						FROM 
							`plug_catalog_brands` AS `b`
						ORDER BY
							`b`.`brand_range`
						LIMIT
							:start,:num
					");
					$res->bindValue(':start', $start, PDO::PARAM_INT);
					$res->bindValue(':num', $count, PDO::PARAM_INT);
					$res->execute();
					$resArray = $res->fetchAll();
					if(!empty($resArray))
					{
						#get navArray
						#set arrow left
						$navArray = array();
				
						$toLeft = 5;
						$toRight = 5;
				
						#set pagers
						for( $i=1;$i<($total+1);$i++ ) 
						{
							if(  $page - $toLeft < $i && $page + $toRight > $i ) 
							{
								$navArray[] = array( 'num'=> $i, 'title'=>'На страницу '.$i, 'text'=>$i,'class'=>( $i==$page ) ? 'act' : '');
							}
						}
				
						#set arrows
						if( $page!=1 ) array_unshift( $navArray, array(	'num'=> 1, 'title'=>'К первой странице', 'text'=> '&laquo;', 'class'=> 'start'), array( 'num'=> $page-1, 'title'=>'Назад', 'text'=> '&larr;', 'class'=> 'back' ) );
						if( $page!=$total ) array_push( $navArray,  array('num'=> $page+1, 'title'=>'Вперед', 'text'=> '&rarr;','class'=> 'next'), array('num'=> $total, 'title'=>'К последней странице', 'text'=> '&raquo;','class'=> 'end') );
		
						
						foreach($resArray as &$value)
						{
							if( $value['brand_logo']!="" && file_exists($this->root.$this->brandImagesPath.$this->getFolderById	( $value['id'] ).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$value['brand_logo'])) $value['full_logo_src'] = $this->brandImagesPath.$this->getFolderById	( $value['id'] ).'/'.$this->brandLogosSizer[2][0].'x'.$this->brandLogosSizer[2][1].'/'.$value['brand_logo'];
						}
						
						$this->template->assign('brandsArray', $resArray);
						$this->template->assign('countonpage', $count);
						if( count( $navArray )>1 ) $this->template->assign('navArray', $navArray);

					}else{
						$this->template->assign('empty', 'empty');
					}

					$this->template->display('brands_list.tpl');

				break;
				
				
				#view items list
				case 'items_list':

					$filterArray = array();


					if(isset($postArray['filter']))
					{
						foreach( $postArray['filter'] as $filterName => $filterVal)
						{	
							switch( $filterName )
							{
								case 'cat_filter': if($filterVal!='0') $filterArray[] = "AND `c`.`id`='".(int)$postArray['filter']['cat_filter']."'"; break;
								case 'brands_filter': if($filterVal!='0') $filterArray[] = "AND `b`.`id`='".(int)$postArray['filter']['brands_filter']."'"; break;
								case 'filter_is': 
									if($filterVal!='is_all'){
										switch( $filterVal )
										{
											case 'is_new_filter': $filterArray[] = "AND `i`.`is_new`='1'"; break;
											case 'is_sale_filter': $filterArray[] = "AND `i`.`is_sale`='1'"; break;
											case 'is_best_filter': $filterArray[] = "AND `i`.`is_best`='1'"; break;
											case 'is_markdown_filter': $filterArray[] = "AND `i`.`is_markdown`='1'"; break;
										}
									}
								break;
								case 'hide_in_list_filter': $filterArray[] = "AND `i`.`hide_in_list`='1'"; break;
								case 'in_stock_filter': $filterArray[] = "AND `i`.`in_stock`='1'"; break;
								case 'disabled_filter': $filterArray[] = "AND `i`.`disabled`='1'"; break;
							}
						}

						$this->template->assign('filterArray', $postArray['filter']);						
					}
 

					#get all categories for the filter
					$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_cat` ORDER BY `pid`, `cat_range`");
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						if(isset($postArray['filter']['cat_filter']) && $postArray['filter']['cat_filter']!='0')
						{
							foreach($resArray as &$cat)
							{
								if( $cat['id'] == $postArray['filter']['cat_filter']) $cat['active'] = '1';
							}
						}
						$this->template->assign('catList', $resArray);
					}

					#get all brands for the filter
					$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_brands` ORDER BY `brand_range`");
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						if(isset($postArray['filter']['brands_filter']) && $postArray['filter']['brands_filter']!='0')
						{
							foreach($resArray as &$brand)
							{
								if( $brand['id'] == $postArray['filter']['brands_filter']) $brand['active'] = '1';
							}
						}
						$this->template->assign('brandsList', $resArray);
					}

					$filterStr = (!empty($filterArray)) ? "WHERE `i`.`id`<>'0' ".implode(' ', $filterArray) : "";

					$page = ( isset($postArray['page']) ) ? (int)$postArray['page'] : 1;
					$count = ( isset($postArray['countonpage']) ) ? (int)$postArray['countonpage'] : 20;

					#get total lines in DB
					$res = $this->db->query("
						SELECT 
							count(*) 
						FROM 
							`plug_catalog_items` AS `i` 
						LEFT JOIN 
							`plug_catalog_brands` AS `b`
						ON 
							`b`.`id`=`i`.`brand_id` 
						LEFT JOIN
							`plug_catalog_cat` AS `c`
						ON
							`c`.`id`=`i`.`cat_id`
						".$filterStr
					);
					$resArray = $res->fetch();					
					
					#total lines in DB
					$total = intval( ($resArray[0] - 1) / $count) + 1; 
					
					#set current page
					$page = intval($page); 
					if(empty($page) or $page < 0) $page = 1; 
					if($page > $total) $page = $total;
					
					#set start line
					$start = $page * $count - $count;
				
					$res = $this->db->prepare("
						SELECT 
							`i`.`id`,
							`i`.`external_id`,
							`i`.`articul`,
							`i`.`name`,
							`i`.`hide_in_list`,
							`i`.`disabled`,
							`i`.`link`,
							`i`.`cat_id`,
							`i`.`in_stock`,
							`i`.`is_sale`,
							`i`.`is_new`,
							`i`.`is_best`,
							`i`.`is_markdown`,
							`i`.`count`,
							`i`.`item_logo`,
							`i`.`price`,
							`i`.`create_date`,
							`i`.`update_date`,
							`b`.`name` AS `brand_name`,
							`c`.`name` AS `category_name`
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
						".$filterStr."
						ORDER BY
							`i`.`create_date`DESC, `i`.`create_time`DESC 
						LIMIT
							:start,:num
					");
					$res->bindValue(':start', $start, PDO::PARAM_INT);
					$res->bindValue(':num', $count, PDO::PARAM_INT);
					$res->execute();
					$resArray = $res->fetchAll();
				
					if(!empty($resArray))
					{
						
						foreach( $resArray as &$item )
						{
							if( $item['create_date']=='0000-00-00' ) continue;
							$date = new DateTime( $item['create_date'] );
							$item['create_date'] = $date->format("d-m-Y");
						}
						
						#get navArray
						#set arrow left
						$navArray = array();
				
						$toLeft = 5;
						$toRight = 5;
				
						#set pagers
						for( $i=1;$i<($total+1);$i++ ) 
						{
							if(  $page - $toLeft < $i && $page + $toRight > $i ) 
							{
								$navArray[] = array( 'num'=> $i, 'title'=>'На страницу '.$i, 'text'=>$i,'class'=>( $i==$page ) ? 'act' : '');
							}
						}
				
						#set arrows
						if( $page!=1 ) array_unshift( $navArray, array(	'num'=> 1, 'title'=>'К первой странице', 'text'=> '&laquo;', 'class'=> 'start'), array( 'num'=> $page-1, 'title'=>'Назад', 'text'=> '&larr;', 'class'=> 'back' ) );
						if( $page!=$total ) array_push( $navArray,  array('num'=> $page+1, 'title'=>'Вперед', 'text'=> '&rarr;','class'=> 'next'), array('num'=> $total, 'title'=>'К последней странице', 'text'=> '&raquo;','class'=> 'end') );
	
						
						foreach($resArray as &$value)
						{
							if( $value['item_logo']!="" && file_exists($this->root.$this->itemImagesPath.$this->getFolderById	( $value['id'] ).'/'.$this->itemLogosSizer[3][0].'x'.$this->itemLogosSizer[3][1].'/'.$value['item_logo'])) $value['full_logo_src'] = $this->itemImagesPath.$this->getFolderById	( $value['id'] ).'/'.$this->itemLogosSizer[3][0].'x'.$this->itemLogosSizer[3][1].'/'.$value['item_logo'];
						}
						
						$this->template->assign('countonpage', $count);
						$this->template->assign('itemsArray', $resArray);
						if( count( $navArray )>1 ) $this->template->assign('navArray', $navArray);
					}	
					$this->template->display('items_list.tpl');
				break;
				
				#view add item template
				case 'add_item';
					$this->template->display('add_item.tpl');
				break;
				
				#view types attributes template
				case 'types_attr';
					
					#get types
					$res = $this->db->query("SELECT * FROM `plug_catalog_types`");
					$resArray = $res->fetchAll();
					
					$typesArray = array();
					
					if( !empty( $resArray ) )
					{
						$typesArray = array();
						foreach( $resArray as $types )
						{
							$typesArray[ $types['id'] ] = $types;
						}
					}
					
					#get types attributes
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_attr`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as $attr )
						{
							$typesArray[ $attr['type_id'] ]['attributes'][$attr['id']] = $attr;
						}
					}
					
					
					#get types valuea
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_vals`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as $vals )
						{
							$typesArray[ $vals['type_id'] ]['attributes'][$vals['attr_id']]['values'][ $vals['id'] ] = $vals['value'];
						}
					}

					$this->template->assign('typesArray', $typesArray);
					
					$this->template->display('types_attr.tpl');
				break;
				
				#view item types template
				case 'item_types':
					$res = $this->db->query("SELECT * FROM `plug_catalog_types`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) ) 
					{
						foreach( $resArray as &$dataArray )
						{
							$dataArray['atr_name'] = 't_'.uniqid();
						}
						$this->template->assign('typesArray', $resArray);
					}
					
					$this->template->display('item_types.tpl');
				break;
				
				case 'clearAttrAndValues':
					
					#get types
					$res = $this->db->query("SELECT * FROM `plug_catalog_types` ORDER BY `name`");
					$resArray = $res->fetchAll();
					
					$typesArray = array();
					
					if( !empty( $resArray ) )
					{
						$typesArray = array();
						foreach( $resArray as $types )
						{
							$typesArray[ $types['id'] ] = $types;
						}
					}
					
					#get types attributes
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_attr` ORDER BY `name`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as $attr )
						{
							$typesArray[ $attr['type_id'] ]['attributes'][$attr['id']] = $attr;
						}
					}
					
					
					#get types valuea
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_vals` ORDER BY `value`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as &$vals )
						{
							if( !isset($typesArray[ $vals['type_id'] ]['attributes'][$vals['attr_id']]['name']) ){

								$toDeleteAttrArray[ $vals['attr_id'] ] = $vals['attr_id'];
							}
							$typesArray[ $vals['type_id'] ]['attributes'][$vals['attr_id']]['values'][ $vals['id'] ] = $vals['value'];
						}
					}
					
					#delete empty data
					if( isset( $toDeleteAttrArray ) )
					{
						$query = "DELETE FROM `plug_catalog_types_vals` WHERE `attr_id` IN(".implode(',', $toDeleteAttrArray).")";
						$this->db->query( $query );
					}
					


				break;
				
				#view types attributes values template
				case 'types_attr_vals':
					
					#get types
					$res = $this->db->query("SELECT * FROM `plug_catalog_types` ORDER BY `name`");
					$resArray = $res->fetchAll();
					
					$typesArray = array();
					
					if( !empty( $resArray ) )
					{
						$typesArray = array();
						foreach( $resArray as $types )
						{
							$typesArray[ $types['id'] ] = $types;
						}
					}
					
					#get types attributes
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_attr` ORDER BY `name`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as $attr )
						{
							$typesArray[ $attr['type_id'] ]['attributes'][$attr['id']] = $attr;
						}
					}
					
					
					#get types valuea
					$res = $this->db->query("SELECT * FROM `plug_catalog_types_vals` ORDER BY `value`");
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						foreach( $resArray as &$vals )
						{
							$typesArray[ $vals['type_id'] ]['attributes'][$vals['attr_id']]['values'][ $vals['id'] ] = $vals['value'];
						}
					}
					
					$this->template->assign('typesArray', $typesArray);
					$this->template->display('types_attr_vals.tpl');
				break;
				
				
				case 'saveTypesAttr':
					
					if( isset( $postArray['removed'] ) )
					{
						$this->db->query("DELETE FROM `plug_catalog_types_vals` WHERE `attr_id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_types_attr` WHERE `id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_item_attr_vals` WHERE `attr_id` IN(".implode(',', $postArray['removed']).")");
						unset( $postArray['removed'] );
					}
					#get type id
					$typeId = $postArray['typeId'];
					
					#clear other keys
					unset( $postArray['action'] );
					unset( $postArray['typeId'] );
					
					#remove all data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_types_attr` WHERE `type_id`=:type_id");
					$res->bindValue( ':type_id', $typeId, PDO::PARAM_INT );
					$res->execute();
					
					#save data
					if( !empty( $postArray ) )
					{
						$query = "INSERT INTO `plug_catalog_types_attr` (`id`, `type_id`, `name`, `units`, `selector`, `in_filter`, `in_list`) VALUES ";
						
						foreach( $postArray as $attrId => $attrData )
						{
							$query .= "('".$attrId."', '".$typeId."', '".$attrData['name']."', '".$attrData['units']."', 'select', '".$attrData['inFilter']."', '".$attrData['inList']."'),";
						}
						
						$query = preg_replace('/,$/', '', $query);
						$this->db->query( $query );
					}

					$this->core->log('Каталог', 'Редактирование типов');

				break;
				
				#save types attributes
				case 'saveTypesAttrVals' :
					
					if( isset( $postArray['removed'] ) )
					{
						$this->db->query("DELETE FROM `plug_catalog_item_attr_vals` WHERE `value_id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_types_vals` WHERE `id` IN(".implode(',', $postArray['removed']).")");
						unset( $postArray['removed'] );
					}
					
					#get type id
					$typeId = $postArray['typeId'];
					$attrId = $postArray['attrId'];

					#clear other keys
					unset( $postArray['action'] );
					unset( $postArray['typeId'] );
					unset( $postArray['attrId'] );
					
					#remove all data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_types_vals` WHERE `type_id`=:type_id AND `attr_id`=:attr_id");
					$res->bindValue( ':type_id', $typeId, PDO::PARAM_INT );
					$res->bindValue( ':attr_id', $attrId, PDO::PARAM_INT );
					$res->execute();
					
					#save data
					if( !empty( $postArray ) )
					{
						$query = "INSERT INTO `plug_catalog_types_vals` (`id`, `type_id`, `attr_id`, `value`) VALUES ";
						
						foreach( $postArray as $valueId => $value )
						{
							if( is_numeric( $valueId ) )
							{
								$query .= "('".$valueId."', '".$typeId."', '".$attrId."', '".$value."'),";
							}else{
								$query .= "(NULL, '".$typeId."', '".$attrId."', '".$value."'),";
							}
						}
						
						$query = preg_replace('/,$/', '', $query);
						$this->db->query( $query );
					}
					
					$this->core->log('Каталог', 'Редактирование значений типов');
				break;
				
				case 'saveTypes':
					
					if( isset( $postArray['removed'] ) )
					{
						$this->db->query("DELETE FROM `plug_catalog_types` WHERE `id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_types_attr` WHERE `type_id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_types_vals` WHERE `type_id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("DELETE FROM `plug_catalog_item_attr_vals` WHERE `type_id` IN(".implode(',', $postArray['removed']).")");
						$this->db->query("UPDATE `plug_catalog_items` SET `type_id`='0' WHERE `type_id` IN(".implode(',', $postArray['removed']).")");
						unset( $postArray['removed'] );
					}

					
					unset( $postArray['action'] );
					$this->db->query("DELETE FROM `plug_catalog_types`");
					
					$query = "
						INSERT INTO 
							`plug_catalog_types` 
								(`id`, `name`) 
							VALUES
					";					
					
					$params = "";
					
					foreach( $postArray as $type ){
						if( is_numeric( $type['id'] ) )
						{
							$params .= "('".$type['id']."', '".$type['value']."'),";
						}else{
							$params .= "( NULL, '".$type['value']."'),";
						}
					}
					$params = preg_replace('/,$/', '', $params);
					
					if( $params!="" )
					{
						$this->db->query( $query.$params );
					}
					
					$this->core->log('Каталог', 'Редактирование типов');
				break;
				
				#add new item event
				case 'addItem':
				
					#validate str set
					$this->validate->emptyLink = 'Укажите адрес товара';
					$this->validate->incorrectLink = 'Адрес товара может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
					
					#check link
					$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_items` WHERE `link`=:link ");
					$res->bindValue(':link', $postArray['link']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется. Задайте другой линк для товара';
						
						die( json_encode($tmpArray) );
					}					
					
					#write to db
					$res = $this->db->prepare("
						INSERT INTO 
							`plug_catalog_items`
						SET
							`name`=:name,
							`link`=:link,
							`create_date`=:create_date,
							`create_time`=:create_time,
							`update_date`=:update_date,
							`update_time`=:update_time,
							`item_range`=:item_range
						");
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':create_date', date("Y-m-d"));
					$res->bindValue(':create_time', date("H:i:s"));
					$res->bindValue(':update_date', date("Y-m-d"));
					$res->bindValue(':update_time', date("H:i:s"));
					$res->bindValue(':item_range', '0');
					$res->execute();
				
					$this->core->log('Каталог', 'Создание товара "'.$postArray['name'].'"');

				
				
					#show result
					die($this->validate->error);			
					
				break;
				
				#reload item attributes
				case 'reloadItemAttr':
					#get item attributes
					$res = $this->db->prepare("
						SELECT
							`attr`.`id` AS `attr_id`,
							`attr`.`type_id`,
							`attr`.`selector` AS `attribute_selector`,
							`attr`.`name` AS `attribute_name`,
							`attr`.`units` AS `attribute_units`,
							`attr`.`in_list`,
							`vals`.`value_id`
						FROM
							`plug_catalog_types_attr` AS `attr`
						LEFT JOIN
							`plug_catalog_item_attr_vals` AS `vals`
						ON
							`vals`.`attr_id`=`attr`.`id`
						WHERE 
							`attr`.`type_id`=:type_id
						GROUP BY
							`attr`.`id`
					");
					$res->bindValue(':type_id', $postArray['typeId'], PDO::PARAM_INT);
					$res->execute();
					$attrArray = $res->fetchAll();
					if( !empty($attrArray) )
					{
						#get item attributes values
						$res = $this->db->prepare("
							SELECT 
								*
							FROM
								`plug_catalog_types_vals`
							WHERE `type_id`=:type_id
						");
						$res->bindValue(':type_id', $postArray['typeId'], PDO::PARAM_INT);
						$res->execute();
						$tmpArray = $res->fetchAll();
						if( !empty( $tmpArray ) )
						{
							
							$attrValuesArray = array();
							foreach( $tmpArray as $data)
							{
								$attrValuesArray[ $data['attr_id'] ] [ $data['id'] ] = array(
									'attr_id'	=>	 $data['attr_id'],
									'id'		=>	 $data['id'],
									'value'		=>	 $data['value']
								);
							}

						}							



						foreach( $attrArray as &$data )
						{
							if( isset( $attrValuesArray[ $data['attr_id'] ] ) ) $data['attributesValues'] = $attrValuesArray[ $data['attr_id'] ];
							$data['value_id'] = '0';
						}

						$this->template->assign('itemAttr', $attrArray);
						$this->template->display('itemAttr.tpl');
					}
					
					
				break;
				
				#view edit item template
				case 'edit_item':
					
					#get categories array
					$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_cat`");
					if( $res )	$this->template->assign('categoriesArray', $res->fetchAll());
										
					#get brands array
					$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_brands`");
					if( $res )	$this->template->assign('brandsArray', $res->fetchAll());
					
					#get item data
					$res = $this->db->prepare("
						SELECT 
							`i`.*,
							`b`.`name` AS `brand_name`,
							`c`.`name` AS `category_name`,
							`t`.`name` AS `type_name`
						FROM 
							`plug_catalog_items` AS `i`
						LEFT JOIN
							`plug_catalog_brands` AS `b`
						ON
							`b`.id = `i`.`brand_id`
						LEFT JOIN
							`plug_catalog_cat` AS `c`
						ON
							`c`.id = `i`.`cat_id`
						LEFT JOIN
							`plug_catalog_types` AS `t`
						ON
							`t`.id=`i`.`type_id`
						WHERE
							`i`.`id`=:id
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['id'], PDO::PARAM_INT);
					$res->execute();
					$resArray = $res->fetch();
					if(!empty($resArray))
					{

						#get other images if is exists
						$res = $this->db->prepare("
							SELECT * FROM
								`plug_catalog_img`
							WHERE
								`item_id`=:item_id
							ORDER BY 
								`img_range`
						");
						$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
						$res->execute();
						$imgArray = $res->fetchAll();
						if(!empty($imgArray))
						{
							foreach($imgArray as &$dataImage)
							{
								$dataImage['filename'] = $this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ).'/'.$dataImage['filename'];
							}
							
							$resArray['other_images'] = $imgArray;
						}

						
						if($resArray['create_date']=="0000-00-00") $resArray['create_date'] = date("Y-m-d");
						if($resArray['create_time']=="00:00:00") $resArray['create_time'] = date("H:i:s");

						if($resArray['update_date']=="0000-00-00") $resArray['update_date'] = date("Y-m-d");
						if($resArray['update_time']=="00:00:00") $resArray['update_time'] = date("H:i:s");

						$date = new DateTime( $resArray['create_date'] );
						$createDate = $date->format("d.m.Y");
						unset($date);

						$date = new DateTime( $resArray['update_date'] );
						$updateDate = $date->format("d.m.Y");
						unset($date);

						$date = new DateTime( $resArray['update_date'] );
						$date->modify('+1 month');
						$expiresDate = $date->format("d.m.Y");
						unset($date);
						
						if( $resArray['item_logo']!="" && file_exists($this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$resArray['item_logo'])) $resArray['full_logo_src'] = $this->itemImagesPath.$this->getFolderById	( $postArray['id']).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$resArray['item_logo'];
						
						$resArray['create_date_time']  = $createDate.' '.$resArray['create_time'];				
						$resArray['update_date_time']  = $updateDate.' '.$resArray['update_time'];				
						$resArray['expires_date_time']  = $expiresDate.' '.$resArray['update_time'];				
						
						
						#get all types
						$res = $this->db->query("SELECT * FROM `plug_catalog_types` ");
						$typesArray = $res->fetchAll();
						if( !empty($typesArray) ) $this->template->assign('typesArray', $typesArray);
						
						
						#get item attributes
						$res = $this->db->prepare("
							SELECT
								`attr`.`id` AS `attr_id`,
								`attr`.`type_id`,
								`attr`.`selector` AS `attribute_selector`,
								`attr`.`name` AS `attribute_name`,
								`attr`.`units` AS `attribute_units`,
								`attr`.`in_list`,
								`vals`.`value_id`
							FROM
								`plug_catalog_types_attr` AS `attr`
							LEFT JOIN
								`plug_catalog_item_attr_vals` AS `vals`
							ON
								`vals`.`attr_id`=`attr`.`id`
							WHERE 
								`vals`.`item_id`=:item_id
						");
						$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
						$res->execute();
						$attrArray = $res->fetchAll();

						if( empty($attrArray) )
						{
							$res = $this->db->prepare("
								SELECT
									`attr`.`id` AS `attr_id`,
									`attr`.`type_id`,
									`attr`.`selector` AS `attribute_selector`,
									`attr`.`name` AS `attribute_name`,
									`attr`.`units` AS `attribute_units`,
									`attr`.`in_list`
								FROM
									`plug_catalog_types_attr` AS `attr`
								WHERE 
									`attr`.`type_id`=:type_id
							");
							$res->bindValue(':type_id', $resArray['type_id'], PDO::PARAM_INT);
							$res->execute();
							$attrArray = $res->fetchAll();
						}
						
						if( !empty($attrArray) )
						{
							#get item attributes values
							$res = $this->db->prepare("
								SELECT 
									*
								FROM
									`plug_catalog_types_vals`
								WHERE `type_id`=:type_id
							");

							$res->bindValue(':type_id', $resArray['type_id'], PDO::PARAM_INT);
							$res->execute();
							$tmpArray = $res->fetchAll();
							if( !empty( $tmpArray ) )
							{
								$attrValuesArray = array();
								foreach( $tmpArray as $data)
								{
									$attrValuesArray[ $data['attr_id'] ] [ $data['id'] ] = array(
										'attr_id'	=>	 $data['attr_id'],
										'id'		=>	 $data['id'],
										'value'		=>	 $data['value']
									);
								}
							}							

							foreach( $attrArray as &$data )
							{
								if( isset( $attrValuesArray[ $data['attr_id'] ] ) ) $data['attributesValues'] = $attrValuesArray[ $data['attr_id'] ];
							}

							$this->template->assign('itemAttr', $attrArray);
						}
						

						#get analogs
						$res = $this->db->prepare("
							SELECT 
								`a`.`analog_id`, 
								`i`.`name` 
							FROM 
								`plug_catalog_item_analogs` AS `a` 
							LEFT JOIN
								`plug_catalog_items` AS `i`
							ON
								`i`.`id`=`a`.`analog_id`
							WHERE 
								`a`.`item_id`=:item_id 
						");
						$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
						$res->execute();
						$analogsArray = $res->fetchAll();
						if( !empty( $analogsArray ) )
						{
							$this->template->assign('analogs', $analogsArray);
							$this->template->assign('analogsCount', count( $analogsArray ));
						}
						
						
						#get accompanying
						$res = $this->db->prepare("
							SELECT 
								`a`.`accompanying_id`, 
								`i`.`name` 
							FROM 
								`plug_catalog_item_accompanying` AS `a` 
							LEFT JOIN
								`plug_catalog_items` AS `i`
							ON
								`i`.`id`=`a`.`accompanying_id`
							WHERE 
								`a`.`item_id`=:item_id 
						");
						$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
						$res->execute();
						$accompanyingArray = $res->fetchAll();
						if( !empty( $accompanyingArray ) ) 
						{
							$this->template->assign('accompanying', $accompanyingArray);
							$this->template->assign('accompanyingCount', count( $accompanyingArray ));
						}
						

						#get comments
						$this->getCommentsList( $postArray['id'] );
						$this->template->assign('comments', $this->itemCommentsArray);

						
						$this->template->assign('itemLogosSizer', $this->itemLogosSizer);
						$this->template->assign('item', $resArray);
						$this->template->display('edit_item.tpl');

					}

				break;

				#get edit comment form
				case 'edit_comment_form':
					$res = $this->db->prepare("
						SELECT * FROM 
							`plug_catalog_comments`
						WHERE
							`id`=:id
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
	
					$date = new DateTime( $resArray['comment_date'] );
					$resArray['comment_date'] = $date->format("d.m.Y");
	
					$this->template->assign('comment', $resArray);
					$this->template->display('edit_comment_form.tpl');
				break;
				
				case 'saveEditedComment':
					$date = new DateTime( $postArray['date_time'] );
					$postArray['comment_date'] = $date->format("Y-m-d");
					$postArray['comment_time'] = $date->format("H:i:s");

					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_comments`
						SET
							`user_name`=:user_name,
							`comment_text`=:comment_text,
							`comment_date`=:comment_date,
							`comment_time`=:comment_time
						WHERE
							`id`=:id
						LIMIT 1
					");
					$res->bindValue(':user_name', $postArray['user_name']);
					$res->bindValue(':comment_text', $postArray['comment_text']);
					$res->bindValue(':comment_date', $postArray['comment_date']);
					$res->bindValue(':comment_time', $postArray['comment_time']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
				break;

				case 'reply_comment_form':
					$this->template->assign('nowDate', date("d.m.Y H:i:s"));
					$this->template->display('reply_comment_form.tpl');
				break;

				case 'addCommentReply':
					$date = new DateTime( $postArray['date_time'] );
					$postArray['comment_date'] = $date->format("Y-m-d");
					$postArray['comment_time'] = $date->format("H:i:s");
					$postArray['pid'] = ( isset($postArray['pid']) ) ? $postArray['pid'] : '0';
	
					$res = $this->db->prepare("
						INSERT INTO
							`plug_catalog_comments`
						SET
							`user_name`=:user_name,
							`comment_text`=:comment_text,
							`comment_date`=:comment_date,
							`comment_time`=:comment_time,
							`pid`=:pid,
							`item_id`=:item_id
					");
					$res->bindValue(':user_name', $postArray['user_name']);
					$res->bindValue(':comment_text', $postArray['comment_text']);
					$res->bindValue(':comment_date', $postArray['comment_date']);
					$res->bindValue(':comment_time', $postArray['comment_time']);
					$res->bindValue(':pid', $postArray['pid']);
					$res->bindValue(':item_id', $postArray['item_id']);
					$res->execute();
				break;

				case 'deleteComment':
					#add to ids array this page id
					$id_arr = array( $postArray['id'] => $postArray['id'] );
					
					#get first childrens of this page
					$res = $this->db->prepare("SELECT `id` FROM `plug_catalog_comments` WHERE `pid`=:pid ");
					$res->bindValue(':pid', $postArray['id']);
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
						$res = $this->db->prepare( "SELECT `id` FROM `plug_catalog_comments` WHERE `pid` IN (".$in.") ");
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

				
					$res = $this->db->query("
						DELETE FROM
							`plug_catalog_comments`
						WHERE
							`id` IN(".implode( ',', $id_arr ).") 
					");

				break;

				#get all data for analog window
				case 'getAllForAnalogs':
					$this->loadCatList();
					$this->template->assign('categories', $this->catList);
					$this->template->display('cat_list_analogs.tpl');
				break;	
				
				#get all data for accompaning window
				case 'getAllForAccompaning':
					$this->loadCatList();
					$this->template->assign('categories', $this->catList);
					$this->template->display('cat_list_accompaning.tpl');
				break;
				
				#get all items by category ID
				case 'getItemsByCatId':
					$res = $this->db->prepare("SELECT `id`, `name` FROM `plug_catalog_items` WHERE `cat_id`=:cat_id");
					$res->bindValue( ':cat_id', $postArray['catId'], PDO::PARAM_INT );
					$res->execute();
					$resArray = $res->fetchAll();
					
					if( !empty( $resArray ) )
					{
						foreach( $resArray as $index => &$value )
						{
							foreach( $value as $i => &$v )
							{
								if( is_numeric( $i ) ) unset( $value[ $i ] );
							}
						}
					}
					
					echo json_encode( $resArray );
					
				break;
				
				case 'editItem':
				
					#validate str set
					$this->validate->emptyLink = 'Укажите адрес страницы товара';
					$this->validate->incorrectLink = 'Адрес страницы товара может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';
					$this->validate->protectedLink = 'Данное значение зарезервированно системой и не может быть использовано';
					
					$this->validate->addToValidate("name", $postArray['name'], "notnull");
					$this->validate->addToValidate("link", $postArray['link'], "link");
					
					#first validate start
					if( !$this->validate->validate() ) die($this->validate->error);
				
					#check link
					$res = $this->db->prepare("SELECT `id` FROM `plug_catalog_items` WHERE `link`=:link AND `cat_id`=:cat_id AND `id`<>:id ");
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':cat_id', $postArray['cat_id']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty( $resArray ) )
					{
						$tmpArray = json_decode($this->validate->error, true);
						$tmpArray['validate'] = 'error';
						$tmpArray['link'] = 'Данный адрес уже используется в данной категории. Задайте другой линк для товара';
						
						die( json_encode($tmpArray) );
					}	
					
					#compile item popular
					$postArray['is_best'] = '0';
					$postArray['is_sale'] = '0';
					$postArray['is_new'] = '0';
					$postArray['is_markdown'] = '0';
					
					if( isset( $postArray['popular'] ) )
					{
						switch( $postArray['popular'] )
						{
							case 'is_best':
								$postArray['is_best'] = '1';
							break;
							
							case 'is_sale':
								$postArray['is_sale'] = '1';
							break;
							
							case 'is_new':
								$postArray['is_new'] = '1';
							break;
							
							case 'is_markdown':
								$postArray['is_markdown'] = '1';
							break;
						}
					}
					
					#disabled and hide from item list
					$postArray['disabled'] = (isset( $postArray['disabled'] )) ? '1' : '0';
					$postArray['hide_in_list'] = (isset( $postArray['hide_in_list'] )) ? '1' : '0';
					
					#date and time
					$tmpArray = explode( " ", $postArray['create_date_time'] );
					$date = new DateTime( $tmpArray[0] );
					$postArray['create_date'] = $date->format("Y-m-d");	
					$postArray['create_time'] = $tmpArray[1];	
					
					#delivery and payment
					$postArray['delivery_city'] = (isset( $postArray['delivery_city'] )) ? '1' : '0';
					$postArray['delivery_region'] = (isset( $postArray['delivery_region'] )) ? '1' : '0';
					$postArray['delivery_out_region'] = (isset( $postArray['delivery_out_region'] )) ? '1' : '0';
					$postArray['pay_cash_person'] = (isset( $postArray['pay_cash_person'] )) ? '1' : '0';
					$postArray['pay_card_person'] = (isset( $postArray['pay_card_person'] )) ? '1' : '0';
					$postArray['pay_card_web'] = (isset( $postArray['pay_card_web'] )) ? '1' : '0';
					$postArray['pay_web_money'] = (isset( $postArray['pay_web_money'] )) ? '1' : '0';
					$postArray['pay_entity'] = (isset( $postArray['pay_entity'] )) ? '1' : '0';
					
					
					$res = $this->db->prepare("SELECT `item_logo` FROM `plug_catalog_items` WHERE `id`=:id ");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();

					$item_logo = $resArray['item_logo'];
					
					if( $postArray['item_logo'] == '' ) $postArray['item_logo'] = $item_logo;

					#save logo
					if( $postArray['item_logo']!='' &&  $postArray['item_logo']!='remove' && $postArray['item_logo'] != $item_logo)
					{

						foreach( $this->itemLogosSizer as $size )
						{
							if( !is_dir( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/' ) ) mkdir( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/', 0777, true );
						
							if( $item_logo !='' )
							{
								if( file_exists( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$item_logo  ) ) unlink( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$item_logo);
							}
						}

						#get file external
						$tmpArr = explode(".", basename($postArray['item_logo']));
						$fileExternal = strtolower(array_pop($tmpArr)); 
						unset($tmpArr);
						
						#set output full filename
						$outputName = uniqid().'.'.$fileExternal;
			
						#crop logo
						$this->img->source = $this->root.$postArray['item_logo'];
						$this->img->output = $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$outputName;
						$this->img->crop( $postArray['x'], $postArray['y'], $postArray['w'], $postArray['h'], true ) or die( $this->img->errorReport."-".$this->root.$postArray['item_logo'] );
						
						#resampling logo
						foreach( $this->itemLogosSizer as $index => $size )
						{
							if( $index==0 )
							{
								$this->img->source = $this->img->output;
								$this->img->resize( $size[0], $size[1], true );
							}else{
								$this->img->source = $this->img->output;
								$this->img->output = $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$outputName;
								$this->img->resize( $size[0], $size[1], true );	
							}
						}
						
						$postArray['item_logo'] = basename( $this->img->output );
					}					
					
					#remove logo
					if( $postArray['item_logo']=="remove" && $item_logo != "" )
					{
						
						foreach( $this->itemLogosSizer as $size )
						{
							if( file_exists( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$item_logo  ) ) unlink( $this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$size[0].'x'.$size[1].'/'.$item_logo);
						}

						$postArray['item_logo'] = "";
					}					
					
					#save other images
					foreach( $postArray as $index => $value )
					{
						if( preg_match('/^other_image_[0-9]+$/', $index) )
						{
							#save other images
							if( !is_dir($this->root.$this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ))) mkdir($this->root.$this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ), 0777, true);

							
							if( copy($this->root.$this->core->tempPath.$value, $this->root.$this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ).'/'.$value) )
							{
								$res = $this->db->prepare("
									INSERT INTO 
										`plug_catalog_img`
									SET
										`item_id`=:item_id,
										`filename`=:filename,
										`img_range`=:img_range
								");
								$res->bindValue(':item_id', $postArray['id']);
								$res->bindValue(':filename', $value);
								$res->bindValue(':img_range', $index);
								$res->execute();
							}
						}
						
						#remove old other images
						if( preg_match('/^other_image_remove_[0-9]+$/', $index) )
						{
							$removedImagesArray[] = $value;
						} 
												
						
					}

  					if( isset($removedImagesArray) && !empty($removedImagesArray) )
					{

						$res = $this->db->query("
							SELECT
								`filename` 
							FROM
								`plug_catalog_img`
							WHERE
								`id` IN (".implode(",", $removedImagesArray).")
						");						
						$resArray = $res->fetchAll();
						if( !empty($resArray) )
						{
							foreach( $resArray as $dataArray )
							{
								if( file_exists(  $this->root.$this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ).'/'.$dataArray['filename']) ) unlink( $this->root.$this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ).'/'.$dataArray['filename']);
							}
						}
						
						$res = $this->db->query("
								DELETE FROM
									`plug_catalog_img`
								WHERE
									`id` IN (".implode(",", $removedImagesArray).")
						");
					}
					

					
					#campile discount calc auto
					$postArray['discount_calc_auto'] = (isset( $postArray['discount_calc_auto'] )) ? '1' : '0';
					

					#compile discount percent
					if( isset( $postArray['discount_calc_auto'] ) && $postArray['discount_calc_auto']=='1' && $postArray['old_price'] > 0 && $postArray['price'] > 0 )
					{
						$postArray['discount'] = ceil( (( $postArray['old_price'] - $postArray['price'] ) * 100) / $postArray['old_price'] );
					}
					


					#write to db
					$res = $this->db->prepare("
						UPDATE 	
							`plug_catalog_items`
						SET
							`name`=:name,
							`articul`=:articul,
							`link`=:link,
							`price`=:price,
							`old_price`=:old_price,
							`discount_calc_auto`=:discount_calc_auto,
							`discount`=:discount,
							`cat_id`=:cat_id,
							`brand_id`=:brand_id,
							`type_id`=:type_id,
							`item_desc`=:item_desc,
							`item_quick_desc`=:item_quick_desc,
							`in_stock`=:in_stock,
							`is_sale`=:is_sale,
							`is_new`=:is_new,
							`is_best`=:is_best,
							`is_markdown`=:is_markdown,
							`disabled`=:disabled,
							`hide_in_list`=:hide_in_list,
							`create_date`=:create_date,
							`create_time`=:create_time,
							`update_date`=:update_date,
							`update_time`=:update_time,
							`raiting`=:raiting,
							`external_id`=:external_id,
							`meta_title`=:meta_title,
							`meta_keywords`=:meta_keywords,
							`meta_description`=:meta_description,
							`extra_meta`=:extra_meta,
							`delivery_city`=:delivery_city,
							`delivery_region`=:delivery_region,
							`delivery_out_region`=:delivery_out_region,
							`pay_cash_person`=:pay_cash_person,
							`pay_card_person`=:pay_card_person,
							`pay_card_web`=:pay_card_web,
							`pay_web_money`=:pay_web_money,
							`pay_entity`=:pay_entity,
							`item_logo`=:item_logo
						WHERE 
							`id`=:id
						LIMIT 1
					");
					
					$res->bindValue(':name', $postArray['name']);
					$res->bindValue(':articul', $postArray['articul']);
					$res->bindValue(':link', $postArray['link']);
					$res->bindValue(':price', $postArray['price']);
					$res->bindValue(':old_price', $postArray['old_price']);
					$res->bindValue(':discount_calc_auto', $postArray['discount_calc_auto']);
					$res->bindValue(':discount', $postArray['discount']);
					$res->bindValue(':cat_id', $postArray['cat_id']);
					$res->bindValue(':brand_id', $postArray['brand_id']);
					$res->bindValue(':type_id', $postArray['type_id']);
					$res->bindValue(':item_desc', $postArray['item_desc']);
					$res->bindValue(':item_quick_desc', $postArray['item_quick_desc']);
					$res->bindValue(':in_stock', $postArray['in_stock']);
					$res->bindValue(':is_sale', $postArray['is_sale']);
					$res->bindValue(':is_new', $postArray['is_new']);
					$res->bindValue(':is_best', $postArray['is_best']);
					$res->bindValue(':is_markdown', $postArray['is_markdown']);
					$res->bindValue(':disabled', $postArray['disabled']);
					$res->bindValue(':hide_in_list', $postArray['hide_in_list']);
					$res->bindValue(':create_date', $postArray['create_date']);
					$res->bindValue(':create_time', $postArray['create_time']);
					$res->bindValue(':update_date', date("Y-m-d"));
					$res->bindValue(':update_time', date("H:i:s"));
					$res->bindValue(':raiting', $postArray['raiting']);
					$res->bindValue(':external_id', $postArray['external_id']);
					$res->bindValue(':meta_title', $postArray['meta_title']);
					$res->bindValue(':meta_keywords', $postArray['meta_keywords']);
					$res->bindValue(':meta_description', $postArray['meta_description']);
					$res->bindValue(':extra_meta', $postArray['extra_meta']);
					$res->bindValue(':delivery_city', $postArray['delivery_city']);
					$res->bindValue(':delivery_region', $postArray['delivery_region']);
					$res->bindValue(':delivery_out_region', $postArray['delivery_out_region']);
					$res->bindValue(':pay_cash_person', $postArray['pay_cash_person']);
					$res->bindValue(':pay_card_person', $postArray['pay_card_person']);
					$res->bindValue(':pay_card_web', $postArray['pay_card_web']);
					$res->bindValue(':pay_web_money', $postArray['pay_web_money']);
					$res->bindValue(':pay_entity', $postArray['pay_entity']);
					$res->bindValue(':item_logo', $postArray['item_logo']);
					$res->bindValue(':id', $postArray['id']);

					$res->execute();
					
					#save item attributes
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_attr_vals` WHERE `item_id`=:item_id ");
					$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
					$res->execute();
					
 					if( isset( $postArray['attr'] ) )
					{
						$query = "INSERT INTO `plug_catalog_item_attr_vals` (`item_id`, `type_id`, `attr_id`, `value_id`) VALUES ";
						foreach($postArray['attr'] as $attrId => $attrValueId)
						{
							$query .= "( '".$postArray['id']."', '".$postArray['type_id']."', '".$attrId."', '".$attrValueId."' ),";
						}
						$query = preg_replace('/,$/', ';', $query);
						$this->db->query( $query );
					}
					
					
					
					
					
					#clear all analogs 
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_analogs` WHERE `item_id`=:item_id");
					$res->bindValue('item_id',$postArray['id'], PDO::PARAM_INT );
					$res->execute();
					
					#save analogs
					if( isset( $postArray['analogs'] ) && !empty( $postArray['analogs'] ) )
					{
						$query = "INSERT INTO `plug_catalog_item_analogs` (`item_id`, `analog_id`) VALUES ";
						
						foreach( $postArray['analogs'] as $analogId )
						{
							$query .= "( '".$postArray['id']."', '".$analogId."' ),";
						}
						$query = preg_replace( '/,$/', '', $query );
						
						$this->db->query( $query );
					}					
					
					
					#clear all accompanying 
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_accompanying` WHERE `item_id`=:item_id");
					$res->bindValue('item_id',$postArray['id'], PDO::PARAM_INT );
					$res->execute();
					
					#save accompanying
					if( isset( $postArray['accompanying'] ) && !empty( $postArray['accompanying'] ) )
					{
						$query = "INSERT INTO `plug_catalog_item_accompanying` (`item_id`, `accompanying_id`) VALUES ";
						
						foreach( $postArray['accompanying'] as $accompanyingId )
						{
							$query .= "( '".$postArray['id']."', '".$accompanyingId."' ),";
						}
						$query = preg_replace( '/,$/', '', $query );
						
						$this->db->query( $query );
					}
					
					$this->core->log('Каталог', 'Редактирование товара "'.$postArray['name'].'"');
					
					
					die($this->validate->error);
				break;
				
				case 'setItemPopular':
				
					$propArray = array(
						'is_sale' 		=>	'0',
						'is_best' 		=>	'0',
						'is_new' 		=>	'0',
						'is_markdown' 	=>	'0'
					);
				
					foreach( $propArray as $prop => &$value )
					{
						if( $prop == $postArray['property'] )
						{
							$value = $postArray['set'];
						}else{
							$value = ($postArray['set']=='0') ? '1' : '0'; $postArray['property'] - 1;
						}

					}
					
					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_items`
						SET
							`is_sale`=:is_sale,
							`is_best`=:is_best,
							`is_new`=:is_new,
							`is_markdown`=:is_markdown
						WHERE 
							`id`=:id 
						LIMIT 1
					");
					$res->bindValue(':is_sale', $propArray['is_sale']);
					$res->bindValue(':is_best', $propArray['is_best']);
					$res->bindValue(':is_new', $propArray['is_new']);
					$res->bindValue(':is_markdown', $propArray['is_markdown']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();

					$logRes = $this->db->prepare("SELECT `name` FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
					$logRes->bindValue(':id', $postArray['id']);
					$logRes->execute();
					$logResArray = $logRes->fetch();
					$this->core->log('Каталог', 'Редактирование товара "'.$logResArray['name'].'"');

				break;
				
				case 'setItemProperty':
				
					$res = $this->db->prepare("
						UPDATE
							`plug_catalog_items`
						SET
							`".$postArray['property']."`=:set
						WHERE 
							`id`=:id 
						LIMIT 1
					");
					$res->bindValue(':set', $postArray['set']);
					$res->bindValue(':id', $postArray['id']);
					$res->execute();

					$logRes = $this->db->prepare("SELECT `name` FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
					$logRes->bindValue(':id', $postArray['id']);
					$logRes->execute();
					$logResArray = $logRes->fetch();
					$this->core->log('Каталог', 'Редактирование товара "'.$logResArray['name'].'"');

				break;
				
				case 'removeItem':

					$logRes = $this->db->prepare("SELECT `name` FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
					$logRes->bindValue(':id', $postArray['id']);
					$logRes->execute();
					$logResArray = $logRes->fetch();
					$this->core->log('Каталог', 'Удаление товара "'.$logResArray['name'].'"');

					#remove other images
					$res = $this->db->prepare("SELECT `filename` FROM `plug_catalog_img` WHERE `item_id`=:item_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						foreach($resArray as $data)
						{
							$filename = $this->root.$this->itemImagesOtherPath.$this->getFolderById	($postArray['id']).'/'.$data['filename'];
							if( file_exists( $filename) ) unlink( $filename );
						}
					}

					#remove comments
					$res = $this->db->prepare("DELETE FROM  `plug_catalog_comments` WHERE `item_id`=:item_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->execute();

					#remove logo image
					$res = $this->db->prepare("SELECT `item_logo` FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					$resArray = $res->fetch();
					foreach( $this->itemLogosSizer as $sizeArray)
					{
						$filename = $this->root.$this->itemImagesPath.$this->getFolderById	($postArray['id']).'/'.$sizeArray[0].'x'.$sizeArray[1].'/'.$resArray['item_logo'];
						if( file_exists( $filename) ) unlink( $filename );
					}

					#remove other images data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_img` WHERE `item_id`=:item_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->execute();

					#remove analogs data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_analogs` WHERE `item_id`=:item_id OR `analog_id`=:analog_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->bindValue(':analog_id', $postArray['id']);
					$res->execute();

					#remove plug_catalog_item_accompanying data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_accompanying` WHERE `item_id`=:item_id OR `accompanying_id`=:accompanying_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->bindValue(':accompanying_id', $postArray['id']);
					$res->execute();

					#remove item
					$res = $this->db->prepare("DELETE FROM `plug_catalog_items` WHERE `id`=:id LIMIT 1");
					$res->bindValue(':id', $postArray['id']);
					$res->execute();
					
					$res = $this->db->prepare("DELETE FROM `plug_catalog_item_attr_vals` WHERE `item_id`=:item_id");
					$res->bindValue(':item_id', $postArray['id']);
					$res->execute();

				break;

				case 'getImportArchiveInfo':
					
					$responseArray = array();
					$zip = new PclZip($this->root.$this->core->tempPath.$postArray['archiveFilename']);
					if( $zip->listContent()==0 ) $responseArray['failed'] = 'failed';

					die( json_encode( $responseArray ) );

				break;

				case 'getImportFileInfo':
					libxml_use_internal_errors(true);
					$xml = (array)simplexml_load_file( $this->root.$this->core->tempPath.$postArray['filename']);

					$json = json_encode($xml);
					$_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] = json_decode($json,TRUE);
					$xml = $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'];

					#response array
					$responseArray = array(
						'date'				=> ( isset( $xml['@attributes']['date'] ) ) ? $xml['@attributes']['date'] : 0,
						'time'				=> ( isset( $xml['@attributes']['time'] )) ? $xml['@attributes']['time'] : 0
					);

					#calculate items
					if( isset( $xml['items']['item'][0] ) )
					{ 
						$responseArray['itemsAdd'] = count($xml['items']['item']);
					}elseif( isset( $xml['items']['item']  ) ){
						$responseArray['itemsAdd'] = 1;
					}else{
						$responseArray['itemsAdd'] = 0;	
					}

					#calculate types
					if( isset( $xml['brands']['brand'][0] ) )
					{ 
						$responseArray['typesAdd'] = count($xml['brands']['brand']);
					}elseif( isset( $xml['brands']['brand']  ) ){
						$responseArray['typesAdd'] = 1;
					}else{
						$responseArray['typesAdd'] = 0;	
					}

					#calculate brands
					if( isset( $xml['brands']['brand'][0] ) )
					{ 
						$responseArray['brandsAdd'] = count($xml['brands']['brand']);
					}elseif( isset( $xml['brands']['brand']  ) ){
						$responseArray['brandsAdd'] = 1;
					}else{
						$responseArray['brandsAdd'] = 0;	
					}

					#calculate categories
					if( isset( $xml['categories']['cat'][0] ) )
					{ 
						$responseArray['categoriesAdd'] = count($xml['categories']['cat']);
					}elseif( isset( $xml['categories']['cat']  ) ){
						$responseArray['categoriesAdd'] = 1;
					}else{
						$responseArray['categoriesAdd'] = 0;	
					}

					#calculate items remove
					if( isset( $xml['remove']['items']['item'][0] ) )
					{ 
						$responseArray['itemsRemove'] = count($xml['remove']['items']['item']);
					}elseif( isset( $xml['remove']['items']['item']  ) ){
						$responseArray['itemsRemove'] = 1;
					}else{
						$responseArray['itemsRemove'] = 0;	
					}

					#calculate types remove
					if( isset( $xml['remove']['types']['type'][0] ) )
					{ 
						$responseArray['typesRemove'] = count($xml['remove']['types']['type']);
					}elseif( isset( $xml['remove']['types']['type']  ) ){
						$responseArray['typesRemove'] = 1;
					}else{
						$responseArray['typesRemove'] = 0;	
					}

					#calculate categories remove
					if( isset( $xml['remove']['categories']['cat'][0] ) )
					{ 
						$responseArray['categoriesRemove'] = count($xml['remove']['categories']['cat']);
					}elseif( isset( $xml['remove']['categories']['cat']  ) ){
						$responseArray['categoriesRemove'] = 1;
					}else{
						$responseArray['categoriesRemove'] = 0;	
					}

					#calculate brands remove
					if( isset( $xml['remove']['brands']['brand'][0] ) )
					{ 
						$responseArray['brandsRemove'] = count($xml['remove']['brands']['brand']);
					}elseif( isset( $xml['remove']['brands']['brand']  ) ){
						$responseArray['brandsRemove'] = 1;
					}else{
						$responseArray['brandsRemove'] = 0;	
					}


					if( $responseArray['date']==0 || $responseArray['time']==0 )
					{
						$responseArray['failed'] = 'failed';
						die( json_encode( $responseArray ) );
					}

					#prepare session array
					if( isset(  $xml['items']['item'] ) )
					{
						if( isset( $xml['items']['item'][0]) )
						{
							foreach(  $xml['items']['item'] as $items )
							{
								$responseArray['ids'][] = $items['@attributes']['id'];
							}
						}else{
							$responseArray['ids'][] = $xml['items']['item']['@attributes']['id'];
						}
					}

					#results
					die( json_encode( $responseArray ) );

				break;

				#unpack images archive
				case 'importPrepare':
					$responseArray = array();
					if( !file_exists($this->root.$this->core->tempPath.$postArray['archiveFilename']) )
					{
						$responseArray['failed'] = 'failed';
						die( json_encode( $responseArray ) );
					}

					$zip = new PclZip($this->root.$this->core->tempPath.$postArray['archiveFilename']);
					
					mkdir( $this->root.$this->core->tempPath.$postArray['tmpFolderName'], 0777, true );

					if( $zip->extract(PCLZIP_OPT_PATH, $this->root.$this->core->tempPath.$postArray['tmpFolderName'] ) == 0 )
					{
						$responseArray['failed'] = 'failed';
						die( json_encode( $responseArray ) );
					}

					die( json_encode( $responseArray ) );

				break;

				#items update
				case 'importItemUpdate':

					if(empty($postArray['items'])) die('ooops');

					foreach( $postArray['items'] as $id )
					{
						$this->importItemUpdate($postArray['filename'], $id, $postArray['archiveFilename'], $postArray['tmpFolderName']);
					}

				break;

				#remove
				case 'importRemoveUpdate':
					$xml = ( isset($_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData']) ) ? $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] : array();

					if( !isset( $xml['remove'] ) ) break;

					#remove categories
					if(isset( $xml['remove']['categories']['cat'] ))
					{

						if( !isset( $xml['remove']['categories']['cat'][0] ) )
						{
							$xml['remove']['categories']['cat'] = array(0=>$xml['remove']['categories']['cat']);
						}

						foreach( $xml['remove']['categories']['cat'] as $index => $cat )
						{
							$catId = (isset($cat['@attributes']['id'])) ? $cat['@attributes']['id'] : 'NULL';
							$removeitems = (isset($cat['@attributes']['removeitems'])) ? $cat['@attributes']['removeitems'] : '0';
	
							if( $catId=='NULL' ) continue;
		
							$dataArray = array(
								'action'=>'removeCategory',
								'id'=>$catId
							);
		
							if( $removeitems == '1') $dataArray['removeOnlyCat'] = true;
		
							$this->ajax( $dataArray );
						}
					}

					#remove items
					if(isset( $xml['remove']['items']['item'] ))
					{
						
						if( !isset( $xml['remove']['items']['item'][0] ) )
						{
							$xml['remove']['items']['item'] = array(0=>$xml['remove']['items']['item']);
						}

						foreach( $xml['remove']['items']['item'] as $index => $item )
						{
							$itemId = (isset($item['@attributes']['id'])) ? $item['@attributes']['id'] : 'NULL';
							if( $itemId =='NULL') continue;
	
							$this->ajax( array(
									'action'=>'removeItem',
									'id'=>$itemId
							));
						}
					}


					#remove brands
					if(isset( $xml['remove']['brands']['brand'] ))
					{

						if( !isset( $xml['remove']['brands']['brand'][0] ) )
						{
							$xml['remove']['brands']['brand'] = array(0=>$xml['remove']['brands']['brand']);
						}

						foreach( $xml['remove']['brands']['brand'] as $index => $brand )
						{
	
							$brandId = (isset($brand['@attributes']['id'])) ? $brand['@attributes']['id'] : 'NULL';
							if( $brandId =='NULL') continue;
							
							$this->ajax( array(
									'action'=>'removeBrands',
									'id'=>$brandId
							));
						}
					}

					#remove types
					if(isset( $xml['remove']['types']['type'] ))
					{
						
						if( !isset( $xml['remove']['types']['type'][0] ) )
						{
							$xml['remove']['types']['type'] = array(0=>$xml['remove']['types']['type']);
						}

						foreach( $xml['remove']['types']['type'] as $index => $type )
						{
							$typeId = (isset($type['@attributes']['id'])) ? $type['@attributes']['id'] : 'NULL';
							if( $typeId =='NULL') continue;
							
							$res = $this->db->prepare("DELETE FROM `plug_catalog_types_vals` WHERE `attr_id`=:attr_id");
							$res->bindValue(':attr_id', $typeId);
							$res->execute();
		
							$res = $this->db->prepare("DELETE FROM `plug_catalog_types_attr` WHERE `id`=:id");
							$res->bindValue(':id', $typeId);
							$res->execute();
		
							$res = $this->db->prepare("DELETE FROM `plug_catalog_item_attr_vals` WHERE `attr_id`=:attr_id");
							$res->bindValue(':attr_id', $typeId);
							$res->execute();
		
						}
					}
				
				break;
				
				case 'importCategoriesUpdate':

					$xml = ( isset($_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData']) ) ? $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] : array();

					if( !isset($xml['categories']['cat']) ) break;


					if( !isset( $xml['categories']['cat'][0] ) )
					{
						$xml['categories']['cat'] = array(0=>$xml['categories']['cat']);
					}


					foreach( $xml['categories']['cat'] as $cat )
					{
	
						$catId = (isset($cat['@attributes']['id'])) ? $cat['@attributes']['id'] : 'NULL';
						$pid = (isset($cat['@attributes']['pid'])) ? $cat['@attributes']['pid'] : '0';
	
						$dataArray = array(
							'id'				=> $catId,
							'pid'				=> $pid,
							'name'				=> ( isset( $cat['name'] ) ) ? $cat['name'] : 'Без названия',
							'hide'				=> ( isset( $cat['hide'] ) ) ? $cat['hide'] : '0',
							'off'				=> ( isset( $cat['off'] ) ) ? $cat['off'] : '0',
							'cat_range'			=> ( isset( $cat['range'] ) ) ? $cat['range'] : '0',
							'link'				=> ( isset( $cat['link'] ) ) ? preg_replace('/[^0-9a-zA-Z-_]/', '_', $cat['link']) : 'unnamed'.uniqid()
						);
	
						#compile url for new category
						if( $dataArray['pid'] !='0' )
						{
							$res = $this->db->prepare(" SELECT `url` FROM `plug_catalog_cat` WHERE `id`=:id LIMIT 1 ");
							$res->bindValue(':id', $dataArray['pid']);
							$res->execute();
							$resArray = $res->fetch();
							$dataArray['url'] = $resArray['url'].$dataArray['link'].'/';
						
						}else{
							$dataArray['url']= '/'.$dataArray['link'].'/';
						}		
	
						if( isset( $cat['img'] ) && isset($postArray['tmpFolderName']) )
						{
							if(file_exists( $this->root.$this->core->tempPath.$postArray['tmpFolderName'].'/'.$cat['img'] ))
							{
	
								#get file external
								$tmpArr = explode(".", $cat['img']);
								$fileExternal = strtolower(array_pop($tmpArr)); 
								unset($tmpArr);
								
								#set output full filename
								$outputName = uniqid().'.'.$fileExternal;
			
								#resampling logo 
								foreach( $this->categoryLogosSizer as $index => $size )
								{
									$dir = $this->root.$this->categoryImagesPath.$this->getFolderById	( $dataArray['id'] ).'/'.$size[0].'x'.$size[1];
									if(!is_dir( $dir )) mkdir($dir, 0777, true);
	
									$this->img->source = $this->root.$this->core->tempPath.$postArray['tmpFolderName'].'/'.$cat['img'];
									$this->img->output = $dir.'/'.$outputName;
									$this->img->resize( $size[0], $size[1], true );	
								}
	
								$dataArray['cat_logo'] = $outputName;
							}
						}

						$fields = array();
						$values = array();
						$updated = array();
						foreach( $dataArray as $field => $value )
						{
							$fields[] = "`".$field."`";
							$values[] = "'".addslashes($value)."'";
							$updated[] = "`".$field."`=VALUES(`".$field."`)";
						}
	
						$this->db->query( "
							INSERT INTO 
								`plug_catalog_cat` 
								(".implode(',', $fields).") 
							VALUES 
								(".implode(',', $values).") 
							ON DUPLICATE KEY UPDATE 
								".implode(',', $updated)."
						");
	
					}

				break;
				
				case 'importBrandsUpdate':
					$xml = ( isset($_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData']) ) ? $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] : array();

					if(!isset($xml['brands']['brand'])) break;

					if( !isset( $xml['brands']['brand'][0] ) )
					{
						$xml['brands']['brand'] = array(0=>$xml['brands']['brand']);
					}				

					foreach( $xml['brands']['brand'] as $brand )
					{
						$brandId = (isset($brand['@attributes']['id'])) ? $brand['@attributes']['id'] : 'NULL';
	
	
						$dataArray = array(
							'id'				=> $brandId,
							'external_id'		=> ( isset( $brand['external_id'] ) ) ? $brand['external_id'] : $brandId,
							'name'				=> ( isset( $brand['name'] ) ) ? $brand['name'] : 'Без названия',
							'meta_title'		=> ( isset( $brand['meta_title'] ) ) ? $brand['meta_title'] : '',
							'meta_description'	=> ( isset( $brand['meta_description'] ) ) ? $brand['meta_description'] : '',
							'meta_keywords'		=> ( isset( $brand['meta_keywords'] ) ) ? $brand['meta_keywords'] : '',
							'extra_meta'		=> ( isset( $brand['extra_meta'] ) ) ? $brand['extra_meta'] : '',
							'brand_range'		=> ( isset( $brand['range'] ) ) ? $brand['range'] : '0',
							'link'				=> ( isset( $brand['link'] ) ) ? preg_replace('/[^0-9a-zA-Z-_]/', '_', $brand['link']) : 'unnamed'.uniqid(),
							'brand_quick_desc'	=> ( isset( $brand['quick_desc'] ) ) ? $brand['quick_desc'] : '',
							'brand_descr'		=> ( isset( $brand['desc'] ) ) ? $brand['desc'] : '',
							'hide_in_list'		=> ( isset( $brand['hide_in_list'] ) ) ? $brand['hide_in_list'] : '0',
							'disabled'			=> ( isset( $brand['disabled'] ) ) ? $brand['disabled'] : '0',
							'offsite'			=> ( isset( $brand['offsite'] ) ) ? $brand['offsite'] : '',
							'update_date'		=> date("Y-m-d"),
							'update_time'		=> date("H:i:s")
						);
						
						if( isset( $brand['img'] ) && isset($postArray['tmpFolderName']) )
						{
							if(file_exists( $this->root.$this->core->tempPath.$postArray['tmpFolderName'].'/'.$brand['img'] ))
							{
	
								#get file external
								$tmpArr = explode(".", $brand['img']);
								$fileExternal = strtolower(array_pop($tmpArr)); 
								unset($tmpArr);
								
								#set output full filename
								$outputName = uniqid().'.'.$fileExternal;
			
								#resampling logo
								foreach( $this->brandLogosSizer as $index => $size )
								{
									$dir = $this->root.$this->brandImagesPath.$this->getFolderById	( $dataArray['id'] ).'/'.$size[0].'x'.$size[1];
									if(!is_dir( $dir )) mkdir($dir, 0777, true);
	
									$this->img->source = $this->root.$this->core->tempPath.$postArray['tmpFolderName'].'/'.$brand['img'];
									$this->img->output = $dir.'/'.$outputName;
									$this->img->resize( $size[0], $size[1], true );	
								}
	
								$dataArray['brand_logo'] = $outputName;
							}
						}
						
						$fields = array();
						$values = array();
						$updated = array();
						foreach( $dataArray as $field => $value )
						{
							$fields[] = "`".$field."`";
							$values[] = "'".addslashes($value)."'";
							$updated[] = "`".$field."`=VALUES(`".$field."`)";
						}
	
						$this->db->query( "
							INSERT INTO 
								`plug_catalog_brands` 
								(".implode(',', $fields).") 
							VALUES 
								(".implode(',', $values).") 
							ON DUPLICATE KEY UPDATE 
								".implode(',', $updated)."
						");
	
					}

				break;

				case 'importTypesUpdate':
					$xml = ( isset($_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData']) ) ? $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] : array();

					if(!isset($xml['types']['type'])) break;
					
					if( !isset( $xml['types']['type'][0] ) )
					{
						$xml['types']['type'] = array(0=>$xml['types']['type']);
					}
					
					foreach( $xml['types']['type'] as $type )
					{
						$typeId = (isset($type['@attributes']['id'])) ? $type['@attributes']['id'] : 'NULL';
						$dataArray = array(
							'id'				=> $typeId,
							'name'				=> ( isset( $type['name'] ) ) ? $type['name'] : 'Без названия'
						);
	
						$fields = array();
						$values = array();
						$updated = array();
						foreach( $dataArray as $field => $value )
						{
							$fields[] = "`".$field."`";
							$values[] = "'".addslashes($value)."'";
							$updated[] = "`".$field."`=VALUES(`".$field."`)";
						}
	
						$this->db->query( "
							INSERT INTO 
								`plug_catalog_types` 
								(".implode(',', $fields).") 
							VALUES 
								(".implode(',', $values).") 
							ON DUPLICATE KEY UPDATE 
								".implode(',', $updated)."
						");

					}						

				break;

				case 'clear_catalog':
					$this->template->display('clear_catalog.tpl');
				break;

				case 'clearCatalog':
					
					//clear all types
					if(isset( $postArray['types'] ))
					{
						#remove all types
						$this->db->query("TRUNCATE TABLE `plug_catalog_types`");
						
						#remove all types values
						$this->db->query("TRUNCATE TABLE `plug_catalog_types_vals`");

						#remove all attributes
						$this->db->query("TRUNCATE TABLE `plug_catalog_types_attr`");
						
						#remove all  attribute values
						$this->db->query("TRUNCATE TABLE `plug_catalog_item_attr_vals`");

						#set all items types is 0
						if(!isset( $postArray['items'] ))
						{
							#set all items type_id is 0
							$this->db->query("UPDATE `plug_catalog_items` SET `type_id`=0 ");
						}
					}	

					//clear all brands
					if(isset( $postArray['brands'] ))
					{
						#remove all brands
						$this->db->query("TRUNCATE TABLE `plug_catalog_brands`");

						#set all items brands is 0
						if(!isset( $postArray['items'] ))
						{
							#set all items brand_id is 0
							$this->db->query("UPDATE `plug_catalog_items` SET `brand_id`=0 ");	
						}

						#remove brands images
						$this->core->clearDir( $this->root.$this->brandImagesPath );

					}

					//clear all cateories
					if(isset( $postArray['categories'] ))
					{
						#remove all categories
						$this->db->query("TRUNCATE TABLE `plug_catalog_cat`");

						#set all items cat_id is 0
						if(!isset( $postArray['items'] ))
						{
							$this->db->query("UPDATE `plug_catalog_items` SET `cat_id`=0 ");
						}
					}

					//clear all items
					if(isset( $postArray['items'] ))
					{
						#remove all items
						$this->db->query("TRUNCATE TABLE `plug_catalog_items`");

						#remove all attribute values
						$this->db->query("TRUNCATE TABLE `plug_catalog_item_attr_vals`");
						
						#remove all analogs
						$this->db->query("TRUNCATE TABLE `plug_catalog_item_analogs`");
						
						#remove all analogs
						$this->db->query("TRUNCATE TABLE `plug_catalog_item_accompanying`");
						
						#remove all images data
						$this->db->query("TRUNCATE TABLE `plug_catalog_img`");

						#remove all comments
						$this->db->query("TRUNCATE TABLE `plug_catalog_comments`");

						#remove items images
						$this->core->clearDir( $this->root.$this->itemImagesPath );
					}

					$this->core->log('Каталог', 'Очистка каталога');
				break;

				#orders list
				case 'orders_list':
					
					$page = ( isset($postArray['page']) ) ? (int)$postArray['page'] : 1;
					$count = ( isset($postArray['countonpage']) ) ? (int)$postArray['countonpage'] : 20;

					#get total lines in DB
					$res = $this->db->query("
						SELECT 
							count(*) 
						FROM 
							`plug_catalog_orders`
					");
					$resArray = $res->fetch();					
					
					#total lines in DB
					$total = intval( ($resArray[0] - 1) / $count) + 1; 
					
					#set current page
					$page = intval($page); 
					if(empty($page) or $page < 0) $page = 1; 
					if($page > $total) $page = $total;
					
					#set start line
					$start = $page * $count - $count;
				
					$res = $this->db->prepare("
						SELECT 
							`o`.*,
							`u`.`user_name`,
							`u`.`user_phone`,
							`a`.`name` AS `adm_user_name`
						FROM 
							`plug_catalog_orders` AS `o`
						LEFT JOIN
							`plug_catalog_orders_users` AS `u`
						ON
							`u`.`order_id`=`o`.`id`
						LEFT JOIN
							`adm_users` AS `a`
						ON
							`a`.`id`=`o`.`admin_user_id`
						ORDER BY
							`o`.`order_date`DESC, `o`.`order_time`DESC
						LIMIT
							:start,:num
					");
					$res->bindValue(':start', $start, PDO::PARAM_INT);
					$res->bindValue(':num', $count, PDO::PARAM_INT);
					$res->execute();
					$resArray = $res->fetchAll();
				
					if(!empty($resArray))
					{
						
						foreach( $resArray as &$order )
						{
							$order['user_phone'] = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})/", "+$1($2)-$3-$4-$5", $order['user_phone'] );
							$order['order_date'] = $this->core->dateToRus("d F Y", strtotime($order['order_date']));
							$order['order_time'] = substr($order['order_time'], 0, 5);
							$order['order_cost'] = number_format($order['order_cost'], 2, '.', ' ');
							if( $order['adm_user_name']==NULL ) $order['adm_user_name'] = 'Заказ не принят';
						}
						
						#get navArray
						#set arrow left
						$navArray = array();
				
						$toLeft = 5;
						$toRight = 5;
				
						#set pagers
						for( $i=1;$i<($total+1);$i++ ) 
						{
							if(  $page - $toLeft < $i && $page + $toRight > $i ) 
							{
								$navArray[] = array( 'num'=> $i, 'title'=>'На страницу '.$i, 'text'=>$i,'class'=>( $i==$page ) ? 'act' : '');
							}
						}
				
						#set arrows
						if( $page!=1 ) array_unshift( $navArray, array(	'num'=> 1, 'title'=>'К первой странице', 'text'=> '&laquo;', 'class'=> 'start'), array( 'num'=> $page-1, 'title'=>'Назад', 'text'=> '&larr;', 'class'=> 'back' ) );
						if( $page!=$total ) array_push( $navArray,  array('num'=> $page+1, 'title'=>'Вперед', 'text'=> '&rarr;','class'=> 'next'), array('num'=> $total, 'title'=>'К последней странице', 'text'=> '&raquo;','class'=> 'end') );
						
						$this->template->assign('countonpage', $count);
						$this->template->assign('ordersArray', $resArray);

						if( count( $navArray )>1 ) $this->template->assign('navArray', $navArray);
					}
					$this->template->assign('orderStatuses', $this->orderStatuses);
					$this->template->display('orders_list.tpl');
				break;

				case 'getOrderDetails':
					
					$this->loadSettings();

					#get order data
					$res = $this->db->prepare("
						SELECT
							`o`.*,
							`u`.`user_name`,
							`u`.`user_phone`,
							`u`.`user_email`
						FROM
							`plug_catalog_orders` AS `o`
						LEFT JOIN
							`plug_catalog_orders_users` AS `u`
						ON
							`u`.`order_id`=`o`.`id`
						WHERE
							`o`.`id`=:id
						GROUP BY
							`o`.`id`
						LIMIT 1
					");
					$res->bindValue(':id', $postArray['orderId']);
					$res->execute();
					$orderArray = $res->fetch();

					$orderArray['order_date'] = $this->core->dateToRus("d F Y", strtotime($orderArray['order_date']));
					$orderArray['order_time'] = substr($orderArray['order_time'], 0, 5);
					$orderArray['order_cost'] = number_format($orderArray['order_cost'], 2, '.', ' ');


					#get items count
					$res = $this->db->prepare("
						SELECT
							`oi`.`item_count`
						FROM
							`plug_catalog_orders_items` AS `oi`
						WHERE
							`oi`.`order_id`=:id
					");
					$res->bindValue(':id', $postArray['orderId']);
					$res->execute();
					$resArray = $res->fetchAll();

					$orderArray['items_count'] = (int)0;
					foreach( $resArray as $itemData ) 
					{
						$orderArray['items_count'] = (int)((int)$orderArray['items_count'] + (int)$itemData['item_count']);
					}

					$orderArray['currency'] = $this->core->declOfNum( $orderArray['order_cost'], array( $this->catalogSettings['currency_nom'], $this->catalogSettings['currency_acc'], $this->catalogSettings['currency_nomp'] ) );

					if($orderArray['order_status']!=0 ) unset($this->orderStatuses[0]);

					$this->template->assign('order', $orderArray);
					$this->template->assign('orderStatuses', $this->orderStatuses);
					$this->template->display('order_details.tpl');

				break;

				case 'saveOrderDetails':

					$res = $this->db->prepare("
						UPDATE 
							`plug_catalog_orders`
						SET
							`order_status`=:order_status,
							`order_manager_comment`=:order_manager_comment,
							`order_delivery_self`=:order_delivery_self
						WHERE
							`id`=:id
						LIMIT 1
					");
					$res->bindValue(':order_status', $postArray['order_status']);
					$res->bindValue(':order_manager_comment', $postArray['manager_comment']);
					$res->bindValue(':order_delivery_self', $postArray['delivery_self']);
					$res->bindValue(':id', $postArray['orderId']);
					$res->execute();

					$this->core->log('Каталог', 'Редактирование заказа ID"'.$postArray['orderId'].'"');
				break;

				case 'saveOrderUserData':
					$res = $this->db->prepare("
						UPDATE 
							`plug_catalog_orders_users`
						SET
							`user_name`=:user_name,
							`user_phone`=:user_phone,
							`user_email`=:user_email,
							`region_id`=:region_id,
							`city_id`=:city_id,
							`address_street`=:address_street,
							`address_build`=:address_build,
							`address_liter`=:address_liter,
							`address_entrance`=:address_entrance,
							`address_floor`=:address_floor,
							`address_room`=:address_room
						WHERE
							`order_id`=:order_id
						LIMIT 1
					");
					$postArray['phone'] = preg_replace('/[^0-9]/', '', $postArray['phone']);

					$res->bindValue(':user_name', $postArray['name']);
					$res->bindValue(':user_phone', $postArray['phone']);
					$res->bindValue(':user_email', $postArray['email']);
					$res->bindValue(':region_id', $postArray['region']);
					$res->bindValue(':city_id', $postArray['city']);
					$res->bindValue(':address_street', $postArray['street']);
					$res->bindValue(':address_build', $postArray['build']);
					$res->bindValue(':address_liter', $postArray['liter']);
					$res->bindValue(':address_entrance', $postArray['entrance']);
					$res->bindValue(':address_floor', $postArray['floor']);
					$res->bindValue(':address_room', $postArray['room']);
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();

					$this->core->log('Каталог', 'Редактирование заказа ID"'.$postArray['orderId'].'"');
				break;

				case 'getOrderUserData':
					#get user data
					$res = $this->db->prepare("
						SELECT 
							`u`.*
						FROM
							`plug_catalog_orders_users` AS `u`
						WHERE
							`u`.`order_id`=:order_id
						LIMIT 1
					");
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();
					$userDataArray = $res->fetch();

					#get Regions
					$res = $this->db->prepare("SELECT `region_id`, `region_name` FROM `geo_regions` WHERE `country_id`=:country_id");
					//3159 - this is Russia country ID
					$res->bindValue(':country_id', '3159', PDO::PARAM_STR);
					$res->execute();
					if($res) $resArray = $res->fetchAll();
					
					$regionsArray = array();
					if(!empty($resArray))
					{
						foreach($resArray as $primaryIndex => $dataArray)
						{
							foreach($dataArray as $index => $value)
							{
								if( !is_numeric($index)) $regionsArray[$primaryIndex][$index] = $value;
							}
						}
					}


					#get cities
					$citiesArray = array();
					$res = $this->db->prepare("SELECT `city_id`, `city_name` FROM `geo_cities` WHERE `country_id`=:country_id AND `region_id`=:region_id");
					//3159 - this is Russia country ID
					$res->bindValue(':country_id', '3159', PDO::PARAM_STR);
					$res->bindValue(':region_id', (int)$userDataArray['region_id'], PDO::PARAM_STR);
					$res->execute();
					if($res) $citiesArray = $res->fetchAll();
					if(!empty($citiesArray))
					{
						foreach($citiesArray as $primaryIndex => $dataArray)
						{
							foreach($dataArray as $index => $value)
							{
								if( is_numeric($index)) unset( $citiesArray[$primaryIndex][$index] );
							}
						}

					}

					$this->template->assign('regionsArray', $regionsArray);
					$this->template->assign('citiesArray', $citiesArray);
					$this->template->assign('user', $userDataArray);
					$this->template->display('order_user_details.tpl');
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

				case 'getOrderItems':
					$res = $this->db->prepare("
						SELECT
							`oi`.`item_id`,
							`oi`.`item_external_id`,
							`oi`.`item_articul`,
							`oi`.`item_name`,
							`oi`.`item_price`,
							`oi`.`item_count`,
							`i`.`brand_id`,
							`i`.`cat_id`,
							`i`.`type_id`,
							`i`.`item_logo`,
							`i`.`id` AS `origin_id`

						FROM
							`plug_catalog_orders_items` AS `oi`
						LEFT JOIN 
							`plug_catalog_items` AS `i` 
						ON 
							`i`.`id`=`oi`.`item_id`
						WHERE
							`oi`.`order_id`=:order_id
					");
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();
					$resArray = $res->fetchAll();

					foreach( $resArray as &$itemData )
					{
						if( $itemData['item_logo']!="" && file_exists($this->root.$this->itemImagesPath.$this->getFolderById	( $itemData['item_id'] ).'/'.$this->itemLogosSizer[3][0].'x'.$this->itemLogosSizer[3][1].'/'.$itemData['item_logo']))
						{
							$itemData['full_logo_src'] = $this->itemImagesPath.$this->getFolderById	( $itemData['item_id'] ).'/'.$this->itemLogosSizer[3][0].'x'.$this->itemLogosSizer[3][1].'/'.$itemData['item_logo'];
						}
						if( $itemData['origin_id']==NULL ) $itemData['not_exist'] = '1';
					}

					//print_r($resArray);

					$this->template->assign('itemsArray', $resArray);
					$this->template->display('order_items.tpl');
				break;

				case 'removeOrder':
					
					#remove order
					$res = $this->db->prepare("DELETE FROM `plug_catalog_orders` WHERE `id`=:order_id LIMIT 1");
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();

					#remove order items
					$res = $this->db->prepare("DELETE FROM `plug_catalog_orders_items` WHERE `order_id`=:order_id");
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();

					#remove order user data
					$res = $this->db->prepare("DELETE FROM `plug_catalog_orders_users` WHERE `order_id`=:order_id LIMIT 1");
					$res->bindValue(':order_id', $postArray['orderId']);
					$res->execute();

					$this->core->log('Каталог', 'Удаление заказа ID"'.$postArray['orderId'].'"');
				break;

				case 'catalog_settings_main':
					$res = $this->db->query("SELECT * FROM `plug_catalog_sett` WHERE `id`='1'");
					$resArray = $res->fetch();
					
					$this->template->assign('pageList', $this->core->mmenuArray);
					$this->template->assign('catalogSettings', $resArray);

					$this->template->display('catalog_settings.tpl');
				break;

				case 'catalogSaveSettings':
					$this->validate->addToValidate('currency_quick', $postArray['currency_quick'], 'notnull');
					$this->validate->addToValidate('currency_symbol', $postArray['currency_symbol'], 'notnull');
					$this->validate->addToValidate('currency_nom', $postArray['currency_nom'], 'notnull');
					$this->validate->addToValidate('currency_acc', $postArray['currency_acc'], 'notnull');
					$this->validate->addToValidate('currency_nomp', $postArray['currency_nomp'], 'notnull');
					$this->validate->addToValidate('item_nom', $postArray['item_nom'], 'notnull');
					$this->validate->addToValidate('item_acc', $postArray['item_acc'], 'notnull');
					$this->validate->addToValidate('item_nomp', $postArray['item_nomp'], 'notnull');

					if( $this->validate->validate() )
					{
						$res = $this->db->prepare("
							UPDATE 
								`plug_catalog_sett`
							SET
								`currency_quick`=:currency_quick,
								`currency_symbol`=:currency_symbol,
								`currency_nom`=:currency_nom,
								`currency_acc`=:currency_acc,
								`currency_nomp`=:currency_nomp,
								`item_nom`=:item_nom,
								`item_acc`=:item_acc,
								`item_nomp`=:item_nomp,
								`show_comments`=:show_comments,
								`catalog_page_id`=:catalog_page_id
							WHERE
								`id`='1'
						");
						$res->bindValue(':currency_quick', $postArray['currency_quick']);
						$res->bindValue(':currency_symbol', $postArray['currency_symbol']);
						$res->bindValue(':currency_nom', $postArray['currency_nom']);
						$res->bindValue(':currency_acc', $postArray['currency_acc']);
						$res->bindValue(':currency_nomp', $postArray['currency_nomp']);
						$res->bindValue(':item_nom', $postArray['item_nom']);
						$res->bindValue(':item_acc', $postArray['item_acc']);
						$res->bindValue(':item_nomp', $postArray['item_nomp']);
						$res->bindValue(':show_comments', $postArray['show_comments']);
						$res->bindValue(':catalog_page_id', $postArray['catalog_page_id']);
						$res->execute();
					}

					$this->core->log('Каталог', 'Изменение основных настроек');

					die( $this->validate->error );
				break;

				case 'catalog_settings_templates':
					$res = $this->db->query("SELECT * FROM `plug_catalog_sett` WHERE `id`='1'");
					$resArray = $res->fetch();
					
					$this->template->assign('catalogSettings', $resArray);
					$this->template->display('catalog_settings_templates.tpl');
				break;

				case 'catalogSaveSettingsTemplates':

					if( $postArray['order_email_to_address']!='' )
					{
						$this->validate->addToValidate('order_email_to_address', $postArray['order_email_to_address'], 'email');
						$this->validate->addToValidate('order_email_to_name', $postArray['order_email_to_name'], 'notnull');
						$this->validate->addToValidate('order_email_from_email', $postArray['order_email_from_email'], 'email');
						$this->validate->addToValidate('order_email_from_name', $postArray['order_email_from_name'], 'notnull');
						$this->validate->addToValidate('order_email_subject', $postArray['order_email_subject'], 'notnull');
					}

					if( $this->validate->validate() )
					{
						$res = $this->db->prepare("
							UPDATE 
								`plug_catalog_sett`
							SET
								`order_email_to_address`=:order_email_to_address,
								`order_email_to_name`=:order_email_to_name,
								`order_email_from_email`=:order_email_from_email,
								`order_email_from_name`=:order_email_from_name,
								`order_email_subject`=:order_email_subject,
								`order_email_template`=:order_email_template
							WHERE
								`id`='1'
						");
						$res->bindValue(':order_email_to_address', $postArray['order_email_to_address']);
						$res->bindValue(':order_email_to_name', $postArray['order_email_to_name']);
						$res->bindValue(':order_email_from_email', $postArray['order_email_from_email']);
						$res->bindValue(':order_email_from_name', $postArray['order_email_from_name']);
						$res->bindValue(':order_email_subject', $postArray['order_email_subject']);
						$res->bindValue(':order_email_template', $postArray['order_email_template']);
						$res->execute();
					}

					$this->core->log('Каталог', 'Изменение шаблонов писем администратора');

					die( $this->validate->error );

				break;

				case 'catalog_settings_client_templates':
					$res = $this->db->query("SELECT * FROM `plug_catalog_sett` WHERE `id`='1'");
					$resArray = $res->fetch();
					
					$this->template->assign('catalogSettings', $resArray);
					$this->template->display('catalog_settings_client_templates.tpl');
				break;

				case 'catalogSaveSettingsClientTemplates':
					
					$this->validate->addToValidate('client_email_from', $postArray['client_email_from'], 'email');
					$this->validate->addToValidate('client_from_name', $postArray['client_from_name'], 'notnull');
					$this->validate->addToValidate('client_email_subject', $postArray['client_email_subject'], 'notnull');

					if( $this->validate->validate() )
					{
						$res = $this->db->prepare("
							UPDATE 
								`plug_catalog_sett`
							SET
								`client_email_from`=:client_email_from,
								`client_from_name`=:client_from_name,
								`client_email_subject`=:client_email_subject,
								`client_email_template`=:client_email_template
							WHERE
								`id`='1'
						");
						$res->bindValue(':client_email_from', $postArray['client_email_from']);
						$res->bindValue(':client_from_name', $postArray['client_from_name']);
						$res->bindValue(':client_email_subject', $postArray['client_email_subject']);
						$res->bindValue(':client_email_template', $postArray['client_email_template']);
						$res->execute();
					}

					$this->core->log('Каталог', 'Изменение шаблонов писем клиенту');

					die( $this->validate->error );
				break;

				case 'catalog_settings_oferta':
					$res = $this->db->query("SELECT * FROM `plug_catalog_sett` WHERE `id`='1'");
					$resArray = $res->fetch();
					$this->template->assign('oferta', $resArray);

					$this->template->display('catalog_settings_oferta.tpl');
				break;

				case 'catalogSaveOfertaSettings':
					$res = $this->db->prepare("
						UPDATE 
							`plug_catalog_sett`
						SET
							`oferta_html`=:oferta_html
						WHERE
							`id`='1'
					");
					$res->bindValue(':oferta_html', $postArray['oferta_html']);
					$res->execute();

					$this->core->log('Каталог', 'Изменение договора оферты');
				break;
			}

		}

		public function getItems($itemsArrayId = array())
		{
			if(empty($itemsArrayId)) return array();
			#get item data
			$res = $this->db->query("
				SELECT 
					`i`.`id`,
					`i`.`name`,
					`i`.`brand_id`,
					`i`.`cat_id`,
					`i`.`type_id`
				FROM 
					`plug_catalog_items` AS `i`
				WHERE 
					`i`.`id` IN (".implode(',', $itemsArrayId).")
			");
			$resArray = $res->fetchAll();
			return($resArray);
		}
		
		public function getItem($itemId)
		{
			#get item data
			$res = $this->db->prepare("
				SELECT 
					`i`.`id`,
					`i`.`name`,
					`i`.`brand_id`,
					`i`.`cat_id`,
					`i`.`type_id`
				FROM 
					`plug_catalog_items` AS `i`
				WHERE 
					`i`.`id`=:id
				LIMIT 1
			");
			$res->bindValue(':id', $itemId, PDO::PARAM_INT);
			$res->execute();
			$resArray = $res->fetch();
			return($resArray);
		}

		public function getItemFullData($itemId)
		{
			$postArray['id'] = $itemId;

			#get categories array
			$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_cat`");
			if( $res )	$this->template->assign('categoriesArray', $res->fetchAll());
								
			#get brands array
			$res = $this->db->query("SELECT `id`, `name` FROM `plug_catalog_brands`");
			if( $res )	$this->template->assign('brandsArray', $res->fetchAll());
			
			#get item data
			$res = $this->db->prepare("
				SELECT 
					`i`.*,
					`b`.`name` AS `brand_name`,
					`c`.`name` AS `category_name`,
					`t`.`name` AS `type_name`
				FROM 
					`plug_catalog_items` AS `i`
				LEFT JOIN
					`plug_catalog_brands` AS `b`
				ON
					`b`.id = `i`.`brand_id`
				LEFT JOIN
					`plug_catalog_cat` AS `c`
				ON
					`c`.id = `i`.`cat_id`
				LEFT JOIN
					`plug_catalog_types` AS `t`
				ON
					`t`.id=`i`.`type_id`
				WHERE
					`i`.`id`=:id
				LIMIT 1
			");
			$res->bindValue(':id', $postArray['id'], PDO::PARAM_INT);
			$res->execute();
			$resArray = $res->fetch();
			if(!empty($resArray))
			{

				#get other images if is exists
				$res = $this->db->prepare("
					SELECT * FROM
						`plug_catalog_img`
					WHERE
						`item_id`=:item_id
					ORDER BY 
						`img_range`
						");
				$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
				$res->execute();
				$imgArray = $res->fetchAll();
				if(!empty($imgArray))
				{
					foreach($imgArray as &$dataImage)
					{
						$dataImage['filename'] = $this->itemImagesOtherPath.$this->getFolderById	( $postArray['id'] ).'/'.$dataImage['filename'];
					}
					
					$resArray['other_images'] = $imgArray;
				}

				
				if($resArray['create_date']=="0000-00-00") $resArray['create_date'] = date("Y-m-d");
				if($resArray['create_time']=="00:00:00") $resArray['create_time'] = date("H:i:s");

				if($resArray['update_date']=="0000-00-00") $resArray['update_date'] = date("Y-m-d");
				if($resArray['update_time']=="00:00:00") $resArray['update_time'] = date("H:i:s");

				$date = new DateTime( $resArray['create_date'] );
				$createDate = $date->format("d.m.Y");
				unset($date);

				$date = new DateTime( $resArray['update_date'] );
				$updateDate = $date->format("d.m.Y");
				unset($date);

				$date = new DateTime( $resArray['update_date'] );
				$date->modify('+1 month');
				$expiresDate = $date->format("d.m.Y");
				unset($date);
				
				if( $resArray['item_logo']!="" && file_exists($this->root.$this->itemImagesPath.$this->getFolderById	( $postArray['id'] ).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$resArray['item_logo'])) $resArray['full_logo_src'] = $this->itemImagesPath.$this->getFolderById	( $postArray['id']).'/'.$this->itemLogosSizer[0][0].'x'.$this->itemLogosSizer[0][1].'/'.$resArray['item_logo'];
				
				$resArray['create_date_time']  = $createDate.' '.$resArray['create_time'];				
				$resArray['update_date_time']  = $updateDate.' '.$resArray['update_time'];				
				$resArray['expires_date_time']  = $expiresDate.' '.$resArray['update_time'];				
				
				
				#get all types
				$res = $this->db->query("SELECT * FROM `plug_catalog_types` ");
						$typesArray = $res->fetchAll();
				if( !empty($typesArray) ) $this->template->assign('typesArray', $typesArray);
				
				
				#get item attributes
				$res = $this->db->prepare("
					SELECT
						`attr`.`id` AS `attr_id`,
						`attr`.`type_id`,
						`attr`.`selector` AS `attribute_selector`,
						`attr`.`name` AS `attribute_name`,
						`attr`.`units` AS `attribute_units`,
						`attr`.`in_list`,
						`vals`.`value_id`
					FROM
						`plug_catalog_types_attr` AS `attr`
					LEFT JOIN
						`plug_catalog_item_attr_vals` AS `vals`
					ON
						`vals`.`attr_id`=`attr`.`id`
					WHERE 
						`vals`.`item_id`=:item_id
				");
				$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
				$res->execute();
				$attrArray = $res->fetchAll();

				if( empty($attrArray) )
				{
					$res = $this->db->prepare("
						SELECT
							`attr`.`id` AS `attr_id`,
							`attr`.`type_id`,
							`attr`.`selector` AS `attribute_selector`,
							`attr`.`name` AS `attribute_name`,
							`attr`.`units` AS `attribute_units`,
							`attr`.`in_list`
						FROM
							`plug_catalog_types_attr` AS `attr`
						WHERE 
							`attr`.`type_id`=:type_id
					");
					$res->bindValue(':type_id', $resArray['type_id'], PDO::PARAM_INT);
					$res->execute();
					$attrArray = $res->fetchAll();
				}
						
				if( !empty($attrArray) )
				{
					#get item attributes values
					$res = $this->db->prepare("
						SELECT 
							*
						FROM
							`plug_catalog_types_vals`
						WHERE `type_id`=:type_id
					");

					$res->bindValue(':type_id', $resArray['type_id'], PDO::PARAM_INT);
					$res->execute();
					$tmpArray = $res->fetchAll();
					if( !empty( $tmpArray ) )
					{
						$attrValuesArray = array();
						foreach( $tmpArray as $data)
						{
							$attrValuesArray[ $data['attr_id'] ] [ $data['id'] ] = array(
								'attr_id'	=>	 $data['attr_id'],
								'id'		=>	 $data['id'],
								'value'		=>	 $data['value']
							);
						}
					}							

					foreach( $attrArray as &$data )
					{
						if( isset( $attrValuesArray[ $data['attr_id'] ] ) ) $data['attributesValues'] = $attrValuesArray[ $data['attr_id'] ];
					}

					$this->template->assign('itemAttr', $attrArray);
				}
				

				#get analogs
				$res = $this->db->prepare("
					SELECT 
						`a`.`analog_id`, 
						`i`.`name` 
					FROM 
						`plug_catalog_item_analogs` AS `a` 
					LEFT JOIN
						`plug_catalog_items` AS `i`
					ON
						`i`.`id`=`a`.`analog_id`
					WHERE 
						`a`.`item_id`=:item_id 
				");
				$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
				$res->execute();
				$analogsArray = $res->fetchAll();
				if( !empty( $analogsArray ) )
				{
					$this->template->assign('analogs', $analogsArray);
					$this->template->assign('analogsCount', count( $analogsArray ));
				}
				
				
				#get accompanying
				$res = $this->db->prepare("
					SELECT 
						`a`.`accompanying_id`, 
						`i`.`name` 
					FROM 
						`plug_catalog_item_accompanying` AS `a` 
					LEFT JOIN
						`plug_catalog_items` AS `i`
					ON
						`i`.`id`=`a`.`accompanying_id`
					WHERE 
						`a`.`item_id`=:item_id 
				");
				$res->bindValue(':item_id', $postArray['id'], PDO::PARAM_INT);
				$res->execute();
				$accompanyingArray = $res->fetchAll();
				if( !empty( $accompanyingArray ) ) 
				{
					$resArray['accompanying'] = $accompanyingArray;
				}
				
				return $resArray;
			}
			return array();
		}

		public function uploadFile( $files, $post )
		{
			if( !isset($post['action']) ) return "Неизвестная ошибка";
			
			switch( $post['action'] )
			{
				case 'uploadBrandLogo':
					return $this->uploadBrandFiles( $files, $post );
				break;
				case 'uploadCatLogo':
					return $this->uploadCatFiles( $files, $post );
				break;

				case 'uploadItemLogo':
					return $this->uploadItemFiles( $files, $post, true );
				break;
				
				case 'uploadItemImages':
					return $this->uploadItemFiles( $files, $post );
				break;
								
				case 'uploadImportFile':
					return $this->uploadImportFile( $files, $post );
				break;
				
				case 'uploadImportImagesArchive':
					return $this->uploadImportImagesArchive( $files, $post );
				break;

				default:
					return "Неизвестная ошибка";
				break;
			}

		}

		private function importItemUpdate( $importFilename,  $importItemId, $archiveFilename, $tmpFolderName )
		{
			$xml = ( isset($_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData']) ) ? $_SESSION[ $this->host ]['admin']['catalog']['xmlImportFileData'] : array();

			if(!isset($xml['items']['item'])) break;
			
			if( !isset( $xml['items']['item'][0] ) )
			{
				$xml['items']['item'] = array(0=>$xml['items']['item']);
			}

			foreach( $xml['items']['item'] as $item )
			{
				
				$itemId = (isset($item['@attributes']['id'])) ? $item['@attributes']['id'] : 'NULL';

				if( $itemId != $importItemId) continue;

				//archiveFilename
				$dataArray = array(
					'id'					=> $itemId,
					'external_id'			=> ( isset( $item['external_id'] ) ) ? $item['external_id'] : $itemId,
					'name'					=> ( isset( $item['name'] ) ) ? $item['name'] : 'Без названия',
					'meta_title'			=> ( isset( $item['meta_title'] ) ) ? $item['meta_title'] : '',
					'meta_description'		=> ( isset( $item['meta_description'] ) ) ? $item['meta_description'] : '',
					'meta_keywords'			=> ( isset( $item['meta_keywords'] ) ) ? $item['meta_keywords'] : '',
					'extra_meta'			=> ( isset( $item['extra_meta'] ) ) ? $item['extra_meta'] : '',
					'price'					=> ( isset( $item['price'] ) ) ? $item['price'] : '0',
					'old_price'				=> ( isset( $item['old_price'] ) ) ? $item['old_price'] : '0',
					'item_quick_desc'		=> ( isset( $item['quick_desc'] ) ) ? $item['quick_desc'] : '',
					'item_desc'				=> ( isset( $item['desc'] ) ) ? $item['desc'] : '',
					'hide_in_list'			=> ( isset( $item['hide_in_list'] ) ) ? $item['hide_in_list'] : '0',
					'disabled'				=> ( isset( $item['disabled'] ) ) ? $item['disabled'] : '0',
					'link'					=> ( isset( $item['link'] ) ) ? preg_replace('/[^0-9a-zA-Z-_]/', '_', $item['link']) : 'unnamed_'.uniqid(),
					'cat_id'				=> ( isset( $item['cat_id'] ) ) ? $item['cat_id'] : '0',
					'brand_id'				=> ( isset( $item['brand_id'] ) ) ? $item['brand_id'] : '0',
					'type_id'				=> ( isset( $item['type_id'] ) ) ? $item['type_id'] : '0',
					'item_range'			=> ( isset( $item['range'] ) ) ? $item['range'] : '0',
					'in_stock'				=> ( isset( $item['stock'] ) ) ? $item['stock'] : '0',
					'is_markdown'			=> ( isset( $item['markdown'] ) ) ? $item['markdown'] : '0',
					'is_new'				=> ( isset( $item['new'] ) ) ? $item['new'] : '0',
					'is_sale'				=> ( isset( $item['sale'] ) ) ? $item['sale'] : '0',
					'is_best'				=> ( isset( $item['best'] ) ) ? $item['best'] : '0',
					'articul'				=> ( isset( $item['articul'] ) ) ? $item['articul'] : '',
					'delivery_city'			=> ( isset( $item['delivery']['city'] ) ) ? $item['delivery']['city'] : '0',
					'delivery_region'		=> ( isset( $item['delivery']['region'] ) ) ? $item['delivery']['region'] : '0',
					'delivery_out_region'	=> ( isset( $item['delivery']['out_region'] ) ) ? $item['delivery']['out_region'] : '0',
					'pay_cash_person'		=> ( isset( $item['pay']['cash_person'] ) ) ? $item['pay']['cash_person'] : '0',
					'pay_card_person'		=> ( isset( $item['pay']['card_person'] ) ) ? $item['pay']['card_person'] : '0',
					'pay_card_web'			=> ( isset( $item['pay']['card_web'] ) ) ? $item['pay']['card_web'] : '0',
					'pay_web_money'			=> ( isset( $item['pay']['web_money'] ) ) ? $item['pay']['web_money'] : '0',
					'pay_entity'			=> ( isset( $item['pay']['entity'] ) ) ? $item['pay']['entity'] : '0',
					'update_date'			=> date("Y-m-d"),
					'update_time'			=> date("H:i:s")
				);
						

				#get item attributes
				if( isset( $item['attributes'] ) )
				{
					if( isset($item['attributes']['attribute']) )
					{
						foreach($item['attributes']['attribute'] as $attr)
						{

  							if( !isset($attr["@attributes"]["id"])  ) continue;

  							$attrId = $attr["@attributes"]["id"];
  	  						
  	  						if( !isset( $attr['unit'] ) ) $attr['unit'] = '';
  	  						if( !isset( $attr['name'] ) ) $attr['name'] = 'Без имени';
  	  						if( !isset( $attr['value'] ) ) $attr['value'] = '0';
  							
  							 $res = $this->db->prepare("
  	  							INSERT INTO
  	  								`plug_catalog_types_attr`
  	  							(`id`, `type_id`, `name`, `units`)
  	  							VALUES
  	  							(:attr_id, :type_id, :name, :units )
  	  							ON DUPLICATE KEY UPDATE
  	  							`id`=VALUES(`id`),
  	  							`type_id`=VALUES(`type_id`),
  	  							`name`=VALUES(`name`),
  	  							`units`=VALUES(`units`)
  	  						");
  							$res->bindValue(':attr_id', $attrId);
  							$res->bindValue(':type_id', $dataArray['type_id']);
  							$res->bindValue(':name', $attr['name']);
  							$res->bindValue(':units', $attr['unit']);
  							$res->execute();	


  	  						#check for exists
  	  						$res = $this->db->prepare("
  	  							SELECT `id`
  	  							FROM
  	  								`plug_catalog_types_vals`
  	  							WHERE
  	  								`type_id`=:type_id
  	  							AND
  	  								`attr_id`=:attr_id
  	  							AND
  	  								`value`=:value
  	  							LIMIT 1
  	  						");
  							$res->bindValue(':type_id', $dataArray['type_id']);
  							$res->bindValue(':attr_id', $attrId);
  							$res->bindValue(':value', $attr['value']);
  							$res->execute();
  							$resArray = $res->fetchAll();
  							
  							#insert if not exists
  							if( empty($resArray) )
  							{

  							}else{
  								#update if exists
  								$res = $this->db->prepare("
  									SELECT 	
  										`id`
  									FROM
  										`plug_catalog_item_attr_vals`
  									WHERE
  										`type_id`=:type_id
  									AND
  										`item_id`=:item_id
  									AND
  										`attr_id`=:attr_id
  									LIMIT 1
  								");
  								$res->bindValue(':type_id', $dataArray['type_id']);
  								$res->bindValue(':item_id', $dataArray['id']);
  								$res->bindValue(':attr_id', $attrId);
  								$res->execute();
  								$resArray = $res->fetch();


   								$res = $this->db->prepare("
  									UPDATE
  										`plug_catalog_types_vals`
  									SET
  										`value`=:value
  									WHERE
  										`type_id`=:type_id
  									AND
  										`attr_id`=:attr_id
  									AND
  										`id`=:id
  								");
  								$res->bindValue(':value', $attr['value']);
  								$res->bindValue(':type_id', $dataArray['type_id']);
  								$res->bindValue(':attr_id', $attrId);
  								$res->bindValue(':id', $resArray['id']);
  								$res->execute();
										
  							}

  	  						#check for exists
  	  						$res = $this->db->prepare("
  	  							SELECT 
  	  								`id`
  	  							FROM
  	  								`plug_catalog_item_attr_vals`
  	  							WHERE
  	  								`type_id`=:type_id
  	  							AND
  	  								`item_id`=:item_id
  	  							AND
  	  								`attr_id`=:attr_id
  	  							LIMIT 1
  	  						");
  							$res->bindValue(':type_id', $dataArray['type_id']);
  							$res->bindValue(':item_id', $dataArray['id']);
  							$res->bindValue(':attr_id', $attrId);
  							$res->execute();
  							$resArray = $res->fetch();
  							if( empty($resArray) )
  							{
  								/*
  								echo "
  									insert value into plug_catalog_types_vals
  								";
  								*/
  								//insert value into plug_catalog_types_vals
  								$res = $this->db->prepare("
  									INSERT INTO
  										`plug_catalog_types_vals`
  									SET
  										`type_id`=:type_id,
  										`attr_id`=:attr_id,
  										`value`=:value
  								");
  								$res->bindValue(':type_id', $dataArray['type_id']);
  								$res->bindValue(':attr_id', $attrId);
  								$res->bindValue(':value', $attr['value']);
  								$res->execute();
  								/*
  								echo "
  									and update plug_catalog_item_attr_vals set LASTINSERTID
  								";
  								*/

  								//and update plug_catalog_item_attr_vals set LASTINSERTID
  								$res = $this->db->prepare("
  									INSERT INTO
  										`plug_catalog_item_attr_vals`
  									SET
  										`value_id`=:value_id,
  										`type_id`=:type_id,
  										`item_id`=:item_id,
  										`attr_id`=:attr_id
  								");
  								$res->bindValue(':value_id', $this->db->lastInsertId());
  								$res->bindValue(':type_id', $dataArray['type_id']);
  								$res->bindValue(':item_id', $dataArray['id']);
  								$res->bindValue(':attr_id', $attrId);
  								$res->execute();

  							}else{
  								#check value exists
  								$res = $this->db->prepare("
  									SELECT
  										`id`
  									FROM
  										`plug_catalog_types_vals`
  									WHERE
  										`type_id`=:type_id,
  										`attr_id`=:attr_id,
  										`value`=:value
  									LIMIT 1
  								");
  								$res->bindValue(':type_id', $dataArray['type_id']);
  								$res->bindValue(':attr_id', $attrId);
  								$res->bindValue(':value', $attr['value']);
  								$res->execute();
  								$resArray = $res->fetch();
  								if( !empty( $resArray ) )
  								{

  								}else{
  									#add new value
  									$res = $this->db->prepare("
  										INSERT INTO
  											`plug_catalog_types_vals`
  										SET
  											`type_id`=:type_id,
  											`attr_id`=:attr_id,
  											`value`=:value
  									");
  									$res->bindValue(':type_id', $dataArray['type_id']);
  									$res->bindValue(':attr_id', $attrId);
  									$res->bindValue(':value', $attr['value']);
  									$res->execute();

  									#adn update value id
  									$res = $this->db->prepare("
  										UPDATE
  											`plug_catalog_item_attr_vals`
  										SET
  											`value_id`=:value_id
  										WHERE
  											`type_id`=:type_id
  										AND
  											`item_id`=:item_id
  										AND
  											`attr_id`=:attr_id
  									");
  									$res->bindValue(':value_id', $this->db->lastInsertId());
  									$res->bindValue(':type_id', $dataArray['type_id']);
  									$res->bindValue(':item_id', $dataArray['id']);
  									$res->bindValue(':attr_id', $attrId);
  									$res->execute();  											
  								}
  							}
  	  					}	
					}
				}

				#get images
				if( isset( $item['images']['img'] ) )
				{
					if( isset( $item['images']['img'][0] ) )
					{
						foreach($item['images']['img'] as $image)
						{
							
  							if(!file_exists( $this->root.$this->core->tempPath.$tmpFolderName.'/'.$image["@attributes"]["filename"] )) continue;

							#get file external
							$tmpArr = explode(".", $image["@attributes"]["filename"]);
							$fileExternal = strtolower(array_pop($tmpArr)); 
							unset($tmpArr);
							
							#set output full filename
							$outputName = uniqid().'.'.$fileExternal;


							#if is main photo
							if( isset($image["@attributes"]["main"]) && $image["@attributes"]["main"]=='1' )
							{
								#resampling file and copy
								foreach( $this->itemLogosSizer as $index => $size )
								{
									$dir = $this->root.$this->itemImagesPath.$this->getFolderById	( $dataArray['id'] ).'/'.$size[0].'x'.$size[1];
									if(!is_dir($dir))	mkdir($dir, 0777, true);	
									$this->img->source = $this->root.$this->core->tempPath.$tmpFolderName.'/'.$image["@attributes"]["filename"];
									$this->img->output = $dir.'/'.$outputName;
									$this->img->resize( $size[0], $size[1], true );
								}

								#add to data array
								$dataArray['item_logo'] = $outputName;
							}else{
								if( !isset($otherImages) ) $otherImages = array();
								$otherImages[] = $outputName;
								//write into DB


								#if is other photos
								$dir = $this->root.$this->itemImagesOtherPath.$this->getFolderById	( $dataArray['id'] );
								if(!is_dir($dir))	mkdir($dir, 0777, true);	
								rename($this->root.$this->core->tempPath.$tmpFolderName.'/'.$image["@attributes"]["filename"], $dir.'/'.$outputName);
							}
						}

						if(isset($otherImages))
						{
							#compile query
							$values = array();
							foreach($otherImages as $index => $filename)
							{
								$values[] = "('".$dataArray['id']."','".$filename."', '".$index."')";
							}

							#remove old data
							$res = $this->db->prepare("DELETE FROM `plug_catalog_img` WHERE `item_id`=:item_id");
							$res->bindValue(':item_id', $dataArray['id']);
							$res->execute();

							#write new data
							$this->db->query( "
								INSERT INTO 
									`plug_catalog_img` 
									(`item_id`, `filename`, `img_range`)
									 VALUES ".implode(',', $values)
							);
						}
					}else{
							#get file external
							$tmpArr = explode(".", $item['images']['img']["@attributes"]["filename"]);
							$fileExternal = strtolower(array_pop($tmpArr)); 
							unset($tmpArr);
							
							#set output full filename
							$outputName = uniqid().'.'.$fileExternal;
							
							#set output full filename
							$outputName = uniqid().'.'.$fileExternal;

							#resampling file and copy
							foreach( $this->itemLogosSizer as $index => $size )
							{
								$dir = $this->root.$this->itemImagesPath.$this->getFolderById	( $dataArray['id'] ).'/'.$size[0].'x'.$size[1];
								if(!is_dir($dir))	mkdir($dir, 0777, true);	
								$this->img->source = $this->root.$this->core->tempPath.$tmpFolderName.'/'.$item['images']['img']["@attributes"]["filename"];
								$this->img->output = $dir.'/'.$outputName;
								$this->img->resize( $size[0], $size[1], true );
							}

							#add to data array
							$dataArray['item_logo'] = $outputName;
					}
				} 


				$fields = array();
				$values = array();
				$updated = array();
				foreach( $dataArray as $field => $value )
				{
					$fields[] = "`".$field."`";
					$values[] = "'".addslashes($value)."'";
					$updated[] = "`".$field."`=VALUES(`".$field."`)";
				}

				$this->db->query( "
					INSERT INTO 
						`plug_catalog_items` 
						(".implode(',', $fields).") 
					VALUES 
						(".implode(',', $values).") 
					ON DUPLICATE KEY UPDATE 
						".implode(',', $updated)."
				");
			}

			$this->core->log('Каталог', 'Выполнен импорт');

		}

		public function uploadImportFile( $files, $post )
		{
			$responseArray = array('error' => '');
			#check filesize
			if( $files['importFile']['size'] > (60*1024*1024) ) $responseArray['error'] = "Размер файла превышает 60 МБ"; return;
			
			#return success result
			return "";
		}

		public function uploadImportImagesArchive( $files, $post )
		{
			$responseArray = array('error' => '');
			#check filesize
			if( $files['importArchive']['size'] > (60*1024*1024) ) $responseArray['error'] = "Размер файла превышает 60 МБ"; return;

			#return success result
			return "";
		}

		public function uploadBrandFiles( $files, $post, $logo = false )
		{
			
			#check filesize
			if( $files['brand_logo']['size'] > (3*1024*1024) ) return "Размер файла превышает 3 МБ";
			
			#check file
			if( !$this->img->isImg( $files['brand_logo']['tmp_name'] ) ) return "Данный файл не является изображением, либо изображение повреждено";
		
			#resize brand_logo
			$this->img->source = $files['brand_logo']['tmp_name'];
			$this->img->output = $this->root.$this->core->tempPath.'_'.$files['brand_logo']['name'];
			if ( $this->img->resize( $this->brandLogosSizer[0][0], null, true) )
			{
				#unlink temporary file
				unlink( $files['brand_logo']['tmp_name'] );
				
				#try to rename file
				if( !rename( $this->root.$this->core->tempPath.'_'.$files['brand_logo']['name'], $files['brand_logo']['tmp_name'] ))
				{
					#try to rename file else
					rename( $this->root.$this->core->tempPath.'_'.$files['brand_logo']['name'], $files['brand_logo']['tmp_name'] );
				}
			#if error	
			}else{
				return "При загрузке файла произошла ошибка.";
			}
				
			#return success result
			return "";	
		}	
		
		public function uploadCatFiles( $files, $post, $logo = false )
		{
			
			#check filesize
			if( $files['cat_logo']['size'] > (3*1024*1024) ) return "Размер файла превышает 3 МБ";
			
			#check file
			if( !$this->img->isImg( $files['cat_logo']['tmp_name'] ) ) return "Данный файл не является изображением, либо изображение повреждено";
		
			#resize cat_logo
			$this->img->source = $files['cat_logo']['tmp_name'];
			$this->img->output = $this->root.$this->core->tempPath.'_'.$files['cat_logo']['name'];
			if ( $this->img->resize( $this->categoryLogosSizer[0][0], null, true) )
			{
				#unlink temporary file
				unlink( $files['cat_logo']['tmp_name'] );
				
				#try to rename file
				if( !rename( $this->root.$this->core->tempPath.'_'.$files['cat_logo']['name'], $files['cat_logo']['tmp_name'] ))
				{
					#try to rename file else
					rename( $this->root.$this->core->tempPath.'_'.$files['cat_logo']['name'], $files['cat_logo']['tmp_name'] );
				}
			#if error	
			}else{
				return "При загрузке файла произошла ошибка.";
			}
				
			#return success result
			return "";	
		}	

		public function uploadItemFiles( $files, $post, $logo = false )
		{
			#if is logo
			if( $logo )
			{
				#check filesize
				if( $files['item_logo']['size'] > (3*1024*1024) ) return "Размер файла превышает 3 МБ";
				
				#check file
				if( !$this->img->isImg( $files['item_logo']['tmp_name'] ) ) return "Данный файл не является изображением, либо изображение повреждено";
			
				#resize item_logo
				$this->img->source = $files['item_logo']['tmp_name'];
				$this->img->output = $this->root.$this->core->tempPath.'_'.$files['item_logo']['name'];
				if ( $this->img->resize( $this->itemLogosSizer[0][0], null, true) )
				{
					#unlink temporary file
					unlink( $files['item_logo']['tmp_name'] );
					
					#try to rename file
					if( !rename( $this->root.$this->core->tempPath.'_'.$files['item_logo']['name'], $files['item_logo']['tmp_name'] ))
					{
						#try to rename file else
						rename( $this->root.$this->core->tempPath.'_'.$files['item_logo']['name'], $files['item_logo']['tmp_name'] );
					}
				#if error	
				}else{
					return "При загрузке файла произошла ошибка.";
				}
					
				#return success result
				return "";	
				
			#if is not logo	
			}else{
				#check filesize
				if( $files['item_other_images']['size'] > (3*1024*1024) ) return "Размер файла превышает 3 МБ";

				#check file
				if( !$this->img->isImg( $files['item_other_images']['tmp_name'] ) ) return "Данный файл не является изображением, либо изображение повреждено";
					
				#resize item_other_images
				$this->img->source = $files['item_other_images']['tmp_name'];
				$this->img->output = $this->root.$this->core->tempPath.'_'.$files['item_other_images']['name'];
				if ( $this->img->resize( 800, null, true) )
				{
					#unlink temporary file
					unlink( $files['item_other_images']['tmp_name'] );
					
					#try to rename file
					if( !rename( $this->root.$this->core->tempPath.'_'.$files['item_other_images']['name'], $files['item_other_images']['tmp_name'] ))
					{
						#try to rename file else
						rename( $this->root.$this->core->tempPath.'_'.$files['item_other_images']['name'], $files['item_other_images']['tmp_name'] );
					}
				#if error	
				}else{
					return "При загрузке файла произошла ошибка.";
				}
				
				return "";	
			}
		}
		
		public function getCommentsList( $itemId,  $dateFormat="j F Y" )
		{
			#load data
			$query = "SELECT * FROM `plug_catalog_comments` WHERE `item_id`=:item_id ORDER BY `pid` DESC, `comment_date`, `comment_time`";
			$res = $this->db->prepare( $query );
			$res->bindValue(':item_id', $itemId);
			$res->execute();
			
			$tableArray = $res->fetchAll();
		
			#compile tree array
 			foreach ($tableArray as $row)
			{	
				$tree[$row['id']] = array( 
					'id'			=>	$row['id'],
					'pid'			=>	$row['pid'],
					'user_name'		=>	$row['user_name'], 
					'comment_text'	=>	($row['hide']=='0') ? $this->core->bbcodeToHtml( $row['comment_text'] ) : '', 
					'comment_date'	=>	$this->core->dateToRus( $dateFormat, strtotime( $row['comment_date'] ) ),
					'comment_time'	=>	substr($row['comment_time'], 0, -3),
					'hide'			=>	$row['hide']
				);
			} 
			
			if( !isset($tree) ) return;
	
			#paste childs items to parent
			foreach($tree as $id => $arr)
			{
				//if($arr['hide']!='1') continue;
				if( $arr['pid']!=0 )
				{
					$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
					unset( $tree[ $id ] );
				}
			}
			
	
			$this->itemCommentsArray = $tree;

		}

		#get folder name by id
		public function getFolderById($id)
		{
			return intval($id / 50);
		}

		public function xmlToArray ( $xmlObject, $output = array () )
		{
		    foreach ( (array) $xmlObject as $index => $node )
		    {
		        $output[$index] = ( is_object ( $node ) ) ? $this->xmlToArray ( $node ) : $node;
			}
		    return $output;
		}
		
	}

?>