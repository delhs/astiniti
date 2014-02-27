<?php /* Smarty version 2.6.27, created on 2014-02-19 11:15:02
         compiled from comments_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'comments_list.tpl', 33, false),)), $this); ?>
<ul class="comments_list">
	<?php $_from = $this->_tpl_vars['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['comment']):
?>
		
		<li>
			<div class="comment">
				<div class="comment_inner">

					<div class="toolbar">
						<div class="tool">
							<input type="checkbox" name="hide" id="hide_<?php echo $this->_tpl_vars['comment']['id']; ?>
" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
" value="0" <?php if ($this->_tpl_vars['comment']['hide'] == '0'): ?>checked="checked"<?php endif; ?> />
							<label for="hide_<?php echo $this->_tpl_vars['comment']['id']; ?>
">Публиковать</label>
						</div>

						<div class="tool">
							<span class="icon edit" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
"></span>
							<a href="#" class="edit" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
">Редактировать</a>
						</div>

						<div class="tool">
							<span class="icon delete" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
"></span>
							<a href="#" class="delete" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
">Удалить</a>
						</div>

						<div class="tool">
							<span class="icon ireply" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
"></span>
							<a href="#" class="ireply" data-id="<?php echo $this->_tpl_vars['comment']['id']; ?>
">Ответить</a>
						</div>

						<div class="clear"></div>
					</div>

					<div class="info">
						<span class="username"><?php echo ((is_array($_tmp=$this->_tpl_vars['comment']['user_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
						<span class="date"><?php echo $this->_tpl_vars['comment']['comment_date']; ?>
 г. <?php echo $this->_tpl_vars['comment']['comment_time']; ?>
</span>
					</div>

					<span class="text"><?php echo $this->_tpl_vars['comment']['comment_text']; ?>
</span>

					<div class="clear"></div>
				</div>

				<?php if (isset ( $this->_tpl_vars['comment']['childNodes'] )): ?>
					<div class="reply">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "comments_list.tpl", 'smarty_include_vars' => array('comments' => $this->_tpl_vars['comment']['childNodes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
				<?php endif; ?>

			</div>

		</li>
		
	<?php endforeach; endif; unset($_from); ?>

</ul>