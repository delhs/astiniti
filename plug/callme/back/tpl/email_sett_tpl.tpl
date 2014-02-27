<div class="email_sett">
	<form name="plug_call_me_recipient_email_sett">
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
{literal}<script type="text/javascript">callme.emailSettTplInit();</script>{/literal}