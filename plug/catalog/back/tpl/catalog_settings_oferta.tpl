<form name="catalog_settings_oferta">
	<div class="block">
		<p>Договор оферта</p>
		
		<div class="field">
			<label>Текст договора оферты</label>
			<div class="padding">
				<textarea name="oferta_html" data-type="editor">{$oferta.oferta_html}</textarea>
			</div>
		</div>

	</div>
			

	<div class="block">
		<button type="button" name="save_oferta_settings">Сохранить изменения</button>
		<div class="clear"></div>
	</div>
</form>

{literal}<script type="text/javascript">catalog.catalogSettingsOfertsTplInit();</script>{/literal}