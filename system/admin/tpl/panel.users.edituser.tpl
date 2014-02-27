<div class="panel_users">
	<h1>Учетные записи</h1>
	<form name="useredit" data-user_id="{$editUserId}" class="useredit subpanel">
		<p>Редактирование учетной записи</p>
		<div class="block">
			<div class="field">
				<label>Имя пользователя</label>
				<div class="padding">
					<input type="text" name="name" value="{$usersArray.$editUserId.name|escape}" />
				</div>
			</div>
			
			<div class="field">
				<label>E-mail пользователя</label>
				<div class="padding">
					<input type="text" name="email" value="{$usersArray.$editUserId.email}"/>
				</div>
			</div>
		</div>

		<div class="block">
			<div class="field">
				<label>Логин пользователя</label>
				<div class="padding">
					<input type="text" name="login" value="{$usersArray.$editUserId.login|escape}"/>
				</div>
			</div>
			
			<div class="field">
				<label>Пароль пользователя</label>
				<div class="padding">
					<input type="password" name="pass" value="{$usersArray.$editUserId.pass|escape}"/>
				</div>
			</div>
		</div>
		{if $usersArray.$editUserId.super_admin eq '1'}
		<div class="block superadmininf">Данная учетная запись является глобальной. Такие учетные записи называют &laquo;Супер Админ&raquo;. Учетную запись супер администратора невозможно удалить. Помимо этого, ей доступны все привилегии, которые так же невозможно отменить</div>
		{/if}
		
		{if $usersArray.$editUserId.super_admin neq '1'}
			<div class="block privilegies">
				
				<div class="field">
					<label>Группа учетной записи</label>
					<div class="padding">
						<select name="group">
							<option value="0">--Вне группы--</option>
							{foreach from=$usersGroupsArray  item=group}
							<option value="{$group.id}" {if $usersArray.$editUserId.group_id eq $group.id}selected="selected"{/if}>{$group.name|escape}</option>
							{/foreach}
						</select>
					</div>
				</div>
			
				<div class="field privilegecheck">
					<p>Приилегии учетной записи</p>
					
				<div class="toolbar"><!--
					--><a href="#" class="select">Отметить все</a><!--
					--><a href="#" class="unselect">Снять выделение</a><!--
					--><a href="#" class="invert">Инвертировать выделение</a><!--
				--></div>
				
					{foreach from=$privilegesArray key=id item=privilege}
					<div>	
						<input type="checkbox" name="privilege[]" {foreach from=$editUserPrivilegeArray item=userPrivName}{if $userPrivName eq $privilege.name}checked="checked"{/if}{/foreach} {if $usersArray.$editUserId.group_id neq '0'}disabled="disabled"{/if} id="{$privilege.name}" value="{$privilege.name}" />&nbsp;&nbsp;<label for="{$privilege.name}">{$privilege.descr}</label>
					</div>
					{/foreach}
				</div>
				
				<div class="field">
					<p>Дополнительные параметры</p>
					<input type="checkbox" name="avow" id="avow" value="1" {if $usersArray.$editUserId.avow eq '1'}checked="checked"{/if} />&nbsp;&nbsp;<label for="avow">Пользователь одобрен</label>
				</div>
				
			</div>
		{/if}
		
		
		<div class="forbutton">
			<button type="button">Сохранить изменения</button>
		</div>
		<div class="clear"></div>
	</form>
</div>

{literal}
	<script type="text/javascript">
		$("form[name='useredit'] select[name='group']").change(function(){
			if( $(this).val()=='0' ){
				$(".panel_users .useredit .privilegecheck input[type='checkbox']").removeAttr("disabled").trigger("refresh");
			}else{
				$(".panel_users .useredit .privilegecheck input[type='checkbox']").attr("disabled","disabled").trigger("refresh");
			}
		});	
			
		/* button user edit save event */
		$("form[name='useredit'] button").click(function(e){
			e.preventDefault();
			admin.block();
			
			var form = $("form[name='useredit']");
			var userId = form.attr("data-user_id");
			var data = form.serialize()+"&userId="+userId+"&action=editUser";
			admin.ajax('users', data, form,  function(){
				admin.loadPanel("users");
			});
		});	
		
		/* select button */
		$(".panel_users form[name='useredit'] .block.privilegies .toolbar a.select").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='useredit'] .block.privilegies .privilegecheck input[type='checkbox']:enabled").not(":checked").attr("checked", "checked").trigger("refresh");
		});
		
		/* unselect button */
		$(".panel_users form[name='useredit'] .block.privilegies .toolbar a.unselect").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='useredit'] .block.privilegies .privilegecheck input[type='checkbox']:checked:enabled").removeAttr("checked").trigger("refresh");
		});
		
		/* invert button */
		$(".panel_users form[name='useredit'] .block.privilegies .toolbar a.invert").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='useredit'] .block.privilegies .privilegecheck input[type='checkbox']:enabled").click().trigger("refresh");
		});
		
		$(document).unbind('keydown');
		$("form[name='useredit']").bind('keydown', function(e){
			if(e.keyCode == 13){
				$("form[name='useredit'] button").click();
				e.preventDefault();
			}
		});
	</script>
{/literal}