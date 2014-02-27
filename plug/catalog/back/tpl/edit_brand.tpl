<div class="catalog">
	<form name="edit_brand">
		<p>Редактирование бренда</p>
		
		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field inf">
				<label>ID бренда</label>
				<div class="padding">
					<span>{$brand.id}</span>
				</div>
			</div>	
			
			<div class="field inf">
				<label>ID бренда в 1С</label>
				<div class="padding">
					<span>{if $brand.external_id neq ''}{$brand.external_id|escape}{else}&ndash;{/if}</span>
				</div>
			</div>
			
			<p></p>
				

			<input type="hidden" name="id" value="{$brand.id}" />
			
			<div class="field">
				<label>Название бренда<span class="hlp" title="Название бренда, которое будет отбражаться в списке брендов"></span></label>
				<div class="padding">
					<input type="text" name="name" value="{$brand.name|escape}" />
				</div>
			</div>
			
			<div class="field">
				<label>Линк бренда</label>
				<div class="padding">
					<input type="text" name="link" value="{$brand.link}" />
				</div>
			</div>		
			
			<div class="field">
				<label>Официальная страница<span class="hlp" title="Ссылка на официальную страницу бренда"></span></label>
				<div class="padding">
					<input type="text" name="offsite" value="{$brand.offsite}" />
				</div>
			</div>

			<div class="field">
				<label>Краткое описание</label>
				<div class="padding">
					<textarea name="brand_quick_desc">{$brand.brand_quick_desc}</textarea>
				</div>
			</div>	
			
			<div class="field">
				<label>Полное описание</label>
				<div class="padding">
					<textarea name="brand_descr">{$brand.brand_descr}</textarea>
				</div>
			</div>			
			
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head -opened">Изображение</div>
			<div class="spoiler-body">
			
				<div class="block">
					<div class="field">
						<p>Изображение бренда<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 2:1. Рекомендуемый размер {$brandLogosSizer[0][0]}х{$brandLogosSizer[0][1]}</i></p>
						<div class="brand_logo{if isset($brand.full_logo_src)} exist{/if}"><img src="{if isset($brand.full_logo_src)}{$brand.full_logo_src}{/if}" /></div>
						<button name="removeBrandLogo" {if !isset($brand.full_logo_src)}class="hidden"{/if}>Удалить</button>
						<input type="file" id="brand_logo" name="brand_logo" />
					</div>
				</div>
			
			</div>
		</div>
		
		<div class="spoiler">
			<div class="spoiler-head">Параметры видимости</div>
			<div class="spoiler-body">
			
				<div class="block">
				
					<div class="field">
						<label>Не доступен<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти на страницу бренда, будет сгенерирована ошибка 404, которая означает, что такой страницы не существует. Помимо этого, бренд будет скрыт из списка брендов"></span></label>
						<div class="padding">
							<input type="checkbox" name="disabled" value="1" {if $brand.disabled eq '1'}checked="checked"{/if}/>
						</div>
					</div>		
							
					<div class="field">
						<label>Скрыть из списка брендов<span class="hlp" title="Если отмечен этот пункт, то бренд будет скрыт из списка брендов, но будет доступен при обращении к нему"></span></label>
						<div class="padding">
							<input type="checkbox" name="hide_in_list" value="1" {if $brand.hide_in_list eq '1'}checked="checked"{/if}/>
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
						<label>ID бренда в 1C</label>
						<div class="padding">
							<input type="text" name="external_id" value="{$brand.external_id|escape}"/>
						</div>
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
							<input type="text" name="meta_title" value="{$brand.meta_title|escape}"/>
						</div>
					</div>
					<div class="field">
						<label>Мета тег "KEYWORDS"<span class="hlp" title="Это ключевые слова, которые отражают общую суть того, что представлено на странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_keywords">{$brand.meta_keywords}</textarea>
						</div>
					</div>
					
					<div class="field">
						<label>Мета тег "DESCRIPTION"<span class="hlp" title="Это описание страницы, которое видит поисковая система"></span></label>
						<div class="padding">
							<textarea class="highlight" name="meta_description">{$brand.meta_description}</textarea>
						</div>
					</div>
		
					<div class="field">
						<label>Прочие мета данные<span class="hlp" title="Данное поле позволяет добавить любой html, css или javascript код в секцию &laquo;HEAD&raquo; на данной странице"></span></label>
						<div class="padding">
							<textarea class="highlight" name="extra_meta">{$brand.extra_meta}</textarea>
						</div>
					</div>			
					
					<div class="clear"></div>
				</div>
			
			</div>
		</div>
		
		<div class="block">
			<button type="button" name="saveEditedBrand">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript">{literal}catalog.editBrandTplInit();{/literal}</script>