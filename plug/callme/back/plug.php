<?
class PlugCallme extends Plug{

	public $plugName = 'callme';
	public $plugNameRu = 'Заказать звонок';
	
	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		#paste your code here
		
		ob_start();
		$this->ajax(array('action'=>'history'));
		$this->template->assign('welcomescreen', ob_get_clean());
	}
	
	public function render()
	{
		#paste your code here for view plugin
		
		$this->template->display('default.tpl');

	}
	
	public function install()
	{
		#table plug_callme_history
		$res = $this->db->query("SELECT count(*) FROM `plug_callme_history`");
		if(!$res)
		{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `plug_callme_history` (
				  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'just id',
				  `request_date` date NOT NULL COMMENT 'date',
				  `request_time` time NOT NULL COMMENT 'time',
				  `request_name` varchar(255) NOT NULL COMMENT 'user name',
				  `request_phone` varchar(255) NOT NULL COMMENT 'user phone',
				  `request_comment` text NOT NULL COMMENT 'comment',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
			");
		}	

		#table plug_callme_recipient_email
		$res = $this->db->query("SELECT count(*) FROM `plug_callme_recipient_email`");
		if(!$res)
		{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `plug_callme_recipient_email` (
				  `email` varchar(255) NOT NULL COMMENT 'recipient email address',
				  `name` varchar(255) NOT NULL COMMENT 'recipient persone name',
				  UNIQUE KEY `email` (`email`)
				) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
			");
		}	

		#table plug_callme_recipient_sms
		$res = $this->db->query("SELECT count(*) FROM `plug_callme_recipient_sms`");
		if(!$res)
		{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `plug_callme_recipient_sms` (
				  `phone` varchar(255) NOT NULL COMMENT 'recipient phone number',
				  `name` varchar(255) NOT NULL COMMENT 'recipient persone name',
				  UNIQUE KEY `phone` (`phone`)
				) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
			");
		}	

		#table plug_callme_recipient_sms
		$res = $this->db->query("SELECT count(*) FROM `plug_callme_sett`");
		if(!$res)
		{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `plug_callme_sett` (
				  `api_id` varchar(255) NOT NULL COMMENT 'sms service API ID',
				  `email_template` text NOT NULL COMMENT 'email message template text',
				  `email_from_address` varchar(255) NOT NULL COMMENT 'email sender address',
				  `email_from_name` varchar(255) NOT NULL COMMENT 'email sender name',
				  `email_subject` varchar(255) NOT NULL COMMENT 'mail subject',
				  `email_reply` varchar(255) NOT NULL COMMENT 'email address for reply',
				  `sms_template` text NOT NULL COMMENT 'sms message template text',
				  `captcha_is_on` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'captcha is on',
				  `captcha_type` enum('str','num','math','rnd') NOT NULL DEFAULT 'str' COMMENT 'type of captcha',
				  UNIQUE KEY `api_id` (`api_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
			");

			$this->db->query("INSERT INTO `plug_callme_sett` (`api_id`) VALUES ('');");
		}	

		#add new words
		$res = $this->db->query("SELECT `id` FROM `words` WHERE `word_key`='plug_callme_message_ok'");
		$resArray = $res->fetch();
		if(empty($resArray))
		{
			$this->db->query("
				INSERT INTO `words` SET `word_key`='plug_callme_message_ok', `word_value`='Спасибо. Ваша заявка принята.', `word_desc`='Заказать звонок. Сообщение успешно принятой заявки.'
			");
		}


	}
	
	#decl
	public function declOfNum($number, $titles)
	{
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
	}

	public function ajax( $postArray = array() )
	{
		#paste your code here for ajax response
		switch( $postArray['action'] )
		{
			case 'history':
				#get history
				$res = $this->db->query("SELECT * FROM `plug_callme_history` ORDER BY `request_date`DESC,`request_time`DESC");
				$resArray = $res->fetchAll();

				if( !empty($resArray) ) 
				{
					foreach( $resArray as &$values )
					{
						$date = new DateTime( $values['request_date'] );
						$values['request_date'] = $date->format("d.m.Y");
						
					}
					$this->template->assign( 'history', $resArray );
				}

				$this->template->display('history.tpl');
			break;
			
			case 'clearHistory':
				$this->db->query("DELETE FROM `plug_callme_history`");
			break;

			case 'email_sett_tpl':
				#get settings
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( !empty($resArray) ) $this->template->assign( 'emailSettings', $resArray );
				$this->template->display('email_sett_tpl.tpl');
			break;
			
			case 'sms_sett_tpl':
				#get settings
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( !empty($resArray) ) $this->template->assign( 'smsSettings', $resArray );
				$this->template->display('sms_sett_tpl.tpl');
			break;

			case 'recipient_sms_tpl':
				#get recipients
				$res = $this->db->query("SELECT * FROM `plug_callme_recipient_sms`");
				$resArray = $res->fetchAll();
				if( !empty($resArray) ) $this->template->assign('recipients', $resArray);
				$this->template->display('recipient_sms_tpl.tpl');
			break;
			
			case 'recipient_email_tpl':
				#get recipients
				$res = $this->db->query("SELECT * FROM `plug_callme_recipient_email`");
				$resArray = $res->fetchAll();
				if( !empty($resArray) ) $this->template->assign('recipients', $resArray);
				$this->template->display('recipient_email_tpl.tpl');
			break;
			
			case 'smsTemplateSave':
    			$res = $this->db->prepare("
    				UPDATE 
    					`plug_callme_sett` 
    				SET 
    					`sms_template`=:sms_template
    			");
    			$res->bindValue(':sms_template', $postArray['text'], PDO::PARAM_STR);
    			$res->execute();
			break;

			case 'emailTemplateSave':
				$validate = new Validate();
				$validate->addToValidate('email_from_address', $postArray['email_from_address'], 'email');
				$validate->addToValidate('email_reply', $postArray['email_reply'], 'email');
				$validate->addToValidate('email_from_name', $postArray['email_from_name'], 'notnull');
				$validate->addToValidate('email_subject', $postArray['email_subject'], 'notnull');

				if( !$validate->validate() ) die( $validate->error );

    			$res = $this->db->prepare("
    				UPDATE 
    					`plug_callme_sett` 
    				SET 
    					`email_template`=:email_template,
    					`email_from_address`=:email_from_address,
    					`email_from_name`=:email_from_name,
    					`email_reply`=:email_reply,
    					`email_subject`=:email_subject
    			");
    			$res->bindValue(':email_template', $postArray['text'], PDO::PARAM_STR);
    			$res->bindValue(':email_from_address', $postArray['email_from_address'], PDO::PARAM_STR);
    			$res->bindValue(':email_from_name', $postArray['email_from_name'], PDO::PARAM_STR);
    			$res->bindValue(':email_reply', $postArray['email_reply'], PDO::PARAM_STR);
    			$res->bindValue(':email_subject', $postArray['email_subject'], PDO::PARAM_STR);
    			$res->execute();

    			die( $validate->error );
			break;
			
			case 'getBalance':
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( empty($resArray) ) return 'неверно указан api_id';

				$ch = curl_init("http://sms.ru/my/balance");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					"api_id"		=>	$resArray['api_id']
				));
				$result = (string)curl_exec($ch);
				curl_close($ch);
				
				switch( $result )
				{
					case '200':	die('Ошибка. Неправильный api_id'); break;
					case '211':	die('Ошибка. Метод не найден'); break;
					case '220': die('Ошибка. Сервис временно недоступен, попробуйте чуть позже'); break;
				}

				

				$result = preg_replace('/\s/', '|', $result);
			
				$result = explode('|', $result);
				if( !isset( $result[0] ) || $result[0] != '100' )
				{
					die('Неизвестная ошибка.');
				}else{
					
					$result = explode(".", $result[1]);
					$result[1] = (isset($result[1])) ? $result[1] : '0';
					die( $result[0]." ".$this->declOfNum( $result[0], array("рубль", "рубля", "рублей") ).", ".$result[1]." ".$this->declOfNum( $result[1], array("копейка", "копейки", "копеек") ) );
				}

			break;

			case 'getLimit':
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( empty($resArray) ) return 'неверно указан api_id';

				$ch = curl_init("http://sms.ru/my/limit");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					"api_id"		=>	$resArray['api_id']
				));
				$result = (string)curl_exec($ch);
				curl_close($ch);
				
				switch( $result )
				{
					case '200':	die('Ошибка. Неправильный api_id'); break;
					case '211':	die('Ошибка. Метод не найден'); break;
					case '220': die('Ошибка. Сервис временно недоступен, попробуйте чуть позже'); break;
				}

				$result = preg_replace('/\s/', '|', $result);
				$result = explode('|', $result);
				if( !isset( $result[0] ) || $result[0] != '100' )
				{
					die('Неизвестная ошибка.');
				}else{
					die( "Израсходовано: <b>".$result[2]."</b> ".$this->declOfNum( $result[2], array("номер", "номера", "номеров") )." из <b>".$result[1]."</b>" );
				}

			break;


			case 'sms_center_sett_tpl':
				#get recipients
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( !empty($resArray) ) $this->template->assign('settings', $resArray);
				$this->template->display('sms_center_sett_tpl.tpl');
			break;

			case 'smsCenterSave':
    			$res = $this->db->prepare("
    				UPDATE 
    					`plug_callme_sett` 
    				SET 
    					`api_id`=:api_id
    			");
    			$res->bindValue(':api_id', $postArray['api_id'], PDO::PARAM_STR);
    			$res->execute();
			break;

			case 'recipientSmsSave':
				unset($postArray['action']);
				#clear all data
				$this->db->query("DELETE FROM `plug_callme_recipient_sms`");

				if( !empty($postArray) )
				{
					$queryValues = array();
					foreach($postArray as $data)
					{
						$phone = preg_replace('/[^0-9]/', '', $data['phone']);
						$queryValues[] = "('".$phone."', '".$data['name']."')";
					}
					$this->db->query("INSERT INTO `plug_callme_recipient_sms` (`phone`, `name`) VALUES ".implode(",", $queryValues));
				}
			break;

			case 'recipientEmailSave':
				unset($postArray['action']);
				#clear all data
				$this->db->query("DELETE FROM `plug_callme_recipient_email`");

				if( !empty($postArray) )
				{
					$queryValues = array();
					foreach($postArray as $data)
					{
						$queryValues[] = "('".$data['email']."', '".$data['name']."')";
					}
					$this->db->query("INSERT INTO `plug_callme_recipient_email` (`email`, `name`) VALUES ".implode(",", $queryValues));
				}
			break;

			case 'security_tpl':
				#get secur
				$res = $this->db->query("SELECT `captcha_is_on`, `captcha_type` FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( !empty($resArray) ) $this->template->assign('security', $resArray);
				$this->template->display('security_tpl.tpl');
			break;

			case 'saveSecurity':
				#save settings of secur
				$postArray['captcha_is_on'] = ( isset( $postArray['captcha_is_on'] ) ) ? '1' : '0';

				if( isset( $postArray['captcha_type'] ) )
				{
					$res = $this->db->prepare("
						UPDATE 
							`plug_callme_sett` 
						SET 
							`captcha_is_on`=:captcha_is_on,
							`captcha_type`=:captcha_type
						");
					$res->bindValue(':captcha_is_on', $postArray['captcha_is_on'], PDO::PARAM_STR);
					$res->bindValue(':captcha_type', $postArray['captcha_type'], PDO::PARAM_STR);
					$res->execute();
				}else{
					$res = $this->db->prepare("
						UPDATE 
							`plug_callme_sett` 
						SET 
							`captcha_is_on`=:captcha_is_on
						");
					$res->bindValue(':captcha_is_on', $postArray['captcha_is_on'], PDO::PARAM_STR);
					$res->execute();
				}


			break;
		}
	}
}

?>