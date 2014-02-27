<?
@session_start();

class settings extends Get{

	public $host = '';
	public $root = '';
	public $protocol = '';
	
	public function __construct()
	{
		parent::init();
		$this->host = $this->core->host;
		$this->root = $this->core->root;
		$this->protocol = $this->config->protocol;
	}
	
	public function createRobotsTxt()
	{
		$handle = fopen( $this->root.'/robots.txt', 'w+' );
		fwrite( $handle, "\xEF\xBB\xBFUser-agent: *\n");
		fwrite( $handle, "Host: ".$this->host."\n");
		fwrite( $handle, "\n");
		fwrite( $handle, "#SYSTEM DIRECTORIES\n");
		fwrite( $handle, "Disallow: /system/\n");
		fwrite( $handle, "Disallow: /css/\n");
		fwrite( $handle, "Disallow: /js/\n");
		fwrite( $handle, "Disallow: /mod/\n");
		fwrite( $handle, "Disallow: /plug/\n");
		fwrite( $handle, "Disallow: /tpl/\n");
		fwrite( $handle, "Disallow: /tpl_compile/\n");
		fwrite( $handle, "#END SYSTEM DIRECTORIES\n");
		fclose( $handle );
	}
	
	public function createSitemapXml()
	{
		$partsArray = array();

		$defaultExpiresDate = date( "Y-m-d", strtotime( date("Y-m-d")." +1 month" ) );

		$this->host = $this->protocol.'://'.$this->host;

		#PARTITIONS
		$res = $this->db->query("
			SELECT 
				`url`,
				`expires_date`
			FROM
				`parts`
			WHERE
				`off`='0'
		");
		$resArray = $res->fetchAll();
		if( !empty($resArray) )
		{
			foreach( $resArray as $data )
			{
				$partsArray[] = array(
					'loc'		=> $this->host.$data['url'],
					'lastmod'	=> substr($data['expires_date'], 0, 10),
					'priority'	=> '1.00'
				);
			}
		}


		


		#CATALOG
		$catalogMarkerArray = array(
			#category url marker
			'catMarker'					=>	'cat',
			
			#item link url marker
			'itemMarker'				=>	'item',
			
			#brand link url marker
			'brandMarker'				=>	'brand',

			#brands page url marker
			'brandsMarker'				=>	'brands'
		);

		$res = $this->db->query("
			SELECT 
				`catalog_page_id`
			FROM
				`plug_catalog_sett`
			WHERE
				`id`='1'
			LIMIT 1	
		");	
		if($res)
		{
			$resArray = $res->fetch();

			#get catalog page url
			$res = $this->db->prepare("
				SELECT 
					`url`
				FROM
					`parts`
				WHERE
					`id`=:id
				LIMIT 1	
			");	
			$res->bindValue(':id', $resArray['catalog_page_id'], PDO::PARAM_INT);
			$res->execute();
			$resArray = $res->fetch();
			if( !empty( $resArray ) ) $catalogPageUrl = $resArray['url'];

			if( isset( $catalogPageUrl ) )
			{
				

				#get catalog items
				$res = $this->db->query("
					SELECT 
						`i`.`link` AS `item_link`,
						`i`.`cat_id`,
						`c`.`url` AS `cat_url`
					FROM
						`plug_catalog_items` AS `i`
					LEFT JOIN
						`plug_catalog_cat` AS `c`
					ON
						`c`.`id` = `i`.`cat_id`
					WHERE
						`i`.`disabled`='0'
					ORDER BY
						`i`.`item_range`	
				");
				if( $res )
				{
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						foreach( $resArray as $data )
						{
							$partsArray[] = array(
								'loc'		=> $this->host.$catalogPageUrl.$catalogMarkerArray['catMarker'].$data['cat_url'].$catalogMarkerArray['itemMarker'].'/'.$data['item_link'].'/',
								'lastmod'	=> $defaultExpiresDate,
								'priority'	=> '1.00'
							);
						}
					}
				}





				#get catalog categories
				$res = $this->db->query("
					SELECT 
						`url`
					FROM
						`plug_catalog_cat`
					WHERE
						`off`='0'	
					ORDER BY
						`cat_range`
				");
				if( $res )
				{
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						foreach( $resArray as $data )
						{
							$partsArray[] = array(
								'loc'		=> $this->host.$catalogPageUrl.$catalogMarkerArray['catMarker'].$data['url'],
								'lastmod'	=> $defaultExpiresDate,
								'priority'	=> '1.00'
							);
						}
					}
				}


				#set catalog brands page
				$partsArray[] = array(
					'loc'		=> $this->host.$catalogPageUrl.$catalogMarkerArray['brandsMarker'].'/',
					'lastmod'	=> $defaultExpiresDate,
					'priority'	=> '1.00'
				);

				#get catalog brands
				$res = $this->db->query("
					SELECT 
						`link`
					FROM
						`plug_catalog_brands`
					WHERE
						`disabled`='0'	
					ORDER BY
						`brand_range`
				");
				if( $res )
				{
					$resArray = $res->fetchAll();
					if( !empty($resArray) )
					{
						foreach( $resArray as $data )
						{
							$partsArray[] = array(
								'loc'		=> $this->host.$catalogPageUrl.$catalogMarkerArray['brandMarker'].'/'.$data['link'].'/',
								'lastmod'	=> $defaultExpiresDate,
								'priority'	=> '1.00'
							);
						}
					}
				}


				//
				//..
				//


		
			}
		
		}


		if(empty($partsArray)) return;

		$handle = fopen( $this->root.'/sitemap.xml', 'a+' );
		fwrite( $handle, "\xEF\xBB\xBF<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
		fwrite( $handle, "<urlset\n");
		fwrite( $handle, "    xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n");
		fwrite( $handle, "    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n");
		fwrite( $handle, "    xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n");
		fwrite( $handle, "    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n");

		foreach( $partsArray as $index => $data )
		{
			fwrite( $handle, "\t<url>\n");
			fwrite( $handle, "\t\t<loc>".$data['loc']."</loc>\n");//http://site.ru/page/
			fwrite( $handle, "\t\t<lastmod>".$data['lastmod']."</lastmod>\n");//2013-12-29
			fwrite( $handle, "\t\t<priority>".$data['priority']."</priority>\n");//1.00
			fwrite( $handle, "\t</url>\n");
		}
		fwrite( $handle, "</urlset>");
		fclose( $handle );
	}

	public function load()
	{
		if( !isset( $this->userPrivilege['site_settings'] )){
			die('access denied');
		}
		
		#get my ip address
		$myIp = $this->ip();
		
		#get my ip address in full format 000.000.000.000
		$tmpArray = explode('.', $myIp);
		foreach($tmpArray as &$number)
		{
			if( strlen($number)==1 ) $number = '00'.(string)$number;
			if( strlen($number)==2 ) $number = '0'.(string)$number;
		}
		$myIpV = implode('.', $tmpArray);
		
		#compile ip address template
		$this->template->assign('myIp', $myIp );
  		$this->template->assign('myIpV', $myIpV );
		
		#get exception ip address
		$res = $this->db->prepare("SELECT * FROM `exception_ip`");
		$res->execute();
		$resArray = $res->fetchAll();
		
		#compile exception ip address
		$this->template->assign('excetionIpArray', $resArray);
	
		
		#check file "robots.txt"
		if( file_exists( $this->root.'/robots.txt' ) )
		{
			#get file "robots.txt"
			$handle = fopen( $this->root.'/robots.txt', 'r+' );
			$robotsTxt = fread($handle, filesize( $this->root.'/robots.txt' ));
			$robotsTxt = str_replace("\xEF\xBB\xBF", '', $robotsTxt);
			fclose( $handle );

			#compile template robotsTxt
			$this->template->assign('robotsTxt', $robotsTxt);
		}
		
		
		#check file "sitemap.xml"
		if( file_exists( $this->root.'/sitemap.xml' ) )
		{
			#get file "sitemap.xml"
			$handle = fopen( $this->root.'/sitemap.xml', 'r+' );
			$sitemapXml = fread($handle, filesize( $this->root.'/sitemap.xml' ));
			$sitemapXml = str_replace("\xEF\xBB\xBF", '', $sitemapXml);
			fclose( $handle );

			#compile template robotsTxt
			$this->template->assign('sitemapXml', $sitemapXml);
		}

		#get site settings
		$res = $this->db->prepare("SELECT * FROM `settings` LIMIT 1");
		$res->execute();
		$resArray = $res->fetchAll();

		#compile settings template
		$this->template->assign('settings', $resArray[0] );
		
		#compile host template
		$this->template->assign('host', $this->host);
		
		#compile host protocol
		$this->template->assign('protocol', $this->config->protocol);
		
		#compile time zone array template
		$this->template->assign('timeZoneArray', array(
			'America'	=> array(
				'America/Anchorage',
				'America/Argentina/Buenos_Aires',
				'America/Caracas',
				'America/Denver',
				'America/Halifax',
				'America/Los_Angeles',
				'America/New_York',
				'America/Sao_Paulo',
				'America/St_Johns',
				'America/Tegucigalpa'
			),
			'Asia'	=> array(
				'Asia/Brunei',
				'Asia/Dhaka', 
				'Asia/Katmandu',
				'Asia/Krasnoyarsk',
				'Asia/Kolkata',
				'Asia/Kuwait',
				'Asia/Magadan',	
				'Asia/Muscat', 
				'Asia/Rangoon',
				'Asia/Seoul',
				'Asia/Tehran',
				'Asia/Yekaterinburg',
			),
			'Atlantic'	=> array(
				'Atlantic/South_Georgia',
				'Atlantic/Azores'
			),
			'Australia'	=> array(
				'Australia/Canberra',
				'Australia/Darwin' 
			)
			
		));
		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.settings.tpl');
	}
	
	
	public function save( $post )
	{
		
		#if is recreate "robots.txt"
		if( isset( $post['action'] ) && $post['action']=='recreateRobotsTxt' )
		{
			$this->log('Настройки сайта', 'Пересоздание файла "robots.txt"');
			
			#check file "robots.txt"
			if( file_exists( $this->root.'/robots.txt' ) )
			{
				unlink( $this->root.'/robots.txt' );
			}
			
			$this->createRobotsTxt();
		
			return;
		}

		#if is recreate "sitemap.xml"
		if( isset( $post['action'] ) && $post['action']=='createSitemapXml' )
		{
			$this->log('Настройки сайта', 'Пересоздание файла "sitemap.xml"');

			#check file "sitemap.xml"
			if( file_exists( $this->root.'/sitemap.xml' ) )
			{
				unlink( $this->root.'/sitemap.xml' );
			}

			$this->createSitemapXml();

			return;
		}

			
		
		
		#get site settings
		$res = $this->db->prepare("SELECT * FROM `settings` LIMIT 1");
		$res->execute();
		$resArray = $res->fetchAll();
		
		
		#site closed compile
		$closed = $post['closed'];
		
		if( $closed=='1' && $resArray[0]['closed']=='0' ) $this->log('Настройки сайта', 'Включение блокировки сайта');
		if( $closed=='0' && $resArray[0]['closed']=='1' ) $this->log('Настройки сайта', 'Отключение блокировки сайта');
		
		#gzip level compile
		$gzip_level = (isset( $post['gzip_level'] )) ? $post['gzip_level'] : $resArray[0]['gzip_level'];
		
		#save exception ip address
		$this->db->query("TRUNCATE TABLE `exception_ip`");
		if( isset($post['exception_ip']) )
		{
			foreach( $post['exception_ip'] as $index => $ip )
			{
				if( $ip == trim('') ||!preg_match('/\.[0-9]+/', $ip) ) continue;

				$tmpArray = explode('.', $ip);
				foreach( $tmpArray as &$value )
				{
					if( strlen($value)==3 )
					{
						$value = preg_replace('/^0{1,2}/', '', $value);
					}elseif( strlen($value)==2 )
					{
						$value = preg_replace('/^0{1}/', '', $value);
					}
				}
				$ip = implode('.', $tmpArray);
				$query .= "('".$ip."'),";
			}
			
			if( $query!='' )
			{
				$query = substr( $query, 0, -1);
				$this->db->query( "INSERT INTO `exception_ip` (`ip`) VALUES ".$query );
			}

		}
		
		#check file "robots.txt"
		if( !file_exists( $this->root.'/robots.txt' ) )
		{
			fclose( fopen( $this->root.'/robots.txt', 'x' ) );
		}
		
		#save file "robots.txt"
		$robotsTxt = "\xEF\xBB\xBF".$post['robots_txt'];
		$handle = fopen( $this->root.'/robots.txt', 'w+' );
		fwrite( $handle, $robotsTxt);
		fclose($handle);
		



		#check file "sitemap.xml"
		if( !file_exists( $this->root.'/sitemap.xml' ) )
		{
			fclose( fopen( $this->root.'/sitemap.xml', 'x' ) );
		}
		
		#save file "sitemap.xml"
		$sitemapXML = "\xEF\xBB\xBF".$post['sitemap_xml'];
		$handle = fopen( $this->root.'/sitemap.xml', 'w+' );
		fwrite( $handle, $sitemapXML);
		fclose($handle);



		#if unite javascript then create .htaccess from /js/ dir and js.php if not exist
		if( isset( $post['unite_js'] ) )
		{
			if( !file_exists( $this->root.'/js/.htaccess' ) )
			{
				$handle = fopen( $this->root.'/js/.htaccess', "w+");
				fwrite($handle, "RewriteEngine on\nRewriteBase /js/\nRewriteCond %{ENV:REDIRECT_MYFLAG} ^$\nRewriteRule  ^(.*)$ js.php [L,E=MYFLAG:1]");
				fclose($handle);
			}
			
			if( !file_exists( $this->root.'/js/js.php' ) )
			{
				$handle = fopen( $this->root.'/js/js.php', "w+");
				fwrite($handle, "<?\n
				@session_start();\n
				\$buffer = '';\n
				if(isset(  \$_SESSION[\$_SERVER['HTTP_HOST'] ]['metaBufferJsArray'] )){\n
				\tforeach( \$_SESSION[ \$_SERVER['HTTP_HOST'] ]['metaBufferJsArray'] as \$v ){\n
				\t\t\$buffer.= file_get_contents( \$_SERVER['DOCUMENT_ROOT'].\$v );\n
				\t}\n
				}\n
				if( isset(\$_SESSION[ \$_SERVER['HTTP_HOST'] ]['gzip']) ){\n
				\t\$gzipLevel = \$_SESSION[ \$_SERVER['HTTP_HOST'] ]['gzip'];\n
				\t\$buffer = gzencode( \$buffer, \$gzipLevel );\n
				\theader(\"Content-Encoding: gzip\");\n
				\theader(\"Vary: Accept-Encoding\");\n
				\theader(\"Content-Length: \".strlen(\$buffer));\n
				}\n\n
				header(\"Content-type: text/javascript;\");\n\n
				echo \$buffer;
				\n?>");
				fclose($handle);
			}
		#if not unite javascript then delete .htaccess from /js/ dir and js.php if is exist
		}else{
			if( file_exists( $this->root.'/js/.htaccess' ) )
			{
				unlink($this->root.'/js/.htaccess');
			}
		
			if( file_exists( $this->root.'/js/js.php' ) )
			{
				unlink($this->root.'/js/js.php');
			}
		}
		
		
		
		#if unite css then create .htaccess from /css/ dir and css.php if not exist
		if( isset( $post['unite_css'] ) )
		{
			if( !file_exists( $this->root.'/css/.htaccess' ) )
			{
				$handle = fopen( $this->root.'/css/.htaccess', "w+");
				fwrite($handle, "RewriteEngine on\nRewriteBase /css/\nRewriteCond %{ENV:REDIRECT_MYFLAG} ^$\nRewriteRule  ^(.*)\.css$ css.php [L,E=MYFLAG:1]");
				fclose($handle);
			}
			
			if( !file_exists( $this->root.'/css/css.php' ) )
			{
				$handle = fopen( $this->root.'/css/css.php', "w+");
				fwrite($handle, "<?\n
				@session_start();\n
				\$buffer = '';\n
				if(isset(  \$_SESSION[\$_SERVER['HTTP_HOST'] ]['metaBufferCssArray'] )){\n
				\tforeach( \$_SESSION[ \$_SERVER['HTTP_HOST'] ]['metaBufferCssArray'] as \$v ){\n
				\t\t\$buffer.= file_get_contents( \$_SERVER['DOCUMENT_ROOT'].\$v );\n
				\t}\n
				}\n
				if( isset(\$_SESSION[ \$_SERVER['HTTP_HOST'] ]['gzip']) ){\n
				\t\$gzipLevel = \$_SESSION[ \$_SERVER['HTTP_HOST'] ]['gzip'];\n
				\t\$buffer = gzencode( \$buffer, \$gzipLevel );\n
				\theader(\"Content-Encoding: gzip\");\n
				\theader(\"Vary: Accept-Encoding\");\n
				\theader(\"Content-Length: \".strlen(\$buffer));\n
				}\n
				header(\"Content-type: text/css;\");\n\n
				echo \$buffer;
				\n?>");
				fclose($handle);
			}
		#if not unite css then delete .htaccess from /css/ dir and css.php if is exist
		}else{
			if( file_exists( $this->root.'/css/.htaccess' ) )
			{
				unlink($this->root.'/css/.htaccess');
			}
		
			if( file_exists( $this->root.'/css/css.php' ) )
			{
				unlink($this->root.'/css/css.php');
			}
		}
		
		$unite_js = (isset( $post['unite_js'] )) ? '1' : '0';
		$unite_css = (isset( $post['unite_css'] )) ? '1' : '0';
		$gzip = (isset( $post['gzip'] )) ? '1' : '0';
		$invert_title_prefix = (isset( $post['invert_title_prefix'] )) ? '1' : '0';
		
		#save site settings
		$res = $this->db->prepare("
			UPDATE 
				`settings`
			SET 
				`closed`=:closed,
				`gzip`=:gzip,
				`gzip_level`=:gzip_level,
				`timezone`=:timezone, 
				`super_meta`=:super_meta,
				`unite_js`=:unite_js,
				`unite_css`=:unite_css,
				`global_meta_title_prefix`=:global_meta_title_prefix,
				`invert_title_prefix`=:invert_title_prefix
			");
			
		$res->bindValue(':closed', $closed);
		$res->bindValue(':gzip', $gzip);
		$res->bindValue(':gzip_level', $gzip_level);
		$res->bindValue(':timezone', $post['timezone']);
		$res->bindValue(':super_meta', $post['super_meta']);
		$res->bindValue(':unite_js', $unite_js);
		$res->bindValue(':unite_css', $unite_css);
		$res->bindValue(':global_meta_title_prefix', $post['global_meta_title_prefix']);
		$res->bindValue(':invert_title_prefix', $invert_title_prefix);
		$res->execute();
		
		$this->log('Настройки сайта', 'Изменение настроек сайта');
		
	}
	
	public function ip()
	{
		$ip = '';
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) and filter_var(strtok(@$_SERVER['HTTP_X_FORWARDED_FOR'],','),FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=strtok($_SERVER['HTTP_X_FORWARDED_FOR'],','); 
		} 
		
		elseif(isset($_SERVER['GEOIP_ADDR']) and filter_var(@$_SERVER['GEOIP_ADDR'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['GEOIP_ADDR']; 
		}
		
		elseif(isset($_SERVER['HTTP_X_REAL_IP']) and filter_var(@$_SERVER['HTTP_X_REAL_IP'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['HTTP_X_REAL_IP']; 
		} 
		
		elseif(isset($_SERVER['HTTP_CLIENT_IP']) and filter_var(@$_SERVER['HTTP_CLIENT_IP'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		
		elseif(isset($_SERVER['REMOTE_ADDR']) and filter_var(@$_SERVER['REMOTE_ADDR'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['REMOTE_ADDR']; 
		}else{ 
		$ip='0.0.0.0'; 
		}
		
		return $ip;
	}

	
}
?>