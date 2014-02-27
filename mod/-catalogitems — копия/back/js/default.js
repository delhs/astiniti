var modCatalogitems = {
		
	init:function(){
		$("#cap").on("click", "#mod_catalogitems_menu a[data-action]", function(e){
			e.preventDefault();
			modCatalogitems.viewTemplate($(this).attr("data-action"));
			var dataAction = $(this).attr("data-action");
		});
	},
	viewTemplate:function( templateName, dataArray, callback ){
		admin.block();
		
		if( typeof dataArray =="function" ){
			callback = dataArray;
			dataArray = undefined;
		}
		
		var dataObj = {};
		dataObj.action = templateName;
		if( dataArray && ( typeof dataArray =="array" || typeof dataArray =="object")){
			$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		$("#mod_catalogitems_menu a[data-action].act").removeClass("act");
		$(".mod_catalogitems").html( "" );
		admin.mod.ajax( "catalogitems", dataObj, function( html ){
			$(".mod_catalogitems").html( html );
			$("#mod_catalogitems_menu a[data-action='"+templateName+"']").addClass("act");
			admin.rebuild();
			if( typeof callback == "function" ) callback();
			admin.unblock();
		});
	},
	indexItemsListTplInit:function(){
		
		//add new item button event
		$(".mod_catalogitems a.add_items").click(function(e){
			e.preventDefault();
			modCatalogitems.addItemsSplashShow();
		});

		//item click event
		$(".mod_catalogitems ul.index_list").off("click.gotoitem");
		$(".mod_catalogitems ul.index_list").on("click.gotoitem", "a[item-id]", function(e){
			e.preventDefault();
			var itemId = $(this).attr("item-id");
			admin.loadPanel('plugins', {action:'catalog'}, function(){
				catalog.viewTemplate('edit_item', {id:itemId});
			})
		});

		//delete item button event
		$(".mod_catalogitems ul.index_list").on("click.delete", "a.delete", function(e){
			e.preventDefault();
			var itemId = $(this).parent().slideUp(200, function(){
				$(this).remove();
			});
		});

		//delete item button event
		$(".mod_catalogitems button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = {};
			data['items'] = {};
			if( $(".mod_catalogitems ul.index_list li").size()>0 ){
				$(".mod_catalogitems ul.index_list a[item-id]").each(function(){
					var id = $(this).attr("item-id");
					data['items'][ id ] = id;
				});
			}
			data.action = "saveItemsList";

			data = decodeURIComponent($.param(data));
			admin.mod.ajax('catalogitems', data, function(){
				modCatalogitems.viewTemplate('index_items_list');
			});

		});

	},
	addItemsSplashShow:function(){
		admin.block();
		admin.mod.ajax( "catalogitems", {action:"items_splash_tpl"}, function( html ){
			$.splash( html, {
				fullscreen: true,
				openCallback: function(){
				
					$(".mod_catalogitems.categories").parents(".splash_splash.fullscreen").find("a.splash_close_btn").css({right:"8px"});
				
					$(".mod_catalogitems.categories").parents(".splash_splash.fullscreen").jScrollPane({
						showArrows:false,
						mouseWheelSpeed:50,
						verticalGutter:10,
						horizontalGutter:10,
						maintainPosition:true,
						autoReinitialise: true
					});						
					
					//delete add and remove buttons
					$(".mod_catalogitems.categories ul a.add, .mod_catalogitems .categories ul a.delete, .mod_catalogitems .categories ul a.edit").remove();
					
					//clear empty ul
					$(".mod_catalogitems.categories ul li ul:empty").parent("li").children("a.node_ctrl.plus").removeClass("plus").addClass("empty");
					
					//click on category event
					$(".mod_catalogitems.categories a[data-get]").click(function(e){
						e.preventDefault();
						
						if( $(this).data("loaded")=="true" ) return;
						
						admin.block();
						var that = $(this);
						var catId = $(this).attr("data-action");
						var ul = $("<ul>", {class:"items"});
						var b = $("<b>", {class:"empty", text:"Товаров не найдено"});
						
 						admin.plug.ajax( "catalog", {catId:catId, action:"getItemsByCatId"}, function( json ){
							var dataObj = $.parseJSON( json );
							
							if( dataObj[0] !=undefined ){
								$.each( dataObj, function( i, data ){
									var selected = ( $(".mod_catalogitems ul.index_list a[item-id='"+data.id+"']").length==1 ) ? "selected" : "";
									var item = '<li><a href="#" class="'+selected+'" item-id="'+data.id+'">'+data.name+'</a></li>';
									ul.prepend( item );
								});

								that.parent("li").append( ul );
							}else{
								that.parent("li").append( b );
							}
							
							that.data("loaded", "true");
							admin.unblock();
						}); 
						
					});
					
					//click on items
					$(".mod_catalogitems.categories").off("click.itemselect");
					$(".mod_catalogitems.categories").on("click.itemselect", "ul.items li>a[item-id]", function(e){
						e.preventDefault();
						$(this).toggleClass("selected");
						
						var itemId = $(this).attr("item-id");
						var itemName = $(this).text();
						var li = '<li><a title="Перейти в каталог и открыть страницу товара" href="#" item-id="'+itemId+'">'+itemName+'</a><a href="#" class="delete" title="Удалить из списка"></a></li>';
						
						if( $(this).hasClass("selected") ){
							$(".mod_catalogitems ul.index_list").append(li);
							admin.rebuild();
						}else{
							$(".mod_catalogitems ul.index_list a[item-id='"+itemId+"']").parent("li").remove();
						}
						
					});
					
					/* node ctrl events */
					$(".mod_catalogitems.categories a.node_ctrl").click(function(e){
						e.preventDefault();
						$(this).toggleClass("plus");
						$(this).parent("li").find("ul:first").slideToggle(200);
						
						//compile string
						var openNodes = {};
						$(".mod_catalogitems .categories a.node_ctrl").not('.plus').not('.empty').each(function(){
							var id = $(this).attr("data-id");
							openNodes[id] = id;
						});
						
						//save in cookies
						$.cookie("catalogCatOpenNodes", JSON.stringify(openNodes), {expires:1, path:"/admin/"});
					});
					
					//open nodes
					if($.cookie("catalogCatOpenNodes")!=null){
						var openNodes = JSON.parse($.cookie("catalogCatOpenNodes"));
						$.each(openNodes, function(id, v){
							$(".mod_catalogitems .categories a.node_ctrl[data-id='"+id+"']").removeClass("plus").parent("li").find("ul:first").slideToggle(0);
						});
					}
					
					//toolbar buttons events
					$(".mod_catalogitems.categories .toolbar a.show").click(function(e){
						e.preventDefault();
						$(".mod_catalogitems.categories .categories a.node_ctrl.plus").click();
					});
					
					$(".mod_catalogitems.categories .toolbar a.hide").click(function(e){
						e.preventDefault();
						$(".mod_catalogitems.categories .categories a.node_ctrl").not(".plus").click();
					});
					
					$(".mod_catalogitems.categories .toolbar a.invert").click(function(e){
						e.preventDefault();
						$(".mod_catalogitems.categories .categories a.node_ctrl").click();
					});
					
					//visible first items
					$(".mod_catalogitems.categories .categories>ul").show();
					admin.unblock();
				}
			});
			
		});
	}
}

$(function(){
	modCatalogitems.init();
})