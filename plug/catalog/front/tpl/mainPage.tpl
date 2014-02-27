{strip}
<div class="plug_catalog catalog_main_page">





	


	{if isset($category)}
		<div class="cat_name">
			<h1>{$category.name}</h1>
			<h2>As soon as <strong>Dagny marked</strong> a new issue, he got a notification by email and now he is reviewing Dagny's comments, snapshot of the buggy part of the website and technical details - what device, OS and browser Dagny was using.As soon as Dagny marked a new issue, he got a notification by email and now he is reviewing Dagny's comments, snapshot of the buggy part of the website and technical details - what device, OS and browser Dagny was using.</h2>
		</div>
	{/if}


	<!--filter-->
	{if (!isset($items) and $catalog.current_cat_id neq 0) or isset($filterArray) }

	<div class="filter compact">
		<div class="filter-row">
		<!--category-->
		{*<input type="hidden" name="category" value="{$filterArray.c[0]}" />*}
		<!--END category-->

		<!--price-->
		<div class="block to_compact">
			<p>Сортировка по цене</p>
			<div class="selector">
				<label>
					<input type="radio" name="sortbyprice" {foreach from=$filterArray.p item=id} {if $id eq '0'}checked="checked"{/if} {/foreach} {if !isset($filterArray.p)}checked="checked"{/if} data-type="p" data-value="0"/>
					Сначала дешевые
				</label>
			</div>
			<div class="selector">
				<label>
					<input type="radio" name="sortbyprice" {foreach from=$filterArray.p item=id} {if $id eq '1'}checked="checked"{/if} {/foreach} data-type="p" data-value="1"/>
					Сначала дорогие
				</label>
			</div>

			<div class="selector to_compact">
				<!--price range-->
				<span class="price_from">
					<div>
						<span>От</span>
						<input type="text" name="price_min" value="{if isset($filterArray.pr[0])}{$filterArray.pr[0]}{else}0{/if}" />
					</div>
				</span>
				<span class="price_to">
					<div>
						<span>До</span>
						<input type="text" name="price_max" value="{if isset($filterArray.pr[1])}{$filterArray.pr[1]}{else}1000{/if}" />
					</div>
				</span>
				<!--END price range-->

				<div class="clear"></div>

				<div id="price_range_slider">
					<div class="buble_min"></div>
					<div class="buble_max"></div>
				</div>
			</div>

		</div>
		<!--END price-->

		<!--brands-->
		{if isset($brandsArray)}
		<div class="block brands">
			<p>Производители</p>
			{foreach from=$brandsArray item=brand}
				<div class="selector">
					<input type="checkbox" {foreach from=$filterArray.b item=id} {if $id eq $brand.id}checked="checked"{/if} {/foreach} data-type="b" data-value="{$brand.id}" />
					<a href="{$brand.full_url}">{$brand.name|escape}</a>
				</div>
			{/foreach}
			<div class="clear"></div>
			<a href="#" class="show_all_brands">Показать все({$brandsCount})</a>
			<a href="#" class="hide_brands">Только популярные</a>
		</div>
		{/if}
		<!--END brands-->

		<!--types-->
		{if isset($typesArray)}
		<div class="block types">
			<p>Типы и характеристики</p>
			{foreach from=$typesArray item=type}
				<div class="selector">
					<label>
						<input type="checkbox" {foreach from=$filterArray.t item=id} {if $id eq $type.id}checked="checked"{/if} {/foreach} data-type="t" data-value="{$type.id}" />
						{$type.name|escape}
					</label>


					{foreach from=$attributesArray key=type_id item=data}
						{if $type_id eq  $type.id} 
							<a href="#" class="show_attr" title="Параметры"></a>

							<div class="block attributes">
							{foreach from=$data key=attr_id item=attr}
								<div class="selector">
									<label>{$attr.name|escape} {if $attr.units neq ''}({$attr.units|escape}){/if}</label>
									<select data-type="{$type.id}" data-attr="{$attr_id}">
										<option value="0">--</option>
										{foreach from=$attr.values key=value_id item=value}
										<option  {foreach from=$filterArray.attr key=attrId item=valueId} {if $attrId eq $attr_id && $valueId eq $value_id}selected="selected"{/if} {/foreach} value="{$value_id}">{$value|escape}</option>
										{/foreach}
									</select>
									
								</div>		
							{/foreach}
						</div>
						{/if}
					
					{/foreach}

				</div>
			{/foreach}
			<div class="clear"></div>
			<a href="#" class="show_all_types">Показать все({$typesCount})</a>
			<a href="#" class="hide_types">Показать меньше</a>
		</div>
		{/if}

		<!--END types-->
		<div class="clear"></div>

		<a href="#" class="button filter_apply">Показать</a>
		<a href="#" class="button filter_reset">Сбросить</a>
		<a href="#" class="toggle_filter"></a>
	
	</div>
	</div>
	<div class="clear"></div>
	{/if}
	<!--END filter-->



	{if isset($categories) and $catalog.current_cat_id eq 0 and !isset($filterArray) }
		<div class="cat_list">
			
			<h1>Отдых должен приносить <strong>удовольствие</strong></h1>
			<h2>Здесь <strong>может</strong> располагаться какой-то очень важный текст.</h2>

			{include file="cat_list.menu.tpl" }
			<div class="clear"></div>
		</div>

	{else}

		<div class="left_list_menu">
			
			<ul>
				<li>
					<a class="outer" title="Перейти на страницу выбора категорий" href="{$catalog.catalog_page_url}">Категории</a>
					{include file="cat_list_all.menu.tpl" }
				</li>
			</ul>
			
			{if isset($categories)}
				{include file="cat_list.menu.tpl" }
			{/if}

			<div class="clear"></div>

			<ul>
				<li>
					<a class="outer" title="Перейти на страницу списка производителей" href="{$catalog.brandsPage}">Производители</a>
					{if isset($fullBrandsList)}
					<ul class="all_brands">
						{foreach from=$fullBrandsList item=brand}
						<li>
							<a title="Перейти на страницу производителя &laquo;{$brand.name|escape}&raquo;" href="{$brand.full_url}">{$brand.name|escape}</a>
						</li>
						{/foreach}
					</ul>
					{/if}
				</li>
			</ul>
			
			{if isset($brandsArray)}
			<ul class="brands">
				{foreach from=$brandsArray item=brand}
				<li>
					<a title="Перейти на страницу производителя &laquo;{$brand.name|escape}&raquo;" href="{$brand.full_url}">{$brand.name|escape}</a>
				</li>
				{/foreach}
				<div class="clear"></div>
			</ul>
			{/if}

		</div>	
		
	{/if}



	{if !isset( $items )}<p>Товаров не найдено.</p>{/if}
	{if isset( $items ) and ($catalog.current_cat_id neq 0 or isset($filterArray))}
		
		<ul class="list_items" data-mode="{if isset($viewMode)}{$viewMode}{else}list{/if}">
		{foreach from=$items item=item}
			<li>
				<a href="{$item.full_item_url}" title="{$item.meta_title|escape}" class="item_name">{$item.name|escape}</a>

				<a href="{$item.full_item_url}" title="{$item.meta_title|escape}" class="item_logo">
					
					{if $item.item_logo neq '' }
						<img src="{$item.item_logo}"/>
					{else}
						<div class="no_photo"></div>
					{/if}


					{if $item.is_new eq '1'}
						<div class="new"></div>
					{elseif $item.is_sale eq '1'}
						<div class="sale"></div>
					{elseif $item.is_best eq '1'}
						<div class="best"></div>
					{elseif $item.is_markdown eq '1'}
						<div class="mark"></div>
					{/if}

					{if $item.discount neq '0' and $item.is_sale eq '1'}<div class="percent">-{$item.discount}%</div>{/if}
				</a>
				
				

				<div class="description">
					
					
					{if isset ($item.attributes)}
					<ul class="attributes">
					{foreach from=$item.attributes item=attr}
						{if $attr.in_list eq '1'}
						<li title="{$attr.attribute_name|escape}:&nbsp;{$attr.attribute_value|escape}&nbsp;{$attr.attribute_units|escape}">
							<i>&nbsp;</i>
							<span>{$attr.attribute_name|escape}:&nbsp;</span>
							<b>{$attr.attribute_value|escape}&nbsp;{$attr.attribute_units|escape}</b>
						</li>
						{/if}
					{/foreach}
					</ul>
					{/if}	
					
					<span class="desc">{$item.item_quick_desc|escape}</span>
					
					{*
					<a href="{$item.full_cat_url}" title="Смотреть все товары категории &laquo;{$item.category_name|escape}&raquo;" class="category">{$item.category_name|escape}</a>
					<a href="{$item.full_brand_url}" title="Перейти на страницу производителя" class="brand">{$item.brand_name}</a>
					*}

				</div>
				

				

				<div class="buyblock">
					<a href="#" class="buy price" data-id="{$item.id}">

						{if $item.is_sale eq '1'}
							<span class="old_price">{$item.old_price}&nbsp;{$catalog.currency_symbol}</span>						
							<span class="new_price">{$item.price}&nbsp;{$catalog.currency_symbol}</span>
						{else}
							{$item.price}&nbsp;{$catalog.currency_symbol}
						{/if}
						<span class="go-order">Оформить</span>
					</a>
					<a href="#" class="buy" data-id="{$item.id}">{pasteWord name="plug_index_catalog_buy"}</a>
					<a href="#" class="del_from_compare" data-id="{$item.id}">Убрать из сравнения</a>
					<a href="#" class="add_to_compare" data-id="{$item.id}">Добавить в сравнение</a>
					<!--<img src="{$item.qrcode}/M/8/3/FFCCCC/000000"/>-->

				</div>

				
				{if $item.in_stock eq '0'}
					<span class="not_in_stock">Нет в наличии</span>
				{/if}




			</li>


		{/foreach}
		<div class="clear"></div>
		</ul>
		
		{if isset($navArray)}
			<ul class="catalog_nav">
			{foreach from=$navArray key=key item=item}
				<li>
					<a href="{$item.href}" class="{$item.class}" title="{$item.title|escape}">{$item.text}</a>
				</li>
			{/foreach}
			<div class="clear"></div>
			</ul>
		{/if}
		<div class="clear"></div>		
	{/if}
	


	<div class="clear"></div>

</div>
{/strip}