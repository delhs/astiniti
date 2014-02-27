<?php /* Smarty version 2.6.27, created on 2014-01-20 11:27:52
         compiled from add_cat.tpl */ ?>
<div class="plug_catalog">
	<form name="plug_catalog_add_cat">
		<div class="block">
			
			<p>Создание новой категории</p>
			
			<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
			
			<div class="field">
				<label>Название категории</label>
				<div class="padding">
					<input type="text" name="name" />
				</div>
			</div>
	
			<div class="field">
				<label>Линк категории</label>
				<div class="padding">
					<input type="text" name="link" />
				</div>
			</div>		
			
		</div>
		
		<div class="block">
			<button type="button" name="addCategory">Создать категорию</button>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

<script type="text/javascript"><?php echo 'catalog.addCatTplInit();'; ?>
</script>