<div class="edit_item">
	<form name="plug_catalog_edit_item">
		<p>Редактирование товара</p>
		
		<input type="hidden" name="id" value="{$item.id}" />
		<input type="hidden" name="raiting" value="{$item.raiting}" />
		

		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field inf">
				<label>ID товара</label>
				<div class="padding">
					<span>{$item.id}</span>
				</div>
			</div>	
			
			<div class="field inf">
				<label>ID товара в 1С</label>
				<div class="padding">
					<span>{if $item.external_id neq ''}{$item.external_id|escape}{else}&ndash;{/if}</span>
				</div>
			</div>
			
			<p></p>
			
			<div class="field">
				<label>Название товара<span class="hlp" title="Название товара, которое будет отбражаться в списке товаров"></span></label>
				<div class="padding">
					<input type="text" name="name" value="{$item.name|escape}" />
				</div>
			</div>
			
			<div class="field">
				<label>Линк товара<span class="hlp" title="Это адрес, по которому данный товар будет доступен. В данный момент полная ссылка на товар выглядит так: &laquo;{$fulllink}&raquo;, где &laquo;{$item.link}&raquo; и есть линк этого товара. Линк товара может содержать только латинские буквы, цифры, символ &laquo;_&raquo; и символ &laquo;-&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="link" value="{$item.link}" />
				</div>
			</div>		
			
			<div class="field">
				<label>Артикул</label>
				<div class="padding">
					<input type="text" name="articul" value="{$item.articul|escape}" />
				</div>
			</div>

			<div class="field">
				<label>Цена</label>
				<div class="padding">
					<input type="text" name="price" value="{$item.price}" />
				</div>
			</div>	
			
			<div class="field">
				<label>Старая цена<span class="hlp" title="Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="old_price" value="{$item.old_price}" />
				</div>
			</div>				
			
			<div class="field">
				<label>Скидка в процентах<span class="hlp" title="Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="text" name="discount" value="{$item.discount}" {if $item.discount_calc_auto eq '1'}disabled="disabled"{/if} />
				</div>
			</div>	

			<div class="field">
				<label>Расчитывать скидку автоматически<span class="hlp" title=" Если отметить этот пункт, то процент скидки будет расчитываться автоматически руководствуясь старой и новой ценой. Это значение используется, если в разделе &laquo;Популярность товара&raquo; отмечен пункт &laquo;Распродажа&raquo;"></span></label>
				<div class="padding">
					<input type="checkbox" name="discount_calc_auto" value="1" {if $item.discount_calc_auto eq '1'}checked="checked"{/if} />
				</div>
			</div>

			{if isset($categoriesArray)}
			<div class="field">
				<label>Категория</label>
				<div class="padding">
					<select name="cat_id">
						<option value="0">--Вне категории--</option>
						{foreach from=$categoriesArray item=catData}
						<option {if $item.cat_id eq $catData.id}selected="selected"{/if} value="{$catData.id}">{$catData.name|escape}</option>
						{/foreach}
					</select>
				</div>
			</div>					
			{/if}
			
			{if isset($brandsArray)}
			<div class="field">
				<label>Бренд</label>
				<div class="padding">
					<select name="brand_id">
						<option value="0">--Не имеет бренда--</option>
						{foreach from=$brandsArray item=brandData}
						<option {if $item.brand_id eq $brandData.id}selected="selected"{/if} value="{$brandData.id}">{$brandData.name|escape}</option>
						{/foreach}
					</select>
				</div>
			</div>					
			{/if}					
			
			{if isset($typesArray)}
			<div class="field">
				<label>Тип</label>
				<div class="padding">
					<select name="type_id">
						<option value="0">--Не имеет типа--</option>
						{foreach from=$typesArray item=typeData}
						<option {if $item.type_id eq $typeData.id}selected="selected"{/if} value="{$typeData.id}">{$typeData.name|escape}</option>
						{/foreach}
					</select>
				</div>
			</div>	
			{/if}
			
			<div class="field">
				<label>Краткое описание</label>
				<div class="padding">
					<textarea name="item_quick_desc">{$item.item_quick_desc}</textarea>
				</div>
			</div>	
			
			<div class="field">
				<label>Полное описание</label>
				<div class="padding">
					<textarea name="item_desc">{$item.item_desc}</textarea>
				</div>
			</div>			
			
			<div class="field">
				<label>Товар есть в наличии</label>
				<div class="padding">
					<input {if $item.in_stock eq '1'}checked="checked"{/if} type="radio" name="in_stock" value="1"/>
				</div>
			</div>	
			
			<div class="field">
				<label>Товар отсутствует</label>
				<div class="padding">
					<input {if $item.in_stock eq '0'}checked="checked"{/if} type="radio" name="in_stock" value="0"/>
				</div>
			</div>	
			
			<div class="field">
				<label>Только под закз</label>
				<div class="padding">
					<input {if $item.in_stock eq '2'}checked="checked"{/if} type="radio" name="in_stock" value="2"/>
				</div>
			</div>	

			<div class="field">
				<label>Дата и время создания</label>
				<div class="padding">
					<input type="text" name="create_date_time" value="{$item.create_date_time}"/>
				</div>
			</div>

			<div class="field">
				<label>Дата и время последнего обновления<span class="hlp" title="Дата и время обновления устанавливается автоматически после нажатия на кнопку &laquo;Сохранить&raquo;, либо после обновления из &laquo;1С&raquo;. Данное значение отправляется в заголовок страницы &laquo;Last-Modified&raquo;(данная информация актуальна для SEO специалистов)"></span></label>
				<div class="padding">
					<span style="margin-top: 7px;display: inline-block;">{$item.update_date_time}</span>
				</div>
			</div>

			<div class="field">
				<label>Expires<span class="hlp" title="Днная информация актуальна для SEO специалистов. Заголовок &laquo;Expires&raquo; будет сформирован и отправлен следующим образом: Дата последнего обновления + 1 месяц."></span></label>
				<div class="padding">
					<span style="margin-top: 7px;display: inline-block;">{$item.expires_date_time}</span>
				</div>
			</div>

		</div>
		

		<div class="spoiler">
			<div class="spoiler-head -opened">Дополнительные атрибуты товара</div>
			<div class="spoiler-body">

				<div class="block attributes">
					{if isset($itemAttr)}
						{foreach from=$itemAttr item=attr}
						<div class="field">
							<label>{$attr.attribute_name|escape}&nbsp;{if $attr.attribute_units neq ''}({$attr.attribute_units}){/if}</label>
							{*if $attr.attribute_selector eq 'select'*}
								<div class="padding">
									<select data-id="{$attr.attr_id}" name="attribute_{$attr.attr_id}">
									<option value="0">--Не выбрано--</option>
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<option {if $attr.value_id eq $attrData.id}selected="selected"{/if} value="{$attrData.id}">{$attrData.value}</option>
									{/foreach}
									</select>
								</div>
							{*/if*}
							
							{*
							
							{if $attr.attribute_selector eq 'radiobutton'}
								<div class="padding">
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<input {if $attr.value_id eq $attrData.id}checked="checked"{/if} type="radio" value="{$attrData.id}" name="attribute_{$attr.attr_id}" />{$attrData.value}
									{/foreach}
								</div>
							{/if}	
							*}
							
							{*
							{if $attr.attribute_selector eq 'checkbox'}
								<div class="padding">
									{foreach from=$attr.attributesValues key=attr_id item=attrData}
										<input {if $attr.value_id eq $attrData.id}checked="checked"{/if} type="checkbox" value="{$attrData.id}" name="attribute_{$attr.attr_id}" />{$attrData.value}
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
				</div>
				

			</div>
		</div>		
		
		<div class="spoiler">
			<div class="spoiler-head -opened">Изображения</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<p>Основное изображение товара<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 1:1. Рекомендуемый размер {$itemLogosSizer[0][0]}х{$itemLogosSizer[0][1]}</i></p>
						<div class="item_logo{if isset($item.full_logo_src)} exist{/if}"><img src="{if isset($item.full_logo_src)}{$item.full_logo_src}{/if}" /></div>
						<button name="removeItemLogo" {if !isset($item.full_logo_src)}class="hidden"{/if}>Удалить</button>
						<input type="file" id="item_logo" name="item_logo" />
					</div>
				</div>
				
				
				<div class="block">
					<div class="field">
						<p>Другие изображения товара<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с любым соотношением сторон</i></p>
						<div class="item_other_images">
							{foreach from=$item.other_images item=imgData}
								<div>
									<img class="exist" src="{$imgData.filename}"/>
									<a href="#" data-id="{$imgData.id}" class="remove_other_image exist"></a>
								</div>
							{/foreach}
						</div>
						<div class="clear"></div>						
						<input type="file" id="item_other_images" name="item_other_images" />
					</div>
				</div>				
				
			
			</div>
		</div>
		
	
		<div class="spoiler">
			<div class="spoiler-head">Аналоги{if isset($analogsCount)}&nbsp;<b class="count">({$analogsCount})</b>{/if}</div>
			<div class="spoiler-body">
			
				<div class="block">
					
					<div class="field">
						<ul class="analogs">
						{if isset($analogs)}{foreach from=$analogs item=analog}
							<li><a href="#" item-id="{$analog.analog_id}">{$analog.name}</a><a href="#" class="delete" title="Удалить из списка аналогов"></a></li>
						{/foreach}{/if}
						</ul>
					</div>	
					
					<div class="field">
						<button type="button" name="add_analog">Добавить аналог</button>
						<div class="clear"></div>
					</div>						
				</div>
			
			</div>
		</div>			
		
		<div class="spoiler">
			<div class="spoiler-head">Сопутствующие товары{if isset($accompanyingCount)}&nbsp;<b class="count">({$accompanyingCount})</b>{/if}</div>
			<div class="spoiler-body">
			
				<div class="block">
					
					<div class="field">
						<ul class="accompanying">
						{if isset($accompanying)}{foreach from=$accompanying item=accomp}
							<li><a href="#" item-id="{$accomp.accompanying_id}">{$accomp.name}</a><a href="#" class="delete" title="Удалить из списка супутствующих товаров"></a></li>
						{/foreach}{/if}
						</ul>
					</div>	
					
					<div class="field">
						<button type="button" name="add_accomp">Добавить сопутствующий товар</button>
						<div class="clear"></div>
					</div>						
				</div>
			
			</div>
		</div>	
	
	
	
		<div class="spoiler">
			<div class="spoiler-head">Условия доставки и способы оплаты</div>
			<div class="spoiler-body">
				
				<div class="block">
				
					<div class="field">
						<label>Возможность доставки по городу</label>
						<div class="padding">
							<input type="checkbox" name="delivery_city" {if $item.delivery_city eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Возможность доставки по области</label>
						<div class="padding">
							<input type="checkbox" name="delivery_region" {if $item.delivery_region eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Возможность доставки в другие регионы</label>
						<div class="padding">
							<input type="checkbox" name="delivery_out_region" {if $item.delivery_out_region eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>
				</div>
				
				<div class="block">
				
					<div class="field">
						<label>Оплата наличными при получении</label>
						<div class="padding">
							<input type="checkbox" name="pay_cash_person" {if $item.pay_cash_person eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>		
					
					<div class="field">
						<label>Оплата картой при получении</label>
						<div class="padding">
							<input type="checkbox" name="pay_card_person" {if $item.pay_card_person eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Оплата картой на сайте</label>
						<div class="padding">
							<input type="checkbox" name="pay_card_web" {if $item.pay_card_web eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Оплата электронными деньгами на сайте</label>
						<div class="padding">
							<input type="checkbox" name="pay_web_money" {if $item.pay_web_money eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>
					
					<div class="field">
						<label>Безналичный расчет(Юр. лица)</label>
						<div class="padding">
							<input type="checkbox" name="pay_entity" {if $item.pay_entity eq '1'}checked="checked"{/if} value="1"/>
						</div>
					</div>

				</div>
				
			</div>
		</div>
	
		<div class="spoiler">
			<div class="spoiler-head">Популярность товара</div>
			<div class="spoiler-body">
				
				<div class="block">
				
					<div class="field">
						<label>Обычный товар</label>
						<div class="padding">
							<input type="radio" name="popular" {if $item.is_best eq '0' && $item.is_sale eq '0' && $item.is_new eq '0' && $item.is_markdown eq '0'}checked="checked"{/if} value="null"/>
						</div>
					</div>

					<div class="field">
						<label>Лидер продаж</label>
						<div class="padding">
							<input type="radio" name="popular" {if $item.is_best eq '1'}checked="checked"{/if} value="is_best"/>
						</div>
					</div>
					
					<div class="field">
						<label>Распродажа</label>
						<div class="padding">
							<input type="radio" name="popular" {if $item.is_sale eq '1'}checked="checked"{/if} value="is_sale"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Новинка</label>
						<div class="padding">
							<input type="radio" name="popular" {if $item.is_new eq '1'}checked="checked"{/if} value="is_new"/>
						</div>
					</div>	
					
					<div class="field">
						<label>Уцененный</label>
						<div class="padding">
							<input type="radio" name="popular" {if $item.is_markdown eq '1'}checked="checked"{/if} value="is_markdown"/>
						</div>
					</div>
					
					<div class="field">
						<label>Рейтинг</label>
						<div class="padding">
							<a class="raitingnull" title="Установить нулевой рейтинг" href="#"></a>
							<ul class="raiting">
								{section name="i" start=1 step=1 loop=6} 
								<li {if $item.raiting >= $smarty.section.i.index}class="set"{/if}><a href="#"></a></li>
								{/section}
							</ul>
							<div class="clear"></div>
						</div>
					</div>					
					
				</div>
				
			</div>
		</div>


		<div class="spoiler">
			<div class="spoiler-head">Параметры видимости</div>
			<div class="spoiler-body">
			
				<div class="block">
				
					<div class="field">
						<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на страницу товара, будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, товар будет скрыт из списка товаров"></span></label>
						<div class="padding">
							<input type="checkbox" name="disabled" value="1" {if $item.disabled eq '1'}checked="checked"{/if}/>
						</div>
					</div>		
							
					<div class="field">
						<label>Скрыть из списка товаров<span class="hlp" title="Если отмечен этот пункт, то товар будет скрыт из списка товаров, но будет доступен при обращении к нему"></span></label>
						<div class="padding">
							<input type="checkbox" name="hide_in_list" value="1" {if $item.hide_in_list eq '1'}checked="checked"{/if}/>
						</div>
					</div>	
				</div>
			
			</div>
		</div>		
		
		
		<div class="spoiler">
			<div class="spoiler-head">Дополнительные параметры</div>
			<div class="spoiler-body">
				<div class="block">
					<div class="field">
						<label>ID товара в 1C</label>
						<div class="padding">
							<input type="text" name="external_id" value="{$item.external_id|escape}"/>
						</div>
					</div>
				</div>
			</div>
		</div>		
		

		<div class="spoiler">
			<div class="spoiler-head">Комментарии</div>
			<div class="spoiler-body">
				<div class="block">
					
					<div class="field">
						<a href="#" id="newComment">Написать комментарий</a>
					</div>

					<div class="field">
						{if isset($comments)}
							{include file="comments_list.tpl"}
						{else}
							<p>Комментариев нет</p>
						{/if}
					</div>

				</div>
			</div>
		</div>


		<div class="spoiler">
			<div class="spoiler-head">Meta теги</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<label>Мета тег "TITLE"<span class="hlp" title="Это мета тег &laquo;Title&raquo;, текст которого выводится на вкладке страницы в браузере"></span></label>
						<div class="padding">
							<input type="text" name="meta_title" value="{$item.meta_title|escape}"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords">{$item.meta_keywords}</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description">{$item.meta_description}</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta">{$item.extra_meta}</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
			
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="saveEditedItem">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript">{literal}catalog.editItemTplInit();{/literal}</script>