var plug_catalog = { 
	viewMode: "table",
	settings:{},

	//loupe settings
	loupeSettings:{
		zoom:300,
		radius:310,
		css: 'cat_loupe'
	},

	init:function(){

		if( core.browser.name==null ) core.browser.detect();

		//toggle filter button event
		var toggleFilterBtn = document.querySelector('a.toggle_filter');
		if( toggleFilterBtn!=null ){
			toggleFilterBtn.addEventListener('click', function(e){
				e.preventDefault();
				plug_catalog.filter.toggle();
			}, false);
		}

		

		//get settings
		this.loadSettings(function(){

			plug_catalog.filter.init();

			//initializing cart
			plug_catalog.cart.init();

			//reload cart
			plug_catalog.cart.reload(function(){
				//initializing cart infotips
				plug_catalog.infotips.cart.init();
			});

			//initializing compare
			plug_catalog.compare.init();
	
			//initializing compare infotips
			plug_catalog.infotips.compare.init();
			
			//initializing compare infotips
			plug_catalog.infotips.compare.init();

			if( $(".catalog_compare_page").length ) plug_catalog.comparePage();


			//get view mode
			if( $.cookie("catalog_vm")!=null ){
				plug_catalog.viewMode = $.cookie("catalog_vm");
			}
	
			//set view mode
			//plug_catalog.setViewMode( plug_catalog.viewMode );
			
			//set event for toolbar view mode buttons
			$(".catalog_main_page .toolbar li>a.view_mode").click(function(e){
				e.preventDefault();
				var that = $(this);
				$(".list_items").animate({top:'50px', opacity:0}, 35, function(){
					plug_catalog.setViewMode( that.attr("data-mode") );
					$(this).css({top:'-50px'}).animate({top:0, opacity:1}, 600, "easeOutExpo");
				});
				
			});	

			//set event for buy buttons
			$("a.buy").click(function(e){
				e.preventDefault();
				$(this).addClass("in-cart");
				plug_catalog.cart.addOne( $(this).attr("data-id") );
				plug_catalog.infotips.cart.reload();
			});
	
			$("a.add_to_compare").each(function(){
				var itemId = $(this).attr("data-id");
				if( plug_catalog.compare.items[ itemId ] != undefined ){
					$(this).hide();
					$("a.del_from_compare[data-id='"+itemId+"']").css({display:"block"});
				}
			});
			

			//add to compare
			$("a.add_to_compare").click(function(e){
				e.preventDefault();
				var itemId = $(this).attr("data-id");
				plug_catalog.compare.addOne( itemId );
				plug_catalog.infotips.compare.reload();
				$(this).hide();
				$("a.del_from_compare[data-id='"+itemId+"']").css({display:"block"});
			});


			//delete from compare
			$("a.del_from_compare").click(function(e){
				e.preventDefault();
				var itemId = $(this).attr("data-id");
				plug_catalog.compare.removeOne( itemId );
				plug_catalog.infotips.compare.reload();
				$(this).hide();
				$("a.add_to_compare[data-id='"+itemId+"']").css({display:"block"});
			});

			//open cart splash window
			$(".show_order").click(function(e){
				e.preventDefault();
	
				//create card window
				var splash = $(document.createElement('div')).attr('class', 'catalog-card-splash');
				$("body").append(splash);
			
				$(".catalog-card-splash").on("click.remove_item", "a.delete", function(e){
					e.preventDefault();
					var itemId = $(this).attr("data-id");
					var $li = $(this).parent("li");
					var $ghost = $(document.createElement('div')).attr('class', 'catalog-card-splash-ghost');
					$li.addClass("catalog-card-splash-removed")
						.after( $ghost )
						.animate({
							left:"-200px",
							opacity:0
						}, 160, function(){
							$(this).remove();
							$ghost.slideUp(100, function(){
								$(this).remove();
								plug_catalog.cart.removeAll( itemId, function(){
									plug_catalog.infotips.cart.reload();
								});
							});
						});

				});
	
				//set event for window
				$(window).off("resize.catalog_card_splash");
				$(window).on("resize.catalog_card_splash", function(){
					var left = $(".show_order").offset().left;
					var top = $(".show_order").offset().top + $(".show_order").outerHeight(true);
					$(".catalog-card-splash").css({left: left + "px", top: top + "px"});
				});
	
				//show
				$(".catalog-card-splash").slideDown(100, function(){
					var loader = $(document.createElement("div")).attr("class", "loader36");
					var list =  $(document.createElement("ul"));
					$(this).append( loader );
					$(this).append( list );
	
					plug_catalog.cart.getCartFullData( function( dataObj ){
						$.each( dataObj, function(id, item){
							var li = 	'<li>';
								li += 		'<a href="'+item.full_item_url+'">';
								li +=			(item.image!='') ?'<img src="'+item.image+'" />' : '<div class="no_photo"></div>';
								li +=			'<span class="name">'+item.name+'</span>';
								li +=			'<span class="count">'+item.count+'</span>';
								li +=			'<span class="mltp">&times;</span>';
								li +=			'<span class="currency">'+plug_catalog.settings.currency_symbol+'</span>';
								li +=			'<span class="price">'+item.price+'</span>';
								li += 		'</a>';
								li += 		'<a href="#" class="delete" data-id="'+id+'"></a>';
								li += 	'</li>';
							$(".catalog-card-splash ul").append( li );
						});
	
						$(".catalog-card-splash ul").slideDown(200);
						$(".catalog-card-splash .loader36").fadeOut(210, function(){
							$(this).remove();
							if( dataObj.length==0 ){
								$(".catalog-card-splash ul").append( '<li>Пусто</li>' );
							}
						});
					});

					//set close event
					$(document).on("click.cartclose", function(e){
					    if ($(e.target).closest(".catalog-card-splash").length) return;
					    $('.catalog-card-splash').slideUp(200, function(){
					    	$(this).remove();
					    	$(document).off("click.cartclose");
					    });
					    e.stopPropagation();	
					});
				});
				$(window).resize();
	
			});
		});

	},
	filter:{

		mode:'compact', // full | compact

		init:function(){

			var $filter = $(".plug_catalog .filter");

			if(!$filter.length) return;

			this.setMode( this.mode );


			var $slider = $("#price_range_slider");
			if($slider.length){
				var $priceMin = $filter.find("input[name='price_min']");
				var $priceMax = $filter.find("input[name='price_max']");

				var $bubleMin = $filter.find(".buble_min");
				var $bubleMax = $filter.find(".buble_max");
				$slider.slider({
					min:0,
					max:1000,
					step:10,
					range:true,
					animate:"fast",
					values: [ $priceMin.val(), $priceMax.val()],
					slide: function( event, ui ) {
						$priceMin.val( ui.values[0] );
						$priceMax.val( ui.values[1] );
						$bubleMin.text( core.number_format(ui.values[0] , false, 2, ' ') + ' '+ plug_catalog.settings.currency_symbol ).css({left: Math.floor(  (100*ui.values[0])/1000  ) +  "%"});
						$bubleMax.text( core.number_format(ui.values[1] , false, 2, ' ') + ' '+ plug_catalog.settings.currency_symbol ).css({left: Math.floor(  (100*ui.values[1])/1000  ) +  "%"});
					},
					create:function( event, ui ){
						$bubleMin.text( core.number_format($priceMin.val() , false, 2, ' ') + ' '+ plug_catalog.settings.currency_symbol ).css({left: Math.floor(  (100*$priceMin.val() )/1000  ) +  "%", opacity:1});
						$bubleMax.text( core.number_format($priceMax.val() , false, 2, ' ') + ' '+ plug_catalog.settings.currency_symbol ).css({left: Math.floor(  (100*$priceMax.val())/1000  ) +  "%", opacity:1});
					}
				});
	
				$priceMin.keyup(function(){
					$slider.slider( "values", [ $priceMin.val(),  $priceMax.val()] );
				});
				$priceMax.keyup(function(){
					$slider.slider( "values", [ $priceMin.val(), $priceMax.val()] );
				});
			}
	
			//show / hide brands full list in filter
			var brandsMaxVisible = 4;
			if( $filter.find(".block.brands>.selector").size() > brandsMaxVisible ){
				//if not exists checked inputs then hide more brandsMaxVisible
				if( $filter.find(".block.brands>.selector:gt("+(brandsMaxVisible-1)+") input:checked").size()==0 ){
					 $filter.find(".block.brands>.selector:gt("+(brandsMaxVisible-1)+")").hide();
					 $filter.find("a.show_all_brands").show();
				//if exists checked inputs then hide more brandsMaxVisible	 
				}else{
					$filter.find("a.hide_brands").show();
				}
				
				//show all brands button
				$filter.find("a.show_all_brands").click(function(e){
					e.preventDefault();
					$filter.find(".block.brands>.selector:hidden").slideDown(100);
					$(this).hide();
					$filter.find("a.hide_brands").show();
				});
	
				//show only most popular brands
				$filter.find("a.hide_brands").click(function(e){
					e.preventDefault();
					$filter.find(".block.brands>.selector:gt("+(brandsMaxVisible-1)+")").slideUp(100);
					$(this).hide();
					$filter.find("a.show_all_brands").show();
				});
			}
		
			//show / hide types full list in filter
			var typesMaxVisible = 4;
			if( $filter.find(".block.types>.selector").size() > typesMaxVisible ){
				//if not exists checked inputs then hide more typesMaxVisible
				if( $filter.find(".block.types>.selector:gt("+(typesMaxVisible-1)+") input:checked").size()==0 ){
					 $filter.find(".block.types>.selector:gt("+(typesMaxVisible-1)+")").hide();
					 $filter.find("a.show_all_types").show();
				//if exists checked inputs then hide more typesMaxVisible	 
				}else{
					$filter.find("a.hide_types").show();
				}
				
				//show all types button
				$filter.find("a.show_all_types").click(function(e){
					e.preventDefault();
					$filter.find(".block.types>.selector:hidden").slideDown(100);
					$(this).hide();
					$filter.find("a.hide_types").show();
				});
	
				//show only most popular types
				$filter.find("a.hide_types").click(function(e){
					e.preventDefault();
					$filter.find(".block.types>.selector:gt("+(typesMaxVisible-1)+")").slideUp(100);
					$(this).hide();
					$filter.find("a.show_all_types").show();
				});
			}


			//run filter button
			$filter.find("a.filter_apply").click(function(e){
				e.preventDefault();
				var filterArray = {};
				
				//for input type checkbox
				$filter.find("[data-type]:checked").each(function(){
					if( filterArray[ $(this).attr("data-type") ] == undefined ) filterArray[ $(this).attr("data-type") ] = [];
					filterArray[ $(this).attr("data-type") ].push( $(this).attr("data-value") );
				});
	
				//category
				if( $filter.find("input[name='category']").length ){
					var categoryId = $filter.find("input[name='category']").val();
					if( categoryId!="" ){
						filterArray['c'] = [];
						filterArray['c'].push( categoryId );
					} 
				}
	
				//price range
				filterArray['pr'] = [];
				filterArray['pr'].push( $filter.find("input[name='price_min']").val() );
				filterArray['pr'].push( $filter.find("input[name='price_max']").val()  );
	
	
				//for select
				var attrFilterArray = [];
				$filter.find("select[data-type] option:selected[value!='0']").each(function(){
					var $select = $(this).parent();
					var typeId = $select.attr("data-type");
					var attrId = $select.attr("data-attr");
	
					//return if type of this attr not checked
					if( !$filter.find("[data-type='t'][data-value='"+typeId+"']:checked").length ) return;
	
					//set array if not exist
					if( filterArray['attr'] == undefined ) filterArray['attr'] = [];
	
					//push to the filter
					filterArray['attr'] .push( 'and/' + typeId + '/' + attrId + '/' + $(this).val() );
	
				});
	
				//compile url
				var url = "";
				$.each( filterArray, function(marker, filterData){
					url += "/" + marker+"/" + filterData.join("/");
				});
	
				url = ( url == "" ) ? plug_catalog.settings.catalog_page_url : plug_catalog.settings.filterMarker + url;
				url = url.replace(/\/{2,}/gi, '/');
	
	
				//creat link
				$("body").append('<a id="filter_away_button" href="'+url+'"></a>');
				//console.log(url);
				//click
				var target = document.querySelector('#filter_away_button');
				var event = document.createEvent('MouseEvents');
				event.initMouseEvent('click', true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
				target.dispatchEvent(event); 
	
			});
			
			//filter reset button
			$filter.find("a.filter_reset").click(function(e){
				e.preventDefault();
				$filter.find("input:checked").removeAttr("checked").trigger("refresh");
				$filter.find("select option").removeAttr("selected").trigger("refresh");
				$filter.find("input[name='sortbyprice'][data-value='0']").attr("checked", "checked").trigger("refresh");
			});
	
	
			//filter show/hide attributes button
			$filter.find("a.show_attr").click(function(e){
				e.preventDefault();
				$(this).toggleClass("-open").parents(".selector").find(".block.attributes").slideToggle(140);
			});
	
			//filter show all attributes if is have selected values
			$filter.find(".attributes option[value!='0'][selected='selected']").parents(".selector").find("a.show_attr").click();
	
			//filter remove show_attr button if attributes not exist
			$filter.find("a.show_attr").each(function(){
				if( !$(this).parents(".selector").find(".block.attributes").length)	 $(this).remove();	
			});

		},

		toggle:function(){

			if( this.mode=='full' ){
				this.setMode('compact');
			}else{
				this.setMode('full');
			}
		},
		setMode:function( mode ){

			var filterBlock = document.querySelector('.filter');

			if( filterBlock.classList.contains('compact') && mode=='compact' ) return;
			if( !filterBlock.classList.contains('compact') && mode=='full' ) return;

			filterBlock.classList.add('hidden');
			this.mode = mode;
			setTimeout(function(){
				if( mode=='compact' ){
					filterBlock.classList.add( 'compact' );
				}else{
					filterBlock.classList.remove( 'compact' );
				}
				filterBlock.classList.remove('hidden');
			}, 400);


		}
	},

	loadSettings:function( callback ){
		core.plug.ajax("catalog", {action:"getSettings"}, function( json ){
			var settings = $.parseJSON( json );
			plug_catalog.settings = settings;
			if( callback && typeof(callback)=="function" ) callback();
		});
	},
	comparePage:function(){
		$(".catalog_compare_page .compare_block").each(function(){
			var outerWidth = 0;
			$(this).find("table").each(function(){
				outerWidth += $(this).outerWidth(true);
			});

			$(this).css({
				width: outerWidth  + "px",
				height: $(this).find("table:first").outerHeight(true) + "px"
			});
		});

		$(".catalog_compare_page .compare_block").each(function(){
			if( $(this).children("table").size()>1 ){
				$(this).parent(".compare_wrapper").addClass("sortable").sortable({ placeholder: "compare_placeholder"});
				$(this).sortable({ placeholder: "compare_placeholder", revert: 150});
			}
		});

		$(".catalog_compare_page .compare_block a.delete").click(function(e){
			e.preventDefault();
			var itemId = $(this).attr("data-id");
			plug_catalog.compare.removeAll( itemId );
			plug_catalog.infotips.compare.reload();
			$(".catalog_compare_page .compare_block table[data-id='"+itemId+"']").animate({
				opacity:0,
			}, 150, function(){
				$(this).css({width: $(this).outerWidth(true) + "px" })
				.html("")
				.animate({width:0}, 200, function(){
					var $mainPid = $(this).parents(".full_block_compare");
					$(this).remove();
					if( !$mainPid.find(".compare_block table").length ){
						$mainPid.slideUp(200, function(){
							$(this).remove();
						});
					}
				});
			});
		});

	},
	cartPage:function(){
		//delete item button event
		$(".catalog_cart_page table.items a.delete").click(function(e){
			e.preventDefault();
			core.blockScreen();
			var itemId = $(this).attr("data-id");
			plug_catalog.cart.removeAll( itemId );
			plug_catalog.infotips.cart.reload();
			$(this).hide();
			$(".catalog_cart_page table.items a.recover[data-id='"+itemId+"']").css({display:"block"});
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"']").addClass("removed");
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .updown a").addClass("disabled");
			core.unblockScreen();
		});

		//recover item button event
		$(".catalog_cart_page table.items a.recover").click(function(e){
			e.preventDefault();
			core.blockScreen();
			var itemId = $(this).attr("data-id");
			var $count = $(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .count");
			var count = parseInt( $count.text() );
			if( count==0 ){
				count = 1;
				$count.text("1");
			}

			plug_catalog.cart.addFew( itemId, count);
			plug_catalog.infotips.cart.reload();
			$(this).hide();
			$(".catalog_cart_page table.items a.delete[data-id='"+itemId+"']").css({display:"block"});
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"']").removeClass("removed");
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .updown a").removeClass("disabled");
			core.unblockScreen();
		});

		//add one item button event
		$(".catalog_cart_page table.items a.up").click(function(e){
			e.preventDefault();

			if( $(this).hasClass("disabled") ) return;
			
			core.blockScreen();
			var itemId = $(this).attr("data-id");
			var $count = $(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .count");
			var $price = $(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .price");
			var price = $price.text().replace(/\s/gi, '');
			
			var count = parseInt( $count .text() );
			$count.text( count+1 );

			var cost = ((count+1) * price).toFixed(2);
			cost = core.number_format(cost, 2, '.', ' ');
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .cost").text( cost );

			plug_catalog.cart.addOne( itemId, function(){
				core.unblockScreen();
			});
			plug_catalog.infotips.cart.reload();

			
		});

		//remove one item button event
		$(".catalog_cart_page table.items a.down").click(function(e){
			e.preventDefault();
			
			if( $(this).hasClass("disabled") ) return;

			core.blockScreen();
			var itemId = $(this).attr("data-id");
			var $count = $(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .count");
			var count = parseInt( $count.text() );
			var price = $(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .price").text().replace(/\s/gi, '');


			if( (count - 1)==0 ){
				core.unblockScreen();
				return;
			}


			var cost = ((count-1) * price).toFixed(2);
			cost = core.number_format(cost, 2, '.', ' ');
			$(".catalog_cart_page table.items tr[data-id='"+itemId+"'] .cost").text( cost );



			$count.text( count-1 );
			plug_catalog.cart.removeOne( itemId, function(){
				core.unblockScreen();
			});
			plug_catalog.infotips.cart.reload();
			if( (count-1)==0 )	$(".catalog_cart_page table.items a.delete[data-id='"+itemId+"']").click();
			
		});


		var $form = $(".catalog_cart_page form[name='userdata']");
		
		//mask input for phone
		$form.find("input[name='phone']").mask("+7(999)-999-99-99");
		$form.find("input[name='build']").numberMask({type:"int"});
		$form.find("input[name='liter']").numberMask({pattern:/[a-zA-Zа-яА-Я0-9]+/});
		$form.find("input[name='entrance']").numberMask({type:"int"});
		$form.find("input[name='floor']").numberMask({type:"int"});


		if( core.browser!="msie" || (core.browser=="msie" && core.browser.version>8) ){
			$form.find("input[name='delivery_date']").pickmeup({
				format: 'd.m.Y',
				 min: $form.find("input[name='delivery_date']").val()
			});
		}

		//get regions and paste into select
		core.wait(500, function(){
			
			var $selectReg = $form.find("select[name='region']");
			$selectReg.attr('disabled', 'disabled').trigger('refresh').find("option:gt(0)").remove();
			$selectReg.next('.jq-selectbox').addClass('updated');
			core.plug.ajax('catalog', {action:"getRegions"}, function( json ){
				var regions = $.parseJSON( json );
				$.each(regions, function(index, dataObj ){
					var $selectOpt = $('<option>', {value: dataObj.region_id, text:dataObj.region_name});
					$selectReg.append( $selectOpt ).trigger('refresh');
				});
				$selectReg.removeAttr('disabled').trigger('refresh').next('.jq-selectbox').removeClass('updated');
			});

			$selectReg.change(function(){
				var region_id = $(this).val();
				var $selectCit = $(".catalog_cart_page form[name='userdata'] select[name='city']");
				$selectCit.attr('disabled', 'disabled').trigger('refresh').find("option:gt(0)").remove();
				$selectCit.next('.jq-selectbox').addClass('updated');
				core.plug.ajax('catalog', {action:"getCities", region_id:region_id}, function( json ){
					var regions = $.parseJSON( json );
					$.each(regions, function(index, dataObj ){
						var $selectOpt = $('<option>', {value: dataObj.city_id, text:dataObj.city_name});
						$selectCit.append( $selectOpt ).trigger('refresh');
					});
					$selectCit.removeAttr('disabled').trigger('refresh').next('.jq-selectbox').removeClass('updated');
				});
			});

		});
		
		//more info for user button event
		$form.find(".more_info_toggle").click(function(e){
			e.preventDefault();
			$form.find(".more_data_block").slideToggle(600, "easeOutExpo");
			$form.find(".more_info_toggle").toggleClass("hidden");
		});

		//self delivery checkbox
		var addressFieldHeight = $form.find(".field.address").outerHeight(true);
		$form.find("input[type='checkbox'][name='self_delivery']").change(function(){
			$form.find(".field.address").slideToggle(600, "easeOutExpo");
		});

		//oferta consent checkbox
		$form.find("input[name='oferta_consent']").change(function(e){
			e.preventDefault();
			if( $(this).prop('checked') ){
				$form.find("button[name='save_order'][disabled]").removeAttr("disabled");
			}else{
				$form.find("button[name='save_order']").attr("disabled", "disabled");
			}
		});

		//show oferta button event
		$("a#show_oferta").click(function(e){
			e.preventDefault();
			core.blockScreen();
			core.plug.ajax('catalog', {action:'getOferta'}, function( html ){
				core.unblockScreen();
				$.splash( html, 
					{
						fullscreen:true, 
						cssClass:"oferta", 
						closeToEscape:true,
						openCallback:function(){
							core.fancyBoxLoad();
						}
					}
				);
			});
		});

		//save order button event
		$form.find("button[name='save_order']").click(function(e){
			e.preventDefault();
			core.blockScreen();
			var items = { items:plug_catalog.cart.items	}
			var data = $form.serialize() + "&action=saveOrder" + "&" + decodeURIComponent($.param( items ));
			core.plug.ajax("catalog", data, $form, function( json ){
				var response = $.parseJSON( json );
				plug_catalog.cart.clear();
				core.messageBox( response.message, 3000, function(){
					core.blockScreen();
					document.location.href = "/";
				});
			});
		});


		//keydon event on ENTER keyboard button
		$form.off("keydown.oninput");
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save_order']").click();
			}
		});


	},
	itemShowBullet:function( selector ){
		return;
		var name = selector.attr("title");
		var price = selector.attr("data-price");
		var bulletId = "bullet_" + Math.floor( Math.random()*9999 );
		var bullet = $(document.createElement("div")).attr("class", "item_bullet").attr(id, bulletId);
		var bulletName = $(document.createElement("div")).attr("class", "bullet_name").text(name);
		var bulletPrice = $(document.createElement("div")).attr("class", "bullet_price").text(price);

		selector.data( "bulletId", bulletId );
		$("body").append( bullet );
		bullet.append( bulletName );
		bullet.append( bulletPrice );
		bullet.show();
		//bullet.fadeIn( 200 );
	},
	itemHideBullet:function( selector ){

		var bulletId = selector.data( "bulletId", bulletId );
		if( bulletId == undefined ) return;

		$("#"+bulletId).fadeOut(50, function(){
			$(this).remove();
		});
	},
	itemPage:function( settings ){
		
		if( window.catalogReplyUserList == undefined )	window.catalogReplyUserList = {};

		var $itemPage = $(".catalog_item_page"),
		$form = $itemPage.find("form[name='comment']"),
		$wisibb_editor = $itemPage.find(".wisibb_editor"),
		$replyList = $form.find(".reply_list"),
		itemId = $form.attr("item-id");

		if( $itemPage.find(".comments").length ){
			
			//insert editor
			$wisibb_editor.wysibb({buttons: "bold,italic,underline,|,link,|,quote,|,smilebox", img_uploadurl:'/', debug:false, showHotkeys:false, hotkeys:false});

			//send comment button event
			$itemPage.find("button[name='send_comment']").click(function(e){
				core.blockScreen();

				var bbcode = $wisibb_editor.bbcode();

				$form.find("textarea[name='text']").val( bbcode );

				var data = $form.serialize() + "&action=sendComment&item_id="+itemId;
				var replyObj = {};

				replyObj.reply = window.catalogReplyUserList;
				
				var replyDecodeStr = decodeURIComponent($.param(replyObj));
				data = data + "&" + replyDecodeStr;
			
				core.plug.ajax("catalog", data, $form, function(){
					core.messageBox('<p>Спасибо за Ваш комментарий</p>', 2000, function(){
						core.blockScreen();
						core.reload();
					});
				});
			});

			//reply button event
			$itemPage.find("a.reply_to").click(function(e){
				e.preventDefault();

				var id = $(this).attr("data-id");
				var username = $(".anchor[href='#comment_"+id+"']").parents(".info").find(".username").text();
				
				window.catalogReplyUserList[ id ] = username;
				
				$replyList.html("");
				$form.find(".reply_to:visible").hide();

				$.each(window.catalogReplyUserList, function(id, uname){
					var $replyItem = $(document.createElement("span")).attr("class", "reply_item"),
						$replyUser = $(document.createElement("a")).attr("href", "#comment_"+id).text(uname),
						$replyRemove = $(document.createElement("a")).attr("href", "#").attr("class", "remove_reply_to").attr("id", id).text("X");
					
					$replyList.append( $replyItem );
					$replyItem.append( $replyUser ).append( $replyRemove );
					$form.find(".reply_to:hidden").show();
				});

				$.scrollTo("[name='editor_block']", 500, 500);
			});



			//reply button event
			$replyList.off("click.remove");
			$replyList.on("click.remove", "a.remove_reply_to", function(e){
				e.preventDefault();
				var id = $(this).attr("id");

				delete window.catalogReplyUserList[ id ];
				$form.find(".reply_to:visible").hide();
				$replyList.html("");

				$.each(window.catalogReplyUserList, function(id, uname){
					var $replyItem = $(document.createElement("span")).attr("class", "reply_item"),
						$replyUser = $(document.createElement("a")).attr("href", "#comment_"+id).text(uname),
						$replyRemove = $(document.createElement("a")).attr("href", "#").attr("class", "remove_reply_to").attr("id", id).text("X");
					

					$replyList.append( $replyItem );
					$replyItem.append( $replyUser ).append( $replyRemove );
					$form.find(".reply_to:hidden").show();
				});

			});


			//update comment captcha
			$form.find("img[src ^= '/captcha.php']").click(function(){
				plug_catalog.itemPageUpdateCaptcha();
			});

			$form.find("input[type='text']").keydown(function( e ) {
				if( e.keyCode == 13 ){
					event.preventDefault();
					$itemPage.find("button[name='send_comment']").click();
				}
			});
			

		}


		var tmpArray = [$(".leftblock .photo img").attr("src") ];
		$(".rightblock .otherimages img").each(function(){
			tmpArray.push( $(this).attr("src") );
		});
		
		$.preloadImages(tmpArray, function(){
			$(".leftblock .photo img").dhloupe(plug_catalog.loupeSettings);
		});


		$(".rightblock ul li a").click(function(e){
			e.preventDefault();
			$(".photo img").dhloupe({die:true});
			var newSrc = $(this).children("img").attr("src");
			$(".photo").children("img").fadeTo(200, 0.1, function(){
				$(".photo").children("img").attr("src", newSrc);

				$.preloadImages([newSrc], function(){
					$(".leftblock .photo img").dhloupe(plug_catalog.loupeSettings);
				});

				$(".photo").children("img").fadeTo(200, 1);
			});
		});	


		$(".accompanyings .container>ul").carouFredSel({
			width: '100%',
			height: 'auto',
			prev: '.accompanyings .prev',
			next: '.accompanyings .next',
			auto: true,
			scroll:1,
			pagination: ".accompanyings .pager>div",
			mousewheel: false,
			swipe: {
				onMouse: true,
				onTouch: true
			}
		});	


    

		$(".analogs .container>ul").carouFredSel({
			width: '100%',
			height: 'auto',
			prev: '.analogs .prev',
			next: '.analogs .next',
			auto: true,
			scroll:1,
			pagination: ".analogs .pager>div",
			mousewheel: false,
			swipe: {
				onMouse: true,
				onTouch: true
			}
		});	
    	

		//tabs
		$(".tabs .tabcontrols .ctrl").click(function(e){
			e.preventDefault();
			if( $(this).hasClass("active") ) return;

			var index = $(this).index();
			var $tabs = $(this).parents(".tabs");

			$tabs.find(".active").removeClass("active");
			$(this).addClass("active");

			$tabs.find(".tab.visible").removeClass("visible").stop(true, true).fadeOut(200);
			$tabs.find(".tab:eq("+index+")").addClass("visible").stop(true, true).fadeIn(230);
		});

		$(".tabs .tabcontrols .ctrl:first").click();

		//set bullet event for analogs
		$(".analogs .container>ul li a, .accompanyings .container>ul li a").hover( 
			function(){
				$(this).find(".item_bullet").slideUp(0).slideDown(200, "easeOutExpo", function(){
					$(this).find(".bullet_price").fadeIn(300);
				});
			},
			function(){
				$(this).find(".item_bullet").slideUp(120, "easeOutExpo", function(){
					$(this).find(".bullet_price").hide();
				});
			} 
		);
	},
	itemPageUpdateCaptcha:function(){
		var $form = $(".catalog_item_page form[name='comment']");
		var $captcha = $form.find("img[src ^= '/captcha.php']");
		var captchaSrc = $captcha.attr("src");
		captchaSrc = captchaSrc.replace(/nocache=.+$/gi, 'nocache='+Math.floor(Math.random()*999999));
		$captcha.attr("src", captchaSrc);
	},
	infotips:{
		compare:{
			init:function(){
				if( $("#infotip-compare-box").length>0 ) return;
				var box = $(document.createElement("a")).attr("href", plug_catalog.settings.comparePage).attr("id", "infotip-compare-box");
				var spanText = $(document.createElement("span")).text("В сравнении:");
				var spanUnits = $(document.createElement("span")).attr("class", "units").text("");
				var spanCount = $(document.createElement("span")).attr("class", "count");



				$("body").append( box );
				box.append( spanText ).append( spanCount ).append( spanUnits );
				this.reload();
				plug_catalog.cart.reload();
			},
			reload:function(callback){
				var that = this;
				this.hide(function(){
					if( !$.isEmptyObject(plug_catalog.compare.items) ){
						var allCount = 0;
						$.each( plug_catalog.compare.items, function( id, count ){
							for(var i=0;i<count;i++){
								allCount++;
							}
							if( callback!=undefined && typeof( callback )=="function" ) callback();
						});
						$("#infotip-compare-box span.count").text( allCount );
						$("#infotip-compare-box span.units").text( core.declOfNum( allCount, [plug_catalog.settings.item_nom, plug_catalog.settings.item_acc, plug_catalog.settings.item_nomp] ) );
						that.show();
					}else{
						if( callback!=undefined && typeof( callback )=="function" ) callback();	
					}
				});


			},
			show:function( callback ){
				$("#infotip-compare-box").animate({left:0}, 250, "easeOutExpo", function(){
					if( callback!=undefined && typeof( callback )=="function" ) callback();
				});
			},
			hide:function( callback ){
				var width = $("#infotip-compare-box").outerWidth(true);
				$("#infotip-compare-box").animate({left:-width+"px"}, 250, "easeOutExpo", function(){
					if( callback!=undefined && typeof( callback )=="function" ) callback();
				});
			}
		},
		cart:{
			init:function(){
				if( $("#infotip-cart-box").length>0 ) return;
				var box = $(document.createElement("a")).attr("href", plug_catalog.settings.cartPage).attr("id", "infotip-cart-box");
				var spanText = $(document.createElement("span")).text("В корзине:");
				var spanUnits = $(document.createElement("span")).attr("class", "units").text("");
				var spanCount = $(document.createElement("span")).attr("class", "count");


				$("body").append( box );
				box.append( spanText ).append( spanCount ).append( spanUnits );
				this.reload();
			},
			reload:function(callback){
				var that = this;
				this.hide(function(){
					if( !$.isEmptyObject(plug_catalog.cart.items) ){
						var allCount = 0;
						$.each( plug_catalog.cart.items, function( id, count ){
							for(var i=0;i<count;i++){
								allCount++;
							}
						});
						$("#infotip-cart-box span.count").text( allCount );
						$("#infotip-cart-box span.units").text( core.declOfNum( allCount, [plug_catalog.settings.item_nom, plug_catalog.settings.item_acc, plug_catalog.settings.item_nomp] ) );
						that.show();
						if( callback!=undefined && typeof( callback )=="function" ) callback();	
					}else{
						if( callback!=undefined && typeof( callback )=="function" ) callback();	
					}
				});
			},
			show:function( callback ){
				$("#infotip-cart-box").animate({left:0}, 250, "easeOutExpo", function(){
					if( callback!=undefined && typeof( callback )=="function" ) callback();
				});
			},
			hide:function( callback ){
				var width = $("#infotip-cart-box").outerWidth(true);
				$("#infotip-cart-box").animate({left:-width+"px"}, 250, "easeOutExpo", function(){
					if( callback!=undefined && typeof( callback )=="function" ) callback();
				});
			}
		}
	},
	//set view mode
	setViewMode:function( viewMode ){
		$.cookie("catalog_vm", viewMode, {expires:30, path:"/"});
		plug_catalog.viewMode = viewMode;
		$(".catalog_main_page .toolbar li>a.view_mode.act").removeClass("act");
		$(".catalog_main_page .toolbar li>a.view_mode[data-mode='"+viewMode+"']").addClass("act");
		$(".catalog_main_page .list_items").attr( "data-mode", viewMode );
	},
	//cart main object
	cart:{
		//items id from cart
		items: {},
		itemsCount : 0,
		itemsCost : 0,

		//init cart
		init:function(){
			if( $.cookie("catalog_cart")!=null ){
				this.items = JSON.parse( $.cookie("catalog_cart") );
			}
			$(".cart .button.compare").attr("href", plug_catalog.settings.comparePage);
			$(".cart .button.order").attr("href", plug_catalog.settings.cartPage);
		},
		getCartFullData:function( callback ){
			if( !callback || typeof( callback )!="function" ) return;
			core.plug.ajax("catalog", {action:"getCartFullData"}, function( json ){
				var dataObj = $.parseJSON( json );
				count = 0;
				cost = 0;
				$.each( dataObj, function(i, v){
					count++;
					cost = cost + parseFloat( v.cost );
				});

				var allCount = 0;
				$.each( dataObj, function( id, obj ){
					for(var i=0;i<obj.count;i++){
						allCount++;
					}
				});

				plug_catalog.cart.itemsCount = allCount;
				plug_catalog.cart.itemsPosCount = count;
				plug_catalog.cart.itemsCost = cost;
				callback( dataObj );
			});
		},	
		//reload cart
		reload:function(callback){
			
			var tmpObj = {};
			$.each( this.items, function(id, count){
				if( count != undefined && count != null ){
					tmpObj[ id ] = count;
				}
			});
			this.items = tmpObj;
			$.cookie("catalog_cart", JSON.stringify( this.items ), {expires:30, path:"/"});
			
			//set top panel infoemation
			plug_catalog.cart.getCartFullData(function( dataObj ){

				$(".cart [data-type='count']").text(plug_catalog.cart.itemsCount);
				$(".cart [data-type='pos_count']").text(plug_catalog.cart.itemsPosCount);
				$(".cart [data-type='itemword']").text(core.declOfNum( plug_catalog.cart.itemsCount, [plug_catalog.settings.item_nom, plug_catalog.settings.item_acc, plug_catalog.settings.item_nomp] ));
				
				if( plug_catalog.cart.itemsCost.toString().match(/\./gi)===null ){
					$(".cart [data-type='cost']").text( core.number_format(plug_catalog.cart.itemsCost, 0, '', ' ') );
				}else{
					$(".cart [data-type='cost']").text( core.number_format(plug_catalog.cart.itemsCost, 2, ',', ' ') );
				}
				$(".cart [data-type='currency']").text( core.declOfNum( plug_catalog.cart.itemsCost, [plug_catalog.settings.currency_nom, plug_catalog.settings.currency_acc, plug_catalog.settings.currency_nomp] ));
				$(".cart [data-type='currency_symbol']").text( plug_catalog.settings.currency_symbol);


				$("a.buy.in-cart").removeClass("in-cart");
				
				$.each(plug_catalog.cart.items, function(itemId, count){
					$("a.buy[data-id='"+itemId+"']").addClass("in-cart");
				});


				plug_catalog.cart.items = {};
				$.each( dataObj, function(id, data){
					plug_catalog.cart.items[id] = data.count;
				});
				core.unblockScreen();
				if( callback && typeof(callback)=="function" ) callback();

			});
			
		},
		//add one item to cart
		addOne:function( itemId, callback ){
			core.blockScreen();
			this.items[ itemId ] = ( this.items[ itemId ]==undefined ) ? 1 : parseInt( this.items[ itemId ] ) + 1;
			$.cookie("catalog_cart", JSON.stringify( this.items ), {expires:30, path:"/"});	
			this.reload(callback);
		},
		//add a few item to cart
		addFew:function( itemId, count, callback ){
			core.blockScreen();
			this.items[ itemId ] = ( this.items[ itemId ]==undefined ) ? count : parseInt( this.items[ itemId ] ) + count;
			$.cookie("catalog_cart", JSON.stringify( this.items ), {expires:30, path:"/"});	
			this.reload(callback);
		},
		//remove one item from cart
		removeOne:function( itemId, callback ){
			core.blockScreen();
			if( this.items[ itemId ]==undefined ) return;
			if( this.items[ itemId ]>1 ){
				this.items[ itemId ] = this.items[ itemId ] - 1;
			}else{
				this.items[ itemId ] = undefined;
			}
			this.reload(callback);
		},
		//remove all item of id from cart
		removeAll:function( itemId, callback ){
			core.blockScreen();
			if( this.items[ itemId ]==undefined ) return;
			this.items[ itemId ] = undefined;
			this.reload(callback);
		},
		//clear cart
		clear:function(callback){
			core.blockScreen();
			this.items = {};
			this.reload(callback);	
		}
	},
	//compare main object
	compare:{
		//items id from compare
		items: {},
		
		//init compare
		init:function(){
			if( $.cookie("catalog_compare")!=null ){
				this.items = JSON.parse( $.cookie("catalog_compare") );
			}
		},	
		//reload compare
		reload:function(){
			var tmpObj = {};
			
			$.each( this.items, function(id, count){
				if( count != undefined && count != null ){
					tmpObj[ id ] = count;
				}
			});

			this.items = tmpObj;
			$.cookie("catalog_compare", JSON.stringify( this.items ), {expires:30, path:"/"});	
		},
		//add one item to compare
		addOne:function( itemId ){
			this.items[ itemId ] = ( this.items[ itemId ]==undefined ) ? 1 : parseInt( this.items[ itemId ] ) + 1;
			$.cookie("catalog_compare", JSON.stringify( this.items ), {expires:30, path:"/"});	
		},
		//add a few item to compare
		addFew:function( itemId, count ){
			this.items[ itemId ] = ( this.items[ itemId ]==undefined ) ? count : parseInt( this.items[ itemId ] ) + count;
			$.cookie("catalog_compare", JSON.stringify( this.items ), {expires:30, path:"/"});	
		},
		//remove one item from compare
		removeOne:function( itemId ){
			if( this.items[ itemId ]==undefined ) return;
			if( this.items[ itemId ]>1 ){
				this.items[ itemId ] = this.items[ itemId ] - 1;
			}else{
				this.items[ itemId ] = undefined;
			}
			this.reload();
		},
		//remove all item of id from compare
		removeAll:function( itemId ){
			if( this.items[ itemId ]==undefined ) return;
			this.items[ itemId ] = undefined;
			this.reload();
		},
		//clear compare
		clear:function(){
			this.items = {};
			this.reload();	
		}
	}

}

$(function(){			
	if( typeof(plug_ajax)!="undefined" ){
		plug_ajax.ready(function(){
			plug_catalog.init();
		});
	}else{
		plug_catalog.init();
	}
});