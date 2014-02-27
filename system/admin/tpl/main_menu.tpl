<ul id="main_menu" class="dmenu">

	<li><a class="yellow" href="#" data-panel="parts">Разделы</a>{*{include file="mmenu.tpl" }*}</li>
	<li><a class="yellow" href="#" data-panel="partsett">Настройки раздела</a></li>
	<li><a class="yellow" href="#" data-panel="content">Контент</a></li>
	{if isset($modNamesArray)}
	<li><a class="yellow" href="#" data-panel="modules">Модули</a></li>
	{/if}
	<li class="separator"></li>

	{if isset($plugNamesArray)}
		<li><a class="orange" href="#" data-panel="plugins">Плагины</a>{include file="menuplug.tpl"}</li>
		<li class="separator"></li>
	{/if}
	
	{if isset($registeredMmenuItemsArray)}
	{foreach from=$registeredMmenuItemsArray item=dataArray}
		<li><a class="{$dataArray.color}" data-registry="menu" href="#" data-panel="{$dataArray.dataPanel}" data-action="{$dataArray.dataAction}">{$dataArray.name}</a></li>
	{/foreach}
		<li class="separator"></li>
	{/if}
	
	<li><a class="purple" href="#" data-panel="words">Словари</a></li>
	<li><a class="purple" href="#" data-panel="settings">Настройки сайта</a></li>
	<li><a class="purple" href="#" data-panel="filemanager">Файловый менеджер</a></li>
	<li><a class="purple" href="#" data-panel="redirect">Перенаправления</a></li>
	<li class="separator"></li>
	<li>
		<a class="green" href="#" data-panel="users">Учетные записи</a>
		<ul class="mmenu">
			<li><a href="#" data-panel="users" data-action="users">Учетные записи</a></li>
			<li><a href="#" data-panel="users" data-action="adduser">Создать учетную запись</a></li>
			<li><a href="#" data-panel="users" data-action="groups">Группы</a></li>
			<li><a href="#" data-panel="users" data-action="addgroup">Создать группу</a></li>
		</ul>
	</li>
	{if !isset($developer)}<li><a class="green" href="#" data-panel="mydata">Мой пользователь</a></li>{/if}
	<li class="separator"></li>
	<li><a class="blue" href="#" data-panel="log">Журнал</a></li>
	<li><a class="blue" href="#" data-panel="backup">Восстановление</a></li>
	{if isset($developer)}
	<li class="separator"></li>
	<li>
		<a class="red" href="#" data-panel="helper">Разработка</a>
		<ul class="mmenu">
			<li><a href="#" data-panel="helper" data-action="createplugin">Создать плагин</a></li>
			<li><a href="#" data-panel="helper" data-action="createmodule">Создать модуль</a></li>
		</ul>
	</li>
	{/if}
	<li>&nbsp;</li>
</ul>