<div class="sms_sett">
	<form name="plug_call_me_recipient_sms_sett">
		<div class="block">
			<p>Шаблон СМС сообщения получателя</p>

			<div class="field">
				<label>Текст сообщения</label>
				<div class="padding">
					<textarea name="text">{$smsSettings.sms_template}</textarea>
				</div>
				<div class="padding">
					<div class="charcount">Количество СМС сообщений:&nbsp;&nbsp;<b></b></div>
				</div>
			</div>

			<div class="field">
				<label>Псевдокод</label>
				<div class="padding">
					%name% - <i>Имя пользователя, которому необходимо перезвонить (может достигать до 50 символов).</i>
				</div>
				<div class="padding">
					%phone% - <i>Номер телефона пользователя, которому необходимо перезвонить.</i>
				</div>
				<div class="padding">
					%comment% - <i>Комментарий пользователя (может достигать до 100 символов).</i>
				</div>
			</div>

		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
{literal}<script type="text/javascript">callme.smsSettTplInit();</script>{/literal}