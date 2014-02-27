<?php /* Smarty version 2.6.27, created on 2014-01-19 22:02:28
         compiled from menuplug.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'menuplug.tpl', 2, false),)), $this); ?>
<ul class="mmenu"><?php $_from = $this->_tpl_vars['plugNamesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><li><!--
	--><a href="#" data-panel="plugins" data-action="<?php echo $this->_tpl_vars['item']['plug_name']; ?>
" ><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['plug_name_ru'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a><!--
	--></li><?php endforeach; endif; unset($_from); ?></ul>