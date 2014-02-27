{strip}
{if isset($itemsArray)}
	
<div class="mod_catalogitems">
	
	<h2>Заголовок</h2>
	
	<ul class="list_items">
		{foreach from=$itemsArray item=item}
			<li>
				<div class="item-front"></div>
				<div class="item-right"></div>
				<div class="item-bottom-one"></div>
				<div class="item-bottom-two"></div>
				<div class="item-bottom-three"></div>
			</li>
		{/foreach}
	</ul>
	<a class="prev controls" href="#"></a>
	<a class="next controls" href="#"></a>
	<div class="pager controls"><div></div></div>
	<div class="clear"></div>

</div>
{/if}
{/strip}