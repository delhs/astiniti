<div class="types_attr_vals">
	<form name="plug_catalog_types_attr_vals">
		<p>Значения атрибутов
			<br/><i>Чтобы добавить новый тип, перейдите в раздел <a class="goto_item_types" href="#">Список типов</a></i>
			<br/><i>Чтобы добавить типу атрибут, перейдите в раздел <a class="goto_types_attr" href="#">Атрибуты типов</a></i>
		</p>
		<div class="block">
			{if isset($typesArray)}
				{foreach from=$typesArray item=type}
				<div class="spoiler">
					<div class="spoiler-head">Тип&nbsp;&ndash;&nbsp;&laquo;{$type.name|escape}&raquo;</div>
					<div class="spoiler-body">
						<div class="block" data-type="{$type.id}">
							<div class="field">
								{if isset($type.attributes)}
								{foreach from=$type.attributes item=attribute}
								<div class="spoiler">
								<div class="spoiler-head">Атрибут&nbsp;&ndash;&nbsp;&laquo;{$attribute.name|escape}&raquo;{if $attribute.units neq ''}&nbsp;({$attribute.units|escape}){/if}</div>
									<div class="spoiler-body">
										<div class="block" data-attr-id="{$attribute.id}">
											<p>Возможные значения:</p>
											{foreach from=$attribute.values key=valueId item=value}
											<div class="field">
												<div class="nopadding">
													<input type="text" name="name" data-value="{$valueId}" value="{$value}" /><a href="#" title="Удалить значение атрибута" class="delete"></a>
												</div>
											</div>
											{/foreach}
										</div>
										
										<div class="block">
											<button type="button" type-id="{$type.id}" attr-id="{$attribute.id}" name="save_attr_vals">Сохранить значения для данного атрибута</button>
											<button type="button" type-id="{$type.id}" attr-id="{$attribute.id}" name="add_att_val">Добавить значение</button>
											<div class="clear"></div>
										</div>

									</div>
								</div>
								{/foreach}
								{else}
									<p>У данного типа нет ни одного атрибута. Прежде чем добавить значение, необходимо создать хотя бы один атрибут. Сделать это можно перейдя в раздел <a class="goto_types_attr" href="#">Атрибуты типов</a></p>
								{/if}
							</div>
						</div>
						

					</div>
				</div>
				{/foreach}
			{/if}	
		</div>
	</form>
</div>
<script type="text/javascript">{literal}catalog.editTypesAttrValsInit();{/literal}</script>