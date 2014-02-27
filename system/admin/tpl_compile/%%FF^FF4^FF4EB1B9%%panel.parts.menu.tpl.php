<?php /* Smarty version 2.6.27, created on 2014-01-19 22:02:30
         compiled from panel.parts.menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'panel.parts.menu.tpl', 3, false),)), $this); ?>
<ul><?php $_from = $this->_tpl_vars['partitions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><!--
--><li><?php if ($this->_tpl_vars['item']['id'] != 1): ?><a href="#" class="node_ctrl plus" data-id="<?php echo $this->_tpl_vars['item']['id']; ?>
"></a><?php endif; ?><!--
		--><a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" data-get="page" data-action="<?php echo $this->_tpl_vars['item']['id']; ?>
" class="<?php if ($this->_tpl_vars['item']['off'] == '1'): ?>disabled<?php endif; ?><?php if ($this->_tpl_vars['item']['in_menu'] == '0'): ?> hidden<?php endif; ?><?php if (isset ( $this->_tpl_vars['active'] ) && $this->_tpl_vars['active'] == $this->_tpl_vars['item']['id']): ?> act<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</a><?php if ($this->_tpl_vars['item']['off'] == '1'): ?><span class="hlp" title="В настройках данного раздела отмечен пункт &laquo;Не доступен&raquo;. Это означает, что при попытке перейти на данный раздел будет сгенерирована ошибка 404 - страница не найдена"></span><?php endif; ?><?php if ($this->_tpl_vars['item']['in_menu'] == '0'): ?><span class="hlp" title="В настройках данного раздела отмечен пункт &laquo;Скрыть из главного меню&raquo;. Это означает, что раздел не показывается в главном меню разделов сайта"></span><?php endif; ?><!--
		--><?php if ($this->_tpl_vars['item']['id'] != 1): ?><!--
		--><?php if ($this->_tpl_vars['createSubSections'] == 1): ?><a href="#" class="add" title="Добавить подраздел." data-pid="<?php echo $this->_tpl_vars['item']['id']; ?>
" ></a><?php endif; ?><!--
		--></a><a href="#" class="delete" title="Удалить раздел." data-id="<?php echo $this->_tpl_vars['item']['id']; ?>
" data-name="<?php echo $this->_tpl_vars['item']['name']; ?>
"></a><!--
		--><?php endif; ?><!--
		
		--><?php if ($this->_tpl_vars['item']['id'] == 1): ?><!--
		--><a href="#" class="add" title="Добавить новый корневой раздел." data-pid="<?php echo $this->_tpl_vars['item']['id']; ?>
"></a><!--
		--><?php endif; ?><!--
		
		--><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "panel.parts.menu.tpl", 'smarty_include_vars' => array('partitions' => $this->_tpl_vars['item']['childNodes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><!--
	--></li><!--
--><?php endforeach; endif; unset($_from); ?></ul>