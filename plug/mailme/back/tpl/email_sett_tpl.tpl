<div class="email_sett">
	<form name="plug_mail_me_recipient_email_sett">
		<div class="block">
			<p>Шаблон E-mail сообщения получателя</p>

			<div class="field">
				<label>E-mail адрес отправителя</label>
				<div class="padding">
					<input type="text" name="email_from_address" value="{$emailSettings.email_from_address}" />
				</div>
			</div>

			<div class="field">
				<label>E-mail для ответа</label>
				<div class="padding">
					<input type="text" name="email_reply" value="{$emailSettings.email_reply}" />
				</div>
			</div>

			<div class="field">
				<label>Имя отправителя</label>
				<div class="padding">
					<input type="text" name="email_from_name" value="{$emailSettings.email_from_name|escape}" />
				</div>
			</div>

			<div class="field">
				<label>Тема письма</label>
				<div class="padding">
					<input type="text" name="email_subject" value="{$emailSettings.email_subject|escape}" />
				</div>
			</div>

			<div class="field">
				<label>Текст письма</label>
				<div class="padding">
					<textarea name="text" data-type="editor">{$emailSettings.email_template}</textarea>
				</div>
			</div>

			<div class="field">
				<label>Псевдокод</label>
				<div class="padding">
					%name% - <i>Имя пользователя, отправившего пистьмо (может достигать до 50 символов).</i>
				</div>
				<div class="padding">
					%email% - <i>E-mail адрес пользователя, отправившего пистьмо.</i>
				</div>
				<div class="padding">
					%text% - <i>Текст письма пользователя (может достигать до 900 символов).</i>
				</div>
			</div>

		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
{literal}<script type="text/javascript">mailme.emailSettTplInit();</script>{/literal}