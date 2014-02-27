<?php /* Smarty version 2.6.27, created on 2014-01-31 10:43:01
         compiled from panel.log.tpl */ ?>
<div class="panel_log">

	<h1>Журнал событий</h1>

	<div class="form">
		
		<?php if (isset ( $this->_tpl_vars['logArray'] )): ?>
			<div class="block list">
			
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>Дата<a href="#" data-sort="log_date" <?php if (isset ( $this->_tpl_vars['sortArray']['log_date'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['log_date']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Время<a href="#" data-sort="log_time" <?php if (isset ( $this->_tpl_vars['sortArray']['log_time'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['log_time']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Событие<a href="#" data-sort="log_comment" <?php if (isset ( $this->_tpl_vars['sortArray']['log_comment'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['log_comment']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Панель<a href="#" data-sort="panel_name" <?php if (isset ( $this->_tpl_vars['sortArray']['panel_name'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['panel_name']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Раздел<a href="#" data-sort="page_name" <?php if (isset ( $this->_tpl_vars['sortArray']['page_name'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['page_name']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Имя пользователя<a href="#" data-sort="user_name" <?php if (isset ( $this->_tpl_vars['sortArray']['user_name'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['user_name']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
							<td>Логин пользователя<a href="#" data-sort="user_login" <?php if (isset ( $this->_tpl_vars['sortArray']['user_login'] )): ?>class="<?php echo $this->_tpl_vars['sortArray']['user_login']; ?>
"<?php endif; ?> title="Изменить способ сортировки"></a></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['logArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log']):
?>
						<tr>
							<td><?php echo $this->_tpl_vars['log']['log_date']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['log_time']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['log_comment']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['panel_name']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['page_name']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['user_name']; ?>
</td>
							<td><?php echo $this->_tpl_vars['log']['user_login']; ?>
</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>	
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<p>Журнал событий пуст</p>
		<?php endif; ?>
		
		<?php if (isset ( $this->_tpl_vars['navArray'] )): ?>
			<div class="block">
				<ul class="nav">
				<?php $_from = $this->_tpl_vars['navArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<li>
						<a href="#" data-num="<?php echo $this->_tpl_vars['item']['num']; ?>
" class="<?php echo $this->_tpl_vars['item']['class']; ?>
" title="<?php echo $this->_tpl_vars['item']['title']; ?>
"><?php echo $this->_tpl_vars['item']['text']; ?>
</a>
					</li>
				<?php endforeach; endif; unset($_from); ?>
				</ul>
			</div>
		<?php endif; ?>
		
		<?php if (isset ( $this->_tpl_vars['developer'] ) && isset ( $this->_tpl_vars['logArray'] )): ?>
			<div class="block">
				<button type="button" name="clearLog">Очистить журнал</button>
			</div>
		<?php endif; ?>
		
	</div>
	
</div>

<?php if ($this->_tpl_vars['developer']): ?>
<?php echo '
	<script type="text/javascript">
		$(".panel_log button[name=\'clearLog\']").click(function(e){
			e.preventDefault();
			admin.confirmBox(\'Очистить весь журнал логов?\', function(){
				admin.block();
				admin.ajax(\'log\', {action:"clearLog"}, function(){
					admin.reloadPanel(\'log\');
				});
			});
		});
	</script>
'; ?>
	
<?php endif; ?>

<?php echo '
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
				if( $(this).hasClass("desc")  ) var sortDesc = \'desc\';
				if( $(this).hasClass("asc")  ) var sortDesc = \'asc\';
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
				if( $(this).hasClass("desc")  ) var sortDesc = \'desc\';
				if( $(this).hasClass("asc")  ) var sortDesc = \'asc\';
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

			if( empty ) sort = \'null\';
			
			admin.loadPanel("log", {page:page, sort:sort});
		});
	</script>
'; ?>