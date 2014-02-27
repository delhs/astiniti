<?php /* Smarty version 2.6.27, created on 2014-01-06 13:15:56
         compiled from recipient_email_tpl.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'recipient_email_tpl.tpl', 30, false),)), $this); ?>
<div class="recipient_email">
	<form name="plug_mail_me_recipient_email">
		<div class="block">
			<p>Получатели E-mail сообщений</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Отдел</td>
						<td>E-mail получателя</td>
						<td>Имя получателя</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					<?php if (isset ( $this->_tpl_vars['recipients'] )): ?>
						<?php $_from = $this->_tpl_vars['recipients']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['recipient']):
?>
						<tr>
							<td>
								<select name="departament_id">
									<option value="0">--Не указано--</option>
									<?php if (isset ( $this->_tpl_vars['departaments'] )): ?>
										<?php $_from = $this->_tpl_vars['departaments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['departament']):
?>
											<option value="<?php echo $this->_tpl_vars['departament']['id']; ?>
" <?php if ($this->_tpl_vars['recipient']['departament_id'] == $this->_tpl_vars['departament']['id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['departament']['name']; ?>
</option>
										<?php endforeach; endif; unset($_from); ?>
									<?php endif; ?>
								</select>
							</td>
							<td><input type="text" name="email" class="email" value="<?php echo $this->_tpl_vars['recipient']['email']; ?>
" /></td>
							<td><input type="text" name="name" class="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['recipient']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
							<td class="delete"><span class="icon" title="Удалить получателя"></span></td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add">Добавить получателя</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
<?php echo '<script type="text/javascript">mailme.recipientEmailTplInit( '; ?>
<?php echo $this->_tpl_vars['departamentsList']; ?>
<?php echo ' );</script>'; ?>