<div class="plug_catalog">
	<form name="plug_catalog_edit_cat">
		<p>Редактирование категории &laquo;{$cat.name|escape}&raquo;</p>
		
		<div class="block">
	
			<input type="hidden" name="pid" value="{$cat.pid}" />
			
			<div class="field">
				<label>Название категории<span class="hlp" title="Название категории, которое будет отбражаться в списке категорий"></span></label>
				<div class="padding">
					<input type="text" name="name" value="{$cat.name|escape}" />
				</div>
			</div>
	
			<div class="field">
				<label>Линк категории</label>
				<div class="padding">
					<input type="text" name="link" value="{$cat.link}"/>
				</div>
			</div>
			
			<div class="field">
				<label>Не доступна<span class="hlp" title="Если отмечен этот пункт, то при попытке перейти в данную категорию будет сгенерирована ошибка 404, которая означает, что такой категории не существует. Помимо этого, категория будет скрыта из списка категорий"></span></label>
				<div class="padding">
					<input type="checkbox" name="off" value="1" {if $cat.off eq '1'}checked="checked"{/if}/>
				</div>
			</div>	
			
			<div class="field">
				<label>Скрыть из списка категорий<span class="hlp" title="Если отмечен этот пункт, то категория будет скрыта из списка категорий, но будет доступна при обращении к ней"></span></label>
				<div class="padding">
					<input type="checkbox" name="hide" value="1" {if $cat.hide eq '1'}checked="checked"{/if}/>
				</div>
			</div>	
			
		</div>
	
		<div class="block">
			<div class="field">
				<p>Изображение категории<br/><i>Для загрузки принимаются файлы изображений(gif, png, jpg) с соотношением сторон 1:1. Рекомендуемый размер {$categoryLogosSizer[0][0]}х{$categoryLogosSizer[0][1]}</i></p>
				<div class="cat_logo{if isset($cat.full_logo_src)} exist{/if}"><img src="{if isset($cat.full_logo_src)}{$cat.full_logo_src}{/if}" /></div>
				<button name="removeCatLogo" {if !isset($cat.full_logo_src)}class="hidden"{/if}>Удалить</button>
				<input type="file" id="cat_logo" name="cat_logo" />
			</div>
		</div>

		<div class="block">
			<button type="button" name="saveEditedCat">Сохранить</button>
			<div class="clear"></div>
		</div>
		
	</form>

</div>

<script type="text/javascript">{literal}catalog.editCatTplInit();{/literal}</script>