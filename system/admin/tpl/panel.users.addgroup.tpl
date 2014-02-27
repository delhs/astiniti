<div class="panel_users">
	<h1>Учетные записи</h1>
		<form name="groupcreate" class="groupcreate subpanel">
			<p>Создание группы учетных записей</p>
			<div class="block">
				<div class="field">
					<label>Имя группы</label>
					<div class="padding">
						<input type="text" name="name" />
					</div>
				</div>
			</div>

			<div class="block">				
				<div class="field privilegecheck">
					<p>Привилегии группы</p>
					{foreach from=$privilegesArray key=id item=privilege}
					<div>	
						<input type="checkbox" name="privilege[]" id="{$privilege.name}" value="{$privilege.name}" />&nbsp;&nbsp;<label for="{$privilege.name}">{$privilege.descr}</label>
					</div>
					{/foreach}
				</div>
			</div>
			
			<div class="forbutton">
				<button type="button">Создать</button>
			</div>
			<div class="clear"></div>			
			
		</form>
</div>

{literal}
	<script type="text/javascript">
		/* group create button save event */
		$("form[name='groupcreate'] button").click(function(e){
			e.preventDefault();
			var data = $("form[name='groupcreate']").serialize()+"&action=groupCreate";
			admin.block();
			admin.ajax('users', data, $("form[name='groupcreate']"), function(){
				admin.loadPanel('users', {action:'groups'});
			});
		});
		
		$(document).unbind('keydown');
		$("form[name='groupcreate']").bind('keydown', function(e){
			if(e.keyCode == 13){
				$("form[name='groupcreate'] button").click();
				e.preventDefault();
			}
		});
	</script>
{/literal}