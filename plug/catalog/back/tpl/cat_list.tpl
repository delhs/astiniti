<div class="cat_list">

{if !isset($cat_not_exists)}

	<p>Список категорий</p>
	<p><i>Чтобы изменить порядок следования категорий, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши</i></p>
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

{literal}<script type="text/javascript">catalog.catListTplInit();</script>{/literal}