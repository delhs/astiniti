<div class="items_list">
	
	<div class="block filter">
		
		{if isset($catList)}
		<div class="field">	
			<label>Фильтр по категории:</label>
			<div class="padding">
				<select name="cat_filter" data-type="select_filter">
					<option value="0">--Все категории--</option>
					{foreach from=$catList item=cat}<option value="{$cat.id}"{if isset($cat.active)} selected="selected"{/if}>{$cat.name|escape}</option>{/foreach}
				</select>
			</div>
		</div>
		{/if}

		{if isset($brandsList)}
		<div class="field">	
			<label>Фильтр по бренду:</label>
			<div class="padding">
				<select name="brands_filter" data-type="select_filter">
					<option value="0">--Все бренды--</option>
					{foreach from=$brandsList item=brand}<option value="{$brand.id}"{if isset($brand.active)} selected="selected"{/if}>{$brand.name|escape}</option>{/foreach}
				</select>
			</div>
		</div>
		{/if}

		<div class="field">
			<label>Фильтр по влагам:</label>
			<div class="padding filter_checkboxes">
				<label for="filter_is_all">Все</label><input {if isset($filterArray.filter_is) && $filterArray.filter_is eq 'is_all' }checked="checked"{/if} type="radio" id="filter_is_all" checked="checked" name="filter_is" value="is_all" data-type="check_filter" />
				<label for="filter_is_new_filter">Новинки</label><input {if isset($filterArray.filter_is) && $filterArray.filter_is eq 'is_new_filter' }checked="checked"{/if} type="radio" id="filter_is_new_filter" name="filter_is" value="is_new_filter" data-type="check_filter" />
				<label for="filter_is_sale_filter">Распродажные</label><input {if isset($filterArray.filter_is) && $filterArray.filter_is eq 'is_sale_filter' }checked="checked"{/if} type="radio" id="filter_is_sale_filter" name="filter_is" value="is_sale_filter" data-type="check_filter" />
				<label for="filter_is_best_filter">Лидеры продаж</label><input {if isset($filterArray.filter_is) && $filterArray.filter_is eq 'is_best_filter' }checked="checked"{/if} type="radio" id="filter_is_best_filter" name="filter_is" value="is_best_filter" data-type="check_filter" />
				<label for="filter_is_markdown_filter">Уценка</label><input {if isset($filterArray.filter_is) && $filterArray.filter_is eq 'is_markdown_filter' }checked="checked"{/if} type="radio" id="filter_is_markdown_filter" name="filter_is" value="is_markdown_filter" data-type="check_filter" />
			</div>

		</div>

		<div class="field">
			<label>Фильтр по видимости:</label>
			<div class="padding filter_checkboxes">
				<label for="in_stock_filter">В наличии</label><input {if isset($filterArray.in_stock_filter)}checked="checked"{/if} type="checkbox" id="in_stock_filter" name="in_stock_filter" value="1" data-type="check_filter" />
				<label for="hide_in_list_filter">Скрытые</label><input {if isset($filterArray.hide_in_list_filter)}checked="checked"{/if} type="checkbox" id="hide_in_list_filter" name="hide_in_list_filter" value="1" data-type="check_filter" />
				<label for="disabled_filter">Не доступные</label><input {if isset($filterArray.disabled_filter)}checked="checked"{/if} type="checkbox" id="disabled_filter" name="disabled_filter" value="1" data-type="check_filter" />
			</div>
		</div>		

	</div>


	{if isset($itemsArray)}
	<p>Список товаров</p>

	<div class="block">
			
		<select class="nav" name="countonpage" title="Количество результатов, выводимых на страницу">
			{section name="i" start=10 step=10 loop=101} 
			<option {if $countonpage eq $smarty.section.i.index}selected="selected"{/if} value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
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
		
	<div class="block">
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td>Изображение</td>
					<td>ID</td>
					<td>ID&nbsp;в&nbsp;1С</td>
					<td>Артикул</td>
					<td>Создан/Обновлен</td>
					<td>Название</td>
					<td>Цена</td>
					<td>Категория</td>
					<td>Бренд</td>
					<td class="small">В наличии</td>
					<td class="small">Распродажа</td>
					<td class="small">Новинка</td>
					<td class="small">Лучший товар</td>
					<td class="small">Уценка</td>
					<td class="small">Скрыт</td>
					<td class="small">Не доступен</td>
					<td class="small">&nbsp;</td>
				</tr>
			</thead>
			<tbody>
				{foreach from=$itemsArray key=key item=item}
				<tr data-id="{$item.id}">
					<td>{if isset($item.full_logo_src)}<img src="{$item.full_logo_src}" />{else}<div class="no_photo"></div>{/if}</td>
					<td>{$item.id}</td>
					<td>{$item.external_id}</td>
					<td>{$item.articul}</td>
					<td>{$item.create_date}<br/>{$item.update_date}</td>
					<td>{$item.name}</td>
					<td>{$item.price}</td>
					<td>{$item.category_name}</td>
					<td>{$item.brand_name}</td>
					<td data-action="in_stock"><span class="icon yellowcheck{if $item.in_stock eq 1} on{/if}"></span></td>	
					<td data-action="is_sale"><span class="icon greencheck popular{if $item.is_sale eq 1} on{/if}"></span></td>					
					<td data-action="is_new"><span class="icon greencheck popular{if $item.is_new eq 1} on{/if}"></span></td>					
					<td data-action="is_best"><span class="icon greencheck popular{if $item.is_best eq 1} on{/if}"></span></td>	
					<td data-action="is_markdown"><span class="icon greencheck popular{if $item.is_markdown eq 1} on{/if}"></span></td>	
					<td data-action="hide_in_list"><span class="icon redcheck{if $item.hide_in_list eq 1} on{/if}"></span></td>					
					<td data-action="disabled"><span class="icon redcheck{if $item.disabled eq 1} on{/if}"></span></td>					
					<td data-action="delete"><span class="icon reddelete"></span></td>					
				</tr>
				{/foreach}
			</tbody>
		</table>
		
	</div>	

	
	<div class="block">
			
		<select class="nav" name="countonpage" title="Количество результатов, выводимых на страницу">
			{section name="i" start=10 step=10 loop=101} 
			<option {if $countonpage eq $smarty.section.i.index}selected="selected"{/if} value="{$smarty.section.i.index}">{$smarty.section.i.index}</option>
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
	<div class="block"><p>Товаров не найдено</p></div>
	{/if}
	
	
</div>

<script type="text/javascript">catalog.itemsListTplInit();</script>