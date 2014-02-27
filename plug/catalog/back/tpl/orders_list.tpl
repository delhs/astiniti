<div class="orders_list">
	<p>Список заказов</p>

	{if isset($ordersArray)}
		<div class="block">
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Статус</td>
						<td>ID заказа</td>
						<td>Дата и время&nbsp;заказа</td>
						<td>Сумма</td>
						<td>Доставка</td>
						<td>Заказчик</td>
						<td>Телефон заказчика</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					{foreach from=$ordersArray key=key item=order}
					<tr data-id="{$order.id}">
						<td>{foreach from=$orderStatuses key=status item=statusText}{if $status eq $order.order_status}<span class="status_{$status}">{$statusText|escape}</span>{/if}{/foreach}</td>				
						<td>{$order.id}</td>				
						<td>{$order.order_date}&nbsp;г. <span class="order_time">{$order.order_time}</span></td>				
						<td>{$order.order_cost}</td>				
						<td>{if $order.order_delivery_self eq '1'}Не требуется{else}Требуется{/if}</td>				
						<td>{$order.user_name|escape}</td>			
						<td>{$order.user_phone}</td>
						<td class="remove"><span class="icon reddelete" title="Удалить заказ"></span></td>			
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>

		<div class="block">
				
			<select class="nav" name="countonpage" title="Количество результатов, выводимых на страницу">
				{section name="i" start=10 step=10 loop=101} 
				<option {if $countonpage eq $smarty.section.i.index}selected="selected"{/if} value="{$smarty.section.i.index}">{$smarty.section.i.index}</	option>
				{/section}
			</select>
			
			{if isset($navArray)}
				<ul class="nav">
				{foreach from=$navArray key=key item=item}
					<li>
						<a href="#" data-num="{$item.num}" class="{$item.class}" title="{$item.title}">{$item.text}</a>
					</li>
				{/foreach}
				</ul>
			{/if}
			<div class="clear"></div>
		</div>

	{else}
		<div class="block"><p>Заказов не найдено</p></div>	
	{/if}
	

</div>
{literal}<script type="text/javascript">catalog.ordersListTplInit();</script>{/literal}