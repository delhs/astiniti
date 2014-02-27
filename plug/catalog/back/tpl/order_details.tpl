<div class="order_details form">
	<span>Карточка заказа. ID {$order.id}</span>

	<div class="block">

		<div class="field">
			<label>Дата и время заказа:</label><i>{$order.order_date}&nbsp;г. <span class="order_time">{$order.order_time}</span></i>
		</div>

		<div class="field">
			<label>Сумма заказа:</label><i>{$order.order_cost}&nbsp;{$order.currency}</i>
		</div>

		<div class="field">
			<label>Всего товаров:</label><i><b>{$order.items_count} шт.</b>&nbsp;&nbsp;&nbsp;<a id="get_order_items_data" href="#">Открыть перечень</a></i>
		</div>

		<div class="field">
			<label>Заказчик и адрес доставки:</label><i><a id="get_order_user_data" href="#">{$order.user_name|escape}</a></i>
		</div>

		<div class="field">
			<label>Требуется доставка:</label><i><input type="checkbox" name="order_delivery_self" value="1" {if $order.order_delivery_self eq '0'}checked="checked"{/if}/></i>
		</div>

		<div class="field">
			<label>Статус:</label>
			<div class="padding">
				<select name="order_status">
					{foreach from=$orderStatuses key=statusId item=statusText }
						<option value="{$statusId}" {if $order.order_status eq $statusId}selected="selected"{/if}>{$statusText|escape}</option>
					{/foreach}

				</select>
			</div>
		</div>

		<div class="field">
			<label>Комментарий заказчика:</label><textarea readonly="readonly" name="comment">{$order.order_comment|escape}</textarea>
		</div>

		<div class="field">
			<label>Комментарий менеджера:</label><textarea name="manager_comment">{$order.order_manager_comment|escape}</textarea>
		</div>

	</div>


	<div class="block">
		<button type="button" name="save_order_details">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>

</div>