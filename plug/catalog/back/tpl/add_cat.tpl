<div class="plug_catalog">
	<form name="plug_catalog_add_cat">
		<div class="block">
			
			<p>Создание новой категории</p>
			
			<input type="hidden" name="pid" value="{$pid}" />
			
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

<script type="text/javascript">{literal}catalog.addCatTplInit();{/literal}</script>