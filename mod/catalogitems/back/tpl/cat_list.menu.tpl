{strip}
<ul>
	{foreach from=$categories key=key item=item}
	<li>
		
		<a href="#" class="node_ctrl plus" data-id="{$item.id}"></a>

		<a href="{$item.url}" data-get="page" data-action="{$item.id}" class="{if $item.off eq '1'}disabled{/if}{if $item.hide eq '1'} hidden{/if}{if isset($currentCatId) && $currentCatId eq $item.id} act{/if}">{$item.name|escape}</a>
		
		{if $item.off eq '1'}
			<span class="hlp" title="В настройках данной категории отмечен пункт &laquo;Не доступна&raquo;. Это означает, что при попытке перейти в данную категорию будет сгенерирована ошибка 404 - страница не найдена"></span>
		{/if}

		{if $item.hide eq '1'}
			<span class="hlp" title="В настройках данной категории отмечен пункт &laquo;Скрыть из списка категорий&raquo;. Это означает, что категория не показывается в списке категорий сайта"></span>
		{/if}
		
		<a href="#" class="edit" title="Редактировать категорию." data-id="{$item.id}" ></a>
		
		<a href="#" class="add" title="Добавить подкатегорию." data-pid="{$item.id}" ></a>

		<a href="#" class="delete" title="Удалить категорию." data-id="{$item.id}" data-name="{$item.name}"></a>
	
		{include file="cat_list.menu.tpl" categories=$item.childNodes}

	</li>
	{/foreach}
</ul>
{/strip}