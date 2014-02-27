<?php /* Smarty version 2.6.27, created on 2014-02-10 18:01:50
         compiled from cat_list_analogs.tpl */ ?>
<div class="plug_catalog categories">
	<div class="cat_list">
	<?php if (! isset ( $this->_tpl_vars['cat_not_exists'] )): ?>
		<p>Выберите нужную категорию, кликнув по ней, а затем, щелкните по товарам, которые будут являться аналогами</p>
		<div class="form">
			<div class="block toolbar">
				<a href="#" class="hide">Свернуть все</a><!--
				--><a href="#" class="show">Развернуть все</a><!--
				--><a href="#" class="invert">Инвертировать</a>
			</div>
			
			<div class="block categories">
				<span>Каталог</span>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cat_list.menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<div class="clear"></div>
			</div>
			
		</div>
	</div>
	
	
	<?php else: ?>
		<p>Список категорий пуст</p>
	<?php endif; ?>
</div>