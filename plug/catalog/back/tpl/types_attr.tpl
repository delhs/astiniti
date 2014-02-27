<div class="catalog_types_attr">
	<form name="plug_catalog_types_attr">
		<div class="block">
			<p>Атрибуты типов</p>
		
			{if isset($typesArray)}
				{foreach from=$typesArray item=type}
				<div class="spoiler">
					<div class="spoiler-head">{$type.name|escape}</div>
					<div class="spoiler-body">
						<div class="block">
							<div class="field">
								<table type-id="{$type.id}" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<td>Название</td>
											<td>Единицы измерения</td>
											<td class="small">Отображать у товаров</td>
											<td class="small">Отображать в фильтре</td>
											<td class="small">&nbsp;</td>
										</tr>
									</thead>
									<tbody>
									{foreach from=$type.attributes item=attribute}
										<tr data-attr="{$attribute.id}" data-type="{$type.id}">
											<td>
												<input type="text" name="name" value="{$attribute.name|escape}" />
											</td>
											<td>
												<input type="text" name="units" value="{$attribute.units|escape}" />
											</td>
											<td class="in_list">
												<span class="icon yellowcheck {if $attribute.in_list eq '1'}on{/if}"></span>
											</td>
											<td class="in_filter">
												<span class="icon greencheck {if $attribute.in_filter eq '1'}on{/if}"></span>
											</td>
											<td class="delete">
												<span class="icon reddelete hover"></span>
											</td>
										</tr>	
										
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="block">
							<button type="button" type-id="{$type.id}" name="save_type">Сохранить атрибуты типа</button>
							<button type="button" type-id="{$type.id}" name="add_type">Добавить атрибут</button>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				{/foreach}
			{/if}	
		</div>
	</form>
</div>
<script type="text/javascript">{literal}catalog.itemTypesAttrTplInit();{/literal}</script>