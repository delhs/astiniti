<div class="panel_users">
	<h1>Учетные записи</h1>
	<form name="grouplist" class="grouplist subpanel">
		<p>Группы учетных записей</p>
		
			<ul class="names">
				<li>Название группы</li>
				{foreach from=$usersGroupsArray item=group}
				<li data-group_id="{$group.id}">
					{$group.name|escape}
				</li>
				{/foreach}
			</ul>

			<ul class="privileges">
				<li>Привилегии</li>
				{foreach from=$usersGroupsArray item=group}
				<li>
					{foreach from=$group.privileges key=id item=privilegeArray}
					<div>
						<input type="checkbox" {if isset($privilegeArray.checked) }checked="checked"{/if} name="privilege[{$group.id}][]" id="{$privilegeArray.name}_{$group.id}" value="{$privilegeArray.name}" />&nbsp;&nbsp;<label for="{$privilegeArray.name}_{$group.id}">{$privilegeArray.descr}</label>
					</div>
					{/foreach}
				</li>
				{/foreach}
			</ul>
			
			<ul class="li_icons">
				<li>&nbsp;</li>
				{foreach from=$usersGroupsArray item=group}
				<li>
					<a href="" data-group_id="{$group.id}" class="icon edit" title="Редактировать группу учетных записей"></a>
				</li>
				{/foreach}
			</ul>	
			
			<ul class="li_icons">
				<li>&nbsp;</li>
				{foreach from=$usersGroupsArray item=group}
				<li>
					<a href="#" data-group_id="{$group.id}" class="icon delete" title="Удалить группу учетных записей"></a>
				</li>
				{/foreach}
			</ul>
			
		<div class="forbutton">
			<button type="button">Сохранить изменения</button>
		</div>
		<div class="clear"></div>	
	
	</form>
</div>
{literal}
	<script type="text/javascript">
		/* group list button save changes event */
		$(".grouplist button").click(function(e){
			e.preventDefault();
			admin.block();
			
			var data = $("form[name='grouplist']").serialize()+"&action=groupListChange";
			admin.ajax('users', data, function(){
				admin.loadPanel('users', {action:'groups'});
			});
		});
		
		/* group delete button event */
		$(".grouplist a.icon.delete").click(function(e){
			e.preventDefault();
			var groupId = $(this).attr("data-group_id");
			var groupName = $(".grouplist ul.names>li[data-group_id='"+groupId+"']").text();
			admin.confirmBox('Удалить группу &laquo;'+groupName.trim()+'&raquo;?<br/>Все пользователи, состоявшие в этой группе будут выведены вне групп.',
				function(){
					admin.ajax('users', {action:"deleteGroup", groupId:groupId}, function(){
						admin.loadPanel('users', {action:'groups'});
					});
				}
			);
		});
	
		/* edit group button event */
		$(".grouplist a.icon.edit").click(function(e){
			e.preventDefault();
			var groupId = $(this).attr("data-group_id");
			admin.loadPanel('users', {action:'editgroup', editedGroupId:groupId});
		});
	
		$(document).unbind('keydown');
		$("form[name='grouplist']").bind('keydown', function(e){
			if(e.keyCode == 13){
				$("form[name='grouplist'] button").click();
				e.preventDefault();
			}
		});
	</script>
{/literal}
