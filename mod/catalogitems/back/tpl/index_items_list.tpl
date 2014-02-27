<div class="form">
	<div class="block">
		<p>Товары на главной странице</p>
		<div class="field">
			<a href="#" class="add_items">Добавить товары</a>
		</div>
		<ul class="index_list">
			{if isset($itemsArray)}
				{foreach from=$itemsArray item=item}
					<li>
						<a href="#" title="Перейти в каталог и открыть страницу товара" item-id="{$item.id}">{$item.name|escape}</a>
						<a href="#" class="delete" title="Удалить из списка"></a>
					</li>
				{/foreach}
			{/if}
		</ul>
	</div>

	<div class="block">
		<button type="button" name="save">Сохранить</button>
		<div class="clear"></div>
	</div>
</div>

{literal}<script type="text/javascript">modCatalogitems.indexItemsListTplInit();</script>{/literal}