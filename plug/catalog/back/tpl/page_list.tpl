{capture name=p assign=lastPid}$lastPid=0{/capture}
{foreach from=$pageList key=key item=page}
	{capture name=arrow assign=arrows}{section name=lpr loop=$level}&emsp;{/section}{/capture}
		<option value="{$page.id}" {if $catalogSettings.catalog_page_id eq $page.id} selected="selected"{/if}>{$arrows}{$page.name|escape|htmlspecialchars}</option>
	{include file="page_list.tpl" pageList=$page.childNodes level=$level+1}
{/foreach}