<?php /* Smarty version 2.6.27, created on 2014-02-08 14:05:02
         compiled from default.tpl */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php if ($this->_tpl_vars['debug']): ?>DEBUG MODE<?php else: ?>Панель управления проектом &laquo;<?php echo $this->_tpl_vars['projectName']; ?>
&raquo;<?php endif; ?></title> 
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
		<meta content="KUNANO" name="author"/>
		<meta content="KUNANO" name="copyright"/>
		<meta http-equiv="content-language" content="ru"/>
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" /> 
		<script type="text/javascript" src="/system/admin/editor/ckeditor/ckeditor.js"></script>
		<?php $_from = $this->_tpl_vars['cssFilesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['href']):
?><link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['href']; ?>
"/>
		<?php endforeach; endif; unset($_from); ?>
	
		<?php $_from = $this->_tpl_vars['jsFilesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['src']):
?><script type="text/javascript" src="<?php echo $this->_tpl_vars['src']; ?>
"></script>
		<?php endforeach; endif; unset($_from); ?>

		<?php echo '
		<!--[if lt IE 10]>
		<style type="text/css">
			#load_all>*{
				display:none!important;
			}
			#shit_IE{
				display:block!important;
				position:absolute;
				width:300px;
				height:300px;
				background:url(/system/admin/img/shit_ie.jpg);
				left:50%;
				top:50%;
				margin:-150px 0 0 -150px;
			}
		</style>
		<![endif]-->
		'; ?>

	</head>

	
	<body>
		<div id="load_all">
			<div id="shit_IE"></div>
			<span class="logo">KUNANO</span>
			<div class="bar"><div></div></div>
			<i>Размер окна браузера слишком мал для отображения панели управления проектом</i>
			<input type="text" name="login" id="login" value="" autocomplete="off" />
			<input type="password" name="password" id="password" value="" autocomplete="off" />
			<div class="loadercicle"></div>
			<div class="tooltip" for="login">Введите логин</div>
			<div class="tooltip" for="password">Введите пароль</div>
		</div>
		
		<div id="loader"><div></div><i></i></div>
		
		<div class="infotipbox"></div>
		
		<div id="toppanel">
			<a href="http://kunano.ru" target="_blank" class="main_logo"></a>
			
			<div class="tools">
				<a href="#" class="togglemmenubutton act -forstate" title="Скрыть/показать главное меню"></a>
				<a href="#" class="airmodebutton -forstate" title="Переключиться в режим редактирования сайта &laquo;на&nbsp;лету&raquo;"></a>
				<a href="#" class="changetheme -forstate" title="Сменить тему оформления"></a>
				<a href="#" class="visible -forair" title="Скрыть/показать разметку"></a>
								<span class="separator -forair"></span>
								<a href="#" class="changetitle -forair" title="Изменить заголовок страницы"></a>
				<span class="separator -forair"></span>
				<a href="#" class="reload -forair" title="Обновить загруженную страницу"></a>
				<a href="#" class="back -forair" title="Вернуться в статический (обычный) режим редактирования сайта"></a>
			</div>
			
			<div class="currentpageblock">Текущий раздел:<a href="/" target="_blank"><?php echo $this->_tpl_vars['hostname']; ?>
<span>/</span></a></div>

			<div class="userinfoblock">
				<span>Вы вошли в систему как </span><div class="username">&laquo;<b></b>&raquo;</div>
			</div>
			<a class="logoutbutton" href="#" onclick="admin.auth.logout();return false;" title="Выйти из панели управления проектом"></a>
			
			<?php if (isset ( $this->_tpl_vars['multylang'] )): ?>
			<a title="Сменить базу данных" href="#" class="multylang"></a>
			<div id="multylanglist">
				<p>Выберите базу данных</p>
				<ul>
					<?php $_from = $this->_tpl_vars['multylang']['prefixes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<li><a <?php if ($this->_tpl_vars['multylang']['currentLang'] == $this->_tpl_vars['item']): ?>class="act"<?php endif; ?> href="#" data-lang="<?php echo $this->_tpl_vars['item']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</a></li>
					<?php endforeach; endif; unset($_from); ?>
				</ul>
			</div>
			<?php endif; ?>
			
			
		</div>
		
		<div id="cap"></div>

		<div class="state">
			<div class="container">
				<div class="inner"></div>
			</div>
		</div>
		
		<div class="clear"></div>

		<div id="scrollbuttoncontainer">
			<a href="#" id="scrolltop"></a>
			<a href="#" id="scrollbottom"></a>
		</div>
		
		
		
	
	</body>
</html>