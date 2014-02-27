<?php /* Smarty version 2.6.27, created on 2014-01-31 17:29:53
         compiled from mmenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mmenu.tpl', 1, false),)), $this); ?>
<ul class="mmenu"><?php $_from = $this->_tpl_vars['mmenuArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><li><a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" target="<?php echo $this->_tpl_vars['item']['target']; ?>
"<?php if ($this->_tpl_vars['item']['quick_desc'] != ''): ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['quick_desc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?> class="<?php echo $this->_tpl_vars['item']['active']; ?>
"<?php if (isset ( $this->_tpl_vars['item']['inAirAdminMode'] )): ?> data-action="<?php echo $this->_tpl_vars['item']['id']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
<?php if (isset ( $this->_tpl_vars['item']['icon'] )): ?><span class="coloranimate"></span><span style="background:url(<?php echo $this->_tpl_vars['item']['icon']; ?>
);"></span><?php endif; ?></a><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mmenu.tpl", 'smarty_include_vars' => array('mmenuArray' => $this->_tpl_vars['item']['childNodes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li><?php endforeach; endif; unset($_from); ?></ul>