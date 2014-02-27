<?php /* Smarty version 2.6.27, created on 2014-01-22 11:57:17
         compiled from panel.plugins.tpl */ ?>
<?php if (isset ( $this->_tpl_vars['plugNamesArray'] )): ?>

	<div class="panel_plugins">
		<h1>Плагины проекта</h1>
		<?php if ($this->_tpl_vars['plugNamesArray']): ?>
		<ul class="plug_list">
			<?php $_from = $this->_tpl_vars['plugNamesArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
			<li>
				<a href="#" data-action="<?php echo $this->_tpl_vars['item']['plug_name']; ?>
"><span><?php echo $this->_tpl_vars['item']['plug_name_ru']; ?>
</span></a>
			</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
		<?php endif; ?>
	</div>
	
	
	<?php echo '
		<script type="text/javascript">
			$(".panel_plugins ul.plug_list a[data-action]").click(function(e){
				e.preventDefault();
				var plugName = $(this).attr("data-action");
				admin.loadPanel(\'plugins\', {action:plugName});
			});
		</script>
	'; ?>

<?php else: ?>
	<h1>Плагины проекта</h1>
	<p>В вашем проекте нет установленных плагинов</p>
<?php endif; ?>