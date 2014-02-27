<div class="departaments">
	<form name="plug_mail_me_departaments">
		<div class="block">
			<p>Отделы</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Название отдела</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					{if isset($departaments)}
						{foreach from=$departaments item=departament}
						<tr>
							<td><input type="text" name="name" class="name" value="{$departament.name}" /></td>
							<td class="delete"><span class="icon" title="Удалить отдел"></span></td>
						</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</div>
	

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add">Добавить отдел</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
{literal}<script type="text/javascript">mailme.departamentsEmailTplInit();</script>{/literal}