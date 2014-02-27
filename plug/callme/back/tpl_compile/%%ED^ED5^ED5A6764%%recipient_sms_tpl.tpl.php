<?php /* Smarty version 2.6.27, created on 2013-12-17 12:43:08
         compiled from recipient_sms_tpl.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'recipient_sms_tpl.tpl', 19, false),)), $this); ?>
<div class="recipient_sms">
	<form name="plug_call_me_recipient_sms">
		<div class="block">
			<p>Получатели СМС сообщений</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Телефон получателя</td>
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
							<td><input type="text" name="phone" class="phone" value="<?php echo $this->_tpl_vars['recipient']['phone']; ?>
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
<?php echo '<script type="text/javascript">callme.recipientSmsTplInit();</script>'; ?>