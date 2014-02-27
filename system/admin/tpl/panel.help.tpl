<div class="panel_help">
	<h1>Справка</h1>
	<div class="tiles">
		<ul>

			<li>
				<span>Главное меню</span>
				<div>
					<p><a href="#" data-guide="viewMainMenu">Показать где находится главное меню</a></p>
					
					{if isset($plugNamesArray)}
					<p><span>есть плагины:{foreach from=$plugNamesArray item=dataArray}<b>{$dataArray.name}</b><br/>{/foreach}</span></p>
					{/if}

					{if isset($registeredMmenuItemsArray)}
					<p><span>есть доп.:{foreach from=$registeredMmenuItemsArray item=dataArray}<b>{$dataArray.name}</b><br/>{/foreach}</span></p>
					{/if}
					
					{if isset($modNamesArray)}
					<p><span>есть Модули:{foreach from=$modNamesArray item=dataArray}<b>{$dataArray.mod_name_ru}</b><br/>{/foreach}</span></p>
					{/if}
					
				</div>
			</li>		
		
			<li>
				<span>Управление разделами проекта</span>
				<div>
					<p><a href="#" data-panel="parts" data-guide="viewPartsPanel">Пройти интерактивный тур по управлению разделами</a></p>
				</div>
			</li>		
		
			<li>
				<span>Lorem ipsum</span>
				<div>
					<p>Lorem ipsum</p>
					<p>Lorem ipsum Lorem </p>
					<p>Lorem ipsum Lorem </p>
					<p>Lorem ipsum Lorem </p>
					<p>Lorem ipsum Lorem </p>
					Lorem ipsumLorem ipsum
				</div>
			</li>		
		
			<li>
				<span>Lorem ipsum</span>
				<div>
					<p>Lorem ipsum</p>
				</div>
			</li>		
		
			<li>
				<span>Lorem ipsum</span>
				<div>
					<p>Lorem ipsum</p>
					<p>Lorem ipsum Lorem ipsumLorem ipsum</p>
					<p>Lorem ipsum Lorem ipsumLorem ipsum</p>
					<p>Lorem ipsum Lorem ipsumLorem ipsum</p>
					<p>Lorem ipsum Lorem ipsumLorem ipsum</p>
					Lorem ipsumLorem ipsum
				</div>
			</li>	
			
			
			
		</ul>
		
		<div class="clear"></div>
	</div>
	
</div>


{literal}
<script type="text/javascript">
	$(".panel_help .tiles a[data-guide]").click(function(e){
		e.preventDefault();
		var tourBlock = $(this).attr("data-guide");
		var panel = $(this).attr("data-panel");
		if( panel != undefined ){
			admin.loadPanel( panel, function(){
				console.log('wait...');
				admin.wait(300, function(){
					console.log('try to start the tour');
					admin.tour.start(tourBlocks[tourBlock], {skipLabel:'Прервать тур', doneLabel:'Завершить и вернуться к справке'}, function(){
						admin.loadPanel("help");
					});
				});

			});
		}else{
			admin.tour.start(tourBlocks[tourBlock], {skipLabel:'OK', doneLabel:'Завершить'});	
		}
	
	});
</script>
{/literal}