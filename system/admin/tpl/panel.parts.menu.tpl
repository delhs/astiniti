<ul>{foreach from=$partitions key=key item=item}<!--
--><li>{if $item.id neq 1}<a href="#" class="node_ctrl plus" data-id="{$item.id}"></a>{/if}<!--
		--><a href="{$item.url}" data-get="page" data-action="{$item.id}" class="{if $item.off eq '1'}disabled{/if}{if $item.in_menu eq '0'} hidden{/if}{if isset($active) && $active eq $item.id} act{/if}">{$item.name|htmlspecialchars}</a>{if $item.off eq '1'}<span class="hlp" title="В настройках данного раздела отмечен пункт &laquo;Не доступен&raquo;. Это означает, что при попытке перейти на данный раздел будет сгенерирована ошибка 404 - страница не найдена"></span>{/if}{if $item.in_menu eq '0'}<span class="hlp" title="В настройках данного раздела отмечен пункт &laquo;Скрыть из главного меню&raquo;. Это означает, что раздел не показывается в главном меню разделов сайта"></span>{/if}<!--
		-->{if $item.id neq 1}<!--
		-->{if $createSubSections eq 1}<a href="#" class="add" title="Добавить подраздел." data-pid="{$item.id}" ></a>{/if}<!--
		--></a><a href="#" class="delete" title="Удалить раздел." data-id="{$item.id}" data-name="{$item.name}"></a><!--
		-->{/if}<!--
		
		-->{if $item.id eq 1}<!--
		--><a href="#" class="add" title="Добавить новый корневой раздел." data-pid="{$item.id}"></a><!--
		-->{/if}<!--
		
		-->{include file="panel.parts.menu.tpl" partitions=$item.childNodes}<!--
	--></li><!--
-->{/foreach}</ul>