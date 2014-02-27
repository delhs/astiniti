<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{if $debug}DEBUG MODE{else}Панель управления проектом &laquo;{$projectName}&raquo;{/if}</title> 
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
		<meta content="KUNANO" name="author"/>
		<meta content="KUNANO" name="copyright"/>
		<meta http-equiv="content-language" content="ru"/>
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" /> 
		<script type="text/javascript" src="/system/admin/editor/ckeditor/ckeditor.js"></script>
		{foreach from=$cssFilesArray item=href}<link rel="stylesheet" type="text/css" href="{$href}"/>
		{/foreach}
	
		{foreach from=$jsFilesArray item=src}<script type="text/javascript" src="{$src}"></script>
		{/foreach}

		{literal}
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
		{/literal}
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
				{*<a href="#" class="editor act -forair" title="Скрыть/показать текстовый редактор"></a>*}
				<span class="separator -forair"></span>
				{*<a href="#" class="save -forair" title="Сохранить изменения"></a>
				<span class="separator -forair"></span>*}
				<a href="#" class="changetitle -forair" title="Изменить заголовок страницы"></a>
				<span class="separator -forair"></span>
				<a href="#" class="reload -forair" title="Обновить загруженную страницу"></a>
				<a href="#" class="back -forair" title="Вернуться в статический (обычный) режим редактирования сайта"></a>
			</div>
			
			<div class="currentpageblock">Текущий раздел:<a href="/" target="_blank">{$hostname}<span>/</span></a></div>

			<div class="userinfoblock">
				<span>Вы вошли в систему как </span><div class="username">&laquo;<b></b>&raquo;</div>
			</div>
			<a class="logoutbutton" href="#" onclick="admin.auth.logout();return false;" title="Выйти из панели управления проектом"></a>
			
			{if isset($multylang)}
			<a title="Сменить базу данных" href="#" class="multylang"></a>
			<div id="multylanglist">
				<p>Выберите базу данных</p>
				<ul>
					{foreach from=$multylang.prefixes key=key item=item}
					<li><a {if $multylang.currentLang eq $item}class="act"{/if} href="#" data-lang="{$item}">{$item}</a></li>
					{/foreach}
				</ul>
			</div>
			{/if}
			
			
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