{strip}
<div class="plug_catalog_brand_page">
		
		<div class="leftcoll">
			
			{if $brand.brand_logo neq ''}
			<img class="logo" src="{$brand.full_logo_src}" title="{$brand.name|escape}" alt="{$brand.name|escape}" />
			{else}
			<h1 class="logo">{$brand.name|escape}</h1>
			{/if}
			
			{if $brand.offsite neq '' }<div class="offsite">Оффициальный сайт производителя: <a target="_blank" href="{$brand.offsite}">{$brand.offsite}</a></div>{/if}
			
			{if isset( $brandCatList )}
			<div class="items">
				<h2>Товары этого производителя</h2>
				<ul>
				{foreach from=$brandCatList item=catArray}
					<li>
						{$catArray.cat_name|escape}
						<ul>
						{foreach from=$catArray.types item=arr}
							<li><a href="{$arr.url}">{$arr.type_name|escape}&nbsp;({$arr.count})</a></li>
						{/foreach}
						</ul>
					</li>
				{/foreach}
				</ul>
			</div>
			{/if}
		</div>
		<div class="rightcoll">
			<h1>{if $brand.brand_logo neq ''}{$brand.name|escape}{/if}</h1>
			{if $brand.brand_descr neq ''}<div>{$brand.brand_descr}</div>{/if}
		</div>
</div>
{/strip}