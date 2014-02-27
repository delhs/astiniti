<?php /* Smarty version 2.6.27, created on 2014-02-14 17:15:39
         compiled from panel.modules.view.tpl */ ?>
<h1>Настройка модуля &laquo;<?php echo $this->_tpl_vars['xModuleArray']['nameRu']; ?>
&raquo;</h1>
<?php echo $this->_tpl_vars['xModuleArray']['buffer']; ?>


<?php echo '
	<script type="text/javascript">
		admin.mod.loadedId = '; ?>
<?php echo $this->_tpl_vars['xModuleArray']['id']; ?>
<?php echo ';
	</script>
'; ?>