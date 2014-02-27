<?php
@session_start();

/*
	--help--

	valid request -  /captcha/captcha.php
	valid request -  /captcha/captcha.php?to=ANY_STRING
	valid request -  /captcha/captcha.php?type=NEEDED_TYPE(str OR num OR math OR rnd)	
	valid request -  /captcha/captcha.php?to=ANY_STRING&type=NEEDED_TYPE(str OR num OR math OR rnd)

	if isset $_GET['to']  then tour value is  $_SESSION['captcha'][ $_GET['to']  ] 
	if not isset $_GET['to']  then tour value is  $_SESSION['captcha'] 

	$captchaType = 0 = 'str'
	$captchaType = 1 = 'num'
	$captchaType = 2 = 'math'
	$captchaType = RAND(0, 2) = 'rnd'
*/

#set vars
$captchaCode = '';
$captchaResult = '';
$captchaType = 0;
$captchaTo = '';

#get captcha TYPE
if( isset( $_GET['type'] ) && trim($_GET['type'])!='')
{
	switch( $_GET['type']  )
	{
		case 'str': $captchaType = 0; break;
		case 'num': $captchaType = 1; break;
		case 'math': $captchaType = 2; break;
		case 'rnd': $captchaType = rand(0, 2); break;
		default: $captchaType = 0; break;
	}
}

#get captcha TO
if( isset( $_GET['to'] ) && trim($_GET['to'])!='') $captchaTo = $_GET['to'];


#generate captcha code and set captcha result
switch( $captchaType )
{
	#if captcha type is string
	case 0:
		for ($i = 0; $i < 5; $i++) 
		{
			$captchaCode .= chr(rand(97, 122));
			$captchaResult = $captchaCode;
		}
	break;

	#if captcha type is numeric
	case 1:
		for ($i = 0; $i < 5; $i++) 
		{
			$captchaCode .= rand(0, 9);
			$captchaResult = $captchaCode;
		}
	break;

	#if captcha type is mathematics
	case 2:
		$firstNum = rand(0, 9);
		$secondNum = rand(0, 9);
		$symbol = rand(0, 1);
		switch( $symbol )
		{
			case 0: 
				$captchaCode = $firstNum.'+'.$secondNum.'=';
				$captchaResult = $firstNum + $secondNum; 
			break;

			case 1: 
				if( $firstNum > $secondNum )
				{
					$captchaCode = $firstNum.'-'.$secondNum.'=';
					$captchaResult = $firstNum - $secondNum; 
				}else{
					$captchaCode = $secondNum.'-'.$firstNum.'=';
					$captchaResult = $secondNum - $firstNum; 
				}
			break;
		}
	break;
}

#set session data value
if( $captchaTo!='' )
{
	$_SESSION['captcha'] = array();
	$_SESSION['captcha'][ $captchaTo ] = (string)$captchaResult;
}else{
	$_SESSION['captcha'] = (string)$captchaResult;
}

#generate picture
$image = imagecreatetruecolor(100, 32);
$black = imagecolorallocate($image, 0, 0, 0);
$color = imagecolorallocate($image, 0, 0, 0); 
$white = imagecolorallocate($image, 255, 255, 255);

#paste captchaResult into the picture
imagefilledrectangle($image,0,0,100,50,$white);
imagettftext($image, 24, 0, 12, 28, $color, "AngelicWar.ttf", $captchaCode);

#give image
header("Content-type: image/png");
imagepng($image);
?>