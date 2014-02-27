<?php /* Smarty version 2.6.27, created on 2014-01-31 11:22:17
         compiled from panel.backup.tpl */ ?>
<div class="panel_backup">
	<h1>Восстановление</h1>

	<div class="form">
		<div class="block">
			<div class="field">
				<button type="button" name="createBackup">Создать&nbsp;резервную&nbsp;копию&nbsp;базы&nbsp;данных</button>
			</div>
		</div>
		
		<div class="block">
			<p>Резервные копии базы данных</p>
			<?php if (isset ( $this->_tpl_vars['files'] )): ?>
			<div class="field">
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>Дата&nbsp;создания</td>
							<td>Время&nbsp;создания</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php $_from = $this->_tpl_vars['files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
							<tr data-filename="<?php echo $this->_tpl_vars['file']['filename']; ?>
">
								<td><?php echo $this->_tpl_vars['file']['date']; ?>
</td>
								<td><?php echo $this->_tpl_vars['file']['time']; ?>
</td>
								<td class="fordelete"><span title="Удалить файл резервной копии" class="delete"></span></td>
							</tr>
						<?php endforeach; endif; unset($_from); ?>
					</tbody>
				</table>
			</div>
			<?php else: ?>
			<p><i>Резервные копии не найдены</i></p>
			<?php endif; ?>
		</div>		
	</div>
	
</div>

<?php echo '
<script type="text/javascript">
	$(".panel_backup button[name=\'createBackup\']").click(function(){
		admin.block();
		admin.ajax(\'backup\', {action:\'createBackup\'}, function(){
			admin.unblock();
			admin.reloadPanel();
		});
	});
	
	$(".panel_backup table tbody tr").hover(
		function(){
			$(this).addClass("hover");
		}, 
		function(){
			$(this).removeClass("hover");
		}
	);	
	
	
	
	$(".panel_backup table tbody td.fordelete").hover(
		function(){
			$(this).find("span").addClass("hover");
		}, 
		function(){
			$(this).find("span").removeClass("hover");
		}
	);
	
	$(".panel_backup table tbody td").click(function(){
		var filename = $(this).parent("tr").attr("data-filename");
		
		if( $(this).hasClass("fordelete") ){
			admin.confirmBox(\'<p>Удалить файл восстановления базы данных?</p>\', function(){
				admin.block();
				admin.ajax(\'backup\', {action:\'deleteBackup\', filename:filename}, function(){
					admin.block();
					admin.reloadPanel();
				});
			});
		}else{
			admin.confirmBox(\'<p>Восстановить состояние базы данных из файла?</p><p><b>Внимание!</b> Вся информация, которая хранится в базе данных будет перезаписана данными из файла восстановления.<br/><i>Восстановлена будет только информация, которая хранится вбазе данных. Изображения, документы и прочие файлы не будут восстановлены.</i></p><p>Продолжить?</p>\', function(){
				admin.block();
				admin.ajax(\'backup\', {action:\'applyBackup\', filename:filename}, function(){
					admin.reloadPanel(function(){
						admin.infoBox("<p>База данных была успешно восстановлена</p>");
						admin.unblock();
					});
				});
			});
		}
		
		
		

	});
</script>
'; ?>