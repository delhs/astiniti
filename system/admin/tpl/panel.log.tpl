<div class="panel_log">

	<h1>Журнал событий</h1>

	<div class="form">
		
		{if isset($logArray)}
			<div class="block list">
			
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>Дата<a href="#" data-sort="log_date" {if isset($sortArray.log_date)}class="{$sortArray.log_date}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Время<a href="#" data-sort="log_time" {if isset($sortArray.log_time)}class="{$sortArray.log_time}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Событие<a href="#" data-sort="log_comment" {if isset($sortArray.log_comment)}class="{$sortArray.log_comment}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Панель<a href="#" data-sort="panel_name" {if isset($sortArray.panel_name)}class="{$sortArray.panel_name}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Раздел<a href="#" data-sort="page_name" {if isset($sortArray.page_name)}class="{$sortArray.page_name}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Имя пользователя<a href="#" data-sort="user_name" {if isset($sortArray.user_name)}class="{$sortArray.user_name}"{/if} title="Изменить способ сортировки"></a></td>
							<td>Логин пользователя<a href="#" data-sort="user_login" {if isset($sortArray.user_login)}class="{$sortArray.user_login}"{/if} title="Изменить способ сортировки"></a></td>
						</tr>
					</thead>
					<tbody>
					{foreach from=$logArray item=log}
						<tr>
							<td>{$log.log_date}</td>
							<td>{$log.log_time}</td>
							<td>{$log.log_comment}</td>
							<td>{$log.panel_name}</td>
							<td>{$log.page_name}</td>
							<td>{$log.user_name}</td>
							<td>{$log.user_login}</td>
						</tr>
					{/foreach}	
					</tbody>
				</table>
			</div>
		{else}
			<p>Журнал событий пуст</p>
		{/if}
		
		{if isset($navArray)}
			<div class="block">
				<ul class="nav">
				{foreach from=$navArray key=key item=item}
					<li>
						<a href="#" data-num="{$item.num}" class="{$item.class}" title="{$item.title}">{$item.text}</a>
					</li>
				{/foreach}
				</ul>
			</div>
		{/if}
		
		{if isset($developer) and isset($logArray)}
			<div class="block">
				<button type="button" name="clearLog">Очистить журнал</button>
			</div>
		{/if}
		
	</div>
	
</div>

{if $developer}
{literal}
	<script type="text/javascript">
		$(".panel_log button[name='clearLog']").click(function(e){
			e.preventDefault();
			admin.confirmBox('Очистить весь журнал логов?', function(){
				admin.block();
				admin.ajax('log', {action:"clearLog"}, function(){
					admin.reloadPanel('log');
				});
			});
		});
	</script>
{/literal}	
{/if}

{literal}
	<script type="text/javascript">
		/* hover list */
/*		$(".panel_log .list ul li").hover(
			function(){
				var index = $(this).index();
				$(".panel_log ul").each(function(){
					$(this).find("li:eq("+index+")").addClass("hover");
				})
			},
			function(){
				$(".panel_log ul li.hover").removeClass("hover");
			}
		);
	*/	
		/* peganation event */
		$(".panel_log ul.nav li a").click(function(e){
			e.preventDefault();
			
			var sort = {};
			$(".panel_log a[data-sort]").each(function(){
				var sortItem = $(this).attr("data-sort");
				if( $(this).hasClass("desc")  ) var sortDesc = 'desc';
				if( $(this).hasClass("asc")  ) var sortDesc = 'asc';
				sort[ sortItem ] = sortDesc;
			});
			var page = $(this).attr("data-num");
			admin.loadPanel("log", {page:page, sort:sort});
		});
		
		
		/* sort event */
		$(".panel_log a[data-sort]").click(function(e){
			e.preventDefault();
			
			var page = $(".panel_log ul.nav li a.act").attr("data-num");
			
			if( $(this).hasClass("asc") ){
				$(this).removeClass("asc").addClass("desc");
			}else if( $(this).hasClass("desc") ){
				$(this).removeClass("desc");
			}else{
				$(this).addClass("asc");
			}
			
			
			var sort = {};
			$(".panel_log a[data-sort]").each(function(){
				var sortItem = $(this).attr("data-sort");
				if( $(this).hasClass("desc")  ) var sortDesc = 'desc';
				if( $(this).hasClass("asc")  ) var sortDesc = 'asc';
				sort[ sortItem ] = sortDesc;
			});
			var page = $(this).attr("data-num");
			
			var empty = true;
			$.each( sort, function(i, v){
				if( v!=undefined ){
					empty = false;
					return;
				}
			});

			if( empty ) sort = 'null';
			
			admin.loadPanel("log", {page:page, sort:sort});
		});
	</script>
{/literal}