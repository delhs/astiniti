{strip}
<div class="plug_catalog catalog_cart_page">
	 

	{if !isset($itemsArray)}
		<h1>Ваша корзина пуста</h1>
	{else}

		<h1>Ваш заказ</h1>

		<table cellpadding="0" cellspacing="0" class="items">
			<thead>
				<tr>
					<td>Изображение</td>
					<td>Название</td>
					<td>Описание</td>
					<td>Цена&nbsp;({$catalog.currency_quick})</td>
					<td>Количество</td>
					<td>Стоимость&nbsp;({$catalog.currency_quick})</td>
					<td>&nbsp;</td>
				</tr>
			</thead>
			</tbody>
				{foreach from=$itemsArray.itemsArray key=id item=item }
				<tr data-id="{$item.id}">
					<td>
						{if $item.in_stock neq '1'}<span class="no_in_stock">Товара нет в наличии</span>{/if}
						<a target="_blank" href="{$item.full_item_url}" title="{$item.meta_title|escape}" class="item_logo">
							{if $item.item_logo neq '' }<img src="{$item.item_logo}"/>{else}<div class="no_photo"></div>{/if}
							{if $item.discount neq '0'}<div class="percent">-{$item.discount}%</div>{/if}
						</a>
					</td>
					<td class="name">
						{$item.name|escape}
						<ul class="attributes">
						{foreach from=$item.attributes key=attrId item=attrData}
							<li><b>{$attrData.attribute_name|escape}</b>&nbsp;&ndash;&nbsp;{$attrData.attribute_value|escape}</li>
						{/foreach}
						</ul>
					</td>
					<td><span class="desc">{$item.item_quick_desc|escape}</span></td>
					<td>
						{if $item.is_sale eq '1'}<span class="old_price">{$item.old_price}&nbsp;{$catalog.currency_symbol}</span>{/if}
						<span class="price">{$item.price}&nbsp;{$catalog.currency_symbol}</span>
					</td>
					<td><div class="counter"><span class="count">{$item.count}</span><div class="updown"><a href="#" class="up" data-id="{$item.id}">+</a><a href="#" class="down" data-id="{$item.id}">-</a></div></div></td>
					<td><span class="cost">{$item.cost}</span></td>	
					<td><a href="#" class="delete" title="Удалить товар" data-id="{$item.id}"></a><a href="#" class="recover" title="Вернуть товар" data-id="{$item.id}"></a></td>	
				</tr>
				{/foreach}
			</tbody>
		</table>


		<table cellpadding="0" cellspacing="0" class="result">
			<thead>
				<tr>
					<td>Всего товаров(шт.)</td>
					<td>Всего позиций</td>
					<td>Сумма&nbsp;({$catalog.currency_quick})</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class="cart"><i data-type="count">-</i></span></td>
					<td><span class="cart"><i data-type="pos_count">-</i></span></td>
					<td><span class="cart"><i data-type="cost">-</i></span></td>
				</tr>
			</tbody>
		</table>

		<div class="hr"></div>

		<div class="clear"></div>

		<form name="userdata">
			<h4>Информация о покупателе:</h4>
			<div class="field">
				<label>Имя:<i class="star">*</i></label>
				<input type="text" name="name" />
				<span class="info"></span>
			</div>

			<div class="field">
				<label>Телефон:<i class="star">*</i></label>
				<input type="text" name="phone" />
				<span class="info">На этот номер позвонит наш менеджер для уточнения деталей заказа.</span>
			</div>


			<div class="field">
				<a href="#" class="more_info_toggle">Показать развернутую форму</a>
				<a href="#" class="more_info_toggle hidden">Скрыть развернутую форму</a>
			</div>

			<div class="more_data_block">
				<div class="field">
					<label>E-mail:</label>
					<input type="text" name="email" maxlength="50"/>
					<span class="info">На ваш E-mail будет отправлена информация о заказе.</span>
				</div>
	
				<div class="field">
					<label>Регион Область Край:</label>
					<select name="region" disabled="disabled">
						<option value="0">--Не выбрано--</option>
					</select>
				</div>
	
				<div class="field">
					<label>Город:</label>
					<select name="city" disabled="disabled">
						<option value="0">--Не выбрано--</option>
					</select>
				</div>
	
				<div class="separator"></div>
	
				<div class="field">
					<label for="self_delivery">Заберу самостоятельно</label>
					<input type="checkbox" id="self_delivery" name="self_delivery" />
				</div>
	
				<div class="field address">
					
					<div class="field">
						<label>Улица:</label>
						<input type="text" name="street" maxlength="50"/>
					</div>
	
					<div class="col">
						<label>Дом:</label><input type="text" name="build" maxlength="4"/>
					</div>
	
					<div class="col">
						<label>Корпус:</label><input type="text" name="liter" maxlength="2"/>
					</div>
	
					<div class="col">
						<label>Подъезд:</label><input type="text" name="entrance" maxlength="1"/>
					</div>
	
					<div class="col">
						<label>Этаж:</label><input type="text" name="floor" maxlength="2"/>
					</div>
	
					<div class="col">
						<label>Квартира:</label><input type="text" name="room" maxlength="5"/>
					</div>
	
					<div class="clear"></div>
				
					<div class="field">
						<label>Дата доставки:</label>
						<input type="text" name="delivery_date" value="{$deliveryDate}" readonly="readonly" />
					</div>
	
					
				</div>
	
				<div class="separator"></div>

			</div>

			<div class="field">
				<label>Комментарий:</label>
				<textarea name="comment"></textarea>
			</div>

			<span class="bottom_info">Поля отмеченные звездочкой(<i class="star">*</i>) обязательны для заполнения.</span>

			<div class="clear"></div>

			<div class="separator"></div>

			<div class="field">
				<div class="oferta_block">
					<span>Я согласен с условиями</span> <a href="#" id="show_oferta">публичной оферты</a>
					<input type="checkbox" name="oferta_consent" />
				</div>
				<button type="button" name="save_order" disabled="disabled">Оформить</button>
				<div class="clear"></div>
			</div>


		</form>

	{/if}


</div>

<script type="text/javascript">{literal}$(document).ready(function(){plug_catalog.cartPage();});{/literal}</script>
{/strip}