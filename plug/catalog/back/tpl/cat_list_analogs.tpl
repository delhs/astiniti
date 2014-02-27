<div class="plug_catalog categories">
	<div class="cat_list">
	{if !isset($cat_not_exists)}
		<p>Выберите нужную категорию, кликнув по ней, а затем, щелкните по товарам, которые будут являться аналогами</p>
		<div class="form">
			<div class="block toolbar">
				<a href="#" class="hide">Свернуть все</a><!--
				--><a href="#" class="show">Развернуть все</a><!--
				--><a href="#" class="invert">Инвертировать</a>
			</div>
			
			<div class="block categories">
				<span>Каталог</span>
				{include file="cat_list.menu.tpl" }
				<div class="clear"></div>
			</div>
			
		</div>
	</div>
	
	
	{else}
		<p>Список категорий пуст</p>
	{/if}
</div>