<?php /* Smarty version 2.6.27, created on 2014-01-22 11:40:19
         compiled from add_item.tpl */ ?>
<div class="add_item">
	<form name="plug_catalog_add_item">
		<div class="block">
			
			<p>Добавление нового товара</p>
			
			<div class="field">
				<label>Название товара</label>
				<div class="padding">
					<input type="text" name="name" />
				</div>
			</div>
	
			<div class="field">
				<label>Линк товара</label>
				<div class="padding">
					<input type="text" name="link" />
				</div>
			</div>		
			
		</div>
		
		<div class="block">
			<button type="button" name="addItem">Добавить товар</button>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

<script type="text/javascript"><?php echo 'catalog.addItemTplInit();'; ?>
</script>