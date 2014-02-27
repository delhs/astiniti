<?php /* Smarty version 2.6.27, created on 2014-01-27 13:04:25
         compiled from default.tpl */ ?>
<!--don't remove this code from here-->
<ul class="addon_menu_back">
	<li><a href="#">Назад</a></li>
	<li class="separator"></li>
</ul>
<!--end-->


<!--your module menu code-->
<ul id="plug_callme_menu" class="addon_menu">
	<li>
		<a href="#">История</a>
		<ul>
			<li><a href="#" data-action="history">История заказов звонка</a></li>
		</ul>
	</li>
	<li>
		<a href="#">Настройки</a>
		<ul>
			<li><a data-action="email_sett_tpl" href="#">Шаблон E-mail сообщений</a></li>
			<li><a data-action="sms_sett_tpl" href="#">Шаблон СМС сообщений</a></li>
			<li><a data-action="recipient_sms_tpl" href="#">Получатели СМС</a></li>
			<li><a data-action="recipient_email_tpl" href="#">Получатели E-mail</a></li>
			<li><a data-action="sms_center_sett_tpl" href="#">СМС центр</a></li>
			<li><a data-action="security_tpl" href="#">Безопасность</a></li>
		</ul>
	</li>
</ul>
<!--end-->


<div class="plug_callme">
	<?php echo $this->_tpl_vars['welcomescreen']; ?>

</div>