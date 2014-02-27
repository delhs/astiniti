<?
/* 
#############################################################################################################################
#																															#
#	сопутствующие файлы: 	jquery.validate.js																				#
#						 	validate.css совместно с PIE папкой																#
#	использование :																											#
#																															#
#	НА КЛИЕНТЕ:																												#
#	1.	$.post('/test_valid.php', {name: $('input').val() } , function(data){												#
#			$.validate({res:data}); 																						#
#		}); 																												#
#			отправляем форму методом POST в скрипт, который обрабатывает полученные данные,									#
#		 	ставим callback на $.validate({res:data}), где data - это результат с сервера									#
#																															#
#	НА СЕРВЕРЕ:																												#
#																															#
#	1.	$valid = new validate();     - подключаем класс																		#
#																															#
#	2.	$valid->addToValidate( 'name', $_POST['name'], 'notnull' );  вызываем метод "addToValidate" для каждого значения	#
#		и передаем имя поля формы, значение поля, правило проверки															#
# 	3.	$valid->validate()   - вызываем метод validate(), который вернет true или false ( результат проверки )				#
#	4.	die( $valid->error ); отправляем на клиент свойство error для вывода ошибок( даже если их нет).						#
#																															#
#############################################################################################################################


 */


class Validate
{

	public $register = false;			//чувствительность к регистру

	/* #	Список правил с текстами сообщений об ошибках */

	/* notnull - не пустое */
	public	$empty = 'Не оставляйте это поле пустым';
	
	/* int - не пустое целое число */
	public	$emptyInt = 'empty int';
	public	$incorrectInt = 'incorrect integer';
	
	/* notint - не пустая строка */
	public	$emptyNotint = 'empty Not integer';
	public	$incorrectNotint = 'incorrect Not integer';
	
	
	/* name - не пустое имя */
	public	$emptyName = 'Укажите свое имя';
	public	$shortName = 'Короткое имя';
	public	$incorrectName = 'Некорректный ввод имени';
	
	/* surname - не пустая фамилия */
	public	$emptySurame = 'Укажите свою фамилию';
	public	$incorrectSurname = 'Некорректный ввод фимилии';
	
	
	/* email - не пустой e-mail */
	public	$emptyEmail = 'Укажите e-mail';
	public	$incorrectEmail = 'Некорректный E-mail';
	
	
	/* phone - не пустой телефон */
	public	$emptyPhone = 'Укажите номер телефона';
	public	$incorrectPhone = 'Некорректный номер телефона';
	
	/* dateeu - не пустая дата YYYY-MM-DD */
	public	$emptyDateEu = 'Введите дату';
	public	$incorrectDateEu = 'Некорректный ввод даты';
	
	/* date - не пустая дата DD-MM-YYYY */
	public	$emptyDate = 'Введите дату';
	public	$incorrectDate = 'Некорректный ввод даты';
	
	/* login - не пустой логин 2-20 символов, первый только буква */
	public	$emptyLogin = 'Введите логин';
	public	$shortLogin = 'Длина логина может быть только от 1 до 20 символов';
	
	/* num - не пустые целые числа и числа с плавающей точкой (разделитель точка) */
	public	$emptyNum = 'Введите число';
	public	$incorrectNum = 'Некорректный ввод числа';
	
	/* time - не пустое время в формате HH:MM:SS */
	public	$emptyTime = 'Введите время';
	public	$incorrectTime = 'Некорректный ввод времени';
	
	/* captcha - не пустая каптча ( только буквы и цифры латинского алфавита 4-10 символов) */
	public	$emptyCaptcha = 'Введите символы с картинки';
	public	$incorrectCaptcha = 'Неверный код';
	
	/* linknotnum - link страницы ( только латиница, не может быть только числом ) */
	public	$emptyLinknotnum = 'Укажите адрес страницы';	
	public	$incorrectLinkNotNum = 'Адрес страницы может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';	
	public	$incorrectLinkdNotNum = 'Адрес не может состоять только из циферного значения';	
	public $protectedLinkNotNum = 'Данное значение зарезервированно программой и не может быть использовано';
			/* защищенные имена разделов */
			public $protectedLinksNotNum = array();
	
	/* link - link страницы ( только латиница ) */
	public	$emptyLink = 'Укажите адрес страницы';	
	public	$incorrectLink = 'Адрес страницы может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';	
	public $protectedLink = 'Данное значение зарезервированно программой и не может быть использовано';
			/* защищенные имена разделов */
			public $protectedLinks = array();
	
	/* password  - пароль (не короче 3-х символов) */	
	public $emptyPassword = 'Задайте пароль';
	public $quickPassword = 'Слишком короткий пароль';
	
	/* confirmpassword  - сравнивает данное поле с паролем. */	
	public $emptyConfirmPassword = 'Повторите ввод пароля';
	public $incorrectConfirmPassword = 'Пароли не совпадают';
	
	/* url - корректность url адреса*/
	public $emptyUrl = 'Укажите URL адрес';
	public $incorrectUrl = 'Некорректный URL адрес';

	public $captcha = '';

	public $ToValidate = array( );		//массив с данными для проверки
		
	public $error = '';				//содержит в себе результаты проверки в json формате
	
	public $valid = true;				//флаг валидности
	
	public $json = '';					//json строка, которая попадаетв error
	

	public function __construct( )
	{
		global $config;
		
		$this->protectedLinksNotNum = $config->protectedLinks;
		$this->protectedLinks = $config->protectedLinks;
		
		if( $config->multylang && file_exists( $_SERVER['DOCUMENT_ROOT'].'/lib/validateLangs/'.$config->urlPref.'.php' ) )
		include $_SERVER['DOCUMENT_ROOT'].'/lib/validateLangs/'.$config->urlPref.'.php';	
	}
	
	
	public function addToValidate(  $inputName,  $inputValue ,$rullName )
	{

		$this->ToValidate[  $inputName ] = array( 'rullName' => $rullName, 'inputValue' => $inputValue );
	}
	
	
	public function validate( )
	{
		$this->error = '';
		$this->json = '';
		
		foreach( $this->ToValidate as $k => $v )
		{
			$rullName = $v['rullName'];
			
			$inputValue = ( $this->register  ) ? $inputValue =   $v['inputValue']  : $inputValue = mb_strtolower(  $v['inputValue']  );
			
			$inputValue = htmlspecialchars( trim( $inputValue ) );

				switch($rullName)
				{
								
					case 'int' : 
											{	
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyInt.'",'; $this->valid = false;  }
												elseif( !preg_match( '/^[0-9]+$/', $inputValue)  ) { $this->json.='"'.$k.'":"'.$this->incorrectInt.'",'; $this->valid = false;  }	
												break;
											}
										
					case 'notnull' : 
											{
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->empty.'",'; $this->valid = false;  }				
												break;
											}
					
					
					case 'name' :
											{ 
												$inputValue = preg_replace('/[ф]/', '', $inputValue);
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyName.'",'; $this->valid = false;  } 
												elseif(  mb_strlen( $inputValue,'UTF-8' )< 2 ) { $this->json.='"'.$k.'":"'.$this->shortName.'",'; $this->valid = false;  }
												elseif( !preg_match( '/^[^\@\#\$\%\^\&\*\(\)\.\,\'\"\№\;\+\-]+$/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectName.'",'; $this->valid = false;  }  
												break;
											} 
					
					case 'surname' :
											{ 
												$inputValue = preg_replace('/[ф]/', '', $inputValue);
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptySurame.'",'; $this->valid = false;  } 
												elseif( !preg_match( '/^[^\@\#\$\%\^\&\*\(\)\.\,\'\"\№\;\+\-]+$/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectSurname.'",'; $this->valid = false;  }  
												break;
											} 

											
					case 'email' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyEmail.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', mb_strtolower( $inputValue, 'UTF-8') ) ) { $this->json.='"'.$k.'":"'.$this->incorrectEmail.'",'; $this->valid = false;  }  
												break;
											}	
				
					case 'phone' :
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyPhone.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/^[0-9\s-_\(\)]+$/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectPhone.'",'; $this->valid = false;  }  
												break;
											} 	
				
				
					case 'dateEU' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyDateEu.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectDateEu.'",'; $this->valid = false;  }  
												break;
											}
				
				
					case 'date' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyDate.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/(0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectDate.'",'; $this->valid = false;  }  
												break;
											}
										
					case 'login' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyLogin.'",'; $this->valid = false;  } 
											
												elseif( mb_strlen( $inputValue,'UTF-8' )< 2 ) { $this->json.='"'.$k.'":"'.$this->shortLogin.'",'; $this->valid = false;  }  
												break;
											}			
											
					case 'num' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyNum.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/\-?\d+(\.\d{0,})?/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectNum.'",'; $this->valid = false;  }  
												break;
											}
											
					case 'time' : 
											{ 
											
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyTime.'",'; $this->valid = false;  } 
											
												elseif( !preg_match( '/^([0-1]\d|2[0-3])(:[0-5]\d){2}$/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectTime.'",'; $this->valid = false;  }  
												break;
											}	
											
					case 'captcha' : 
											{ 
												if(  $inputValue == ''  )  { $this->json.='"'.$k.'":"'.$this->emptyCaptcha.'",'; $this->valid = false;  } 
												elseif( $inputValue!==$this->captcha ) { $this->json.='"'.$k.'":"'.$this->incorrectCaptcha.'",'; $this->valid = false;  }
												break;
											}							
			
					
					case 'linknotnum' : 
											{ 
											
												if( $inputValue == ''){ $this->json.='"'.$k.'":"'.$this->emptyLinknotnum.'",'; $this->valid = false;  }  
												elseif( preg_match( '/^[0-9]+$/', $inputValue ) ) { $this->json.='"'.$k.'":"'.$this->incorrectLinkdNotNum.'",'; $this->valid = false;  }  
												elseif( !preg_match( '/^([a-zA-Z-_0-9]){0,}([0-9]{0,}+)([a-zA-Z-_]){1,}+([0-9]{0,}+)$/', $inputValue )  )  { $this->json.='"'.$k.'":"'.$this->incorrectLinkNotNum.'",'; $this->valid = false;  }  
												elseif( array_search(   $inputValue, $this->protectedLinksNotNum )!==false  ) { $this->json.='"'.$k.'":"'.$this->protectedLinkNotNum.'",'; $this->valid = false;  } 
												break;
											}					
											
					case 'link' : 
											{ 
											
												if( $inputValue == ''){ $this->json.='"'.$k.'":"'.$this->emptyLink.'",'; $this->valid = false;  }  
												elseif( !preg_match( '/^[0-9a-zA-Z-_]+$/', $inputValue )  )  { $this->json.='"'.$k.'":"'.$this->incorrectLink.'",'; $this->valid = false;  }  
												elseif( array_search(   $inputValue, $this->protectedLinks )!==false  ) { $this->json.='"'.$k.'":"'.$this->protectedLink.'",'; $this->valid = false;  } 
												break;
											}						
					
			
					case 'password' : 
											{ 
												if(  $inputValue == ''  ) { $this->json.='"'.$k.'":"'.$this->emptyPassword.'",'; $this->valid = false;   }
												elseif( !preg_match( '/^(.){3,}$/', $inputValue )  )  { $this->json.='"'.$k.'":"'.$this->quickPassword.'",'; $this->valid = false;  }  
												break;
											}
											
											
					case 'confirmpassword' : 
											{ 	
										
												foreach( $this->ToValidate as $k => $v )
												{
													if( $v['rullName'] == 'password' ) 
													
														$passwordValue = $v['inputValue'];
													
												}
												
												if( !isset( $passwordValue ) ) break;
												
												if(  $inputValue == ''  ) { $this->json.='"'.$k.'":"'.$this->emptyConfirmPassword.'",'; $this->valid = false;   }
												elseif( $inputValue != $passwordValue  )  { $this->json.='"'.$k.'":"'.$this->incorrectConfirmPassword.'",'; $this->valid = false;  }  
												break;
											}
											
				case 'url' :				{
												if(  $inputValue == ''  ) { $this->json.='"'.$k.'":"'.$this->emptyUrl.'",'; $this->valid = false;   }
												elseif(!preg_match('/(([a-z0-9\-\.]+)?[a-z0-9\-]+(!?\.[a-z]{2,4}))/', $inputValue)){ $this->json.='"'.$k.'":"'.$this->incorrectUrl.'",'; $this->valid = false;  }
											}				
				}
			
		}
		

		if( !empty( $this->json ) ) 
		{
			$this->json = ",".$this->json;
	
			$this->json = preg_replace( '/,$/','',$this->json );
		}
			
			
		
		if( !$this->valid )
		{
			$this->error = '{"validate":"error" '.$this->json.'}';
			
			return false;
			
		}	else	{
						$this->error = '{"validate":"not errors" }';
						
						return true;
					}
	

		
	}
	
	
}


?>