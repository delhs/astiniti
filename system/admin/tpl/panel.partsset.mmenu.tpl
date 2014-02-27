{capture name=p assign=lastPid}$lastPid=0{/capture}
{foreach from=$pidsArray key=key item=item}
	{capture name=arrow assign=arrows}{section name=lpr loop=$level}&emsp;{/section}{/capture}
		<option value="{$item.id}" {if $part.pid eq $item.id} selected="selected"{/if} {if $part.id eq $item.id}disabled="disabled"{/if} >{$arrows}{$item.name|escape|htmlspecialchars}</option>
	{include file="panel.partsset.mmenu.tpl" pidsArray=$item.childNodes level=$level+1}
{/foreach}