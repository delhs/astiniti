<?php /* Smarty version 2.6.27, created on 2014-01-22 14:46:56
         compiled from panel.parts.add.tpl */ ?>
<div class="panel_partsett">
	<h1>Параметры нового раздела</h1>
	
	<form name="partsett">
	
		<div class="block">
			<p>Основные параметры</p>
			
			<div class="field">
				<label>Название раздела<span class="hlp" title="Это название, которое будет отображаться в главном меню разделов сайта."></span></label>
				<div class="padding">
					<input type="text" name="name" value="<?php echo $this->_tpl_vars['part']['name']; ?>
" />
				</div>
			</div>
			
			<div class="field">
				<label>Краткое описание<span class="hlp" title="Этот текст будет выводиться в атрибуте &laquo;title&raquo; главного меню разделов сайта"></span></label>
				<div class="padding">
					<input type="text" name="quick_desc" value="<?php echo $this->_tpl_vars['part']['quick_desc']; ?>
" />
				</div>
			</div>				
			
			<?php if ($this->_tpl_vars['part']['id'] != 1): ?>
				<div class="field">
					<label>Линк страницы<span class="hlp" title="Это адрес, по которому данная страниц будет доступна. В данный момент полная ссылка на страницу выглядит так: &laquo;<?php echo $this->_tpl_vars['hostname']; ?>
<?php echo $this->_tpl_vars['part']['url']; ?>
&raquo;, где &laquo;<?php echo $this->_tpl_vars['part']['link']; ?>
&raquo; и есть линк этой страницы. Линк страницы может содержать только латинские буквы, цифры, символ &laquo;_&raquo; и символ &laquo;-&raquo;"></span></label>
					<div class="padding">
						<input type="text" name="link" value="<?php echo $this->_tpl_vars['part']['link']; ?>
" />
					</div>
				</div>
			<?php endif; ?>
			
			<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['part']['pid']; ?>
" />
			
			
			<div class="clear"></div>
		</div>
		
		<button type="button" name="save">Создать раздел</button>
		
		<div class="clear"></div>
	</form>
</div>
	




<?php echo '
<script type="text/javascript">
	

	
	$("input[name=\'expires_date\']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:\'dd.mm.yy\'});
	$("input[name=\'edit_date\']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:\'dd.mm.yy\'});

	$("input[name=\'name\']").select();
	
	
	admin.tmp = \'translateEvent\';
	$("input[name=\'name\']").blur(function(){
		if( admin.tmp == undefined ) return;
		admin.tmp = undefined;
	});
	
	$("input[name=\'name\']").keyup(function(){
		if( admin.tmp == undefined ) return;
		var link = $(this).val();
		link = link.translit().toLowerCase();
		$("input[name=\'link\']").val( link );
	});
	

	
	$("input[type=\'radio\'], input[type=\'checkbox\'], input[type=\'file\'], select").styler({selectSmartPositioning:true, browseText:"Обзор..."});
	
	$("form[name=\'partsett\'] button[name=\'save\']").click(function(){
		var form = $("form[name=\'partsett\']");
		var data = form.serialize()+"&action=addPartSave";
		admin.block();
		admin.ajax( \'parts\', data, form, function( json ){
			var jsonObj = $.parseJSON( json );
			admin.setPageId( jsonObj.pageId , function(){
				admin.block();
				admin.loadPanel(\'partsett\');
			});
		});
	});

	$("form[name=\'partsett\'] a.removeicons").click(function(){
		
		$("form[name=\'partsett\'] .icons img").remove();
		
		if( $("form[name=\'partsett\'] .icons div").size()==0 )
		{
			$("form[name=\'partsett\'] .icons").append(\'<div class="icon40x40">\');
			$("form[name=\'partsett\'] .icons").append(\'<div class="icon100x100">\');
			$("form[name=\'partsett\'] .icons").append(\'<div class="icon140x140">\');
		}
		
		$("form[name=\'partsett\'] input[name=\'icon40x40\']").val("remove");
		$("form[name=\'partsett\'] input[name=\'icon100x100\']").val("remove");
		$("form[name=\'partsett\'] input[name=\'icon140x140\']").val("remove");
		
		return false;
	});
	
	function partssettFileUploadIcon(){
		admin.block();
		$("form[name=\'partsett\'] .icons span").show();
		admin.upload( \'fileupload\', \'partsett\', \'uploadIcon\', \'partssettFileUploadResponseIcon\' );
	}
	
	function partssettFileUploadResponseIcon( filename ){
		if(filename==\'\' || filename==\'failed\' ){
			$("form[name=\'partsett\'] .icons span").hide();
			admin.unblock();
			return;
		}
		
		$("form[name=\'partsett\'] .icons div").remove();
		
		if( $("form[name=\'partsett\'] .icons img").size()==0 ){
			$("form[name=\'partsett\'] .icons").append(\'<img class="icon40x40" src="/admin/temp/40x40\'+filename+\'">\');
			$("form[name=\'partsett\'] .icons").append(\'<img class="icon100x100" src="/admin/temp/100x100\'+filename+\'">\');
			$("form[name=\'partsett\'] .icons").append(\'<img class="icon140x140" src="/admin/temp/140x140\'+filename+\'">\');
		}else{
			$("form[name=\'partsett\'] .icons img.icon40x40").attr("src", "/admin/temp/40x40"+filename);
			$("form[name=\'partsett\'] .icons img.icon100x100").attr("src", "/admin/temp/100x100"+filename);
			$("form[name=\'partsett\'] .icons img.icon140x140").attr("src", "/admin/temp/140x140"+filename);
		}
		
		$("form[name=\'partsett\'] input[name=\'icon40x40\']").val("40x40"+filename);
		$("form[name=\'partsett\'] input[name=\'icon100x100\']").val("100x100"+filename);
		$("form[name=\'partsett\'] input[name=\'icon140x140\']").val("140x140"+filename);
		
		$("form[name=\'partsett\'] .icons span").hide();
		admin.unblock();
	}	
	
	$(document).unbind(\'keydown\');
	$("form[name=\'partsett\'] input[type=\'text\']").bind(\'keydown\', function(e){
		if(e.keyCode == 13){
			$("form[name=\'partsett\'] button[name=\'save\']").click();
		}
	});

	
	
</script>
'; ?>