<div class="history">
	<p>История заказов звонка</p>
	<div class="form">
		
		{if isset($history)}
			<div class="block">
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>Дата</td>
							<td>Время</td>
							<td>Имя</td>
							<td>Телефон</td>
							<td>Комментарий</td>
						</tr>
					</thead>
					<tbody>
					{foreach from=$history item=row}
						<tr>
							<td>{$row.request_date}</td>
							<td>{$row.request_time}</td>
							<td>{$row.request_name|escape}</td>
							<td>{$row.request_phone}</td>
							<td><span>{$row.request_comment|escape}</span></td>
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
{literal}<script type="text/javascript">callme.historyTplInit();</script>{/literal}