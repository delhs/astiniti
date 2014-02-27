{strip}
<ul>
	{foreach from=$fullCatList key=key item=cat}
	<li>
		<a href="{$cat.full_url}">{$cat.name|escape}</a>

		{include file="cat_list_all.menu.tpl" fullCatList=$cat.childNodes}

	</li>
	{/foreach}
</ul>
{/strip}