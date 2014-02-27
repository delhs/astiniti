<?php /* Smarty version 2.6.27, created on 2014-01-31 10:58:34
         compiled from main_menu.tpl */ ?>
<ul id="main_menu" class="dmenu">

	<li><a class="yellow" href="#" data-panel="parts">Разделы</a></li>
	<li><a class="yellow" href="#" data-panel="partsett">Настройки раздела</a></li>
	<li><a class="yellow" href="#" data-panel="content">Контент</a></li>
	<?php if (isset ( $this->_tpl_vars['modNamesArray'] )): ?>
	<li><a class="yellow" href="#" data-panel="modules">Модули</a></li>
	<?php endif; ?>
	<li class="separator"></li>

	<?php if (isset ( $this->_tpl_vars['plugNamesArray'] )): ?>
		<li><a class="orange" href="#" data-panel="plugins">Плагины</a><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menuplug.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li>
		<li class="separator"></li>
	<?php endif; ?>
	
	<?php if (isset ( $this->_tpl_vars['registeredMmenuItemsArray'] )): ?>
	<?php $_from = $this->_tpl_vars['registeredMmenuItemsArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dataArray']):
?>
		<li><a class="<?php echo $this->_tpl_vars['dataArray']['color']; ?>
" data-registry="menu" href="#" data-panel="<?php echo $this->_tpl_vars['dataArray']['dataPanel']; ?>
" data-action="<?php echo $this->_tpl_vars['dataArray']['dataAction']; ?>
"><?php echo $this->_tpl_vars['dataArray']['name']; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
		<li class="separator"></li>
	<?php endif; ?>
	
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
	<?php if (! isset ( $this->_tpl_vars['developer'] )): ?><li><a class="green" href="#" data-panel="mydata">Мой пользователь</a></li><?php endif; ?>
	<li class="separator"></li>
	<li><a class="blue" href="#" data-panel="log">Журнал</a></li>
	<li><a class="blue" href="#" data-panel="backup">Восстановление</a></li>
	<?php if (isset ( $this->_tpl_vars['developer'] )): ?>
	<li class="separator"></li>
	<li>
		<a class="red" href="#" data-panel="helper">Разработка</a>
		<ul class="mmenu">
			<li><a href="#" data-panel="helper" data-action="createplugin">Создать плагин</a></li>
			<li><a href="#" data-panel="helper" data-action="createmodule">Создать модуль</a></li>
		</ul>
	</li>
	<?php endif; ?>
	<li>&nbsp;</li>
</ul>