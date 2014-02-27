<?php /* Smarty version 2.6.27, created on 2014-02-06 23:00:28
         compiled from mmenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mmenu.tpl', 1, false),)), $this); ?>
<?php echo '<ul class="mmenu">'; ?><?php $_from = $this->_tpl_vars['mmenuArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?><?php echo '<li><a href="'; ?><?php echo $this->_tpl_vars['item']['url']; ?><?php echo '" target="'; ?><?php echo $this->_tpl_vars['item']['target']; ?><?php echo '"'; ?><?php if ($this->_tpl_vars['item']['quick_desc'] != ''): ?><?php echo ' title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['quick_desc'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?><?php echo '"'; ?><?php endif; ?><?php echo ' class="'; ?><?php echo $this->_tpl_vars['item']['active']; ?><?php echo '"'; ?><?php if (isset ( $this->_tpl_vars['item']['inAirAdminMode'] )): ?><?php echo ' data-action="'; ?><?php echo $this->_tpl_vars['item']['id']; ?><?php echo '"'; ?><?php endif; ?><?php echo '>'; ?><?php echo $this->_tpl_vars['item']['name']; ?><?php echo ''; ?><?php if (isset ( $this->_tpl_vars['item']['icon'] )): ?><?php echo '<span class="coloranimate"></span><span style="background:url('; ?><?php echo $this->_tpl_vars['item']['icon']; ?><?php echo ');"></span>'; ?><?php endif; ?><?php echo '</a>'; ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mmenu.tpl", 'smarty_include_vars' => array('mmenuArray' => $this->_tpl_vars['item']['childNodes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php echo '</li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ul>'; ?>