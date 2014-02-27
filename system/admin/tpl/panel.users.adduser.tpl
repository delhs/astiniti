<div class="panel_users">
	<h1>Учетные записи</h1>
	<form name="createUser" class="subpanel">
		<p>Создание учетной записи</p>
		<div class="block">
			<div class="field">
				<label>Имя пользователя</label>
				<div class="padding">
					<input type="text" name="name" />
				</div>
			</div>
			
			<div class="field">
				<label>E-mail пользователя<span class="hlp" title="С помощью этого E-mail адреса, администратор системы сможет связаться с пользователем, который владеет данной учетной записью"></span></label>
				<div class="padding">
					<input type="text" name="email"/>
				</div>
			</div>
		</div>

		<div class="block">
			<div class="field">
				<label>Логин пользователя<span class="hlp" title="Логин используется для входа в данную панель управления"></span></label>
				<div class="padding">
					<input type="text" name="login"/>
				</div>
			</div>
			
			<div class="field">
				<label>Пароль пользователя<span class="hlp" title="Пароль используется для входа в данную панель управления"></span></label>
				<div class="padding">
					<input type="text" name="pass"/>
				</div>
			</div>
		</div>
		
		<div class="block">
			<div class="field">
				<label>Группа учетной записи<span class="hlp" title="Каждой группе назначены какие-либо привилегии. Таким образом, учетной записи будут назначены привилегии выбранной группы"></span></label>
				<div class="padding">
					<select name="group">
						<option selected="selected" value="0">--Вне группы--</option>
						{foreach from=$usersGroupsArray  item=group}
						<option value="{$group.id}">{$group.name}</option>
						{/foreach}
					</select>
				</div>
			</div>
			
			<div class="field privilegecheck">
				<p>Приилегии учетной записи<span class="hlp" title="Если учетная запись не входит ни в одну группу, то можно назначить индивидуальные привилегии"></span></p>
				
				<div class="toolbar"><!--
					--><a href="#" class="select">Отметить все</a><!--
					--><a href="#" class="unselect">Снять выделение</a><!--
					--><a href="#" class="invert">Инвертировать выделение</a><!--
				--></div>
				
				{foreach from=$privilegesArray key=id item=privilege}
				<div>	
					<input type="checkbox" name="privilege[]" id="{$privilege.name}" value="{$privilege.name}" />&nbsp;&nbsp;<label for="{$privilege.name}">{$privilege.descr}</label>
				</div>
				{/foreach}
			</div>
			
			<div class="field">
				<p>Дополнительные параметры</p>
				<input type="checkbox" checked="checked" name="avow" id="avow" value="1" />&nbsp;&nbsp;<label for="avow">Пользователь одобрен<span class="hlp" title="Если данный пункт не отмечен, то пользователю будет отказано в авторизации"></span></label>
			</div>
			
		</div>
		
		<div class="forbutton">
			<button type="button">Создать учетную запись</button>
		</div>
		<div class="clear"></div>
	</form>
</div>


{literal}
	<script type="text/javascript">
		/*select event from create user */
		$("form[name='createUser'] select[name='group']").change(function(){
			if( $(this).val()=='0' ){
				$("form[name='createUser'] .privilegecheck input[type='checkbox']").removeAttr("disabled").trigger("refresh");
			}else{
				$("form[name='createUser'] .privilegecheck input[type='checkbox']").attr("disabled","disabled").trigger("refresh");
			}
		});	
		
		/*create user button event*/
		$("form[name='createUser'] button").click(function(){
			var data = $("form[name='createUser']").serialize()+"&action=createUser";
			admin.block();
			admin.ajax( "users", data, $("form[name='createUser']"), function(){
				admin.reloadPanel();
			});
			return false;
		});

		/* select button */
		$(".panel_users form[name='createUser'] .privilegecheck .toolbar a.select").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='createUser'] .privilegecheck input[type='checkbox']:enabled").not(":checked").attr("checked", "checked").trigger("refresh");
		});
		
		/* unselect button */
		$(".panel_users form[name='createUser'] .privilegecheck .toolbar a.unselect").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='createUser'] .privilegecheck input[type='checkbox']:checked:enabled").removeAttr("checked").trigger("refresh");
		});
		
		/* invert button */
		$(".panel_users form[name='createUser'] .privilegecheck .toolbar a.invert").click(function(e){
			e.preventDefault();
			$(".panel_users form[name='createUser'] .privilegecheck input[type='checkbox']:enabled").click().trigger("refresh");
		});		
		
		$(document).unbind('keydown');
		$("form[name='createUser']").bind('keydown', function(e){
			if(e.keyCode == 13){
				$("form[name='createUser'] button").click();
				e.preventDefault();
			}
		});
		
	</script>
{/literal}
