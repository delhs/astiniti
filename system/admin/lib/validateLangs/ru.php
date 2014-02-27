<?
	/* notnull - не пустое */
	$this->empty = 'Не оставляйте это поле пустым';
	
	/* int - не пустое целое число */
	$this->emptyInt = 'empty int';
	$this->incorrectInt = 'incorrect integer';
	
	/* notint - не пустая строка */
	$this->emptyNotint = 'empty Not integer';
	$this->incorrectNotint = 'incorrect Not integer';
	
	
	/* name - не пустое имя */
	$this->emptyName = 'Укажите свое имя';
	$this->shortName = 'Короткое имя';
	$this->incorrectName = 'Некорректный ввод имени';
	
	/* surname - не пустая фамилия */
	$this->emptySurame = 'Укажите свою фамилию';
	$this->incorrectSurname = 'Некорректный ввод фимилии';
	
	
	/* email - не пустой e-mail */
	$this->emptyEmail = 'Укажите e-mail';
	$this->incorrectEmail = 'Некорректный E-mail';
	
	
	/* phone - не пустой телефон */
	$this->emptyPhone = 'Укажите номер телефона';
	$this->incorrectPhone = 'Некорректный номер телефона';
	
	/* dateeu - не пустая дата YYYY-MM-DD */
	$this->emptyDateEu = 'Введите дату';
	$this->incorrectDateEu = 'Некорректный ввод даты';
	
	/* date - не пустая дата DD-MM-YYYY */
	$this->emptyDate = 'Введите дату';
	$this->incorrectDate = 'Некорректный ввод даты';
	
	/* login - не пустой логин 2-20 символов, первый только буква */
	$this->emptyLogin = 'Введите логин';
	$this->incorrectLogin = 'Логин не может состоять только из цифр и иметь спецсимволы. Длина логоина 1 - 20 латинских символов';
	
	/* num - не пустые целые числа и числа с плавающей точкой (разделитель точка) */
	$this->emptyNum = 'Введите число';
	$this->incorrectNum = 'Некорректный ввод числа';
	
	/* time - не пустое время в формате HH:MM:SS */
	$this->emptyTime = 'Введите время';
	$this->incorrectTime = 'Некорректный ввод времени';
	
	/* captcha - не пустая каптча ( только буквы и цифры латинского алфавита 4-10 символов) */
	$this->emptyCaptcha = 'Введите символы с картинки';
	$this->incorrectCaptcha = 'Неверный код';
	
	/* linknotnum - link страницы ( только латиница, не может быть только числом ) */
	$this->incorrectLinkNotNum = 'Адрес страницы может содержать только латинские буквы, цифры, симввол \"-\" и символ \"_\"';	
	$this->incorrectLinkdNotNum = 'Адрес не может состоять только из циферного значения';	
	$this->protectedLinkNotNum = 'Данное значение зарезервированно программой и не может быть использовано';
	
	/* password  - пароль (не короче 3-х символов) */	
	$this->emptyPassword = 'Задайте пароль';
	$this->quickPassword = 'Слишком короткий пароль';
	
	/* confirmpassword  - сравнивает данное поле с паролем. */	
	$this->emptyConfirmPassword = 'Повторите ввод пароля';
	$this->incorrectConfirmPassword = 'Пароли не совпадают';

	/* url - корректность url адреса*/
	$this->emptyUrl = 'Укажите URL адрес';
	$this->incorrectUrl = 'Некорректный URL адрес';
	
?>