<div class="panel_users">
	<h1>Учетные записи</h1>
	<form name="groupedit" data-group_id="{$editedGroupId}" class="groupedit subpanel">
		<p>Редактирование группы учетных записей</p>
		<div class="block">
			<div class="field">
				<label>Имя группы</label>
				<div class="padding">
					<input type="text" name="name" value="{$usersGroupsArray.$editedGroupId.name|escape}" />
				</div>
			</div>
		</div>

		<div class="block">				
			<div class="field privilegecheck">
				<p>Привилегии группы</p>
				{foreach from=$privilegesArray key=id item=privilege}
				<div>	
					<input type="checkbox" name="privilege[]" id="{$privilege.name}" value="{$privilege.name}" {foreach from=$usersGroupsArray.$editedGroupId.privileges key=groupPrivId item=privilegeArray}{if $privilegeArray.checked eq 'checked' and $privilegeArray.name eq $privilege.name}checked="checked"{/if}{/foreach} />&nbsp;&nbsp;<label for="{$privilege.name}">{$privilege.descr}</label>
				</div>
				{/foreach}
			</div>
		</div>
		
		<div class="forbutton">
			<button type="button">Сохранить изменения</button>
		</div>
		<div class="clear"></div>			
		
	</form>	
</div>

{literal}
	<script type="text/javascript">
		/* group edit button save event */
		$("form[name='groupedit'] button").click(function(e){
			e.preventDefault();
			admin.block();
			
			var form = $("form[name='groupedit']");
			var groupId = form.attr("data-group_id");
			var data = form.serialize()+"&action=editGroup&groupId="+groupId;
			
			admin.ajax('users', data, form, function(){
				admin.loadPanel('users', {action:'groups'});
			});
		});
		
		$(document).unbind('keydown');
		$("form[name='groupedit']").bind('keydown', function(e){
			if(e.keyCode == 13){
				$("form[name='groupedit'] button").click();
				e.preventDefault();
			}
		});
	</script>
{/literal}