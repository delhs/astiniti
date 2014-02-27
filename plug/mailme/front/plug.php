<?
class PlugMailme extends Plug{

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
				$res = $this->db->query("SELECT `captcha_is_on`, `captcha_type` FROM `plug_mailme_sett`");
				if(!$res)
				{
					$this->template->assign( 'not_install', 'not_install' );
					$this->template->display('default.tpl');
					die();
				}


				$dep = $this->db->query("SELECT * FROM `plug_mailme_departaments`");
				if(!$dep)
				{
					$this->template->assign( 'not_install', 'not_install' );
					$this->template->display('default.tpl');
					die();	
				}

				$depArray = $dep->fetchAll();
				if( empty($depArray) ) 
				{
					$this->template->assign( 'not_install', 'not_install' );
					$this->template->display('default.tpl');
					die();
				}
				
				$this->template->assign('departaments', $depArray);


				$resArray = $res->fetch();
				if( !empty($resArray) ) 
				{
					$this->template->assign( 'security', $resArray );
					$this->template->assign( 'uniqid', uniqid() );
				}
				$this->template->display('default.tpl');
			break;

			case 'mailme':
				$validate = new Validate();

				#get security settings
				$res = $this->db->query("SELECT `captcha_is_on`, `captcha_type` FROM `plug_mailme_sett`");
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
					$validate->captcha = $_SESSION["captcha"]["mailme"];
					$validate->addToValidate('captcha', $postArray['captcha'], 'captcha');
				}

				#trim data
				$postArray['email'] = mb_substr($postArray['email'], 0, 256);
				$postArray['name'] = mb_substr($postArray['name'], 0, 49);
				$postArray['text'] = mb_substr($postArray['text'], 0, 1800);


				#validate data
				$validate->addToValidate('name', $postArray['name'], 'name');
				$validate->addToValidate('email', $postArray['email'], 'email');
				$validate->addToValidate('text', $postArray['text'], 'notnull');

				$validate->empty = 'Напишите письмо';

				#break if data is not valid
				if( !$validate->validate() ) die( $validate->error );

				#get message text
				$res  = $this->db->query("SELECT `word_value` FROM `words` WHERE `word_key`='plug_mailme_message_ok' LIMIT 1 ");
				$resArray = $res->fetch();
				if( !empty($resArray) )
				{
					$array = json_decode($validate->error, true);
					$array['message'] = $resArray['word_value'];
					$validate->error = json_encode( $array );
				}

				#load recipients email
				$res = $this->db->prepare("
					SELECT
						`e`.`email`,
						`e`.`name`
					FROM 
						`plug_mailme_recipient_email` AS `e`
					WHERE
						`e`.`departament_id`=:departament
				");
				$res->bindValue(':departament', (int)$postArray['departament'], PDO::PARAM_INT);
				$res->execute();
				$recipientsEmail = $res->fetchAll();

				#load settings
				$res = $this->db->query("SELECT * FROM `plug_mailme_sett`");
				$recipientsSettings = $res->fetch();
				
				#replace pseudocodes from templates
				$recipientsSettings['email_template'] = str_replace('%name%', $postArray['name'], $recipientsSettings['email_template']);
				$recipientsSettings['email_template'] = str_replace('%email%', $postArray['email'], $recipientsSettings['email_template']);
				$recipientsSettings['email_template'] = str_replace('%text%', $postArray['text'], $recipientsSettings['email_template']);
				
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

				#get departament name
				$res = $this->db->prepare("SELECT `name` FROM `plug_mailme_departaments` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $postArray['departament'], PDO::PARAM_INT);
				$res->execute();
				$depArray = $res->fetch();

				if( !empty($depArray) ) 
				{	
					#write history
					$hres = $this->db->prepare("
						INSERT INTO 
							`plug_mailme_history`
						SET 
							`request_departament`=:request_departament, 
							`request_date`=:request_date, 
							`request_time`=:request_time,
							`request_name`=:request_name,
							`request_email`=:request_email,
							`request_text`=:request_text
						");
					$hres->bindValue(":request_departament", $depArray['name'], PDO::PARAM_STR);
					$hres->bindValue(":request_date", date("Y-m-d"), PDO::PARAM_STR);
					$hres->bindValue(":request_time", date("H:i:s"), PDO::PARAM_STR);
					$hres->bindValue(":request_name", $postArray['name'], PDO::PARAM_STR);
					$hres->bindValue(":request_email", $postArray['email'], PDO::PARAM_STR);
					$hres->bindValue(":request_text", $postArray['text'], PDO::PARAM_STR);
					$hres->execute();
				}

				#send response
				die($validate->error);


			break;
		}
	}
}

?>