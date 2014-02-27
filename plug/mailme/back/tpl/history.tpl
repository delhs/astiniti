<div class="history">
	<p>История писем</p>
	<div class="form">
		
		{if isset($history)}
			<div class="block">
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>Дата</td>
							<td>Время</td>
							<td>Отдел</td>
							<td>Имя</td>
							<td>E-mail</td>
							<td>Текст письма</td>
						</tr>
					</thead>
					<tbody>
					{foreach from=$history item=row}
						<tr>
							<td>{$row.request_date}</td>
							<td>{$row.request_time}</td>
							<td>{$row.request_departament|escape}</td>
							<td>{$row.request_name|escape}</td>
							<td>{$row.request_email|escape}</td>
							<td><span>{$row.request_text|escape}</span></td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>

			<div class="block">
				<button type="button" name="clear">Очистить историю</button>
				<div class="clear"></div>
			</div>


		{else}
			<div class="block">Пусто.</div>
		{/if}
	
	</div>
</div>
{literal}<script type="text/javascript">mailme.historyTplInit();</script>{/literal}