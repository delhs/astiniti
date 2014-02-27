catalog = {

	plugName: 'catalog',
	tempDir:'/system/admin/temp',
	tempPath:'/system/admin/temp/',
	
	itemscurrentpage:1,
	brandscurrentpage:1,
	itemscountonpage:20,
	brandscountonpage:20,

	categoryLogosSizer:[400,400],
	brandLogosSizer:[400,200],
	itemLogosSizer:[800,800],

	init:function(){
		//#main menu events
 		$("#cap").on('click', "#plug_catalog_menu a[data-action]", function(e){
			e.preventDefault();
			catalog.viewTemplate($(this).attr("data-action"));
		});
	},
	
	viewTemplate:function( templateName, dataArray, callback ){
		admin.block();
		
		if( dataArray && typeof( dataArray )=="function" ){
			callback = dataArray;
			dataArray = undefined;
		}
		
		var dataObj = {};
		dataObj.action = templateName;
		if( dataArray && ( typeof( dataArray )=="array" || typeof( dataArray )=="object")){
			$.each(dataArray, function(i, v){
				dataObj[ i ] = v;
			});
		}

		switch( templateName )
		{
			case 'items_list': dataObj.countonpage = catalog.itemscountonpage; dataObj.page = catalog.itemscurrentpage; break;
			case 'brands_list': dataObj.countonpage = catalog.brandscountonpage; dataObj.page = catalog.brandscurrentpage; break;
		}
		
		$("#plug_catalog_menu a[data-action].act").removeClass("act");
		$(".plug_catalog").html( "" );
		admin.plug.ajax( catalog.plugName, dataObj, function( html ){
			$(".plug_catalog").html( html );
			$("#plug_catalog_menu a[data-action='"+templateName+"']").addClass("act");
			admin.rebuild();
			if( callback && typeof(callback)=="function" ) callback();
			admin.unblock();
		});
	},
	/*
	set surrent actrive category id
	@param - category ID
	*/
	setActiveCategory:function( id, callback ){
		admin.plug.ajax( catalog.plugName, {action:"setActiveCategory", id: id}, function(){
			if(callback && typeof(callback)=="function") callback();
		});
	},
	
	importTplInit:function(){

		//set input file change event
		$(".plug_catalog #importFile").change(function(){

			admin.plug.upload("importFile", "catalog", "uploadImportFile", function( filename, errorMessage ){
				if( errorMessage != "" ) return;
				
					
				//get import data information
				admin.block();
				admin.plug.ajax("catalog", {action:"getImportFileInfo", filename:filename}, function( json ){
					var data = $.parseJSON( json );
					
					if( data.failed != undefined ){
						admin.unblock();
						admin.infoBox('Загруженный файл поврежден.');
						return;
					}

					//set import data
					catalog.importCatalog.importData = data;
					
					//set filename
					catalog.importCatalog.filename = filename;

					//show labels
					$(".import .information .import_file_date").show().find("span").text( data.date );
					$(".import .information .import_file_time").show().find("span").text( data.time );

					//show labels
					if( (data.itemsAdd+data.typesAdd+data.categoriesAdd+data.brandsAdd)>0 ){
						$(".import .information .add_title_add").show();
						if( data.typesAdd >0 ) $(".import .information .import_file_add_types").show().find("b").text( data.typesAdd ).parent().find("span").text( admin.declOfNum( data.typesAdd, ['тип товара', 'типа товара', 'типов товара'] ) );
						if( data.itemsAdd >0 ) $(".import .information .import_file_add_items").show().find("b").text( data.itemsAdd ).parent().find("span").text( admin.declOfNum( data.itemsAdd, ['товар', 'товара', 'товаров'] ) );
						if( data.categoriesAdd >0 ) $(".import .information .import_file_add_categories").show().find("b").text( data.categoriesAdd ).parent().find("span").text( admin.declOfNum( data.categoriesAdd, ['категория', 'категории', 'категорий'] ) );
						if( data.brandsAdd >0 ) $(".import .information .import_file_add_brands").show().find("b").text( data.brandsAdd ).parent().find("span").text( admin.declOfNum( data.brandsAdd, ['производитель', 'производителя', 'производителей'] ) );
					}

					//show labels
					if( (data.itemsRemove+data.typesRemove+data.categoriesRemove+data.brandsRemove)>0 ){
						$(".import .information .remove_title").show();
						if( data.typesRemove >0 ) $(".import .information .import_file_remove_types").show().find("b").text( data.typesRemove ).parent().find("span").text( admin.declOfNum( data.typesRemove, ['тип товара', 'типа товара', 'типов товара'] ) );
						if( data.itemsRemove >0 ) $(".import .information .import_file_remove_items").show().find("b").text( data.itemsRemove ).parent().find("span").text( admin.declOfNum( data.itemsRemove, ['товар', 'товара', 'товаров'] ) );
						if( data.categoriesRemove >0 ) $(".import .information .import_file_remove_categories").show().find("b").text( data.categoriesRemove ).parent().find("span").text( admin.declOfNum( data.categoriesRemove, ['категория', 'категории', 'категорий'] ) );
						if( data.brandsRemove >0 ) $(".import .information .import_file_remove_brands").show().find("b").text( data.brandsRemove ).parent().find("span").text( admin.declOfNum( data.brandsRemove, ['производитель', 'производителя', 'производителей'] ) );
					}

					//enable button to start import
					$(".import button[name='startImport']").removeAttr("disabled");

					admin.unblock();

				});

			});
		});

		//set input file change event
		$(".plug_catalog #importArchive").change(function(){

			admin.plug.upload("importArchive", "catalog", "uploadImportImagesArchive", function( filename, errorMessage ){
				if( errorMessage != "" ) return;
				
				//get import data information
				admin.block();
				admin.plug.ajax("catalog", {action:"getImportArchiveInfo", archiveFilename:filename}, function( json ){
					var data = $.parseJSON( json );
					
					if( data.failed != undefined ){
						admin.unblock();
						admin.errorBox('Загруженный архив поврежден, либо не является ZIP архивом');
						return;
					}

					//set archive filename
					catalog.importCatalog.archiveFilename = filename;
					admin.unblock();

				});


			});
		});

		//set event to button of start import
		$(".import button[name='startImport']").click(function(){
			catalog.itemscurrentpage = 1;
			catalog.brandscurrentpage = 1;
			catalog.importCatalog.startUpdate("prepare");
		});
	},
	//import main object
	importCatalog:{
		//import archive filename
		archiveFilename:undefined,

		//unzipped folder name
		tmpFolderName:undefined,

		//import filename
		filename:undefined,

		//one count items
		oneCount:50,

		//all import items
		counter:undefined,

		//current step
		step:0,

		//importa data information object
		importData:{},

		startUpdate:function( value ){
			var that = this;
			var importData = catalog.importCatalog.importData;

			if( that.counter == undefined ){
				admin.block();
				that.counter = that.importData.ids.length;
			}

			that.remainingTime = Date.now();

			switch( value )
			{
				case 'prepare':
					if( that.archiveFilename==undefined ){
						that.startUpdate('remove');
						return;
					}

					admin.block( "Подготовка...");
					that.tmpFolderName = "unzip_" + Math.floor(Math.random()*9999);
					admin.plug.ajax("catalog", {action:"importPrepare", archiveFilename:that.archiveFilename, tmpFolderName:that.tmpFolderName}, function( json ){
						var data = $.parseJSON( json );
						
						if( data.failed != undefined ){
							admin.unblock();
							admin.errorBox('Не удалось Рразархивировать архив.');
							return;
						}
						that.startUpdate('remove');
						return;
					});

				break;

				case 'remove':
					if( that.importData.brandsRemove==0 &&  that.importData.categoriesRemove==0 && that.importData.typesRemove==0 ){
						that.startUpdate('categoriesAdd');
						return;
					}

					admin.block( "...");
					admin.plug.ajax("catalog", {action:"importRemoveUpdate", filename:that.filename}, function(){
						that.startUpdate('categoriesAdd');
						return;
					});
				break;

				case 'categoriesAdd':
					if( that.importData.categoriesAdd==0 ){
						that.startUpdate('brandsAdd');
						return;
					}

					admin.block( "....");
					admin.plug.ajax("catalog", {action:"importCategoriesUpdate", filename:that.filename, archiveFilename:that.archiveFilename, tmpFolderName:that.tmpFolderName}, function(){
						that.startUpdate('brandsAdd');
						return;
					});
				break;

				case 'brandsAdd':
					if( that.importData.brandsAdd==0 ){
						that.startUpdate('typesAdd');
						return;
					}
					admin.block( ".....");
					admin.plug.ajax("catalog", {action:"importBrandsUpdate", filename:that.filename, archiveFilename:that.archiveFilename, tmpFolderName:that.tmpFolderName}, function(){
						that.startUpdate('typesAdd');
						return;
					});
				break;

				case 'typesAdd':
					if( that.importData.typesAdd==0 ){
						that.startUpdate('itemsAdd');
						admin.block("0%");
						return;
					}
					admin.block( "......");
					admin.plug.ajax("catalog", {action:"importTypesUpdate", filename:that.filename}, function(){
						admin.block("0%");
						that.startUpdate('itemsAdd');
						return;
					});
				break;

				case 'itemsAdd':
					if( that.importData.itemsAdd==0 || that.importData.ids.length==0 ){
						admin.infotip('Импорт завершен');
						that.filename = undefined;
						that.archiveFilename = undefined;
						that.counter = undefined;
						that.step = 0;
						catalog.viewTemplate('items_list');
						admin.unblock();
						return;
					}

					if( that.importData.ids.length<that.oneCount ) that.oneCount = that.importData.ids.length;

					var data = {};
					data.action = "importItemUpdate";
					data.filename = that.filename;
					data.archiveFilename = that.archiveFilename;
					data.tmpFolderName = that.tmpFolderName;
					data.items = that.importData.ids.slice( that.importData.ids.length-that.oneCount  );
					//console.log( data.items );
					var recursiveDecoded = decodeURIComponent($.param(data));
					//send request to import for this one
					admin.plug.ajax("catalog", recursiveDecoded, function(){

						//set step
						that.step = that.step + that.oneCount;
		
						//set progress
						var progress = Math.floor( (that.step/that.counter)*100 );
						
						//view progress
						admin.block( progress + "%");
						

						that.importData.ids.splice( that.importData.ids.length-that.oneCount, that.oneCount );
						
						//call self
						that.startUpdate('itemsAdd');
					});

				break;
			}
		}

	},
	catListTplInit:function(){
	
		//sortable plugin for cagegories
		$(".plug_catalog .categories ul").sortable({
			placeholder: "hold",
			start: function( event, ui ){
				var holdHeight = $(ui.item).outerHeight(true);
				$(".plug_catalog .categories .hold").css({height: holdHeight + "px"});
			},
			stop: function(event, ui){
				var dataArray = {};
				var range = 0;
				$(this).children("li").each(function(){	
					var id = $(this).children("a[data-action]").attr("data-action");
					dataArray[ range ] = id;
					range++;
				});

				dataArray['action'] = 'catRangeChange';
				admin.plug.ajax(catalog.plugName, dataArray, function(){
					admin.infotip('Порядок следования категорий успешно изменен');
				});
			}
		});
		
		/* clear empty ul */
		$(".plug_catalog .categories ul li ul:empty").parent("li").children("a.node_ctrl.plus").removeClass("plus").addClass("empty");
		
		//click on category event
		$(".plug_catalog .categories a[data-get]").click(function(e){
			e.preventDefault();
			var id = $(this).attr('data-action');
			var name = $(this).text();
			catalog.setActiveCategory( id , function(){
				admin.infotip("Текущая категория: &laquo;"+name+"&raquo;");
			});
			$(this).parents("div.block.categories").find("ul li a").removeClass("act");
			$(this).addClass("act");
			return false;
		});

		/* node ctrl events */
		$(".plug_catalog .categories a.node_ctrl").click(function(e){
			e.preventDefault();
			$(this).toggleClass("plus");
			$(this).parent("li").find("ul:first").slideToggle(200);
			
			//compile string
			var openNodes = {};
			$(".plug_catalog .categories a.node_ctrl").not('.plus').not('.empty').each(function(){
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
				$(".plug_catalog .categories a.node_ctrl[data-id='"+id+"']").removeClass("plus").parent("li").find("ul:first").slideToggle(0);
			});
		}
		
		//toolbar buttons events
		$(".plug_catalog .toolbar a.show").click(function(e){
			e.preventDefault();
			$(".plug_catalog .categories a.node_ctrl.plus").click();
		});
		
		$(".plug_catalog .toolbar a.hide").click(function(e){
			e.preventDefault();
			$(".plug_catalog .categories a.node_ctrl").not(".plus").click();
		});
		
		$(".plug_catalog .toolbar a.invert").click(function(e){
			e.preventDefault();
			$(".plug_catalog .categories a.node_ctrl").click();
		});
		
		//visible first items
		$(".plug_catalog .categories>ul").show();
		
		//edit category button event
		$(".plug_catalog .categories a.edit").click(function(e){
			e.preventDefault();
			admin.block();
			var id = $(this).attr("data-id");
			catalog.setActiveCategory(id, function(){
				catalog.viewTemplate("edit_cat");
			});
		});
		
		//add category button event
		$(".plug_catalog .categories a.add").click(function(e){
			e.preventDefault();
			var pid = $(this).attr("data-pid");
			catalog.viewTemplate("add_cat", {pid:pid});
		});
		
		//delete category button event
		$(".plug_catalog .categories a.delete").click(function(e){
			e.preventDefault();
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			admin.confirmBox(
			"Удаление категории &laquo;"+name+"&raquo;. <br/>Внимание, все подкатегории, входящие в нее, так же будут удалены.<br/> Продолжить?", function(){
				admin.block();
				admin.plug.ajax(catalog.plugName, {action:"removeCategory", id:id}, function(){
					catalog.viewTemplate("cat_list");
				});
			});
		});
	},
 	addCatTplInit:function(){
		//set button save event
		var $form = $("form[name='plug_catalog_add_cat']");
		$form.find("input[name='name']").focus();
		$form.find("button[name='addCategory']").click(function(){
			admin.block();
			var data = $form.serialize() + "&action=addCategory";
			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.viewTemplate("cat_list");
			});
		});
		
		$form.find("input[name='name']").select();
		admin.tmp = 'translateEvent';
		$form.find("input[name='name']").blur(function(){
			if( admin.tmp == undefined ) return;
			admin.tmp = undefined;
		});
		$form.find("input[name='name']").keyup(function(){
			if( admin.tmp == undefined ) return;
			var link = $(this).val();
			link = link.translit().toLowerCase();
			$form.find("input[name='link']").val( link );
		});	

		$form.off('keydown');
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				$form.find("button[name='addCategory']").click();
			}
		});

		
	},
	
	editCatTplInit:function(){
		//set button save event
		var $form = $("form[name='plug_catalog_edit_cat']");
		
		admin.jcropApi = undefined;

		$form.find("button[name='saveEditedCat']").click(function(){
			admin.block();

			if( admin.jcropApi != undefined ){
				var cropObj = admin.jcropApi.tellSelect();
				//if crop width or height is zero, then set new width and height
				if( cropObj.w==0 || cropObj.h==0 ){
					cropObj.w = catalog.categoryLogosSizer[0];
					cropObj.h = catalog.categoryLogosSizer[1];
					cropObj.x = 0;
					cropObj.y = 0;
				}
				//floor the values and compile url string
				var urlStr = "";
				$.each( cropObj, function(i, v){
					urlStr += "&" + i + "=" + Math.floor(v);
				});
				var cat_logo = $form.find(".cat_logo>img").attr("src");
			}else{
				var cat_logo = $form.find(".cat_logo").hasClass("remove") ? "remove":"";
				var urlStr = "";
			}	

			var data = $form.serialize() + "&action=editCategory&cat_logo=" + cat_logo + urlStr;
			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.viewTemplate("cat_list");
			});
		});

		$form.find("button[name='removeCatLogo']").click(function(e){
			e.preventDefault();
			if( admin.jcropApi!=undefined ){
				admin.jcropApi.destroy();
				admin.jcropApi = undefined;
			}	
			$(this).addClass("hidden");
			$form.find(".cat_logo").removeClass("exist").addClass("remove");
			$form.find(".cat_logo>img").fadeOut(300);
		});

		$form.find("input[name='cat_logo']").change(function(){
			admin.block();
			admin.plug.upload( "cat_logo", catalog.plugName, "uploadCatLogo", function( filename, errorMessage ){
				if( filename.trim()=="failed" || filename.trim()=="" ){
					if( errorMessage!="" )
					admin.errorBox("<p>"+errorMessage+"</p>");
					else
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				}
				
				$(".cat_logo>img").error(function(){
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				});
				
				if( admin.jcropApi!=undefined ) admin.jcropApi.destroy();
				
				$form.find(".cat_logo.exist").removeClass("exist");
				$form.find(".cat_logo>img").attr("src", catalog.tempPath+filename.trim()).show(0, function(){
					$form.find(".cat_logo img").css({height:"auto"}).Jcrop({
							aspectRatio:  catalog.categoryLogosSizer[0] / catalog.categoryLogosSizer[1],
						}, function(){
						admin.jcropApi = this;
						var x = $form.find(".cat_logo img").outerWidth(true);
						var y = $form.find(".cat_logo img").outerHeight(true);
						admin.jcropApi.animateTo([ 0, 0,  catalog.categoryLogosSizer[0], catalog.categoryLogosSizer[1] ]);
					});
					
					$form.find("button[name='removeCatLogo']").removeClass("hidden");
					
				});
				admin.unblock();
			});
		});

		$form.off('keydown');
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				$form.find("button[name='saveEditedCat']").click();
			}
		});

	},
	
	brandsListTplInit:function(){

		$(".brands_list select[name='countonpage']").val(catalog.brandscountonpage);

		$(".brands_list table tbody td").hover(
			function(){
				$(this).parent("tr").addClass("hover");
				
				if($(this).attr("data-action")!=undefined){
					$(this).children(".icon").addClass("hover");
				}
			},
			function(){
				$(this).parents("table").find("span.icon.hover").removeClass("hover");
				$(this).parents("table").find("tr.hover").removeClass("hover");
			}
		);
		
		$(".brands_list table tbody td").click(function(){
			
			var property = $(this).attr("data-action");
			var id = $(this).parent("tr[data-id]").attr("data-id");
			
			if(property==undefined){
				var id = $(this).parent("tr").attr("data-id");
				catalog.viewTemplate("edit_brand", {id:id}, function(){
					$("#plug_catalog_menu a[data-action].act").removeClass("act");
					$("#plug_catalog_menu a[data-action='brands_list']").addClass("act");
				});
				return;
			}else{
				var set = ( $(this).children("span.icon").hasClass("on") ) ? '0' : '1';
				
				if(property=="delete"){
					admin.confirmBox("Вы действительно хотите удалить данный бренд?", function(){
 						admin.block();
						admin.plug.ajax(catalog.plugName, {action:"removeBrand", id:id}, function(){
							catalog.brandscurrentpage = 1;
							catalog.viewTemplate("brands_list");
						});
					});	
					return;					
				}
				
				$(this).children("span.icon").toggleClass("on");
				admin.plug.ajax(catalog.plugName, {action:"setBrandProperty", id:id, set:set, property:property}, function(){
					admin.infotip("Сохранено");
				});
				return;
			}
		});






		
		
		//sortable
		$(".brands_list table tbody").sortable({
			stop: function(event, ui){
				var dataArray = {};
				var range = 0;
				$(this).children("tr").each(function(){	
					var id = $(this).attr("data-id");
					dataArray[ range ] = id;
					range++;
				});

				dataArray['action'] = 'brandsRangeChange';
				admin.plug.ajax(catalog.plugName, dataArray, function(){
					admin.infotip('Порядок следования брендов успешно изменен');
				});
			}
		});
		
		
		//peganation
		$(".brands_list select[name='countonpage']").change(function(){
			catalog.brandscountonpage =  $(this).val();
			catalog.viewTemplate("brands_list");
		});
		
		$(".brands_list ul.nav a[data-num]").click(function(e){
			e.preventDefault();
			catalog.brandscurrentpage = $(this).attr("data-num");
			catalog.viewTemplate("brands_list");
		});
		
	},
	addBrandTplInit:function(){
		//set button save event
		var $form = $("form[name='plug_catalog_add_brand']");
		$form.find("input[name='name']").focus();
		$form.find("button[name='addBrand']").click(function(){
			var data = $form.serialize() + "&action=addBrand";
			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.brandscurrentpage = 1;
				catalog.viewTemplate("brands_list");
			});
		});
		
		$form.find("input[name='name']").select();
		admin.tmp = 'translateEvent';
		$form.find("input[name='name']").blur(function(){
			if( admin.tmp == undefined ) return;
			admin.tmp = undefined;
		});
		$form.find("input[name='name']").keyup(function(){
			if( admin.tmp == undefined ) return;
			var link = $(this).val();
			link = link.translit().toLowerCase();
			$form.find("input[name='link']").val( link );
		});	

		$form.off('keydown');
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				$form.find("button[name='addBrand']").click();
			}
		});

	},
	editBrandTplInit:function(){
		admin.insertEditor("form[name='edit_brand'] textarea[name='brand_descr']");
		admin.jcropApi = undefined;
		
		var $form = $("form[name='edit_brand']");
		$form.find("button[name='saveEditedBrand']").click(function(){
			admin.block();
			if( admin.jcropApi != undefined ){
				var cropObj = admin.jcropApi.tellSelect();
				//if crop width or height is zero, then set new width and height
				if( cropObj.w==0 || cropObj.h==0 ){
					cropObj.w = catalog.brandLogosSizer[0];
					cropObj.h = catalog.brandLogosSizer[1];
					cropObj.x = 0;
					cropObj.y = 0;
				}
				//floor the values and compile url string
				var urlStr = "";
				$.each( cropObj, function(i, v){
					urlStr += "&" + i + "=" + Math.floor(v);
				});
				var brand_logo = $form.find(".brand_logo>img").attr("src");
			}else{
				var brand_logo = $form.find(".brand_logo").hasClass("remove") ? "remove":"";
				var urlStr = "";
			}	

		
			var data = $form.serialize() + "&action=editBrand&brand_logo="+brand_logo + urlStr;
			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.viewTemplate("brands_list");
			});
		});	
		
		$form.find("button[name='removeBrandLogo']").click(function(e){
			e.preventDefault();
			if( admin.jcropApi!=undefined ){
				admin.jcropApi.destroy();
				admin.jcropApi = undefined;
			}	
			$(this).addClass("hidden");
			$form.find(".brand_logo").removeClass("exist").addClass("remove");
			$form.find(".brand_logo>img").fadeOut(300);
		});
		
		$form.find("input[name='brand_logo']").change(function(){
			admin.block();
			admin.plug.upload( "brand_logo", catalog.plugName, "uploadBrandLogo", function( filename, errorMessage ){
				if( filename.trim()=="failed" || filename.trim()=="" ){
					if( errorMessage!="" )
					admin.errorBox("<p>"+errorMessage+"</p>");
					else
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				}
				
				$form.find(".brand_logo>img").error(function(){
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				});
				
				if( admin.jcropApi!=undefined ) admin.jcropApi.destroy();
				
				$form.find(".brand_logo.exist").removeClass("exist");
				$form.find(".brand_logo>img").attr("src", catalog.tempPath+filename.trim()).show(0, function(){
					$form.find(".brand_logo img").css({height:"auto"}).Jcrop({
							aspectRatio:  catalog.brandLogosSizer[0] / catalog.brandLogosSizer[1],
						}, function(){
						admin.jcropApi = this;
						var x = $form.find(".brand_logo img").outerWidth(true);
						var y = $form.find(".brand_logo img").outerHeight(true);
						admin.jcropApi.animateTo([ 0, 0,  catalog.brandLogosSizer[0], catalog.brandLogosSizer[1] ]);
					});
					
					$form.find("button[name='removeBrandLogo']").removeClass("hidden");
					
				});
				admin.unblock();
			});
		});

		$form.off('keydown');
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				$form.find("button[name='saveEditedBrand']").click();
			}
		});

		
	},
	itemsListTplInit:function(){

		$(".items_list select[name='countonpage']").val(catalog.itemscountonpage);
		//filter select
		var filter = {};
		$(".items_list [data-type='select_filter'], .items_list [data-type='check_filter']").change(function(){
			
			$(".items_list select[data-type='select_filter'], .items_list input[type='radio'][data-type='check_filter']").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='checkbox'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='radio'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});
			
			catalog.itemscurrentpage = 1;
			catalog.brandscurrentpage = 1;
			catalog.viewTemplate("items_list", {filter:filter});
		});


		//set hover event for tr
		$(".items_list table tbody td").hover(
			function(){
				$(this).parent("tr").addClass("hover");
				
				if($(this).attr("data-action")!=undefined){
					$(this).children(".icon").addClass("hover");
				}
			},
			function(){
				$(this).parents("table").find("span.icon.hover").removeClass("hover");
				$(this).parents("table").find("tr.hover").removeClass("hover");
			}
		);
		
		$(".items_list table tbody td").click(function(){
			
			var property = $(this).attr("data-action");
			var id = $(this).parent("tr[data-id]").attr("data-id");
			
			if(property==undefined){
				var id = $(this).parent("tr").attr("data-id");
				catalog.viewTemplate("edit_item", {id:id}, function(){
					$("#plug_catalog_menu a[data-action].act").removeClass("act");
					$("#plug_catalog_menu a[data-action='items_list']").addClass("act");
				});
				return;
			}else{
				var set = ( $(this).children("span.icon").hasClass("on") ) ? '0' : '1';
				
				if(property=="delete"){
					admin.confirmBox("Вы действительно хотите удалить данный товар?<br/><i>Если данный товар присутствует в чьем-либо списке заказов, то такой товар будет помечен как &laquo;удаленный&raquo;</i>", function(){
 						admin.block();
						admin.plug.ajax(catalog.plugName, {action:"removeItem", id:id}, function(){
							catalog.itemscurrentpage = 1;
							catalog.viewTemplate("items_list");
						});
					});	
					return;					
				}
				
				if( set!='0' && $(this).children("span.icon").hasClass("popular") ){
					$(this).parent("tr").find("span.icon.popular.on").removeClass("on");
					$(this).children("span.icon.popular").addClass("on");
					
					admin.plug.ajax(catalog.plugName, {action:"setItemPopular", id:id, set:set, property:property}, function(){
						admin.infotip("Сохранено");
					});
					return;
				}else{
					$(this).children("span.icon").toggleClass("on");
					admin.plug.ajax(catalog.plugName, {action:"setItemProperty", id:id, set:set, property:property}, function(){
						admin.infotip("Сохранено");
					});
					return;
				}
			}
		});
		
		
		
		
		//peganation
		$(".items_list select[name='countonpage']").change(function(){

			$(".items_list select[data-type='select_filter'], .items_list input[type='radio'][data-type='check_filter']").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='checkbox'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='radio'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			catalog.itemscountonpage = $(this).val();
			catalog.itemscurrentpage = 1;

			catalog.viewTemplate("items_list", {filter:filter});
		});		
		
		$(".items_list ul.nav a[data-num]").click(function(e){
			e.preventDefault();
			
			$(".items_list select[data-type='select_filter'], .items_list input[type='radio'][data-type='check_filter']").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='checkbox'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			$(".items_list input[type='radio'][data-type='check_filter']:checked").each(function(){
				filter[ $(this).attr("name") ] = $(this).val();
			});

			catalog.itemscurrentpage = $(this).attr("data-num");
			catalog.viewTemplate("items_list", {filter:filter});
		});		
	
	},
	addItemTplInit:function(){
		//set button save event
		var $form = $("form[name='plug_catalog_add_item']");
		$form.find("input[name='name']").focus();
		$form.find("button[name='addItem']").click(function(){
			var data = $form.serialize() + "&action=addItem";
			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.itemscurrentpage = 1;
				catalog.viewTemplate("items_list");
			});
		});
		
		$form.find("input[name='name']").select();
		admin.tmp = 'translateEvent';
		$form.find("input[name='name']").blur(function(){
			if( admin.tmp == undefined ) return;
			admin.tmp = undefined;
		});
		$form.find("input[name='name']").keyup(function(){
			if( admin.tmp == undefined ) return;
			var link = $(this).val();
			link = link.translit().toLowerCase();
			$form.find("input[name='link']").val( link );
		});	
		
		$form.off('keydown');
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				$form.find("button[name='addItem']").click();
			}
		});
	},
	editItemTplInit:function(){
		admin.insertEditor("form[name='plug_catalog_edit_item'] textarea[name='item_desc']");
		
		var $form = $("form[name='plug_catalog_edit_item']");
		
		$form.find("input[name='price']").numberMask({type:"float", defaultValueInput:'0.00'});
		$form.find("input[name='old_price']").numberMask({type:"float", defaultValueInput:'0.00'});
		$form.find("input[name='discount']").numberMask({type:"int", defaultValueInput:'0'});
		
		//checked discount auto calculation
		$form.find("input[name='discount_calc_auto']").change(function(){
			if( $(this).prop("checked") ){
				$form.find("input[name='discount']").attr("disabled", "disabled");
			}else{
				$form.find("input[name='discount']").removeAttr("disabled");
			}
		});

		$form.find("input[name='create_date_time']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'});
		
		
		//button add analog
		$form.find("button[name='add_analog']").click(function(e){
			e.preventDefault();
			admin.block();
			var itemId = $form.find("input[name='id']").val();
			admin.plug.ajax( catalog.plugName, {itemId:itemId, action:"getAllForAnalogs"}, function( html ){
				$.splash( html, {
					fullscreen: true,
					openCallback: function(){
					
						$(".plug_catalog.categories").parents(".splash_splash.fullscreen").find("a.splash_close_btn").css({right:"8px"});
					
						$(".plug_catalog.categories").parents(".splash_splash.fullscreen").jScrollPane({
							showArrows:false,
							mouseWheelSpeed:50,
							verticalGutter:10,
							horizontalGutter:10,
							maintainPosition:true,
							autoReinitialise: true
						});						
						
						//delete add and remove buttons
						$(".plug_catalog.categories ul a.add, .plug_catalog .categories ul a.delete, .plug_catalog .categories ul a.edit").remove();
						
						//clear empty ul
						$(".plug_catalog.categories ul li ul:empty").parent("li").children("a.node_ctrl.plus").removeClass("plus").addClass("empty");
						
						//click on category event
						$(".plug_catalog.categories a[data-get]").click(function(e){
							e.preventDefault();
							
							if( $(this).data("loaded")=="true" ) return;
							
							admin.block();
							var myId = $("form[name='plug_catalog_edit_item'] input[name='id']").val();
							var that = $(this);
							var catId = $(this).attr("data-action");
							var ul = $("<ul>", {class:"items"});
							var b = $("<b>", {class:"empty", text:"Товаров не найдено"});
							
 							admin.plug.ajax( catalog.plugName, {catId:catId, action:"getItemsByCatId"}, function( json ){
								var dataObj = $.parseJSON( json );
								
								if( dataObj[0] !=undefined ){
									$.each( dataObj, function( i, data ){
										if( data.id == myId ) return;
										var selected = ( $("form[name='plug_catalog_edit_item'] ul.analogs a[item-id='"+data.id+"']").length==1 ) ? "selected" : "";
										var item = '<li class="item"><a href="#" class="'+selected+'" item-id="'+data.id+'">'+data.name+'</a></li>';
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
						$(".plug_catalog.categories").off("click.itemselect");
						$(".plug_catalog.categories").on("click.itemselect", "ul.items li.item>a[item-id]", function(e){
							e.preventDefault();
							$(this).toggleClass("selected");
							
							var itemId = $(this).attr("item-id");
							var itemName = $(this).text();
							var li = '<li><a href="#" item-id="'+itemId+'">'+itemName+'</a><a href="#" class="delete" title="Удалить из списка аналогов"></a></li>';
							
							if( $(this).hasClass("selected") ){
								$form.find("ul.analogs").append(li);
								admin.rebuild();
							}else{
								$form.find("ul.analogs a[item-id='"+itemId+"']").parent("li").remove();
							}
							
							var count = $form.find("ul.analogs>li").size();
							if( count > 0 ){
								$form.find("ul.analogs").parents(".spoiler:first").find(".spoiler-head b.count").html("("+count+")");
							}else{
								$form.find("ul.analogs").parents(".spoiler:first").find(".spoiler-head b.count").html("");
							}							
							
						});
						
						/* node ctrl events */
						$(".plug_catalog.categories a.node_ctrl").click(function(e){
							e.preventDefault();
							$(this).toggleClass("plus");
							$(this).parent("li").find("ul:first").slideToggle(200);
							
							//compile string
							var openNodes = {};
							$(".plug_catalog .categories a.node_ctrl").not('.plus').not('.empty').each(function(){
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
								$(".plug_catalog .categories a.node_ctrl[data-id='"+id+"']").removeClass("plus").parent("li").find("ul:first").slideToggle(0);
							});
						}
						
						//toolbar buttons events
						$(".plug_catalog.categories .toolbar a.show").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl.plus").click();
						});
						
						$(".plug_catalog.categories .toolbar a.hide").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl").not(".plus").click();
						});
						
						$(".plug_catalog.categories .toolbar a.invert").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl").click();
						});
						
						//visible first items
						$(".plug_catalog.categories .categories>ul").show();
						admin.unblock();
					}
				});
				
			});
		});
		
		//click on items in analog list
 		$form.find("ul.analogs").off("click.on_analogs_items");
		$form.find("ul.analogs").on("click.on_analogs_items", "a[item-id]", function(e){
			e.preventDefault();
			var id = $(this).attr("item-id");
			catalog.viewTemplate("edit_item", {id: id}, function(){
				admin.statePanelApi.scrollTo(0,0,100);
			});
		});		
		
		//click on delete items from analog list
		$form.find("ul.analogs").off("click.on_delete");
		$form.find("ul.analogs").on("click.on_delete", "a.delete", function(e){
			e.preventDefault();

			$(this).parent("li").slideUp(200, function(){
				$(this).remove();
				
				var count = $form.find("ul.analogs>li").size();
				if( count > 0 ){
					$form.find("ul.analogs").parents(".spoiler:first").find(".spoiler-head b.count").html("("+count+")");
				}else{
					$form.find("ul.analogs").parents(".spoiler:first").find(".spoiler-head b.count").html("");
				}
				
			});
		});

		//button add accompanying
		$form.find("button[name='add_accomp']").click(function(e){
			e.preventDefault();
			admin.block();
			var itemId = $form.find("input[name='id']").val();
			admin.plug.ajax( catalog.plugName, {itemId:itemId, action:"getAllForAccompaning"}, function( html ){
				$.splash( html, {
					fullscreen: true,
					openCallback: function(){
					
						$(".plug_catalog.categories").parents(".splash_splash.fullscreen").find("a.splash_close_btn").css({right:"8px"});
					
						$(".plug_catalog.categories").parents(".splash_splash.fullscreen").jScrollPane({
							showArrows:false,
							mouseWheelSpeed:50,
							verticalGutter:10,
							horizontalGutter:10,
							maintainPosition:true,
							autoReinitialise: true
						});						
						
						//delete add and remove buttons
						$(".plug_catalog.categories ul a.add, .plug_catalog .categories ul a.delete, .plug_catalog .categories ul a.edit").remove();
						
						//clear empty ul
						$(".plug_catalog.categories ul li ul:empty").parent("li").children("a.node_ctrl.plus").removeClass("plus").addClass("empty");
						
						//click on category event
						$(".plug_catalog.categories a[data-get]").click(function(e){
							e.preventDefault();
							
							if( $(this).data("loaded")=="true" ) return;
							
							admin.block();
							var myId = $form.find("input[name='id']").val();
							var that = $(this);
							var catId = $(this).attr("data-action");
							var ul = $("<ul>", {class:"items"});
							var b = $("<b>", {class:"empty", text:"Товаров не найдено"});
							
 							admin.plug.ajax( catalog.plugName, {catId:catId, action:"getItemsByCatId"}, function( json ){
								var dataObj = $.parseJSON( json );
								
								if( dataObj[0] !=undefined ){
									$.each( dataObj, function( i, data ){
										if( data.id == myId ) return;
										var selected = ( $form.find("ul.accompanying a[item-id='"+data.id+"']").length==1 ) ? "selected" : "";
										var item = '<li class="item"><a href="#" class="'+selected+'" item-id="'+data.id+'">'+data.name+'</a></li>';
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
						$(".plug_catalog.categories").off("click.itemselect");
						$(".plug_catalog.categories").on("click.itemselect", "ul.items li.item>a[item-id]", function(e){
							e.preventDefault();
							$(this).toggleClass("selected");
							
							var itemId = $(this).attr("item-id");
							var itemName = $(this).text();
							var li = '<li><a href="#" item-id="'+itemId+'">'+itemName+'</a><a href="#" class="delete" title="Удалить из списка супутствующих товаров"></a></li>';
							
							if( $(this).hasClass("selected") ){
								$form.find("ul.accompanying").append(li);
								admin.rebuild();
							}else{
								$form.find("ul.accompanying a[item-id='"+itemId+"']").parent("li").remove();
							}
							
							var count = $form.find("ul.accompanying>li").size();
							if( count > 0 ){
								$form.find("ul.accompanying").parents(".spoiler:first").find(".spoiler-head b.count").html("("+count+")");
							}else{
								$form.find("ul.accompanying").parents(".spoiler:first").find(".spoiler-head b.count").html("");
							}
							
						});
						
						/* node ctrl events */
						$(".plug_catalog.categories a.node_ctrl").click(function(e){
							e.preventDefault();
							$(this).toggleClass("plus");
							$(this).parent("li").find("ul:first").slideToggle(200);
							
							//compile string
							var openNodes = {};
							$(".plug_catalog .categories a.node_ctrl").not('.plus').not('.empty').each(function(){
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
								$(".plug_catalog .categories a.node_ctrl[data-id='"+id+"']").removeClass("plus").parent("li").find("ul:first").slideToggle(0);
							});
						}
						
						//toolbar buttons events
						$(".plug_catalog.categories .toolbar a.show").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl.plus").click();
						});
						
						$(".plug_catalog.categories .toolbar a.hide").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl").not(".plus").click();
						});
						
						$(".plug_catalog.categories .toolbar a.invert").click(function(e){
							e.preventDefault();
							$(".plug_catalog.categories .categories a.node_ctrl").click();
						});
						
						//visible first items
						$(".plug_catalog.categories .categories>ul").show();
						admin.unblock();
					}
				});
				
			});
		});		

		
		
		//click on items in accompanying list
 		$form.find("ul.accompanying").off("click.on_accompaning_items");
		$form.find("ul.accompanying").on("click.on_accompaning_items", "a[item-id]", function(e){
			e.preventDefault();
			var id = $(this).attr("item-id");
			catalog.viewTemplate("edit_item", {id: id}, function(){
				admin.statePanelApi.scrollTo(0,0,100);
			});
		});		
		
		//click on delete items from accompanying list
		$form.find("ul.accompanying").off("click.on_delete");
		$form.find("ul.accompanying").on("click.on_delete", "a.delete", function(e){
			e.preventDefault();
			$(this).parent("li").slideUp(200, function(){
				$(this).remove();
				var count = $form.find("ul.accompanying>li").size();
				if( count > 0 ){
					$form.find("ul.accompanying").parents(".spoiler:first").find(".spoiler-head b.count").html("("+count+")");
				}else{
					$form.find("ul.accompanying").parents(".spoiler:first").find(".spoiler-head b.count").html("");
				}
			});
		});
		
		//change item type select
		$form.find("select[name='type_id']").change(function(){
			var itemId = $("form[name='plug_catalog_edit_item'] input[name='id']").val();
			var typeId = $(this).val();
			admin.plug.ajax( catalog.plugName, {itemId:itemId, typeId:typeId, action:"reloadItemAttr"}, function( html ){
				$form.find(".block.attributes").html( html );
				admin.rebuild();
			});
		});
		
		
		//other images remove button
		var removedOtherImagesIdArray = [];
		$(".item_other_images").on("click", "a.remove_other_image", function(e){
			e.preventDefault();
			
			if( $(this).hasClass("exist") ){
				removedOtherImagesIdArray.push( $(this).attr("data-id") );
			}
			$(this).parent("div:first").hide(200, function(){
				$(this).remove();
			});
		});
		
		admin.jcropApi = undefined;

		//save button

		$form.find("button[name='saveEditedItem']").click(function(){
			admin.block();
			var otherImagesArray = [];
			var otherImagesArrayOrder = [];
			//other images
			if( $form.find(".item_other_images img" ).size()>0 ){
				$form.find(".item_other_images img" ).each(function(){
					if( $(this).hasClass("exist") ) return;
					var src = $(this).attr("src");
					otherImagesArray.push(src.basename());
				});
			}
			
			if( admin.jcropApi != undefined ){
				var cropObj = admin.jcropApi.tellSelect();
				//if crop width or height is zero, then set new width and height
				if( cropObj.w==0 || cropObj.h==0 ){
					cropObj.w = catalog.itemLogosSizer[0];
					cropObj.h = catalog.itemLogosSizer[1];
					cropObj.x = 0;
					cropObj.y = 0;
				}
				//floor the values and compile url string
				var urlStr = "";
				$.each( cropObj, function(i, v){
					urlStr += "&" + i + "=" + Math.floor(v);
				});
				var item_logo = $(".item_logo>img").attr("src");
			}else{
				var item_logo = $(".item_logo").hasClass("remove") ? "remove":"";
				var urlStr = "";
			}	

		
		

			var data = $form.serialize() + "&action=editItem&item_logo="+item_logo + urlStr;
			
			//analogs
			var analogsObj = {};
			analogsObj['analogs'] = {};
			if( $("form[name='plug_catalog_edit_item'] ul.analogs li").size()>0 ){
				$("form[name='plug_catalog_edit_item'] ul.analogs a[item-id]").each(function(){
					var id = $(this).attr("item-id");
					analogsObj['analogs'][ id ] = id;
				});
			}
			data += "&" + decodeURIComponent($.param(analogsObj));
			
			
			//accompanings
			var accompaningsObj = {};
			accompaningsObj['accompanying'] = {};
			if( $form.find("ul.accompanying li").size()>0 ){
				$form.find("ul.accompanying a[item-id]").each(function(){
					var id = $(this).attr("item-id");
					accompaningsObj['accompanying'][ id ] = id;
				});
			}
			data += "&" + decodeURIComponent($.param(accompaningsObj));
			
			
			//item attributes
			var attrObj = {};
			attrObj.attr = {};
			$form.find(".block.attributes select").each(function(){
				var attrId = $(this).attr("data-id");
				var attrValueId = $(this).val();
				attrObj['attr'][ attrId ] = attrValueId;
			});
			data += "&" + decodeURIComponent($.param(attrObj));

			
			
			if( otherImagesArray.length>0 ){
				$.each( otherImagesArray, function(i, v){
					data+="&other_image_"+i+"="+v;
				});
			}	
			
			if( removedOtherImagesIdArray.length>0 ){
				$.each( removedOtherImagesIdArray, function(i, v){
					data+="&other_image_remove_"+i+"="+v;
				});
			}

			admin.plug.ajax(catalog.plugName, data, $form, function(){
				catalog.viewTemplate("items_list");
			});
		});	
		
		$form.find("button[name='removeItemLogo']").click(function(e){
			e.preventDefault();
			if( admin.jcropApi!=undefined ){
				admin.jcropApi.destroy();
				admin.jcropApi = undefined;
			}	
			$(this).addClass("hidden");
			$form.find(".item_logo").removeClass("exist").addClass("remove");
			$form.find(".item_logo>img").fadeOut(300);
		});
		
		$form.find("input[name='item_logo']").change(function(){
			admin.block();
			admin.plug.upload( "item_logo", catalog.plugName, "uploadItemLogo", function( filename, errorMessage ){
				if( filename.trim()=="failed" || filename.trim()=="" ){
					if( errorMessage!="" )
					admin.errorBox("<p>"+errorMessage+"</p>");
					else
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				}
				
				
				$form.find(".item_logo>img").error(function(){
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				});
				
				if( admin.jcropApi!=undefined ) admin.jcropApi.destroy();
				
				$form.find(".item_logo.exist").removeClass("exist");
				$form.find(".item_logo>img").attr("src", catalog.tempPath+filename.trim()).show(0, function(){
					$form.find(".item_logo img").css({height:"auto"}).Jcrop({
							aspectRatio:  catalog.itemLogosSizer[0] / catalog.itemLogosSizer[1],
						}, function(){
						admin.jcropApi = this;
						var x = $form.find(".item_logo img").outerWidth(true);
						var y = $form.find(".item_logo img").outerHeight(true);
						admin.jcropApi.animateTo([ 0, 0,  catalog.itemLogosSizer[0], catalog.itemLogosSizer[1] ]);
					});
					
					$form.find("button[name='removeItemLogo']").removeClass("hidden");
					
				});
				admin.unblock();
			});
		});
		
		
		
		$form.find("input[name='item_other_images']").change(function(){
			admin.block();
			admin.plug.upload( "item_other_images", catalog.plugName, "uploadItemImages", function( filename, errorMessage ){
				if( filename.trim()=="failed" || filename.trim()=="" ){
					if( errorMessage!="" )
					admin.errorBox("<p>"+errorMessage+"</p>");
					else
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				}
				
				$form.find(".item_other_images>img").error(function(){
					admin.errorBox("<p>Во время загрузки изображения произошла ошибка.</p><p>Повторите попытку позже или выберите другой файл</p>");
					return;
				});
				
				var img = $("<img>", {src: catalog.tempPath+filename.trim()});
				var div = $("<div>");
				var a = $("<a>", {class:"remove_other_image", href:"#"});
				$form.find(".item_other_images").append( div );
				div.append(img);
				div.append(a);
				
			
				admin.unblock();
			});
		});
		
		
		//raiting stars
		$form.find(" ul.raiting li").hover(function(){
			var index = $(this).index() + 1;
			$(this).parent("ul").find("li:lt("+index+")").addClass("on").addClass("hover");
			$(this).parent("ul").find("li:gt("+index+")").removeClass("on");
		}, function(){
			$(this).parent("ul").find("li").removeClass("on").removeClass("hover");
		});
		
		$form.find("ul.raiting li a").click(function(e){
			e.preventDefault();
			var index = $(this).parent("li").index();
			$(this).parents("ul").find("li:lt("+(index+1)+")").addClass("set");
			$(this).parents("ul").find("li:gt("+index+")").removeClass("set");
			$form.find("input[name='raiting']").val( (index+1) );
		});
		
		$form.find("a.raitingnull").click(function(e){
			e.preventDefault();
			$form.find("ul.raiting li").removeClass("set").removeClass("on").removeClass("hover");
			$form.find("input[name='raiting']").val("0");
		});
		
		//key enter
		$(document).unbind('keydown');
		$form.find("input[type='text']").bind('keydown', function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='saveEditedItem']").click();
			}
		});
		
		//comments
		$toolbar = $form.find(".comments_list .toolbar");

		//delete comment button event
		$toolbar.find(".delete").click(function(e){
			e.preventDefault();
			var id = $(this).attr("data-id");
			admin.confirmBox('<p>Вы действительно хотите удалить этот комментарий?</p>', function(){
				admin.block();
				admin.plug.ajax('catalog', {action:'deleteComment',id:id}, function(){
					catalog.viewTemplate('edit_item', {id:$form.find("input[name='id']").val()});
				});
			});
		});

		//edit comment button event
		$toolbar.find(".edit").click(function(e){
			e.preventDefault();
			var id = $(this).attr("data-id");
			admin.block();
			admin.plug.ajax('catalog', {action:"edit_comment_form", id:id}, function( html ){
				$.splash(html, {cssClass:"catalog_edit_comment_form", openCallback:function(){
					admin.unblock();
					
					//set vars
					var $commentForm = $(".catalog_edit_comment_form form[name='edit_comment']");
					
					//datetime picker
					$commentForm.find("input[name='date_time']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'})
	
					//insert wisybb
					var $wisibb_editor = $commentForm.find("textarea[name='comment_text']").wysibb({buttons: "bold,italic,underline,strike,|,fontcolor,fontsize,|,smilebox,|,link,|", img_uploadurl:'/', debug:false, showHotkeys:false, hotkeys:false});
	
					//save button event
					$commentForm.find("button[name='saveEdited']").click(function(e){
						e.preventDefault();
						admin.block();
						$wisibb_editor.sync();
						var data = $commentForm.serialize() + "&action=saveEditedComment&id="+id;
						admin.plug.ajax('catalog', data, function(){
							$.splashClose();
							catalog.viewTemplate('edit_item', {id:$form.find("input[name='id']").val()});
						});
					});
				}});
			});
		});


		//reply button event
		$toolbar.find(".ireply").click(function(e){
			e.preventDefault();
			var pid = $(this).attr("data-id");
			admin.block();
			admin.plug.ajax('catalog', {action:"reply_comment_form"}, function( html ){
				$.splash(html, {cssClass:"catalog_reply_comment_form", openCallback:function(){
					admin.unblock();
					
					//set vars
					var $commentForm = $(".catalog_reply_comment_form form[name='reply_comment']");
					
					//datetime picker
					$commentForm.find("input[name='date_time']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'})
	
					//insert wisybb
					var $wisibb_editor = $commentForm.find("textarea[name='comment_text']").wysibb({buttons: "bold,italic,underline,strike,|,fontcolor,fontsize,|,smilebox,|,link,|", img_uploadurl:'/', debug:false, showHotkeys:false, hotkeys:false});
	
					//save button event
					$commentForm.find("button[name='addReply']").click(function(e){
						e.preventDefault();
						admin.block();
						$wisibb_editor.sync();
	
						var data = $commentForm.serialize() + "&action=addCommentReply&pid="+pid + "&item_id=" + $form.find("input[name='id']").val();
						admin.plug.ajax('catalog', data, function(){
							$.splashClose();
							catalog.viewTemplate('edit_item', {id:$form.find("input[name='id']").val()});
						});
					});
				}});
			});
		});


		//new comment button event
		$("#newComment").click(function(e){
			e.preventDefault();
			admin.block();
			admin.plug.ajax('catalog', {action:"reply_comment_form"}, function( html ){
				$.splash(html, {cssClass:"catalog_reply_comment_form", openCallback:function(){
					admin.unblock();
					
					//set vars
					var $commentForm = $(".catalog_reply_comment_form form[name='reply_comment']");
					
					//datetime picker
					$commentForm.find("input[name='date_time']").datetimepicker({timeFormat:"HH:mm:ss",dateFormat:'dd.mm.yy'})
	
					//insert wisybb
					var $wisibb_editor = $commentForm.find("textarea[name='comment_text']").wysibb({buttons: "bold,italic,underline,strike,|,fontcolor,fontsize,|,smilebox,|,link,|", img_uploadurl:'/', debug:false, showHotkeys:false, hotkeys:false});
	
					//save button event
					$commentForm.find("button[name='addReply']").click(function(e){
						e.preventDefault();
						admin.block();
						$wisibb_editor.sync();
	
						var data = $commentForm.serialize() + "&action=addCommentReply&pid=0&item_id=" + $form.find("input[name='id']").val();
						admin.plug.ajax('catalog', data, function(){
							$.splashClose();
							catalog.viewTemplate('edit_item', {id:$form.find("input[name='id']").val()});
						});
					});
				}});
			});
		});


		
	},

	settingsTplInit:function(){
		
	},
	itemTypesTplInit:function(){
		//add new type button
		$("form[name='item_types'] button[name='add_item_type']").click(function(e){
			e.preventDefault();
			var name = "t_" + Math.floor( Math.random() * 9999999 );
			var field = $('<div>', {class: "field"});
			var nopadding = $('<div>', {class: "nopadding"});
			var input = $('<input>', {type: "text", name: name, data_t:"type"});
			var a = $('<a>', {title: "Удалить тип товара", href: "#", class: "delete"});
			
			if( $("form[name='item_types'] .field:last").length>0 ){
				$("form[name='item_types'] .field:last").after( field );
			}else{
				$("form[name='item_types'] .block:first").append( field );
				
			}
			
			field.hide().append( nopadding );
			nopadding.append( input );
			nopadding.append( a );
			field.slideDown(200, function(){
				admin.rebuild();
				$("form[name='item_types'] input[data_t='type']:last").focus();
			});
		});
		
		
		var removedObj = {};
		
		//remove type button
		$("form[name='item_types']").on('click', 'a.delete', function(e){
			e.preventDefault();
			var typeId = $(this).parents(".field").find("[data-id]").attr("data-id");
			if( !isNaN( typeId )  ){
				removedObj[ typeId ] = typeId;
			}
			
			
			$(this).parents(".field").slideUp(200, function(){
				$(this).remove();
			});
		});
		
		
		//save all button
		$("form[name='item_types'] button[name='save']").click(function(e){
			e.preventDefault();
			admin.block();
			var form = $("form[name='item_types']");
			
			//view errors
			var errors = {};
			form.find("input[data_t='type']").each(function(){
				if( $(this).val().trim()=="" ){
					errors.validate = 'error';
					errors[ $(this).attr("name") ] = "Укажите название типа";
				}
			});
			
			if( errors.validate!=undefined ){
				admin.unblock();
				$.validate({res: JSON.stringify( errors ), form:form});
			}else{
				//save
				var data = {};
				form.find("input[data_t='type']").each(function(){
					if( $(this).attr("data-id")!=undefined ){
						var values = {};
						values.id = $(this).attr("data-id");
						values.value = $(this).val();
						
						data[ $(this).attr("data-id") ] = values;
					}else{
						var values = {};
						values.id = $(this).attr("name");
						values.value = $(this).val();
						data[ values.id ] = values;
					}
				});
				
				data.action = "saveTypes";
				data.removed = removedObj;
				admin.plug.ajax( catalog.plugName, data, function(){
					catalog.viewTemplate("item_types");
				});
			}
			
			

		});

		
		//key enter
		$("form[name='item_types']").off("keydown");
		$("form[name='item_types']").on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$("form[name='item_types'] button[name='save']").click();
			}
		});		
		
	},
	itemTypesAttrTplInit:function(){
		//hover
		$(".plug_catalog .catalog_types_attr table tbody td.in_list, .plug_catalog .catalog_types_attr table tbody td.in_filter, .plug_catalog .catalog_types_attr table tbody td.delete").hover(
			function(){
				$(this).addClass("hover");
			},
			function(){
				$(this).removeClass("hover");
			}
		);
		
		//icons toggle
		$(".plug_catalog .catalog_types_attr table").off("click.in_list");
		$(".plug_catalog .catalog_types_attr table").on("click.in_list", "td.in_list", function(){
			$(this).children("span.icon").toggleClass("on");
		});
		
		$(".plug_catalog .catalog_types_attr table").off("click.in_filter");
		$(".plug_catalog .catalog_types_attr table").on("click.in_filter", "td.in_filter", function(){
			$(this).children("span.icon").toggleClass("on");
		});
		
		

		//add new attr button
		$(".plug_catalog .catalog_types_attr button[name='add_type']").click(function(e){
			e.preventDefault();
			var typeId = $(this).attr("type-id");
			var rnd = Math.floor( Math.random()*99999 );
			var tr =		'<tr data-attr="new_'+rnd+'" data-type="new_'+rnd+'">';
				tr +=			'<td><input type="text" name="name" value="" /></td>';
				tr +=			'<td><input type="text" name="units" value="" /></td>';
				tr +=			'<td class="in_list"><span class="icon yellowcheck on"></span></td>';
				tr +=			'<td class="in_filter"><span class="icon greencheck on"></span></td>';
				tr +=			'<td class="delete"><span class="icon reddelete hover"></span></td>';
				tr +=		'</tr>';
			$(".plug_catalog .catalog_types_attr table[type-id='"+typeId+"']").append(tr);
		});
		
		var removedObj = {};

		//delete icon
		$(".plug_catalog .catalog_types_attr table").off("click.delete");
		$(".plug_catalog .catalog_types_attr table").on("click.delete", "td.delete", function(){
			var attrId = $(this).parent("tr").attr("data-attr");
			if( !isNaN( attrId )  ){
				removedObj[ attrId ] = attrId;
			}
			
			$(this).parent("tr").remove();
		});
		
		//save button
		$(".plug_catalog .catalog_types_attr button[name='save_type']").click(function(e){
			e.preventDefault();
			admin.block();
			var typeId = $(this).attr("type-id");
			var dataObj = {};
			var success = true;
			$(".plug_catalog .catalog_types_attr").find("table[type-id='"+typeId+"'] tbody tr").each(function(){
				
				if( $(this).find("input[name='name']").val().trim() == "" ){
					success = false;
					admin.unblock();
					admin.errorBox('<p>Ошибка. У каждого атрибута должно быть название.<br/>Проверьте, чтобы у всех атрибутов было заполнено поле &laquo;Название&raquo;</p>');
					return;
				}
				
				var attrId = $(this).attr("data-attr");
				if( dataObj[ attrId ]==undefined) dataObj[ attrId ] = {};
				dataObj[ attrId ] = {};
				dataObj[ attrId ]['name'] = $(this).find("input[name='name']").val();
				dataObj[ attrId ]['units'] = $(this).find("input[name='units']").val();
				dataObj[ attrId ]['inList'] = ( $(this).find("td.in_list span.icon").hasClass("on") ) ? 1 : 0;
				dataObj[ attrId ]['inFilter'] = ( $(this).find("td.in_filter span.icon").hasClass("on") ) ? 1 : 0;
			});
			
			if( !success ) return;
			
			dataObj.typeId = typeId;
			dataObj.action = "saveTypesAttr";
			dataObj.removed = removedObj;
			
			var data = decodeURIComponent($.param( dataObj ));
			admin.plug.ajax( catalog.plugName, data, function(){
				admin.unblock();
				admin.infotip("Сохранено");					
			});
		});
		
		$(".plug_catalog .catalog_types_attr").off("keydown");
		$(".plug_catalog .catalog_types_attr").on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$(this).parents(".spoiler:first").find("button[name='save_type']").click();
			}
		});

	},
	editTypesAttrValsInit:function(){
	
		//link go to item types
		$(".types_attr_vals a.goto_item_types").click(function(e){
			e.preventDefault();
			catalog.viewTemplate('item_types');
		});
		
		//link go to types attr
		$(".types_attr_vals a.goto_types_attr").click(function(e){
			e.preventDefault();
			catalog.viewTemplate('types_attr');
		});
		
		var removedObj = {};
		
		//delete value button
		$(".plug_catalog .types_attr_vals").off("click.delete");
		$(".plug_catalog .types_attr_vals").on("click.delete", "a.delete", function(e){
			e.preventDefault();
			var valueId = $(this).parents(".field:first").find("data-value").attr("data-value");
			if( !isNaN( valueId )  ){
				removedObj[ valueId ] = valueId;
			}
			$(this).parents(".field:first").slideUp(200, function(){
				$(this).remove();
			});
		});		
		
		//save button
		$(".plug_catalog .types_attr_vals button[name='save_attr_vals']").click(function(e){
			e.preventDefault();
			admin.block();
			var form = $("form[name='plug_catalog_types_attr_vals']");
			var attrId = $(this).attr("attr-id");
			var typeId = $(this).attr("type-id");
			
			var success = true;
			var dataObj = {};
			
			$(".types_attr_vals [data-attr-id='"+attrId+"'] input[data-value]").each(function(){
				
				if( $(this).val().trim() == "" ){
					success = false;
					admin.unblock();
					admin.errorBox('<p>Ошибка. Вы оставили одно из значений пустым.</p>');
					return;
				}
				
				
				var valueId = $(this).attr("data-value");
				if( dataObj[ valueId ]==undefined) dataObj[ valueId ] = {};
				dataObj[ valueId ] = $(this).val();	
			});

			if( !success ) return;
			
			dataObj.action = "saveTypesAttrVals";
			dataObj.typeId = typeId;
			dataObj.attrId = attrId;
			dataObj.removed = removedObj;
			var data = $.param( dataObj );
			admin.plug.ajax( catalog.plugName, data, function(){
				admin.unblock();
				admin.infotip('Сохранено');
			
			});
		});
		
		//add value button
		$(".plug_catalog .types_attr_vals button[name='add_att_val']").click(function(e){
			e.preventDefault();
			var form = $("form[name='plug_catalog_types_attr_vals']");
			var attrId = $(this).attr("attr-id");
			var typeId = $(this).attr("type-id");
			var newId = Math.floor( Math.random()*99999 );
			var html = 		'<div class="field">';
				html +=	 	'	<div class="nopadding">';
				html +=	 	'		<input type="text" name="name" data-value="new_'+newId+'" />';
				html +=	 	'		<a href="#" title="Удалить значение атрибута" class="delete"></a>';
				html +=	 	'	</div>';
				html +=	 	'</div>';
				
			form.find("[data-type='"+typeId+"'] [data-attr-id='"+attrId+"']").append( html );
		});
	
		$(".plug_catalog .types_attr_vals").off("keydown");
		$(".plug_catalog .types_attr_vals").on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$(this).parents(".spoiler:first").find("button[name='save_attr_vals']").click();
			}
		});
	},
	clearCatalogTplInit:function(){
		var $form = $("form[name='clear_catalog']");

		$form.find("button[name='clear_catalog']").click(function(e){
			e.preventDefault();
			admin.confirmBox('<p>Вы уаереня, что хотите выполнить очистку?<br/><i style="color: #FF3838;font-size: 20px;">Данное действие необратимо!</i></p>', function(){
				admin.block();
				var data = $form.serialize() + "&action=clearCatalog";
				admin.plug.ajax('catalog', data, function(){
					catalog.itemscurrentpage = 1;
					catalog.brandscurrentpage = 1;
					catalog.viewTemplate("cat_list");
				});
			});
		});
	},
	ordersListTplInit:function(){
		$(".plug_catalog .orders_list table tbody tr[data-id] td").click(function(){
			
			var orderId = $(this).parent("tr").attr("data-id");


			if( $(this).hasClass('remove') ){
				admin.confirmBox("<p>Вы действительно хотите удалить заказ?</p>", function(){
					admin.block();
					admin.plug.ajax("catalog", {action:"removeOrder", orderId:orderId}, function(){
						catalog.viewTemplate('orders_list');
					});
				});
				return;
			}

			admin.block();
			admin.plug.ajax('catalog', {action:"getOrderDetails", orderId:orderId}, function(html){
				admin.unblock();
				$.splash( html ,{
					openCallback:function(){
						admin.rebuild();

						var $mainWnd = $(".splash_splash .order_details.form");
						
						//main details window button save event
						$mainWnd.find("button[name='save_order_details']").click(function(e){
							e.preventDefault();
							admin.block();
							var order_status = $mainWnd.find("select[name='order_status']").val();
							var manager_comment =  $mainWnd.find("textarea[name='manager_comment']").val();
							var delivery_self = ($mainWnd.find("input[name='order_delivery_self']").prop("checked")) ? '0': '1';
							admin.plug.ajax('catalog', {action:"saveOrderDetails", order_status:order_status, manager_comment:manager_comment, orderId:orderId, delivery_self:delivery_self}, function(){
								admin.unblock();
								$.splashClose();
								catalog.viewTemplate('orders_list');
							});
						});

						//main details window button view user data event
						$mainWnd.find("#get_order_user_data").click(function(e){
							e.preventDefault();
							admin.block();
							admin.plug.ajax('catalog', {action:"getOrderUserData", orderId:orderId}, function(html){
								admin.unblock();
								$.splash(html, {openCallback: function(){
									var $form = $(".splash_splash form[name='order_user_data']");
									
									$form.find("input[name='phone']").mask("+7(999)-999-99-99");
									$form.find("input[name='build']").numberMask({type:"int"});
									$form.find("input[name='liter']").numberMask({pattern:/[a-zA-Zа-яА-Я0-9]+/});
									$form.find("input[name='entrance']").numberMask({type:"int"});
									$form.find("input[name='floor']").numberMask({type:"int"});

									//event save user data button event
									$form.find("button[name='save_user_data']").click(function(e){
										admin.block();
										var data = $form.serialize() + "&action=saveOrderUserData&orderId="+orderId;
										$.splashClose();
										admin.plug.ajax('catalog', data, function(){
											catalog.viewTemplate('orders_list');
										});
									});

									//event key ENTER
									$form.off("keydown");
									$form.on("keydown", "input[type='text']", function(e){
										if(e.keyCode == 13 ){
											e.preventDefault();
											$form.find("button[name='save_user_data']").click();
										}
									});

									//event change region select
									$form.find("select[name='region']").change(function(){
										var region_id = $(this).val();
										var $selectCit = $form.find("select[name='city']");
										$selectCit.attr('disabled', 'disabled').trigger('refresh').find("option:gt(0)").remove();
										$selectCit.next('.jq-selectbox').addClass('updated');
										admin.plug.ajax('catalog', {action:"getCities", region_id:region_id}, function( json ){
											var regions = $.parseJSON( json );
											$.each(regions, function(index, dataObj ){
												var $selectOpt = $('<option>', {value: dataObj.city_id, text:dataObj.city_name});
												$selectCit.append( $selectOpt ).trigger('refresh');
											});
											$selectCit.removeAttr('disabled').trigger('refresh').next('.jq-selectbox').removeClass('updated');
										});
									});

									admin.rebuild();
									
								}, closeToEscape: true});
							});
						});
						
						//main window items list view button event
						$mainWnd.find("#get_order_items_data").click(function(e){
							e.preventDefault();
							admin.block();
							admin.plug.ajax('catalog', {action:"getOrderItems", orderId:orderId}, function(html){
								admin.unblock();
								$.splash(html, {closeToEscape:true, fullscreen:true, cssClass:'order_items_splash', openCallback:function(){
									
									/*--replace this--*/
									$(".add_items_to_order").click(function(e){
										e.preventDefault();
										admin.block();
										admin.plug.ajax('catalog', {action:'items_list'}, function(html){
											admin.unblock();
											$.splash(html, {fullscreen:true, closeToEscape:true, cssClass:"add_items_to_order", openCallback:function(){
												$(".splash_splash.add_items_to_order tbody tr td").off('click');
												$(".splash_splash.add_items_to_order tbody tr").click(function(){
													var id = $(this).attr("data-id");
													//console.log('id is '+ id);
												});
											}});
										});
									});
									/*--END replace this--*/

								}});
							});
						});
					},
					closeToEscape:true
				});
			});
		});

	},
	catalogSettingsTplInit:function(){
		var $form = $(".plug_catalog form[name='catalog_settings_main']");

		$form.find("button[name='save_settings']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=catalogSaveSettings";
			admin.plug.ajax('catalog', data, $form, function(){
				catalog.viewTemplate('catalog_settings_main');
			});
		});

		//event key ENTER
		$form.off("keydown");
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save_settings']").click();
			}
		});

	},
	catalogSettingsTemplatesTplInit:function(){
		var $form = $(".plug_catalog form[name='catalog_settings_templates']");

		$form.find("button[name='save_settings_templates']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=catalogSaveSettingsTemplates";
			admin.plug.ajax('catalog', data, $form, function(){
				catalog.viewTemplate('catalog_settings_templates');
			});
		});
		//event key ENTER
		$form.off("keydown");
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save_settings_templates']").click();
			}
		});	
	},
	catalogSettingsClientTemplatesTplInit:function(){
		var $form = $(".plug_catalog form[name='catalog_settings_client_templates']");

		$form.find("button[name='save_settings_client_templates']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=catalogSaveSettingsClientTemplates";
			admin.plug.ajax('catalog', data, $form, function(){
				catalog.viewTemplate('catalog_settings_client_templates');
			});
		});
		//event key ENTER
		$form.off("keydown");
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save_settings_client_templates']").click();
			}
		});	
	},

	catalogSettingsOfertsTplInit:function(){
		var $form = $(".plug_catalog form[name='catalog_settings_oferta']");

		$form.find("button[name='save_oferta_settings']").click(function(e){
			e.preventDefault();
			admin.block();
			var data = $form.serialize() + "&action=catalogSaveOfertaSettings";
			admin.plug.ajax('catalog', data, function(){
				catalog.viewTemplate('catalog_settings_oferta');
			});
		});
		//event key ENTER
		$form.off("keydown");
		$form.on("keydown", "input[type='text']", function(e){
			if(e.keyCode == 13 ){
				e.preventDefault();
				$form.find("button[name='save_oferta_settings']").click();
			}
		});	
	}

}

$(function(){
	catalog.init();
});