<?php /* Smarty version 2.6.27, created on 2014-01-06 12:55:14
         compiled from departaments_tpl.tpl */ ?>
<div class="departaments">
	<form name="plug_mail_me_departaments">
		<div class="block">
			<p>Отделы</p>
			
			<table cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td>Название отдела</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					<?php if (isset ( $this->_tpl_vars['departaments'] )): ?>
						<?php $_from = $this->_tpl_vars['departaments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['departament']):
?>
						<tr>
							<td><input type="text" name="name" class="name" value="<?php echo $this->_tpl_vars['departament']['name']; ?>
" /></td>
							<td class="delete"><span class="icon" title="Удалить отдел"></span></td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add">Добавить отдел</button>
			<div class="clear"></div>
		</div>

	</form>
</div>
<?php echo '<script type="text/javascript">mailme.departamentsEmailTplInit();</script>'; ?>