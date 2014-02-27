<?php /* Smarty version 2.6.27, created on 2014-02-19 11:37:44
         compiled from edit_comment_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'edit_comment_form.tpl', 7, false),)), $this); ?>
<form name="edit_comment">
	<div class="block">

		<div class="field">
			<label>Имя:</label>
			<div class="padding">
				<input type="text" name="user_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['comment']['user_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			</div>
		</div>
	
		<div class="field">
			<label>Дата и время:</label>
			<div class="padding">
				<input type="text" readonly="readonly" name="date_time" value="<?php echo $this->_tpl_vars['comment']['comment_date']; ?>
 <?php echo $this->_tpl_vars['comment']['comment_time']; ?>
" />
			</div>
		</div>
	
		<div class="field editorblock">
			<textarea name="comment_text"><?php echo $this->_tpl_vars['comment']['comment_text']; ?>
</textarea>
		</div>
	</div>

	<div class="block">
		<button name="saveEdited" type="button">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>
</form>