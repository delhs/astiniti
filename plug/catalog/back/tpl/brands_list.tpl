<div class="brands_list">
	
	{if !isset($empty)}
	<p>Список брендов</p>

	<p><i>Чтобы изменить порядок следования брендов, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши</i></p>
	{else}
	<p>Список брендов пуст</p>
	{/if}

	{if isset($brandsArray)}
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
					<td>ID&nbsp;бренда</td>
					<td>ID&nbsp;бренда&nbsp;в&nbsp;1С</td>
					<td>Линк&nbsp;бренда</td>
					<td>Название</td>
					<td class="small">Скрыт</td>
					<td class="small">Не доступен</td>
					<td class="small">&nbsp;</td>
				</tr>
			</thead>
			<tbody>
				{foreach from=$brandsArray key=key item=brand}
				<tr data-id="{$brand.id}">
					<td>{if isset($brand.full_logo_src)}<img src="{$brand.full_logo_src}" />{else}<div class="no_photo"></div>{/if}</td>
					<td>{$brand.id}</td>
					<td>{$brand.external_id}</td>
					<td>{$brand.link}</td>
					<td>{$brand.name}</td>
					<td data-action="hide_in_list"><span class="icon redcheck{if $brand.hide_in_list eq 1} on{/if}"></span></td>
					<td data-action="disabled"><span class="icon redcheck{if $brand.disabled eq 1} on{/if}"></span></td>	
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
	
	{/if}
	

	
	
</div>

<script type="text/javascript">catalog.brandsListTplInit();</script>