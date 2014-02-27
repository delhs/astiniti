{if isset( $plugNamesArray )}

	<div class="panel_plugins">
		<h1>Плагины проекта</h1>
		{if $plugNamesArray}
		<ul class="plug_list">
			{foreach from=$plugNamesArray key=key item=item}
			<li>
				<a href="#" data-action="{$item.plug_name}"><span>{$item.plug_name_ru}</span></a>
			</li>
			{/foreach}
		</ul>
		{/if}
	</div>
	
	
	{literal}
		<script type="text/javascript">
			$(".panel_plugins ul.plug_list a[data-action]").click(function(e){
				e.preventDefault();
				var plugName = $(this).attr("data-action");
				admin.loadPanel('plugins', {action:plugName});
			});
		</script>
	{/literal}
{else}
	<h1>Плагины проекта</h1>
	<p>В вашем проекте нет установленных плагинов</p>
{/if}