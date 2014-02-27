<?php /* Smarty version 2.6.27, created on 2013-12-18 19:56:03
         compiled from sms_center_sett_tpl.tpl */ ?>
<div class="sms_center">
	<form name="plug_call_me_sms_center">
		<div class="block">
			<p>Настройка СМС центра</p>

			<div class="field">
				<label>api_id  (<a target="_blank" href="http://sms.ru/">www.sms.ru</a>) <span class="hlp" title="api_id выдает СМС центр"></span></label>
				<div class="padding">
					<input type="text" name="api_id" value="<?php echo $this->_tpl_vars['settings']['api_id']; ?>
" />
				</div>
			</div>

		</div>

		<div class="block">
			<p>Баланс<span class="hlp" title="Ваш текущий баланс СМС центра"></span></p>
			<div id="sms_center_balance">0</div>
			<button type="button" name="balance">Обновить сейчас</button>
			<div class="clear"></div>
		</div>

		<div class="block">
			<p>Дневной лимит<span class="hlp" title="Текущий лимит позволяет вам отправлять любое количество сообщений на 100 разных номеров в течение дня. Лимит можно увеличить в личном кабинете СМС центра"></span></p>
			<div id="sms_center_limit">0</div>
			<button type="button" name="limit">Обновить сейчас</button>
			<div class="clear"></div>
		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
<?php echo '<script type="text/javascript">callme.smsCenterTplInit();</script>'; ?>