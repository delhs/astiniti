<?php /* Smarty version 2.6.27, created on 2014-02-03 21:33:21
         compiled from panel.filemanager.tpl */ ?>
<h1>Файловый менеджер</h1>

<div id="elfinder" class="static"></div>


<input type="hidden" name="connector_url" value="<?php echo $this->_tpl_vars['url']; ?>
" />


<?php echo '
<script type="text/javascript">
	var opts = {
			url : "'; ?>
<?php echo $this->_tpl_vars['url']; ?>
<?php echo '",
			lang: \'ru\',
			getFileCallback:function(file){
				return;
			}
	}	

	var elf = $(\'#elfinder\').elfinder(opts).elfinder(\'instance\');
	$( "#elfinder" ).resizable("destroy");
	$(window).resize(function(){
		$("#elfinder.static").css({ height: $(".state").outerHeight(true) - 52+ "px"})
		$("#elfinder.static .elfinder-workzone").css({height: $(".state").outerHeight(true) -57 - 52 + "px"})
	});
</script>
'; ?>
