<?php /* Smarty version 2.6.27, created on 2014-01-06 13:39:44
         compiled from email_sett_tpl.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'email_sett_tpl.tpl', 23, false),)), $this); ?>
<div class="email_sett">
	<form name="plug_mail_me_recipient_email_sett">
		<div class="block">
			<p>Шаблон E-mail сообщения получателя</p>

			<div class="field">
				<label>E-mail адрес отправителя</label>
				<div class="padding">
					<input type="text" name="email_from_address" value="<?php echo $this->_tpl_vars['emailSettings']['email_from_address']; ?>
" />
				</div>
			</div>

			<div class="field">
				<label>E-mail для ответа</label>
				<div class="padding">
					<input type="text" name="email_reply" value="<?php echo $this->_tpl_vars['emailSettings']['email_reply']; ?>
" />
				</div>
			</div>

			<div class="field">
				<label>Имя отправителя</label>
				<div class="padding">
					<input type="text" name="email_from_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['emailSettings']['email_from_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>

			<div class="field">
				<label>Тема письма</label>
				<div class="padding">
					<input type="text" name="email_subject" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['emailSettings']['email_subject'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
				</div>
			</div>

			<div class="field">
				<label>Текст письма</label>
				<div class="padding">
					<textarea name="text" data-type="editor"><?php echo $this->_tpl_vars['emailSettings']['email_template']; ?>
</textarea>
				</div>
			</div>

			<div class="field">
				<label>Псевдокод</label>
				<div class="padding">
					%name% - <i>Имя пользователя, отправившего пистьмо (может достигать до 50 символов).</i>
				</div>
				<div class="padding">
					%email% - <i>E-mail адрес пользователя, отправившего пистьмо.</i>
				</div>
				<div class="padding">
					%text% - <i>Текст письма пользователя (может достигать до 900 символов).</i>
				</div>
			</div>

		</div>

		<div class="block">
			<button type="button" name="save">Сохранить</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
<?php echo '<script type="text/javascript">mailme.emailSettTplInit();</script>'; ?>