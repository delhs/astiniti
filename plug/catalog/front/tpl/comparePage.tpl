{strip}
<div class="plug_catalog catalog_compare_page">
	<h1>Сравнение</h1>

	{if isset($empty)}
		<p>Пусто</p>
	{else}
		{foreach from=$compareArray key=typeId item=itemsArray}
			<div class="full_block_compare">
				<p class="type_name">{$itemsArray.type_name}</p>
				<div class="compare_all_wrapper">
					<table class="titles" cellpadding="0" cellspacing="0">
						<tbody>
							<tr class="image"><td><span></span></td></tr>
							<tr><td><span>Название</span></td></tr>
							<tr><td><span>Цена</span></td></tr>
				
							{foreach from=$itemsArray.attr_list key=attrId item=attrData}
								<tr><td><span>{$attrData.attribute_name|escape}{if $attrData.attribute_units neq ''}({$attrData.attribute_units}){/if}</span></td></tr>
							{/foreach}
							<tr><td><span>Производитель</span></td></tr>
							<tr><td><span></span></td></tr>
						</tbody>
					</table>	

					<div class="compare_wrapper">
						<div class="compare_block">
							{foreach from=$itemsArray.items key=itemId item=item}
							<table data-id="{$itemId}" cellpadding="0" cellspacing="0">
								<tbody>
									<tr class="image">
										<td>
											<span>{if $item.item_logo neq ''}<img src="{$item.item_logo}" />{else}<div class="no_photo"></div>{/if}</span>
											<a href="#" class="delete" data-id="{$itemId}"></a>
											{if $item.in_stock eq '0'}<span class="not_in_stock">Товара нет в наличии</span>{/if}
										</td>
									</tr>
									<tr class="name"><td><span>{$item.name|escape}</span></td></tr>
									<tr  class="price"><td><span>{$item.price}&nbsp;{$catalog.currency_symbol}</span></td></tr>
									{foreach from=$item.attributes key=attrId item=attrData}
										<tr><td><span>{$attrData.attribute_value|escape}</span></td></tr>
									{/foreach}
									<tr><td><span><a href="{$item.full_brand_url}" title="Перейти на страницу производителя" class="brand">{$item.brand_name}</a></span></td></tr>
									<tr>
										<td>
											{if $item.is_new eq '1'}
											<a href="#" class="buy -new" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord 		name="plug_index_catalog_buy"}</a>
											{elseif $item.is_sale eq '1'}
											<a href="#" class="buy -sale" data-id="{$item.id}"><span><i></i><b class="old">{$item.old_price}&nbsp;{$catalog.currency_symbol}</b><b class="new">	{$item.price}&nbsp;{$catalog.currency_symbol}</b></span>{pasteWord name="plug_index_catalog_buy"}</a>
											{elseif $item.is_best eq '1'}
											<a href="#" class="buy -best" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord 		name="plug_index_catalog_buy"}</a>
											{else}
											<a href="#" class="buy" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord 	name="plug_index_catalog_buy"}</a>
											{/if}
										</td>
									</tr>
								</tbody>
							</table>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		{/foreach}
	{/if}

</div>
{/strip}