<div class="panel_redirect">
	<h1>Перенаправления</h1>
	

	
	<form name="redirect">
	
		<div class="block">
			<p>Зачем это нужно? Бывает так, что вы уже имеете сайт, будь то простой сайт-визитка или интернет-магазин с большим каталогом, но решаете его усовершенствовать или полностью сменить "движок" для улучшения производительности. Так, в процессе переноса уже существующего материала, который был проиндексирован поисковыми системами, встречаются ситуации, которые не позволяют создать разделы с таким же URL адресом, как у старого сайта, притом, чтобы по данному адресу загружался такой же контент. А иногда просто, нужно чтобы определенная страница вела на сторонний сайт. Суть данной настройки в том, что вы можете задать URL адрес, по запросу которого система будет перенеправлять на необходимый раздел или сторонний ресурс.</p>			
		</div>
	
		<div class="block redirects">
			<ul>
				<li>
					<span class="from">Запрашиваемый URL<span class="hlp" title="URL адрес, по запросу которого должно будет выполниться перенаправление. Можно не указывать протокол(&nbsp;http://&nbsp;) и адрес сайта(&nbsp;{$hostname}&nbsp;)"></span></span>
					<span class="to">URL на который будет выполнено перенаправление<span class="hlp" title="Полный URL адрес, на который будет выполнено перенаправление."></span></span>
					<span class="actions"></span>
				</li>
				{foreach from=$redirectArray key=index item=data}
				<li>
					<input type="text" name="from[{$index}]" class="from" value="{$data.from_url}" />
					<input type="text" name="to[{$index}]" class="to" value="{$data.to_url}"/>
					<a href="#" title="Удалить запись" class="del actions"></a>
				</li>
				{/foreach}
			</ul>
			
			<div class="clear"></div>
		</div>

		<div class="block">
			<button type="button" name="save">Сохранить изменения</button>
			<button type="button" name="add">Добавить перенаправление</button>
			<div class="clear"></div>
		</div>
		
	</form>
</div>

{literal}
<script type="text/javascript">
	
	/* hide block if redirect count is zero */
	if( $("form[name='redirect'] ul>li").size()==1 ) $("form[name='redirect'] .redirects").hide();
	
	/* delete live event for del button */
	$(".inner").off("click", "form[name='redirect'] a.del");
	
	/* add redirect button event */
	$("form[name='redirect'] button[name='add']").click(function(e){
		e.preventDefault();
		var 
		index = Math.floor( Math.random() * 999 ),
		li = $("<li>"),	
		fromInput = $("<input>", {type:"text", name:"from["+index+"]", class:"from"}),	
		toInput = $("<input>", {type:"text", name:"to["+index+"]", class:"to"}),		
		delBtn = $("<a>", {href:"#", class:"del actions", title:"Удалить запись"});
		
		li.append( fromInput ).append( toInput ).append( delBtn );
		$("form[name='redirect'] ul").append( li );
		
		$("form[name='redirect'] .redirects:hidden").show();
		
		li.hide().slideDown(200, function(){
			delBtn.tooltip({
				track:false,
				show:{delay:800, duration:0},
				hide:{duration:0},
				content: function () {
					return $(this).prop('title');
				}
			});		
		});
		admin.resizing();
	});
	
	/* del redirect button event */
	$(".inner").on("click", "form[name='redirect'] a.del", function(e){
		e.preventDefault();
		$(this).parent("li").slideUp(200, function(){
			$(this).remove();
			if( $("form[name='redirect'] ul>li").size()==1 ) $("form[name='redirect'] .redirects:visible").hide();
		});
	});
	
	/* save button event */
	$("form[name='redirect'] button[name='save']").click(function(e){
		e.preventDefault();
		admin.block();
		var form = $("form[name='redirect']");
		var data = form.serialize();
		if(data!=''){
			admin.ajax('redirect', data, form, function(){
				admin.reloadPanel();
			});
		}else{
			admin.ajax('redirect', {empty:""},  function(){
				admin.reloadPanel();
			});
		}

	});
	
	$(document).unbind('keydown');
	$("form[name='redirect']").bind('keydown', function(e){
		if(e.keyCode == 13){
			$("form[name='redirect'] button[name='save']").click();
			e.preventDefault();
		}
	});
</script>
{/literal}