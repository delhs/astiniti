<?php /* Smarty version 2.6.27, created on 2014-01-06 12:42:51
         compiled from default.tpl */ ?>
<!--don't remove this code from here-->
<ul class="addon_menu_back">
	<li><a href="#">Назад</a></li>
	<li class="separator"></li>
</ul>
<!--end-->


<!--your module menu code-->
<ul id="plug_mailme_menu" class="addon_menu">
	<li>
		<a href="#">История</a>
		<ul>
			<li><a href="#" data-action="history">История писем</a></li>
		</ul>
	</li>
	<li>
		<a href="#">Настройки</a>
		<ul>
			<li><a data-action="departaments_tpl" href="#">Отделы</a></li>
			<li><a data-action="email_sett_tpl" href="#">Шаблон E-mail сообщений</a></li>
			<li><a data-action="recipient_email_tpl" href="#">Получатели E-mail</a></li>
			<li><a data-action="security_tpl" href="#">Безопасность</a></li>
		</ul>
	</li>
</ul>
<!--end-->


<div class="plug_mailme">
	<?php echo $this->_tpl_vars['welcomescreen']; ?>

</div>