<?php /* Smarty version 2.6.27, created on 2014-02-05 10:40:05
         compiled from page_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'page_list.tpl', 4, false),array('modifier', 'htmlspecialchars', 'page_list.tpl', 4, false),)), $this); ?>
<?php ob_start(); ?>$lastPid=0<?php $this->_smarty_vars['capture']['p'] = ob_get_contents();  $this->assign('lastPid', ob_get_contents());ob_end_clean(); ?>
<?php $_from = $this->_tpl_vars['pageList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['page']):
?>
	<?php ob_start(); ?><?php unset($this->_sections['lpr']);
$this->_sections['lpr']['name'] = 'lpr';
$this->_sections['lpr']['loop'] = is_array($_loop=$this->_tpl_vars['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['lpr']['show'] = true;
$this->_sections['lpr']['max'] = $this->_sections['lpr']['loop'];
$this->_sections['lpr']['step'] = 1;
$this->_sections['lpr']['start'] = $this->_sections['lpr']['step'] > 0 ? 0 : $this->_sections['lpr']['loop']-1;
if ($this->_sections['lpr']['show']) {
    $this->_sections['lpr']['total'] = $this->_sections['lpr']['loop'];
    if ($this->_sections['lpr']['total'] == 0)
        $this->_sections['lpr']['show'] = false;
} else
    $this->_sections['lpr']['total'] = 0;
if ($this->_sections['lpr']['show']):

            for ($this->_sections['lpr']['index'] = $this->_sections['lpr']['start'], $this->_sections['lpr']['iteration'] = 1;
                 $this->_sections['lpr']['iteration'] <= $this->_sections['lpr']['total'];
                 $this->_sections['lpr']['index'] += $this->_sections['lpr']['step'], $this->_sections['lpr']['iteration']++):
$this->_sections['lpr']['rownum'] = $this->_sections['lpr']['iteration'];
$this->_sections['lpr']['index_prev'] = $this->_sections['lpr']['index'] - $this->_sections['lpr']['step'];
$this->_sections['lpr']['index_next'] = $this->_sections['lpr']['index'] + $this->_sections['lpr']['step'];
$this->_sections['lpr']['first']      = ($this->_sections['lpr']['iteration'] == 1);
$this->_sections['lpr']['last']       = ($this->_sections['lpr']['iteration'] == $this->_sections['lpr']['total']);
?>&emsp;<?php endfor; endif; ?><?php $this->_smarty_vars['capture']['arrow'] = ob_get_contents();  $this->assign('arrows', ob_get_contents());ob_end_clean(); ?>
		<option value="<?php echo $this->_tpl_vars['page']['id']; ?>
" <?php if ($this->_tpl_vars['catalogSettings']['catalog_page_id'] == $this->_tpl_vars['page']['id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['arrows']; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['page']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
</option>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_list.tpl", 'smarty_include_vars' => array('pageList' => $this->_tpl_vars['page']['childNodes'],'level' => $this->_tpl_vars['level']+1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endforeach; endif; unset($_from); ?>