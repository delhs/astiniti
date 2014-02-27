{strip}
<ul class="categories">

	{foreach from=$categories key=key item=cat}
	<li>
		{if $catalog.current_cat_id eq 0 and !isset($filterArray)}
			
			<a href="{$cat.full_url}">
				
				<div class="image">
					{if isset($cat.full_logo_src)}
						<img width="200" height="200" src="{$cat.full_logo_src}" />
					{else}
						<div class="no_photo"></div>
					{/if}
				</div>

				<div class="descrblock">
					<div class="name"><strong>{$cat.name|escape}</strong></div>
					<div class="descr">
						As soon as Dagny marked a new issue, he got a notification by email and now he is reviewing Dagny's comments, snapshot of the buggy part of the website and technical details - what device, OS and browser Dagny was using.As soon as Dagny marked a new issue, he got a notification by email and now he is reviewing Dagny's comments, snapshot of the buggy part of the website and technical details - what device, OS and browser Dagny was using.
					</div>
				</div>

				<div class="lineblock"></div>

			</a>

		{else}
			<a href="{$cat.full_url}">{$cat.name|escape}</a>
		{/if}

		{include file="cat_list.menu.tpl" categories=$cat.childNodes}

	</li>
	{/foreach}
</ul>
{/strip}