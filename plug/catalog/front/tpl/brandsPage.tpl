{strip}
<div class="plug_catalog catalog_brands_page">
	<h1>Производители</h1>

	{if isset($brandsArray)}
		<ul>
		{foreach from=$brandsArray key=letter item=brands}
				{foreach from=$brands item=brand}
				<li>
					<a href="{$brand.url}" title="{$brand.name|escape}">
						{if $brand.brand_logo neq ''}<img src="{$brand.brand_logo}" />
						{else}{$brand.name|escape}{/if}
					</a>
				</li>	
				{/foreach}
		{/foreach}
		</ul>
	{else}
	<p>Производителей не найдено.</p>
	{/if}
</div>
{/strip}