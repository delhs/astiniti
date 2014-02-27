<?php /* Smarty version 2.6.27, created on 2014-02-17 13:19:53
         compiled from itemAttr.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'itemAttr.tpl', 4, false),)), $this); ?>
					<?php if (isset ( $this->_tpl_vars['itemAttr'] )): ?>
						<?php $_from = $this->_tpl_vars['itemAttr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr']):
?>
						<div class="field">
							<label><?php echo ((is_array($_tmp=$this->_tpl_vars['attr']['attribute_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;<?php if ($this->_tpl_vars['attr']['attribute_units'] != ''): ?>(<?php echo $this->_tpl_vars['attr']['attribute_units']; ?>
)<?php endif; ?></label>
															<div class="padding">
									<select data-id="<?php echo $this->_tpl_vars['attr']['attr_id']; ?>
" name="attribute_<?php echo $this->_tpl_vars['attr']['attr_id']; ?>
">
									<option value="0">--Не выбрано--</option>
									<?php $_from = $this->_tpl_vars['attr']['attributesValues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['attr_id'] => $this->_tpl_vars['attrData']):
?>
										<option <?php if ($this->_tpl_vars['attr']['value_id'] == $this->_tpl_vars['attrData']['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $this->_tpl_vars['attrData']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['attrData']['value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
									<?php endforeach; endif; unset($_from); ?>
									</select>
								</div>
														
														
														
														
						</div>
						<?php endforeach; endif; unset($_from); ?>
					<?php else: ?>
					<span>Атрибутов нет</span>
					<?php endif; ?>