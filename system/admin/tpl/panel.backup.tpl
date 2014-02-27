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
			{if isset($files)}
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
						{foreach from=$files item=file}
							<tr data-filename="{$file.filename}">
								<td>{$file.date}</td>
								<td>{$file.time}</td>
								<td class="fordelete"><span title="Удалить файл резервной копии" class="delete"></span></td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			{else}
			<p><i>Резервные копии не найдены</i></p>
			{/if}
		</div>		
	</div>
	
</div>

{literal}
<script type="text/javascript">
	$(".panel_backup button[name='createBackup']").click(function(){
		admin.block();
		admin.ajax('backup', {action:'createBackup'}, function(){
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
			admin.confirmBox('<p>Удалить файл восстановления базы данных?</p>', function(){
				admin.block();
				admin.ajax('backup', {action:'deleteBackup', filename:filename}, function(){
					admin.block();
					admin.reloadPanel();
				});
			});
		}else{
			admin.confirmBox('<p>Восстановить состояние базы данных из файла?</p><p><b>Внимание!</b> Вся информация, которая хранится в базе данных будет перезаписана данными из файла восстановления.<br/><i>Восстановлена будет только информация, которая хранится вбазе данных. Изображения, документы и прочие файлы не будут восстановлены.</i></p><p>Продолжить?</p>', function(){
				admin.block();
				admin.ajax('backup', {action:'applyBackup', filename:filename}, function(){
					admin.reloadPanel(function(){
						admin.infoBox("<p>База данных была успешно восстановлена</p>");
						admin.unblock();
					});
				});
			});
		}
		
		
		

	});
</script>
{/literal}