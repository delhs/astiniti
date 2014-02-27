<?
class PlugCallme extends Plug{

	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{

	}
	
	public function render()
	{
		#paste your code here for view plugin
		$this->template->display('default.tpl');
	}
	
	
	public function ajax( $postArray = array() )
	{
		#paste your code here for ajax response
		switch( $postArray['action'] )
		{
			case 'getForm':
				$this->core->loadWords();

				#get secur settings
				$res = $this->db->query("SELECT `captcha_is_on`, `captcha_type` FROM `plug_callme_sett`");
				if(!$res)
				{
					$this->template->assign( 'not_install', 'not_install' );
					$this->template->display('default.tpl');
					die();
				}
				$resArray = $res->fetch();
				if( !empty($resArray) ) 
				{
					$this->template->assign( 'security', $resArray );
					$this->template->assign( 'uniqid', uniqid() );
				}
				$this->template->display('default.tpl');
			break;

			case 'callme':
				$validate = new Validate();

				#get security settings
				$res = $this->db->query("SELECT `captcha_is_on`, `captcha_type` FROM `plug_callme_sett`");
				$resArray = $res->fetch();
				if( !empty($resArray) && $resArray['captcha_is_on']=='1')
				{
					#set captcha error messages
					switch( $resArray['captcha_type'] )
					{
						case 'str': $validate->emptyCaptcha = 'Введите символы с картинки'; break;
						case 'num': $validate->emptyCaptcha = 'Введите число с картинки'; break;
						case 'math': $validate->emptyCaptcha = 'Введите результат действия скартинки'; break;
						case 'rnd': $validate->emptyCaptcha = 'Введите защитный код'; break;
					}

					#check the captcha
					$validate->captcha = (isset($_SESSION["captcha"]["callme"]) ? $_SESSION["captcha"]["callme"] : uniqid());
					$validate->addToValidate('captcha', $postArray['captcha'], 'captcha');
				}

				if( !isset($postArray['comment']) ) $postArray['comment'] = "";

				#trim data
				$postArray['phone'] = mb_substr($postArray['phone'], 0, 17);
				$postArray['name'] = mb_substr($postArray['name'], 0, 49);
				$postArray['comment'] = mb_substr($postArray['comment'], 0, 99);

				$postArray['phone'] = preg_replace('/[^0-9]/', '', $postArray['phone']);

				#validate data
				$validate->addToValidate('name', $postArray['name'], 'name');
				$validate->addToValidate('phone', $postArray['phone'], 'phone');


				#break if data is not valid
				if( !$validate->validate() ) die( $validate->error );

				#get message text
				$res  = $this->db->query("SELECT `word_value` FROM `words` WHERE `word_key`='plug_callme_message_ok' LIMIT 1 ");
				$resArray = $res->fetch();
				if( !empty($resArray) )
				{
					$array = json_decode($validate->error, true);
					$array['message'] = $resArray['word_value'];
					$validate->error = json_encode( $array );
				}

				#load recipients email
				$res = $this->db->query("
					SELECT
						`e`.`email`,
						`e`.`name`
					FROM 
						`plug_callme_recipient_email` AS `e`
				");
				$recipientsEmail = $res->fetchAll();

				#load recipients sms
				$res = $this->db->query("
					SELECT
						`s`.`phone`,
						`s`.`name`
					FROM 
						`plug_callme_recipient_sms` AS `s`
				");
				$recipientsSms = $res->fetchAll();

				#load settings
				$res = $this->db->query("SELECT * FROM `plug_callme_sett`");
				$recipientsSettings = $res->fetch();
				
				#format phone number
				$postArray['phone'] = sprintf("+%s(%s%s%s)%s%s%s-%s%s-%s%s",$postArray['phone'][0], $postArray['phone'][1], $postArray['phone'][2], $postArray['phone'][3], $postArray['phone'][4], $postArray['phone'][5], $postArray['phone'][6], $postArray['phone'][7], $postArray['phone'][8], $postArray['phone'][9], $postArray['phone'][10]);

				#replace pseudocodes from templates
				$recipientsSettings['email_template'] = str_replace('%name%', $postArray['name'], $recipientsSettings['email_template']);
				$recipientsSettings['email_template'] = str_replace('%phone%', $postArray['phone'], $recipientsSettings['email_template']);
				$recipientsSettings['email_template'] = str_replace('%comment%', $postArray['comment'], $recipientsSettings['email_template']);
				$recipientsSettings['sms_template'] = str_replace('%name%', $postArray['name'], $recipientsSettings['sms_template']);
				$recipientsSettings['sms_template'] = str_replace('%phone%', $postArray['phone'], $recipientsSettings['sms_template']);
				$recipientsSettings['sms_template'] = str_replace('%comment%', $postArray['comment'], $recipientsSettings['sms_template']);
				
				#send mail
				
				if( !empty($recipientsEmail) )
				{
					$mailer = new PHPMailer();

					foreach( $recipientsEmail as $recipient )
					{
						$mailer->CharSet = 'utf-8';
						$mailer->Subject  = $recipientsSettings['email_subject'];
						$mailer->FromName = $recipientsSettings['email_from_name'];
						$mailer->From = $recipientsSettings['email_from_address'];
						$mailer->AddReplyTo( $recipientsSettings['email_reply'] , $recipientsSettings['email_from_name']);
						$mailer->AddAddress( $recipient['email'], $recipient['name']);
						$mailer->Body = $recipientsSettings['email_template'];
						$mailer->IsHTML(true);
						$mailer->Send();
						$mailer->ClearAddresses();
						$mailer->ClearAttachments();
						$mailer->IsHTML(false);
					}
					#free mem
					unset( $mailer );
				}

				#send SMS
				if( !empty($recipientsEmail) )
				{	
					foreach( $recipientsSms as $recipient )
					{
						$ch = curl_init("http://sms.ru/sms/send");
						@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						@curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						@curl_setopt($ch, CURLOPT_POSTFIELDS, array(
							"api_id"		=>	$recipientsSettings['api_id'],
							"to"			=>	$recipient['phone'],
							"text"			=>	$recipientsSettings['sms_template']
						));
						$body = curl_exec($ch);
						curl_close($ch);
					}
				}

				#write history
				$res = $this->db->prepare("
					INSERT INTO 
						`plug_callme_history`
					SET 
						`request_date`=:request_date, 
						`request_time`=:request_time,
						`request_name`=:request_name,
						`request_phone`=:request_phone,
						`request_comment`=:request_comment
					");
				$res->bindValue(":request_date", date("Y-m-d"), PDO::PARAM_STR);
				$res->bindValue(":request_time", date("H:i:s"), PDO::PARAM_STR);
				$res->bindValue(":request_name", $postArray['name'], PDO::PARAM_STR);
				$res->bindValue(":request_phone", $postArray['phone'], PDO::PARAM_STR);
				$res->bindValue(":request_comment", $postArray['comment'], PDO::PARAM_STR);
				$res->execute();

				#send response
				die($validate->error);


			break;
		}
	}
}

?>