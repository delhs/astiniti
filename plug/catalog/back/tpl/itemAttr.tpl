					{if isset($itemAttr)}
						{foreach from=$itemAttr item=attr}
						<div class="field">
							<label>{$attr.attribute_name|escape}&nbsp;{if $attr.attribute_units neq ''}({$attr.attribute_units}){/if}</label>
							{*if $attr.attribute_selector eq 'select'*}
								<div class="padding">
									<select data-id="{$attr.attr_id}" name="attribute_{$attr.attr_id}">
									<option value="0">--Не выбрано--</option>
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<option {if $attr.value_id eq $attrData.id}selected="selected"{/if} value="{$attrData.id}">{$attrData.value|escape}</option>
									{/foreach}
									</select>
								</div>
							{*/if*}
							
							{*
							
							{if $attr.attribute_selector eq 'radiobutton'}
								<div class="padding">
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<input {if $attr.value_id eq $attrData.id}checked="checked"{/if} type="radio" value="{$attrData.id}" name="attribute_{$attr.attr_id}" />{$attrData.value|escape}
									{/foreach}
								</div>
							{/if}	
							*}
							
							{*
							{if $attr.attribute_selector eq 'checkbox'}
								<div class="padding">
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<input {if $attr.value_id eq $attrData.id}checked="checked"{/if} type="checkbox" value="{$attrData.id}" name="attribute_{$attr.attr_id}" />{$attrData.value|escape}
									{/foreach}
								</div>
							{/if}
							*}
							
							{*
							{if $attr.attribute_selector eq 'string'}
								<b>string</b>
							{/if}							
							*}
							
						</div>
						{/foreach}
					{else}
					<span>Атрибутов нет</span>
					{/if}