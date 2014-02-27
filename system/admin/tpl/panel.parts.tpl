<div class="panel_parts">
	<h1>Разделы проекта</h1>
	<p>Чтобы изменить порядок следования разделов, просто перемещайте их внутри списка между собой ухватив левой кнопкой мыши</p>
	<div class="form">
		<div class="block toolbar">
			<a href="#" class="hide">Свернуть все</a><!--
			--><a href="#" class="show">Развернуть все</a><!--
			--><a href="#" class="invert">Инвертировать</a>
		</div>
	
		<div class="block mmenu">
			<span>{$hostname}</span>
			{include file="panel.parts.menu.tpl" }
			<div class="clear"></div>
		</div>
	</div>
</div>

{literal}
	<script type="text/javascript">
		/* ul sortable plugin start */
		$(".panel_parts .mmenu ul").sortable({
			revert: 50,
			placeholder: "hold",
			start: function( event, ui ){
				var holdHeight = $(ui.item).outerHeight(true);
				$(".panel_parts .mmenu .hold").css({height: holdHeight + "px"});
			},
			stop: function(event, ui){
				var dataArray = {};
				var range = 0;
				$(this).children("li").each(function(){	
					var id = $(this).children("a[data-action]").attr("data-action");
					dataArray[ range ] = id;
					range++;
				});

				dataArray['action'] = 'rangeChange';
				admin.ajax("parts", dataArray, function(){
					admin.infotip('Порядок следования разделов успешно изменен');
				});
			}
		});
		
		/* page link ckick event */
		$(".panel_parts .mmenu a[data-get]").click(function(e){
			e.preventDefault();
			var action = $(this).attr("data-action");
			var text = $(this).text();
			
			$(".panel_parts .mmenu a[data-get]").removeClass("act");
			$(this).addClass("act");
			
			admin.setPageId( action, function(){
				admin.infotip("Текущий раздел &laquo;"+text+"&raquo;");
			});
		});
		
		
		/* add page icon event */
		$(".panel_parts .mmenu a.add").click(function(e){
			e.preventDefault();
			var pid = $(this).attr("data-pid");
			admin.block();
			admin.ajax('parts', {action:"addPart", pid:pid}, function( html ){
				$(".panel_parts").html( html);
				admin.rebuild();
			});
		});
		
		
		/* delete page icon event */
		$(".panel_parts .mmenu a.delete").click(function(e){
			e.preventDefault();
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			admin.confirmBox(
			"Удаление раздела &laquo;"+name+"&raquo;. <br/>Внимание, все его подразделы будут удалены, так же будут удалены все модули, установленные на данный раздел.<br/> Продолжить?", function(){
				admin.block();
				admin.ajax('parts', {action:"removePart", id:id}, function(){
					admin.setPageId( '1' , function(){
						admin.loadPanel('parts');
					});
				});
			});
		});
		
		/* clear empty ul */
		$(".panel_parts .mmenu ul li ul:empty").parent("li").children("a.node_ctrl.plus").removeClass("plus").addClass("empty");
		
		/* node ctrl events */
		$(".panel_parts .mmenu a.node_ctrl").click(function(e){
			e.preventDefault();
			$(this).toggleClass("plus");
			$(this).parent("li").find("ul:first").slideToggle(200);
			var openNodes = {};
			$(".panel_parts .mmenu a.node_ctrl").not('.plus').not('.empty').each(function(){
				var id = $(this).attr("data-id");
				openNodes[id] = id;
			});
			$.cookie("mmenuOpenNodes", JSON.stringify(openNodes), {expires:1, path:"/admin/"});
		});
		
		/* open nodes */
		if($.cookie("mmenuOpenNodes")!=null){
			var openNodes = JSON.parse($.cookie("mmenuOpenNodes"));
			$.each(openNodes, function(id, v){
				$(".panel_parts .mmenu a.node_ctrl[data-id='"+id+"']").removeClass("plus").parent("li").find("ul:first").slideToggle(0);
			});
		}
		
		/* tollbar buttons events */
		$(".panel_parts .toolbar a.show").click(function(e){
			e.preventDefault();
			$(".panel_parts .mmenu a.node_ctrl.plus").click();
		});
		
		$(".panel_parts .toolbar a.hide").click(function(e){
			e.preventDefault();
			$(".panel_parts .mmenu a.node_ctrl").not(".plus").click();
		});
		
		$(".panel_parts .toolbar a.invert").click(function(e){
			e.preventDefault();
			$(".panel_parts .mmenu a.node_ctrl").click();
		});
		/* visible first items */
		$(".panel_parts .mmenu>ul").show();		
	
	
	</script>
{/literal}