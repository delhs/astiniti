<?php /* Smarty version 2.6.27, created on 2014-02-05 13:06:09
         compiled from catalog_settings_oferta.tpl */ ?>
<form name="catalog_settings_oferta">
	<div class="block">
		<p>Договор оферта</p>
		
		<div class="field">
			<label>Текст договора оферты</label>
			<div class="padding">
				<textarea name="oferta_html" data-type="editor"><?php echo $this->_tpl_vars['oferta']['oferta_html']; ?>
</textarea>
			</div>
		</div>

	</div>
			

	<div class="block">
		<button type="button" name="save_oferta_settings">Сохранить изменения</button>
		<div class="clear"></div>
	</div>
</form>

<?php echo '<script type="text/javascript">catalog.catalogSettingsOfertsTplInit();</script>'; ?>