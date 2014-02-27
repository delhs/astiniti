<div class="items_types">
	
	<p>Типы товаров</p>
	
	<form name="item_types">
		<div class="block">
				<p>Список типов</p>
			{if isset($typesArray)}
				{foreach from=$typesArray item=types}
				<div class="field">
					<div class="nopadding">
						<input type="text" data_t="type" name="{$types.atr_name}" data-id="{$types.id}" value="{$types.name|escape}" /><a href="#" title="Удалить тип товара" class="delete"></a>
					</div>
				</div>
				{/foreach}
			{/if}
			
		</div>
		
		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add_item_type">Добавить новый тип</button>
			<div class="clear"></div>
		</div>
	</form>
	
</div>
{literal}<script type="text/javascript">catalog.itemTypesTplInit();</script>{/literal}