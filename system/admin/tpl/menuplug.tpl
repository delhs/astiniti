<ul class="mmenu">{foreach from=$plugNamesArray key=key item=item}<li><!--
	--><a href="#" data-panel="plugins" data-action="{$item.plug_name}" >{$item.plug_name_ru|escape}</a><!--
	--></li>{/foreach}</ul>