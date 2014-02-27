<?
#installer
class installer
{

	public function __construct()
	{
		
		
	}

	public function install()
	{

		global $db;
		
		
		#install table "parts"
		$res = $db->GetRow("SELECT `id` FROM `parts` WHERE `id`=%s LIMIT 1 ", array( '1' ) );
		if ($res == NULL || count($res) == 0)
		{
		
			$db->Run("
						CREATE TABLE IF NOT EXISTS `parts` (
						`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'page id',
						`pid` int(11) NOT NULL COMMENT 'parent page id',
						`name` varchar(255) NOT NULL  COMMENT 'page name',
						`link` varchar(255) NOT NULL  COMMENT 'page link',
						`off` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'disabled page',
						`can_del` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'can to delete a page',
						`range` int(11) NOT NULL DEFAULT '1' COMMENT 'page range',
						`quick_desc` varchar(255) NOT NULL COMMENT 'quick description',
						`title` varchar(255) NOT NULL COMMENT 'page tag h1',
						`content` text NOT NULL COMMENT 'page content',
						`url` varchar(255) NOT NULL COMMENT 'page url',
						`meta_description` varchar(255) NOT NULL COMMENT 'page meta tag description',
						`meta_keywords` varchar(255) NOT NULL COMMENT 'page meta tag keywords',
						`meta_title` varchar(255) NOT NULL COMMENT 'page meta tag title',
						`meta_lang` varchar(10) NOT NULL DEFAULT 'ru' COMMENT 'page meta tag content-language',
						`extra_meta` text NOT NULL COMMENT 'page other meta data',
						`template` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'page template file',
						`target` enum('_self','_blank') NOT NULL DEFAULT '_self' COMMENT 'target of page open',
						`skype_block` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'block integration skype',
						`edit_date` varchar(255) NOT NULL DEFAULT 'Sat, 16 Jan 2010 21:16:42  GMT' COMMENT 'last edit date of page',
						`create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'create date of page',
						`in_menu` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'view page in main menu',
						PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
					");

					
			$db->Run("
						INSERT INTO 
							`parts`
						SET
							`id`='1',
							`pid`='0',
							`name`='Главная',
							`link`='',
							`off`='0',
							`can_del`='0',
							`range`='0',
							`url`='/',
							`edit_date`='".date('D, d M Y H:i:s ').'GMT'."',
							`create_date`='".date('Y-m-d H:i:s')."'
					");
		}
		
		
		
		
		
		
		#install table settings
		$res = $db->GetRow("SELECT `id` FROM `settings` WHERE `id`=%s LIMIT 1 ", array( '1' ) );
		if ($res == NULL || count($res) == 0)
		{
		
			$db->Run("
						CREATE TABLE IF NOT EXISTS `settings` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`closed` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'site closed',
						`super_meta` text NOT NULL COMMENT 'meta data that are loaded on all pages',
						PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
					");
					
			$db->Run("	INSERT INTO `settings` SET `id` = '1' ");
		
		}
		
		
		#install table modules
		$res = $db->GetRow("SELECT `id` FROM `modules` WHERE `id`=%s LIMIT 1 ", array( '1' ) );
		if ($res == NULL || count($res) == 0)
		{		
		
			$db->Run("
						CREATE TABLE IF NOT EXISTS `modules` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`page_id` int(11) NOT NULL COMMENT 'id page module',
						`mod_name` varchar(255) NOT NULL COMMENT 'module original name',
						`region` varchar(255) NOT NULL COMMENT 'module region',
						`range` INT NOT NULL DEFAULT  '1' COMMENT 'range of module',
						PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
					");
		
		}

		
		/* установка таблицы пользователей админки */
/* 		
		$res = $db->GetRow("SELECT `id` FROM `adm_users` WHERE `id`=%s LIMIT 1 ", array( '1' ) );
		if ($res == NULL || count($res) == 0)
		{		
		
			$db->Run("
						CREATE TABLE IF NOT EXISTS `adm_users` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`uniqid` varchar(255) NOT NULL,
						`uid` varchar(255) NOT NULL,
						`name` varchar(255) NOT NULL,
						`email` varchar(255) NOT NULL,
						`login` varchar(255) NOT NULL,
						`password` varchar(255) NOT NULL,
						`avow` int(11) NOT NULL DEFAULT '0',
						PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
					");
		
		}
		
		 */
		/* установка таблицы онлайн пользователей админки */
	/* 	
		$res = $db->GetRow("SELECT `id` FROM `adm_users_online` WHERE `id`=%s LIMIT 1 ", array( '1' ) );
		if ($res == NULL || count($res) == 0)
		{		
		
			$db->Run("
						CREATE TABLE IF NOT EXISTS `adm_users_online` (
						`session_id` TINYTEXT NOT NULL ,
						`putdate` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
						`uid` TINYTEXT NOT NULL
						) ENGINE = MYISAM ;
					");
		
		}
		 */
		
		
		
	}
	
	
	

}

?>