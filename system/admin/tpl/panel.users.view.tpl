<div class="panel_users">
	<h1>Учетные записи</h1>
	<div class="userslist form">

			<ul class="names">
				<li>Имя</li>
				{foreach from=$usersArray item=user}
				<li data-user_id="{$user.id}">
					{$user.name}
				</li>
				{/foreach}
			</ul>
			
			<ul>
				<li>Логин</li>
				{foreach from=$usersArray item=user}
				<li>
					{$user.login}
				</li>
				{/foreach}
			</ul>
			
			<ul>
				<li>E-mail</li>
				{foreach from=$usersArray item=user}
				<li>
					{$user.email}
				</li>
				{/foreach}
			</ul>
			
			<ul>
				<li>Дата регистрации</li>
				{foreach from=$usersArray item=user}
				<li>
					{$user.reg_date}
				</li>
				{/foreach}
			</ul>

			<ul class="li_groups">
				<li>Группа<span class="hlp" title="Каждой группе назначены какие-либо привилегии. Таким образом, учетной записи будут назначены привилегии выбранной группы. Вы можете сменить группу с помощью выпадающего списка ниже. Данные изменения будут применены немедленно"></span></li>
				{foreach from=$usersArray item=user}
				<li>
					<select data-user_id="{$user.id}" name="group" {if $user.super_admin eq '1'} disabled="disabled"{/if}>
						<option selected="selected" value="0">--Вне группы--</option>
						{foreach from=$usersGroupsArray  item=group}
						<option {if $user.group_id eq $group.id} selected="selected" {/if} value="{$group.id}">{$group.name|escape}</option>
						{/foreach}
					</select>
				</li>
				{/foreach}
			</ul>

			<ul class="li_icons">
				<li>&nbsp;</li>
				{foreach from=$usersArray item=user}
				<li>
					<a href="#" data-userId="{$user.id}" class="icon edit" title="Редактировать учетную запись"></a>
				</li>
				{/foreach}
			</ul>
			
			<ul class="li_icons">
				<li>&nbsp;</li>
				{foreach from=$usersArray item=user}
				<li>
					{if $user.super_admin eq '1'}
					<span class="hlp" title="Данная учетная запись является глобальной. Такие учетные записи называют &laquo;Супер Админ&raquo;. Учетную запись супер администратора невозможно удалить. Помимо этого, ей доступны все привилегии, которые так же невозможно отменить"></span>
					{elseif $user.uid eq $myuid}
					<span class="hlp" title="Вы не можете удалить собственную учетную запись"></span>	
					{else}
					<a href="#" data-user_id="{$user.id}" class="icon delete" title="Удалить учетную запись"></a>
					{/if}
				</li>
				{/foreach}
			</ul>

		<div class="clear"></div>
	</div>
</div>

{literal}
	<script type="text/javascript">
		/* select event for users list */
		$(".userslist select[name='group']").change(function(){
			admin.block();

			var groupId = $(this).val();
			var userId = $(this).attr("data-user_id");
			admin.ajax('users', {action:"changeGroup", groupId:groupId, userId:userId}, function(){
				admin.infotip("Изменение группы успешно сохранено");
			});
		});	
		
		/*remove user button event*/
		$(".userslist a.icon.delete").click(function(e){
			e.preventDefault();
			var userId = $(this).attr("data-user_id");
			var userName = $(".userslist ul.names>li[data-user_id='"+userId+"']").text();
			admin.confirmBox('Удалить пользователя &laquo;'+userName.trim()+'&raquo;?',
				function(){
					admin.block();
					admin.ajax('users', {action:"deleteUser", userId:userId}, function(){
						admin.reloadPanel();
					});
				}
			);
		});
		
		/* edit user button event */
		$(".userslist a.icon.edit").click(function(e){
			e.preventDefault();
			var userId = $(this).attr("data-userid");
			admin.loadPanel('users', {action:'edituser', editedUserId:userId});
		});
	</script>
{/literal}