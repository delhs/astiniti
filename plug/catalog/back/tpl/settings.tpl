<div class="settings">
	
	<p>Основные настройки</p>

		<form name="catalog_settings">
	
			<div class="block">
				<div class="field">
					<label>Цена</label>
					<div class="padding">
						<input type="text" name="price" value="{$item.price}" />
					</div>
				</div>
			</div>
			
			<div class="block">
				<button type="button" name="saveCatalogSettings">Сохранить</button>
				<div class="clear"></div>
			</div>
			
		</form>
	
	
</div>

<script type="text/javascript">catalog.settingsTplInit();</script>