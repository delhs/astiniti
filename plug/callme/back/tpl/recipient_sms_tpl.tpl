<div class="recipient_sms">
	<form name="plug_call_me_recipient_sms">
		<div class="block">
			<p>Получатели СМС сообщений</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Телефон получателя</td>
						<td>Имя получателя</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					{if isset($recipients)}
						{foreach from=$recipients item=recipient}
						<tr>
							<td><input type="text" name="phone" class="phone" value="{$recipient.phone}" /></td>
							<td><input type="text" name="name" class="name" value="{$recipient.name|escape}" /></td>
							<td class="delete"><span class="icon" title="Удалить получателя"></span></td>
						</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</div>
	

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add">Добавить получателя</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
{literal}<script type="text/javascript">callme.recipientSmsTplInit();</script>{/literal}