<form name="clear_catalog">
	<div class="block">
		<p>Очистка каталога</p>

		<div class="field">
			<label>Очистить список категорий</label>
			<div class="padding">
				<input type="checkbox" name="categories"/>
			</div>
		</div>

		<div class="field">
			<label>Очистить список товаров</label>
			<div class="padding">
				<input type="checkbox" name="items"/>
			</div>
		</div>

		<div class="field">
			<label>Очистить список брендов</label>
			<div class="padding">
				<input type="checkbox" name="brands"/>
			</div>
		</div>

		<div class="field">
			<label>Очистить список типов</label>
			<div class="padding">
				<input type="checkbox" name="types"/>
			</div>
		</div>

	</div>

	<div class="block">
		<button type="button" name="clear_catalog">Очистить</button>
		<div class="clear"></div>
	</div>

</form>
<script type="text/javascript">{literal}catalog.clearCatalogTplInit();{/literal}</script>