<div class="panel_mydata">
	<h1>Учетные записи</h1>
	

	<form name="panel_mydata">
		<p>Редактирование учетной записи</p>
		<div class="block">
			<div class="field">
				<label>Имя пользователя</label>
				<input type="text" name="name" value="{$user.name}" />
			</div>
			
			<div class="field">
				<label>E-mail пользователя</label>
				<input type="text" name="email" value="{$user.email}"/>
			</div>
		</div>

		<div class="block">
			<div class="field">
				<label>Логин пользователя</label>
				<input type="text" name="login" value="{$user.login}"/>
			</div>
			
			<div class="field">
				<label>Пароль пользователя</label>
				<input type="password" name="pass" value="{$user.pass}"/>
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
			
	/* button user edit save event */
	$("form[name='panel_mydata'] button").click(function(e){
		e.preventDefault();
		var form = $("form[name='panel_mydata']");
		var data = form.serialize()+"&action=editUser";
		admin.block();
		admin.ajax('mydata', data, form,  function(){
			admin.confirmBox("Чтобы изменения вступили в силу, необходимо выйти из системы и заново авторизоваться.<br/> Выйти из системы?",
				function(){
					admin.auth.logout()
				},
				function(){
					admin.loadPanel('users');
				}
			);
		});
	});		

	$(document).unbind('keydown');
	$("form[name='panel_mydata']").bind('keydown', function(e){
		if(e.keyCode == 13){
			$("form[name='panel_mydata'] button").click();
			e.preventDefault();
		}
	});
	
	
</script>
{/literal}