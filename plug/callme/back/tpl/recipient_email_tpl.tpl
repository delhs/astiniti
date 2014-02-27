<div class="recipient_email">
	<form name="plug_call_me_recipient_email">
		<div class="block">
			<p>Получатели E-mail сообщений</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>E-mail получателя</td>
						<td>Имя получателя</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					{if isset($recipients)}
						{foreach from=$recipients item=recipient}
						<tr>
							<td><input type="text" name="email" class="email" value="{$recipient.email}" /></td>
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
{literal}<script type="text/javascript">callme.recipientEmailTplInit();</script>{/literal}