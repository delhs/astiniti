<?php /* Smarty version 2.6.27, created on 2014-02-15 00:24:02
         compiled from cat_list_all.menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'cat_list_all.menu.tpl', 5, false),)), $this); ?>
<?php echo '<ul>'; ?><?php $_from = $this->_tpl_vars['fullCatList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['cat']):
?><?php echo '<li><a href="'; ?><?php echo $this->_tpl_vars['cat']['full_url']; ?><?php echo '">'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['cat']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '</a>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cat_list_all.menu.tpl", 'smarty_include_vars' => array('fullCatList' => $this->_tpl_vars['cat']['childNodes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul>'; ?>