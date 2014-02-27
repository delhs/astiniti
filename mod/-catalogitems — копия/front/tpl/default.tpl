{strip}
{if isset($itemsArray)}
	
<div class="mod_catalogitems">
	
	<h2>Заголовок</h2>
	
	<ul class="list_items">
		{foreach from=$itemsArray item=item}
			<li>
				<a href="{$item.full_item_url}" title="{$item.meta_title|escape}" class="item_logo">{if $item.item_logo neq '' }<img src="{$item.item_logo}"/>{else}<div class="no_photo"></div>{/if}</a>
				
				{if $item.discount neq '0'}<div class="percent">-{$item.discount}%</div>{/if}

				<div class="description">
					<a href="{$item.full_item_url}" title="{$item.meta_title|escape}" class="item_name">{$item.name|escape}</a>
					
					{if isset ($item.attributes)}
					<ul class="attributes">
					{foreach from=$item.attributes item=attr}
						{if $attr.in_list eq '1'}
						<li title="{$attr.attribute_name|escape}:&nbsp;{$attr.attribute_value|escape}&nbsp;{$attr.attribute_units|escape}"><span>{$attr.attribute_name|escape}:&nbsp;</span><b>{$attr.attribute_value|escape}&nbsp;{$attr.attribute_units|escape}</b></li>
						{/if}
					{/foreach}
					</ul>
					{/if}	
					
					<span class="desc">{$item.item_quick_desc|escape}</span>
					<a href="{$item.full_cat_url}" title="Смотреть все товары категории &laquo;{$item.category_name|escape}&raquo;" class="category">{$item.category_name|escape}</a>
					<a href="{$item.full_brand_url}" title="Перейти на страницу производителя" class="brand">{$item.brand_name}</a>
				</div>
				

				
				<div class="buyblock">
					{if $item.is_new eq '1'}
					<a href="#" class="buy -new" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord name="plug_index_catalog_buy"}</a>
					{elseif $item.is_sale eq '1'}
					<a href="#" class="buy -sale" data-id="{$item.id}"><span><i></i><b class="old">{$item.old_price}&nbsp;{$catalog.currency_symbol}</b><b class="new">{$item.price}&nbsp;{$catalog.currency_symbol}</b></span>{pasteWord name="plug_index_catalog_buy"}</a>
					{elseif $item.is_best eq '1'}
					<a href="#" class="buy -best" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord name="plug_index_catalog_buy"}</a>
					{else}
					<a href="#" class="buy" data-id="{$item.id}"><span><i></i>{$item.price}&nbsp;{$catalog.currency_symbol}</span>{pasteWord name="plug_index_catalog_buy"}</a>
					{/if}
					
					{if $item.in_stock eq '0'}
						<span class="not_in_stock">Товара нет в наличии</span>
					{/if}
					<a href="#" class="del_from_compare" data-id="{$item.id}">Убрать из сравнения</a>
					<a href="#" class="add_to_compare" data-id="{$item.id}">Добавить в сравнение</a>
					<!--<img src="{$item.qrcode}/M/8/3/FFCCCC/000000"/>-->
				</div>
			</li>
		{/foreach}
	</ul>
	<a class="prev controls" href="#"></a>
	<a class="next controls" href="#"></a>
	<div class="pager controls"><div></div></div>
	<div class="clear"></div>

</div>
{/if}
{/strip}