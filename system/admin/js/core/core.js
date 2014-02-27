String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};

String.prototype.ltrim=function(){return this.replace(/^\s+/,'');};

String.prototype.rtrim=function(){return this.replace(/\s+$/,'');};

String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};

String.prototype.isInt=function(){return !isNaN(parseFloat(this)) && isFinite(this);};

String.prototype.md5=function(){function h(a,b){var c,d,f,e,g;f=a&2147483648;e=b&2147483648;c=a&1073741824;d=b&1073741824;g=(a&1073741823)+(b&1073741823);return c&d?g^2147483648^f^e:c|d?g&1073741824?g^3221225472^f^e:g^1073741824^f^e:g^f^e}function k(a,b,c,d,f,e,g){a=h(a,h(h(b&c|~b&d,f),g));return h(a<<e|a>>>32-e,b)}function l(a,b,c,d,f,e,g){a=h(a,h(h(b&d|c&~d,f),g));return h(a<<e|a>>>32-e,b)}function m(a,b,d,c,e,f,g){a=h(a,h(h(b^d^c,e),g));return h(a<<f|a>>>32-f,b)}function n(a,b,d,c,f,e,g){a=h(a,h(h(d^(b|~c),f),g));return h(a<<e|a>>>32-e,b)}function p(a){var b="",d="",c;for(c=0;3>=c;c++)d=a>>>8*c&255,d="0"+d.toString(16),b+=d.substr(d.length-2,2);return b}var f;f=[];var e,q,r,s,t,a,b,c,d;f=function(a){a=a.replace(/\r\n/g,"\n");for(var b="",d=0;d<a.length;d++){var c=a.charCodeAt(d);128>c?b+=String.fromCharCode(c):(127<c&&2048>c?b+=String.fromCharCode(c>>6|192):(b+=String.fromCharCode(c>>12|224),b+=String.fromCharCode(c>>6&63|128)),b+=String.fromCharCode(c&63|128))}return b}(this);f=function(b){var a,c=b.length;a=c+8;for(var d=16*((a-a%64)/64+1),e=Array(d-1),f=0,g=0;g<c;)a=(g-g%4)/4,f=8*(g%4),e[a]|=b.charCodeAt(g)<<f,g++;a=(g-g%4)/4;e[a]|=128<<8*(g%4);e[d-2]=c<<3;e[d-1]=c>>>29;return e}(f);a=1732584193;b=4023233417;c=2562383102;d=271733878;for(e=0;e<f.length;e+=16)q=a,r=b,s=c,t=d,a=k(a,b,c,d,f[e+0],7,3614090360),d=k(d,a,b,c,f[e+1],12,3905402710),c=k(c,d,a,b,f[e+2],17,606105819),b=k(b,c,d,a,f[e+3],22,3250441966),a=k(a,b,c,d,f[e+4],7,4118548399),d=k(d,a,b,c,f[e+5],12,1200080426),c=k(c,d,a,b,f[e+6],17,2821735955),b=k(b,c,d,a,f[e+7],22,4249261313),a=k(a,b,c,d,f[e+8],7,1770035416),d=k(d,a,b,c,f[e+9],12,2336552879),c=k(c,d,a,b,f[e+10],17,4294925233),b=k(b,c,d,a,f[e+11],22,2304563134),a=k(a,b,c,d,f[e+12],7,1804603682),d=k(d,a,b,c,f[e+13],12,4254626195),c=k(c,d,a,b,f[e+14],17,2792965006),b=k(b,c,d,a,f[e+15],22,1236535329),a=l(a,b,c,d,f[e+1],5,4129170786),d=l(d,a,b,c,f[e+6],9,3225465664),c=l(c,d,a,b,f[e+11],14,643717713),b=l(b,c,d,a,f[e+0],20,3921069994),a=l(a,b,c,d,f[e+5],5,3593408605),d=l(d,a,b,c,f[e+10],9,38016083),c=l(c,d,a,b,f[e+15],14,3634488961),b=l(b,c,d,a,f[e+4],20,3889429448),a=l(a,b,c,d,f[e+9],5,568446438),d=l(d,a,b,c,f[e+14],9,3275163606),c=l(c,d,a,b,f[e+3],14,4107603335),b=l(b,c,d,a,f[e+8],20,1163531501),a=l(a,b,c,d,f[e+13],5,2850285829),d=l(d,a,b,c,f[e+2],9,4243563512),c=l(c,d,a,b,f[e+7],14,1735328473),b=l(b,c,d,a,f[e+12],20,2368359562),a=m(a,b,c,d,f[e+5],4,4294588738),d=m(d,a,b,c,f[e+8],11,2272392833),c=m(c,d,a,b,f[e+11],16,1839030562),b=m(b,c,d,a,f[e+14],23,4259657740),a=m(a,b,c,d,f[e+1],4,2763975236),d=m(d,a,b,c,f[e+4],11,1272893353),c=m(c,d,a,b,f[e+7],16,4139469664),b=m(b,c,d,a,f[e+10],23,3200236656),a=m(a,b,c,d,f[e+13],4,681279174),d=m(d,a,b,c,f[e+0],11,3936430074),c=m(c,d,a,b,f[e+3],16,3572445317),b=m(b,c,d,a,f[e+6],23,76029189),a=m(a,b,c,d,f[e+9],4,3654602809),d=m(d,a,b,c,f[e+12],11,3873151461),c=m(c,d,a,b,f[e+15],16,530742520),b=m(b,c,d,a,f[e+2],23,3299628645),a=n(a,b,c,d,f[e+0],6,4096336452),d=n(d,a,b,c,f[e+7],10,1126891415),c=n(c,d,a,b,f[e+14],15,2878612391),b=n(b,c,d,a,f[e+5],21,4237533241),a=n(a,b,c,d,f[e+12],6,1700485571),d=n(d,a,b,c,f[e+3],10,2399980690),c=n(c,d,a,b,f[e+10],15,4293915773),b=n(b,c,d,a,f[e+1],21,2240044497),a=n(a,b,c,d,f[e+8],6,1873313359),d=n(d,a,b,c,f[e+15],10,4264355552),c=n(c,d,a,b,f[e+6],15,2734768916),b=n(b,c,d,a,f[e+13],21,1309151649),a=n(a,b,c,d,f[e+4],6,4149444226),d=n(d,a,b,c,f[e+11],10,3174756917),c=n(c,d,a,b,f[e+2],15,718787259),b=n(b,c,d,a,f[e+9],21,3951481745),a=h(a,q),b=h(b,r),c=h(c,s),d=h(d,t);return(p(a)+p(b)+p(c)+p(d)).toLowerCase()};

String.prototype.translit=function(){var c={"\u0410":"A","\u0430":"a","\u0411":"B","\u0431":"b","\u0412":"V","\u0432":"v","\u0413":"G","\u0433":"g","\u0414":"D","\u0434":"d","\u0415":"E","\u0435":"e","\u0401":"Yo","\u0451":"yo","\u0416":"Zh","\u0436":"zh","\u0417":"Z","\u0437":"z","\u0418":"I","\u0438":"i","\u0419":"Y","\u0439":"y","\u041a":"K","\u043a":"k","\u041b":"L","\u043b":"l","\u041c":"M","\u043c":"m","\u041d":"N","\u043d":"n","\u041e":"O","\u043e":"o","\u041f":"P","\u043f":"p","\u0420":"R","\u0440":"r","\u0421":"S","\u0441":"s","\u0422":"T","\u0442":"t","\u0423":"U","\u0443":"u","\u0424":"F","\u0444":"f","\u0425":"Kh","\u0445":"kh","\u0426":"Ts","\u0446":"ts","\u0427":"Ch","\u0447":"ch","\u0428":"Sh","\u0448":"sh","\u0429":"Sch","\u0449":"sch","\u042a":"","\u044a":"","\u042b":"Y","\u044b":"y","\u042c":"","\u044c":"","\u042d":"E","\u044d":"e","\u042e":"Yu","\u044e":"yu","\u042f":"Ya","\u044f":"ya"," ":"_","-":"_","@":"_","!":"_","#":"_","%":"_",$:"_","^":"_","&":"_","*":"_","(":"_",")":"_","'":"_",'"':"_","\u2116":"_",";":"_",":":"_","?":"_","+":"_","<":"_",">":"_",".":"_",",":"_"},a="",b;for(b in c)a+=b;a=RegExp("["+a+"]","g");b=function(a){return a in c?c[a]:""};return function(){return this.replace(a,b)}}();

String.prototype.basename=function(){parts = this.split( '/' );return parts[parts.length-1];};

var admin = {
	themes:undefined,
	uid: undefined,
	username:undefined,
	urlPref:undefined,
	tmp:undefined,
	multylang:undefined,
	handle:null,
	statePanel:null,
	statePanelApi:{},
	currentPanel:'parts',
	currentPageId:1,
	failedWinSize:false,
	
	general:{
		overrideF5Key:false,//override the F5 key flag
		connectionVerifying:false,//check the connection flag
		connectionInterval: 1000 * 30,//interval of checking connection
		connectionIntervalIfFailedinterval: 1000,//interval of checking connection if connection is failed
		connectionAttemptsCountIfFailed:100,//coun of attempts trying to connect if connection is failed,
		themesSupport:false,//themes
		admUrl:'/admin/',
		set:function( prop, value ){
			admin.block();
			this[prop] = value;
			admin.init();
		}
	},
	
	/*just wait*/
	wait:function(delay, callback){
		if( callback==undefined || typeof(callback)!="function" ) return false;
		setTimeout(callback,delay);
	},
	setCursor:function( elemId ){
		document.getElementById(elemId).focus();
		return false;
	},
	/* just view login interface */
	auth:{
		attempts:0,
		login:function(){

			if(  $("#load_all").is(":hidden") ){
				$("#load_all").fadeIn(200);
			}
			
			if( admin.uid!=undefined ){
				admin.init();
				return true;
				
			}else{
				$(".loadercicle").fadeOut(200, function(){
					$("input[name='login']").animate({ opacity:1 }, 200, function(){
						admin.setCursor( "login" );
					});
				});
			}
			$(document).off("keypress", "input[name='login']");
			$(document).on("keypress", "input[name='login']", function (e) {
				if (e.which == 13) {
					if ($("#load_all .tooltip[for='login']").is(":visible") ){
						$("#load_all .tooltip[for='login']").fadeOut(200);
					}
					
					$("input[name='login']").animate({marginLeft:"-200px", opacity:0}, 200, "easeOutQuart", function(){ 
						$("input[name='password']").animate({marginTop:"26px", opacity:1}, 120, "easeOutQuart", function(){
							admin.setCursor( "password" );
							if( this.attempts>0  ){
								$("#load_all .tooltip[for='password']").fadeIn(400);
							}
						});
					});
					
				}
			});
			
			$(document).off("keypress", "input[name='password']");
			$(document).on("keypress", "input[name='password']", function (e) {
				
				if (e.which == 13) {
					if ($("#load_all .tooltip[for='password']").is(":visible") ){
						$("#load_all .tooltip[for='password']").fadeOut(200);
					}
					
					$("input[name='password']").animate({marginTop:"9px", opacity:0}, 350, "easeOutQuart", function(){
						$(".loadercicle").fadeIn(350);
						var ilogin = $("input[name='login']").val();
						var ipassword = $("input[name='password']").val();
						try{
							$.post(admin.general.admUrl, {login: ilogin, password: ipassword, nocash:Math.floor(Math.random()*9999)}, function( respArray ){
								$("input[name='login']").css({marginLeft:"-75px"}).val("");
								$("input[name='password']").css({marginTop:"60px"}).val("");
								if( typeof(respArray)=='string' && respArray.trim() == 'NULL'){
									admin.auth.attempts++;
									$(".loadercicle").fadeOut(350, function(){
										$("input[name='login']").animate({opacity:1},350, function(){
											admin.setCursor( "login" );
											if( admin.auth.attempts > 0 ){
												$("#load_all .tooltip[for='login']").fadeIn(400);
											}
										});
									});
									
								}else{
									var respObj = $.parseJSON( respArray );
									admin.uid = respObj.uid;
									admin.username = respObj.username;
									$("#toppanel .username b").html( admin.username );
									admin.init();
								} 
							});
						}catch(e){
							admin.connection.failed();
							return false;
						}
					});
					
	
				}
			});
		},
		logout:function(){
			/* reset authorization params */
			admin.uid = undefined;
			admin.username = undefined;
			admin.auth.attempts = 0;
			
			/* set state mode if current is air */
			if( admin.mode.current=="air" ) admin.mode.state.load();

			/* hide state content */
			$(".state .inner").hide();
			
			/* hide top panel */
			$("#toppanel").hide();
			
			/* hide cap panel */
			$("#cap").hide();
			
			/* sent logout event in to the server */
			try{
				$.post(admin.general.admUrl, {logout: null, nocash:Math.floor(Math.random()*9999)});
			}catch(e){
				admin.connection.failed();
				return false;
			}
			/* view login window */
			admin.auth.login();
			
		}
	},
	/* reload admin panel */
	reload:function(){
		document.location.reload();
	},
	/* main script for initialization */
	init:function(){
		if( this.browser.name==null ) this.browser.detect();
		admin.handle = true;
		
		if( admin.uid===undefined ){
			this.auth.login();
			return;
		}		
		
		$(document).off('keydown.overrideF5Key keyup.overrideF5Key');
		if(this.general.overrideF5Key){
			$(document).on('keydown.overrideF5Key keyup.overrideF5Key', function(e) {
				if(e.which === 116) {
					if( admin.override!=undefined ) return false;
					admin.block();
					admin.override = true;
					admin.reloadPanel(function(){
						admin.override=undefined; 
					});
					return false;
				}
				if(e.which === 82 && e.ctrlKey) {
					if(  admin.override!=undefined  ) return false;
					admin.block();
					admin.override = true;
					admin.reloadPanel(function(){
						admin.override=undefined; 
					});
					return false;
				}
			});
		}


		this.resizing();
		
		/* load theme */
		if(this.general.themesSupport){
			$("#toppanel a.changetheme:hidden").show();
			if( $.cookie("theme")!=null ){
				admin.theme.load( $.cookie("theme") );
			}
		}else{
			$("#toppanel a.changetheme").hide();
		}
		
		/* view top panel */
		$("#toppanel").show();
		
		/* view cap panel */
		$("#cap").show();
		
		$("#toppanel .username b").html( this.username );

		//set event for toggle menu button
		$("#toppanel").off("click");
		
		$("#toppanel").on("click", "a.multylang", function(e){
			e.preventDefault();
			$("#multylanglist").splash();
		});		
		
		
		$("#multylanglist").off("click");
		$("#multylanglist").on("click", "ul a", function(e){
			e.preventDefault();
			if( !$(this).hasClass("act") ){
				admin.setLang($(this).attr("data-lang"));
				$(this).parents("ul").find("a").removeClass("act");
				$(this).addClass("act");
			}
			$.splashClose();
		});			
		
		$("#toppanel").on("click", "a.togglemmenubutton", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mainMenu.toggle();
		});		
		
		//set event for state mode button
		$("#toppanel").on("click", "a.statemodebutton", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.state.load();
		});		
		
		//set event for air mode button
		$("#toppanel").on("click", "a.airmodebutton", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.air.load();
		});	
		
		//set event for save content button
		$("#toppanel").on("click", "a.save", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.air.saveContent();
		});		
		
		//set event for back to state mode button
		$("#toppanel").on("click", "a.back", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.state.load();
		});		
		
		//set event for reload button
		$("#toppanel").on("click", "a.reload", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.air.reload();
		});		
		
		//set event for visible button
		$("#toppanel").on("click", "a.visible", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.air.visibleMesh();
		});	
		
		//set event for editor button
		$("#toppanel").on("click", "a.editor", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			admin.mode.air.toggleEditor();
		});				
		

		
		//set event for changetitle button
		$("#toppanel").on("click", "a.changetitle", function(e){
			e.preventDefault();
			if( $(this).hasClass("disabled") ) return;
			$(this).addClass("act");
			var html =  '<div class="page_main_title_splash">';
				html += '<p>Заголовок раздела</p>';
				html += '<input type="text" name="page_main_title" value="'+$("#air").contents().find("istitle:first").html()+'"/>';
				html += '<button name="savePageTitle">OK</button>';
				html += '</div>';
			$.splash(
				html, {
				closeCallback:function(){
					$("#toppanel a.changetitle").removeClass("act");
					$(".page_main_title_splash").off("keydown");
				},
				openCallback:function(){
					$("button[name='savePageTitle']").click(function(e){
						e.preventDefault();
						var title = $("input[name='page_main_title']").val();
						$.splashClose();
						admin.ajax("content", {action:"saveTitle", airMode:"airMode",  title:title}, function(){
							$("#air").contents().find("istitle:first").html( title );
							admin.infotip("Заголовок раздела успешно изменен");
						});
						
					});
					
					$("input[name='page_main_title']").focus().select();
					
					$(".page_main_title_splash").on("keydown", "input[name='page_main_title']", function(e){
						if(e.keyCode == 13 ){
							$(".page_main_title_splash button[name='savePageTitle']").click();
						}
					});
					
				}
			});
		});
		
		

			/* set event for them change button */
			$("#toppanel").on("click", "a.changetheme", function(e){
				e.preventDefault();
				if(!admin.general.themesSupport) return;
				if( $(this).hasClass("act") ) return;
				
				if( admin.themes==undefined ){
					$.splash('<p>В вашем проекте нет поддержки тем оформления панели администрирования.<br/>Вы можете заказать тему оформления на сайте компании <a href="http://kunano.ru/" target="_blank">KUNANO</a></p>');
					return;
				}
				var  changetheme = $(this);
				$(this).addClass("act");

				var list = $("<ul>", {id:"thems_list"});
				
				//add standart theme
				var li = $("<li>");
				var a = $("<a>", {href:"#", text:"Standart"});
				li.append(a);
				list.append(li);
				a.click(function(e){
					e.preventDefault();
					admin.theme.reset();
				});
				//add other themes
				$.each(admin.themes, function(index, name){
					var li = $("<li>");
					var a = $("<a>", {href:"#", text:name});
					li.append(a);
					list.append(li);
					a.click(function(e){
						e.preventDefault();
						admin.theme.load(name);
					});
				});
					
				//append menu
				$("body").prepend(list);
				list.fadeTo(0,0);
				var left = $(this).offset().left;
				list.css({left:left+"px", marginTop:"-30px"});
				list.animate({marginTop:0, opacity:1}, 200);
				//set events	
				var flag = true;    
				$(document).on('click.changetheme', function (e){
					if (!flag){
						list.animate({marginTop:"30px", opacity:0},150, function(){
							$(this).remove();
						});
						changetheme.removeClass("act");
						$(document).off('click.changetheme');
				    }
					flag = false;
				});
			});


		
		
		/* set current page id and reload main menu */
		this.setPageId(1, function(){
			admin.mainMenu.reload(function(){
				admin.loadPanel("parts");
			});
		});
		
		this.statePanel = $("body>.state>.container");
		
		this.statePanel.jScrollPane({
			showArrows:false,
			mouseWheelSpeed:50,
			verticalGutter:10,
			horizontalGutter:10,
			maintainPosition:true,
			autoReinitialise: true
		});
		
		
		this.statePanelApi = this.statePanel.data('jsp');
		 
		/* scroll top button event */
		$(document).on("click.scrolltop", "#scrolltop", function(e){
			e.preventDefault();
			this.statePanelApi.scrollTo(0,0,100);
		});	
		
		/* scroll bottom button event */
		$(document).on("click.scrolltop", "#scrollbottom", function(e){
			e.preventDefault();
			this.statePanelApi.scrollToBottom(100);
		});
		
		/* scroll buttons hover event */
		$("#scrollbuttoncontainer a").hover(
			function(){
				$(this).fadeTo(100, 1);
			},
			function(){
				$(this).stop(true, true).fadeTo(100, 0.5);
			}
		);
		
		/* init tour */
		this.tour.init();
		
		/* set window resizing event */
		$(window).resize(function(){
			admin.resizing();
		});
		
	
		/* resizing and hide intro panel then load hrlp panel */
		
		this.resizing(function(){
			$("#load_all").fadeOut(300);
			if( admin.general.connectionVerifying ) admin.connection.startChecker();
		});			

		
	},
	loadSpoiler:function(){
		$(".spoiler .spoiler-head").not(".-opened").parent(".spoiler").children(".spoiler-body").hide();
		$(document).off("click.spoiler");
		$(document).on("click.spoiler", ".spoiler .spoiler-head", function(){
			$(this).toggleClass("-opened").parent(".spoiler").children(".spoiler-body").slideToggle(350, function(){
				admin.resizing();
			});
		});
/* 		$(".spoiler .spoiler-head").click(function(){
			$(this).toggleClass("-opened").parent(".spoiler").children(".spoiler-body").slideToggle(350, function(){
				admin.resizing();
			});
		}); */
	},
	/* main menu object */
	mainMenu:{
		autoClose:false,
		/* set events for items */
		setEvents:function( callback ){

			$("ul#main_menu").children("li").children("a").hover(
				function(){
					if( !$(this).hasClass("act") ) $(this).stop(true, true).addClass("hover", 100);
				},function(){
					if( !$(this).hasClass("act") ) $(this).stop(true,true).removeClass("hover", 500);
					
				}		
			);
			
			$("ul#main_menu>li>a, ul#main_menu ul[id!='part_list'] a").click(function(e){
				e.preventDefault();
					
					if( admin.mode.current=='air' ) admin.mode.state.load();
					
					//open and close subitem
					if( $(this).parents("li:first").children("ul").length===1 ){
						
						if( $(this).hasClass("opened") ){
							$(this).removeClass("opened").next("ul").slideUp(200, "easeOutCirc");
						}else{
							$(this).addClass("opened").next("ul").slideDown(400, "easeOutCirc");
						}
						
						return;
					}
				
				
				
				
				$("ul#main_menu>li ul li a.hover").removeClass("hover");	
				$("ul#main_menu>li ul li a.act").removeClass("act");
				
				if( !$(this).hasClass("act") ){
	
					/* if is childrens */
					if( $(this).parents("ul:first").attr("id")!="main_menu" ){
						$(this).parents("ul[id!='main_menu']").find("a.act").removeClass("act").removeClass("hover");
					}else{
						$("ul#main_menu>li>a.act.hover").removeClass("hover", 300);	
						$("ul#main_menu>li>a.act").removeClass("act");
					}
	
					$(this).addClass("act");
					$(this).addClass("hover");				
				}
				
				var id = $(this).attr("data-id");
				var panel = $(this).attr("data-panel");
				var action = $(this).attr("data-action");
				if( panel!=undefined ){
					if( action!==undefined ){
						admin.block();
						admin.loadPanel(panel, {action:action});
					}else{
						admin.block();
						admin.loadPanel(panel);
					}
				}
				return false;
			});
		},
		/* reload main menu */
		reload:function( callback ){
			try{
				$.post(admin.general.admUrl, {get:'mainMenu', nocash:Math.floor(Math.random()*9999)}, function( menuHtml ){
					
					if( $("#cap .dmenu:first").length ){
						$("#cap .dmenu:first").parent().html( menuHtml );
					}else{
						$("#cap").html( menuHtml );
					}
					


					//if( !$("#cap").hasClass("jspScrollable") ){
						$("#cap").jScrollPane({
							showArrows:false,
							mouseWheelSpeed:20,
							verticalGutter:5,
							horizontalGutter:5,
							maintainPosition:true,
							autoReinitialise: true
						});	
					//}else{
					//	$("#cap .jspPane").html( menuHtml );
					//}



			
					admin.mainMenu.setEvents();
					admin.resizing();
					$(".dmenu>li:last").addClass("last");
					$(".dmenu>li:first").addClass("first");
					$(".dmenu>li").each(function(){
						if($(this).hasClass("separator")){
							$(this).prev().addClass("last");
							$(this).next().addClass("first");
						}
					});
					if(callback && typeof(callback=='function')) callback();
				});	
			}catch(e){
				admin.connection.failed();
				return false;
			}
		},
		/* show main menu if is hidden */
		show:function( callback ){
			if( $("#cap").is(":visible") ){
				if(callback && typeof(callback)=="function") callback();
				return false;
			}
			
			$("#toppanel a.togglemmenubutton").addClass("act");
			
			$(".inner").fadeOut(150, function(){
				$(this).css({height:"0px"});
				$(".state .container").css({marginLeft:''});
				$("#cap").show("drop", 250, function(){
					$(".inner").css({height:"auto"}).fadeIn(150, function(){
						if(callback && typeof(callback)=="function") callback();
					});
				});
			});
		},		
		/* hide main menu if is visible */
		hide:function( callback ){
			if( $("#cap").is(":hidden") ){
				if(callback && typeof(callback)=="function") callback();
				return false;
			}
			
			$("#toppanel a.togglemmenubutton").removeClass("act");
			
			$(".inner").fadeOut(150, function(){
				$(this).css({height:"0px"});
				$("#cap").hide("drop", 250, function(){
					$(".state .container").css({marginLeft:"10px"});
					$(".inner").css({height:"auto"}).fadeIn(150, function(){
						if(callback && typeof(callback)=="function") callback();
					});
				});
			});
		},
		/* toggle hide or show */
		toggle:function( callback ){
			if( $("#cap").is(":visible") ){
				this.hide(callback);
			}else{
				this.show(callback);
			}
		}
	},
	/* set language */
	setLang:function(lang){
		if( !admin.multylang ) return;
		admin.block();
		admin.currentLang = lang;
		$.post(admin.general.admUrl, {get:'setLang', action:lang, nocash:Math.floor(Math.random()*9999)}, function(){
			admin.init();
		});	
	},
	/* set current page ID (@pageId - PAGE ID) */
	setPageId:function( pageId, callback ){
		try{
			$.post(admin.general.admUrl, {get:'setId', action:pageId, nocash:Math.floor(Math.random()*9999)}, function( pageObj ){
				var pageObj = $.parseJSON( pageObj );
				admin.currentPageId = pageId;
				$("#toppanel .currentpageblock>a").attr("href", pageObj.url).animate({marginLeft:"-10px", opacity:0}, 80, function(){
					$(this).children("span").text( pageObj.url );
					$(this).css({marginLeft:"25px"}).animate({marginLeft:"5px", opacity:1});
				});
				if(callback && typeof(callback=='function')) callback();
			});	
		}catch(e){
			admin.connection.failed();
			return false;
		}		
	},
	/* set active main menu item */
	setActiveItem:function(panel){
		$("ul#main_menu>li>a[data-panel='"+panel+"']").addClass("act").addClass("hover");
	},
	/* reload current panel */
	reloadPanel:function( callback ){
		this.loadPanel( admin.currentPanel, callback );
		return;
	},
	/* load panel ( @get - PANEL TYPE @action - PANEL NAME) */
	loadPanel:function( panelName, obj, callback){
		this.block();
		this.elfinderClose();
		
		var argCount =  arguments.length;
		if( typeof(arguments[ argCount-1 ])==='function' ){
			callback = arguments[ argCount-1 ];
			delete arguments[ argCount-1 ];
			argCount--;
		}
		if( obj!==undefined && typeof(obj)!=='object' ){
			obj = 'undefined';
		}
		if( obj===undefined ) obj='undefined';


		var html = null;
		
		try{
			$.post(admin.general.admUrl, {get:obj, panelName:panelName, nocash:Math.floor(Math.random()*9999)}, function(html){
				
				if( html.trim()==='access denied' ){
					if( $.splashClose != undefined ) $.splashClose();
					var html = '<div class="errorbox">';
					html += '<span class="icon error"></span><p>Привилегии вашей учетной записи не позволяют выполнить данное действие.<br><i>Для получения привилегий обратитесь к администратору.</i></p>';
					html += '</div>';
					$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){
							admin.unblock();
							admin.wait(4000, function(){
								if( $.splashClose != undefined ) $.splashClose();
							});
						}
					});				
					return;
				}
				
				if( admin.mode.current=='air' ){
					$.splash( '<div class="air_panel"><div class="inner"></div></div>', {
							fullscreen:true, 
							openCallback:function(){
								$(".air_panel").parents(".splash_splash.fullscreen").jScrollPane({
									showArrows:false,
									mouseWheelSpeed:50,
									verticalGutter:10,
									horizontalGutter:10,
									maintainPosition:true,
									autoReinitialise: true
								});		
							},
							closeCallback:function(){
								$("#cap.air_panel_show").removeClass("air_panel_show");
								admin.mainMenu.reload();
								admin.mainMenu.hide();
							}
						} 
					);

					var $container = $(".air_panel .inner");
					$container.show(0).html( html );
					$("#cap").addClass("air_panel_show");
					admin.mainMenu.show();

				}else{
					var $container = $(".state .inner");
					$container.show(0).html( html );
					$("#cap.air_panel_show").removeClass("air_panel_show");
				}

				//var $container = ( admin.mode.current=='state' ) ? $(".state .inner") : $("body");

				//$container.show(0).html( html );
				
				//console.log( $container );

				$container.fadeIn(100, function(){
					admin.resizing();
					admin.insertEditor();
					$( "a[title], span[title], div[title], i[title], p[title], ul[title], b[title]" ).tooltip({
						track:false,
						show:{delay:400, duration:0},
						hide:{duration:0},
						content: function () {
							return $(this).prop('title');
						}
					});
				});

				$("input[type='radio'], input[type='checkbox'], input[type='file'], select").styler({selectSmartPositioning:true, browseText:"Обзор..."});
				admin.codeHighlight();
				admin.currentPanel = panelName;
				
				var item = $("ul#main_menu>li>a[class!='act'][data-panel='"+admin.currentPanel+"']").not("[data-registry]");
				if( !item.hasClass("act") && !item.hasClass("hover") ){
					$("ul#main_menu>li>a.hover.act").removeClass("act").removeClass("hover");
					item.addClass("act").addClass("hover");
				}
				
				admin.loadSpoiler();
				admin.unblock();
				if( $container.parents(".container").hasClass("jspScrollable")) admin.statePanelApi.scrollTo(0,0,100);

				if( panelName=='plugins' && obj.action!=undefined ){
						
					admin.replaceMenuAddon();
				}else{
					if( admin.mainMenu.autoClose ){
						admin.mainMenu.hide();
					}				
				}
				
				if(callback && typeof(callback==='function')) callback();
			
			});
		}catch(e){
			admin.connection.failed();
			return;
		}
		return;
	},
	/* rebuild html from panel*/
	rebuild:function(){
		$(".state .inner").fadeIn(100, function(){
			admin.resizing();
			admin.insertEditor();
			$("input[type='radio'], input[type='checkbox'], input[type='file'], select").styler({selectSmartPositioning:true, browseText:"Обзор..."});
			$( "[title]" ).tooltip({track:false, show:{delay:400, duration:0}, hide:{duration:0}});
		});
		
		
		
		
		this.codeHighlight();
		this.loadSpoiler();
		this.unblock();
	},
	/* service method for add addon menu */
	replaceMenuAddon:function(){

		$("#cap .addon_menu").remove();
		$("#cap .addon_menu_back").remove();

		var main_menu = $("#main_menu");
		var addon_menu = $(".addon_menu:first");
		var addon_menu_back = $(".addon_menu_back:first");

		main_menu.hide("drop", 120, function(){
			main_menu.before( addon_menu_back ).before( addon_menu );
			

			addon_menu_back.show("drop", 120, function(){
				addon_menu.show("drop", 250);
				
				addon_menu_back.find("a:first").unbind('click').bind('click', function(e){
					e.preventDefault();
					admin.returnMainMenu();
				});

				addon_menu.children("li").children("a").unbind('click').bind('click', function(e){
					e.preventDefault();
					if( $(this).hasClass("opened") ){
						$(this).removeClass("opened").next("ul").slideUp(200, "easeOutCirc");
					}else{
						$(this).addClass("opened").next("ul").slideDown(200, "easeOutCirc");
					}
				});
				
				addon_menu.children("li").children("ul").children("li").children("a").unbind('click').bind('click', function(e){
					addon_menu.children("li").children("ul").children("li").children("a.act").removeClass("act");
					$(this).addClass("act");
				});
				
				if( admin.mode.current=='air' )	addon_menu_back.hide();
				
			});



		});
	},
	/* service method for return main menu */
	returnMainMenu:function(){
		var main_menu = $("#main_menu");
		var addon_menu = $(".addon_menu");
		var addon_menu_back = $(".addon_menu_back");
		addon_menu_back.hide("drop", 120);
		if( addon_menu!==undefined && addon_menu.is(":visible") ){
			addon_menu.hide("drop", 250, function(){
				addon_menu_back.remove();
				addon_menu.remove();
				main_menu.show("drop", 200);
			});
		}else{
			main_menu.show("drop", 200);
		}
		this.reloadPanel();
	},
	/* block screen */
	block:function( counter ){

		if( counter && typeof(counter)=="number" || typeof(counter)=="string" ){
			$("#loader>i").text( counter );
			return;
		}else{
			$("#loader:hidden").show();
		}
	},
	/* unblock screen */
	unblock:function(){
		$("#loader>i").text("");
		$("#loader:visible").hide();
	},
	/* insert redactor on page */
	insertEditor:function( selector, callback ){
	
		if( selector==undefined ){
			
			selector = '[data-type="editor"]';
			if( $(selector).size()==0 ) return;

		}
		$(selector).each( function(){
			$that = $(this);
			
			if( $that.hasClass("-ckeditor-inst") ) return;

			var editorId = "editor_" + Math.floor( Math.random()*9999 );

			$that.addClass("-ckeditor-inst").attr("id", editorId);
			var editorHandle = CKEDITOR.replace(editorId, {
				language : 'ru',
				toolbarGroups : [
					{ name: 'mode'},
					{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
					{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
					{ name: 'links' },
					{ name: 'insert' },
					{ name: 'colors' },
					{ name: 'styles' },
					{ name: 'others' },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
					{ name: 'tools' }
				],
				extraPlugins : 'elfinder,dragresize,youtube,loremIpsum',
				extraAllowedContent :{
					'img':{
						attributes:'data-fancy,data-type'
					}
				},
				filebrowserBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=file',
				filebrowserImageBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=image',
				filebrowserFlashBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=flash'
			});
			
			editorHandle.on('change', function(){
				$that.val(CKEDITOR.instances[editorId].getData());
			});
			editorHandle.on('mode', function(e){
				if(e.editor.mode=="source"){
					var mixedMode = {
						name: "htmlmixed",
						scriptTypes: [
										{
											matches: /\/x-handlebars-template|\/x-mustache/i,
											mode: null
										},
										{
											matches: /(text|application)\/(x-)?vb(a|script)/i,
											mode: "vbscript"
										}
									]
					};
					var $source = $("#cke_"+editorId).find("textarea.cke_source");
					var cm = CodeMirror.fromTextArea($source.get(0), {mode: mixedMode, tabMode: "indent", lineNumbers: true});
					cm.setSize('100%', '100%');
					cm.on("change", function() {
						cm.doc.cm.save();
					});

					editorHandle.on('resize',function(e){
						if(typeof(cm)!=undefined){
							var contentWindow = editorHandle.container.$.getElementsByClassName('cke_contents');
							cm.setSize($(contentWindow).width(), $(contentWindow).height());
						}
					});
				}
			});
			return editorHandle;
		});
	},
	/* resize page for set valid sizes */
	resizing:function(callback){
		
		if( $(window).outerWidth(true) <566 ){
			admin.mainMenu.autoClose = true;
		}else{
			admin.mainMenu.autoClose = false;
		}
		
		var toppanelHeight = $("#toppanel").outerHeight(true);
		$("body>.state").css({height: $(window).height() - toppanelHeight + "px"});
		if(callback && typeof(callback=='function')) callback();
	},
	/* close and remove elfinder if is exist */
	elfinderClose:function(){
		if( $('#elfinder').size()==0 ) return;
		$("audio").remove();
		$('.elfinder-contextmenu-ltr').remove();
		$('.elfinder-quicklook').remove();
		$('#elfinder').fadeOut(200, function(){ $(this).remove() });
	},
	/* open elfinder */
	elfinderOpen:function( getFileCallback ){
		if( $("#elfinder").size()==1 ) return false;
		var opts = {
			url : '/system/admin/editor/php/connector.files.php',
			lang: 'ru',
			getFileCallback:function(file){
				getFileCallback(file);
				admin.elfinderClose();
				admin.resizing();
			}
		}
		var elfinder = $('<div>',{id:"elfinder"});
		$("body").append( elfinder );
		$("#elfinder").draggable({ containment: "body", scroll: false });
		var elf = $('#elfinder').elfinder(opts).elfinder('instance');
		if( $("#elfinderclose").size()==0 ) $("#elfinder").prepend('<a id="elfinderclose" href="#"></a>');
		$(document).on("click", "#elfinderclose", function(e){
			e.preventDefault();
			admin.elfinderClose();
		});
	},
	/* highlight code for elements having id="highlight" */
	codeHighlight:function(){
		if( $(".highlight").size()==0 ) return;
		
		var mixedMode = {
			name: "htmlmixed",
			scriptTypes: [
							{
								matches: /\/x-handlebars-template|\/x-mustache/i,
								mode: null
							},
							{
								matches: /(text|application)\/(x-)?vb(a|script)/i,
								mode: "vbscript"
							}
						]
		};
		
		$(".highlight").each( function(){
			if( $(this).attr("highlight")!= undefined ) return;
			var id = "highlight" + Math.floor(Math.random()*10000);
			var that = $(this);
			$(this).attr("id", id);
			$(this).attr("highlight", "exist");
			var cm = CodeMirror.fromTextArea(document.getElementById( id ), {mode: mixedMode, tabMode: "indent", lineNumbers: true});
			cm.on("change", function() {
				cm.doc.cm.save();
			});

			var wrapclass = "_r"+Math.floor(Math.random()*10000);
			$(this).next().wrap('<div class="'+wrapclass+' cmhighl"></div>');

 			$( "."+wrapclass ).resizable({resize: function( event, ui ) {
					cm.setSize(ui.size.width, ui.size.height);
				}
			});
			
		});

		

	},
	/* methods for moduls */
	mod:{
		tmp:undefined,
		loadedId:0,
		
		view:function(moduleName, moduleId, callback){
			admin.loadPanel("modules", function(){
				admin.ajax('modules', {action:"viewModule", moduleName:moduleName, moduleId:moduleId}, function( html ){
					$(".panel_modules").html( html );
					admin.unblock();
					if(callback && typeof(callback)=="function") callback();
				});		
			});
		},
		
		/* ajax request and validate forms */
		ajax:function( modName, postArray, formHandle, callback ){

			formHandle = arguments[2];
			callback = arguments[3];

			if( callback==undefined ){
				callback = formHandle;
				formHandle = undefined;
			}
			try{
				$.post(admin.general.admUrl, {mod_name:modName, post:postArray, modId:admin.mod.loadedId, nocash:Math.floor(Math.random()*9999) }, function(r){
					if( formHandle!=undefined ){
						var res = $.parseJSON( r );
						if( res.validate=='error' ){
							admin.unblock();
							admin.infotip("Вы заполнили не все поля формы или заполнили их неверно.",3000);
						}
						$.validate({form:formHandle, res:r, durationDelay:7000, success:function(){ 
							if(typeof(callback)=='function')callback(r);
							return;
						}});
					}else{
						if(typeof(callback)=='function')callback(r);
					}
					
				});
			}catch(e){
				admin.connection.failed();
				return false;
			}	
		},
		/* upload file */
		/* @param necessarily STRING inputId - id of input type="file" */
		/* @param necessarily STRING modName - module name */
		/* @param necessarily STRING action     - just action param, for example: "uploadMyFile"   */
		/* @param FUNCTION callback - callback function */
		upload:function( inputId, modName, action, callback ){	
			admin.block();
			var filename = $("#"+inputId).attr('name');
			admin.mod.tmp = callback;
			admin.mod.inputId = inputId;
			
			$("#"+inputId).wrap('<form id="xfrm" method="post" target="xiframe" action="'+admin.general.admUrl+'" enctype="multipart/form-data" style="border:0;background:none;box-shadow:none">');
			$("#xfrm").append('<input type="hidden" id="xsrviceaction" name="xsrviceaction" value="xsrviceactionupload" />');
			$("#xfrm").append('<input type="hidden" id="xmodname" name="mod_name" value="'+modName+'" />');
			$("#xfrm").append('<input type="hidden" id="xaction" name="action" value="'+action+'" />');
			$("#xfrm").append('<input type="hidden" id="xfilename" name="xfilename" value="'+filename+'" />');
			$("body").append('<iframe id="xiframe" name="xiframe" style="display:none;">');
			
			$("#xfrm").submit();
		},
		uploadResponse:function( filename, errorMessage ){
			$("#xiframe").remove();
			$("#xaction").remove();
			$("#xmodname").remove();
			$("#xsrviceaction").remove();
			$("#xfilename").remove();
			$("#"+admin.mod.inputId).unwrap();
		
		
			if( errorMessage != '' ){
				admin.infotip( errorMessage, 5000 );
				filename = 'failed';
				admin.unblock();
				return false;
			}
			
			if( admin.mod.tmp !=undefined &&  typeof(admin.mod.tmp)=='function'){
				admin.mod.tmp(filename, errorMessage);
			}
			
			admin.unblock();
		}
	},
	plug:{
		tmp:undefined,
		/* ajax request and validate forms */
		ajax:function( plugName, postArray, formHandle, callback ){

			formHandle = arguments[2];
			callback = arguments[3];

			if( callback==undefined ){
				callback = formHandle;
				formHandle = undefined;
			}
			try{
				$.post(admin.general.admUrl, {plug_name:plugName, post:postArray, nocash:Math.floor(Math.random()*9999) }, function(r){
					if( formHandle!=undefined ){
						var res = $.parseJSON( r );
						if( res.validate=='error' ){
							admin.unblock();
							admin.infotip("Вы заполнили не все поля формы или заполнили их неверно.",3000);
						}
						$.validate({form:formHandle, res:r, durationDelay:7000, success:function(){ 
							if(typeof(callback)=='function')callback(r);
							return;
						}});
					}else{
						if(typeof(callback)=='function')callback(r);
					}
					
				});
			}catch(e){
				admin.connection.failed();
				return false;
			}
		},
		/* upload file */
		/* @param necessarily STRING inputId - id of input type="file" */
		/* @param necessarily STRING plugName - plug name */
		/* @param necessarily STRING action     - just action param, for example: "uploadMyFile"   */
		/* @param FUNCTION callback - callback function  */
		upload:function( inputId, plugName, action, callback ){	
			admin.block();
			var filename = $("#"+inputId).attr('name');
			admin.plug.tmp = callback;
			admin.plug.inputId = inputId;
			$("#"+inputId).wrap('<form id="xfrm" method="post" target="xiframe" action="'+admin.general.admUrl+'" enctype="multipart/form-data" style="border:0;background:none;box-shadow:none">');
			$("#xfrm").append('<input type="hidden" id="xsrviceaction" name="xsrviceaction" value="xsrviceactionupload" />');
			$("#xfrm").append('<input type="hidden" id="xplugname" name="plug_name" value="'+plugName+'" />');
			$("#xfrm").append('<input type="hidden" id="xaction" name="action" value="'+action+'" />');
			$("#xfrm").append('<input type="hidden" id="xfilename" name="xfilename" value="'+filename+'" />');
			$("body").append('<iframe id="xiframe" name="xiframe" style="display:none;">');
			$("#xfrm").submit();
		},
		uploadResponse:function( filename, errorMessage ){

			$("#xiframe").remove();
			$("#xaction").remove();
			$("#xplugname").remove();
			$("#xsrviceaction").remove();
			$("#xfilename").remove();
			$("#"+admin.plug.inputId).unwrap();
			
			if( errorMessage != '' ){
				admin.infotip( errorMessage, 5000 );
				filename = 'failed';
				admin.unblock();
				return false;
			}
			
			if( admin.plug.tmp !=undefined &&  typeof(admin.plug.tmp)=='function'){
				admin.plug.tmp( filename, errorMessage );
			}
			
			admin.unblock();
		}
	},
	/* ajax request and validate forms */
	/* @param necessarily STRING panelName - panel class name */
	/* @param necessarily OBJECT OR STRING OR ARRAY postArray - post data array */
	/* @param HANDLE formHandle - form handle for validator */
	/* @param FUNCTION callback - callback function */
	ajax:function( panelName, postArray, formHandle, callback ){

		formHandle = arguments[2];
		callback = arguments[3];

		if( callback==undefined ){
			callback = formHandle;
			formHandle = undefined;
		}
		try{
			$.post(admin.general.admUrl, { panel_name:panelName, post:postArray, nocash:Math.floor(Math.random()*9999) }, function(r){
				if( r.trim()==='access denied' ){
					admin.block();
					admin.infotip("Привилегии вашей учетной записи не позволяют выполнить данное действие", 3000, function(){
						admin.unblock();
					});
					return false;
				}
				if( formHandle!=undefined ){
					var res = $.parseJSON( r );
					if( res.validate=='error' ){
						admin.unblock();
						admin.infotip("Вы заполнили не все поля формы или заполнили их неверно.",3000);
					}
					$.validate({form:formHandle, res:r, durationDelay:7000, success:function(){ 
						if(typeof(callback)=='function')callback(r);
						return;
					}});
				}else{
					if( typeof(callback)=='function' ) callback(r);
				}
				
				if( panelName='modules' && postArray.action=='viewModule' ){
					admin.replaceMenuAddon();
				}
				
				
			});
		}catch(e){
			admin.connection.failed();
			return false;
		}
	},
	/* upload file */
	/* @param necessarily STRING inputId - id of input type="file" */
	/* @param necessarily STRING panelName - panel class name */
	/* @param necessarily STRING action     - just action param, for example: "uploadMyFile"   */
	/* @param FUNCTION callback - callback function    */
	upload:function( inputId, panelName, action, callback ){
		var filename = $("#"+inputId).attr('name');
		this.tmp = callback;
		this.inputId = inputId;
		$("#"+inputId).wrap('<form id="xfrm" method="post" target="xiframe" action="'+admin.general.admUrl+'" enctype="multipart/form-data" style="border:0;background:none;box-shadow:none">');
		$("#xfrm").append('<input type="hidden" id="xsrviceaction" name="xsrviceaction" value="xsrviceactionupload" />');
		$("#xfrm").append('<input type="hidden" id="xpanelname" name="panel_name" value="'+panelName+'" />');
		$("#xfrm").append('<input type="hidden" id="xaction" name="action" value="'+action+'" />');
		$("#xfrm").append('<input type="hidden" id="xfilename" name="xfilename" value="'+filename+'" />');
		$("body").append('<iframe id="xiframe" name="xiframe" style="display:none;">');

		$("#xfrm").submit();
	},
	/* system method */
	uploadResponse:function( filename, errorMessage ){

		$("#xiframe").remove();
		$("#xaction").remove();
		$("#xpanelname").remove();
		$("#xsrviceaction").remove();
		$("#xfilename").remove();
		$("#"+this.inputId).unwrap();
	
		if( errorMessage != '' ){
			filename = 'failed';
		}
		
		if( this.tmp  != undefined &&  typeof(this.tmp)=='function'){
			this.tmp(filename, errorMessage);
		}
		
		this.unblock();
		
	},
	/* confirmation dialog box */
	confirmBox:function( confirmMessage, yesCallback, noCallback ){
		var html = '<div class="confirmbox">';
		html += '<span class="icon confirm"></span><p>'+confirmMessage+'</p>';
		html += '<button type="button" id="confirmYes">Да</button>';
		html += '<button type="button" id="confirmNo">Нет</button>';
		html += '</div>';
		$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){

			$("#confirmYes").click(function(e){
				e.preventDefault();
				if(yesCallback && typeof(yesCallback)=='function') yesCallback();
				if( $.splashClose != undefined ) $.splashClose();
			});
			
			$("#confirmNo").click(function(e){
				e.preventDefault();
				if(noCallback && typeof(noCallback)=='function') noCallback();
				if( $.splashClose != undefined ) $.splashClose();
			});
			
			$(document).bind('keydown',function(e){
				if(e.keyCode == 13){
					$("#confirmYes").click();
				}
				if(e.keyCode == 27){
					$("#confirmNo").click();
				}
			});
				
			
		}});
	},
	errorBox:function( errorMessage, callback ){
		var html = '<div class="errorbox">';
		html += '<span class="icon error"></span><p>'+errorMessage+'</p>';
		html += '<button type="button" id="ok">OK</button>';
		html += '</div>';
		$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){

			$("#ok").click(function(e){
				e.preventDefault();
				if(callback && typeof(callback)=='function') callback();
				if( $.splashClose != undefined ) $.splashClose();
			});
			
			$(document).bind('keydown',function(e){
				if(e.keyCode == 13){
					$("#ok").click();
				}
			});
		}});
	},
	infoBox:function( infoMessage, callback ){
		var html = '<div class="infobox">';
		html += '<span class="icon info"></span><p>'+infoMessage+'</p>';
		html += '<button type="button" id="ok">OK</button>';
		html += '</div>';
		$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){

			$("#ok").click(function(e){
				e.preventDefault();
				if(callback && typeof(callback)=='function') callback();
				if( $.splashClose != undefined ) $.splashClose();
			});
			
			$(document).bind('keydown',function(e){
				if(e.keyCode == 13){
					$("#ok").click();
				}
			});
		}});
	},
	/* information tooltip */
	infotip:function( text, delay, callback ){
		var hideDelay = 2000;
		var tpClass = Math.floor( Math.random() * 9999 );
		$(".infotipbox").prepend( '<div class="infotip '+tpClass+'"></div>' );
		var tp = $("."+tpClass);
		tp.show(0).fadeTo(1, 0.1, function(){
			if( delay && typeof(delay)=="number") hideDelay = delay;
			tp.html( text );
				var w = tp.outerWidth(true);
				tp.css({marginLeft : -Math.floor( w/2 )+"px"});
			tp.animate({top:"10px", opacity:1, height:"24px" },350, "easeOutCirc").delay( hideDelay ).fadeOut(400, function(){
				$(this).remove();
				if(typeof(callback)=='function')callback();
			});		
		});

	},
	tour:{
		/* guide handle */
		guide: null,
		/* default options */
		options:{
			showStepNumbers:false,
			exitOnOverlayClick:false,
			nextLabel:"Далее",
			prevLabel:"Назад",
			skipLabel:"Прервать",
			doneLabel:"Завершить"
		},
		/* services method */
		init:function( ){
			this.guide = introJs();
		},
		/* start guide */
		start:function( stepBlock, options, callback ){
			if( !stepBlock ) return false;
			
			if( options && typeof( options )=="object" ){
				$.each( options, function(opt, value){
					admin.tour.options[ opt ] = value;
				});
			}else if( options && typeof( options )=="function" ){
				callback = options;
			}
			
			if( callback && typeof(callback)=="function" ){
				this.guide.oncomplete( callback );
			}else{
				//this.guide.oncomplete( function(){ return this } );
			}

			this.options.steps = stepBlock;
			this.guide.setOptions( this.options );
			this.guide.start();
		},
		refresh:function(){
			this.guide.refresh();
		},		
		stop:function(){
			this.guide.exit();
		}
	},
	connection:{
		flag:true,
		lastResult:{},
		intervalId: undefined,
		attempsCounter:0,
		startChecker:function(){
			
			var timeInterval = ( this.flag ) ? admin.general.connectionInterval : admin.general.connectionIntervalIfFailedinterval;

			//clear old interval ident if is exists
			if( this.intervalId!=undefined ){
				clearInterval( this.intervalId );
			}
			
			//set new interval and start
			this.intervalId = setInterval(function(){
				admin.connection.check();
			}, timeInterval);
		},
		stopChecker:function(){
			//clear old interval ident if is exists
			if( this.intervalId!=undefined ){
				clearInterval( this.intervalId );
				//console.log("stop checking");
			}
		},
		//check connection
		check:function(){
			
			var that = this;
			
			try{
				$.post(admin.general.admUrl, {get:"connection", nocash:Math.floor(Math.random()*9999)})
				.done(function( response ){
					
					try{
						that.lastResult = $.parseJSON( response );
					}catch(e){
						if( response.trim()==="NULL" ){
							that.authFailed();
							return false;
						}
						
						that.failed(); 
						return false;
					}
					
					var date = new Date;
					var curTime = date.toLocaleTimeString().split(":");
					var curDate = date.toLocaleDateString().split(".");
	
					$.each(curTime, function(i,v){
						if(v.length==1) curTime[i] = "0" + v;
					});
					
					curTime = curTime.join("-");
	
					$.each(curDate, function(i,v){
						if(v.length==1) curDate[i] = "0" + v;
					});
					
					curDate = curDate.join("-");
	
					if( that.lastResult==null ){
						that.failed();
						return false;
					}
					
					that.lastResult['requestDate'] = curDate;
					that.lastResult['requestTime'] = curTime;
					
					
					if( that.lastResult.autorization !== true ){
						that.authFailed();
					}else{
						that.success();
					}				
				})
				.fail(function(){
					that.failed(); 
				});
				
			}catch(e){
				this.stopChecker();
				this.failed();
			}
			
		},
		failed:function(){
			if( this.flag ){
				admin.unblock();
				var html = '<div class="errorbox">';
				html += '<span class="icon error"></span><p>Соединение с сервером утеряно.<br/><i>Попытка восстановить соединение...</i></p>';
				html += '</div>';
				$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150});
			}
			this.startChecker();
			
			this.attempsCounter++;
			this.flag = false;
			
			if( this.attempsCounter === admin.general.connectionAttemptsCountIfFailed ){
				this.stopChecker();
				if( $.splashClose != undefined ) $.splashClose();
				var html = '<div class="errorbox">';
				html += '<span class="icon error"></span><p>Восстановить соединение с сервером не удалось.<br><i>Проверьте соединение с интернетом и обновите страницу.</i></p>';
				html += '</div>';
				$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150});
			}
			
			
			return false;
		},
		authFailed:function(){
			this.flag = false;
			this.stopChecker();
			admin.auth.logout();
			return false;	
		},
		success:function(){
			if(this.flag===false){
				admin.unblock();
				this.flag = false;
				if( $.splashClose != undefined ) $.splashClose();
				var html = '<div class="infobox">';
				html += '<span class="icon info"></span><p>Соединение с сервером восстановлено.</p>';
				html += '</div>';
				$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){
					admin.wait(2000, function(){
						if( $.splashClose != undefined ) $.splashClose();
					});
				}});
			}
			this.attempsCounter = 0;
			this.startChecker();
			this.flag = true;
		}
	},
	declOfNum:function( number, titles ){  
		cases = [2, 0, 1, 1, 1, 2];  
		return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
	},
	theme:{
		current:undefined,
		reset:function(callback){
			if( this.current!=undefined ){
				$("head [name='"+this.current+"']").remove();
				$("body").removeClass(this.current);
				this.current = undefined;
				$.cookie("theme", null, {expires:30, path:"/admin/"});
				admin.resizing(function(){
					admin.rebuild();
					if(callback && typeof(callback)=="function") callback();					
				});

			}	
		},
		load:function(name, callback){
			if( !admin.general.themesSupport ) return;
			this.reset();
			if( admin.themes == undefined ) return;
			var tag = $("<link>", {rel:"stylesheet", name:name,  type:"text/css", href:"/system/admin/css/themes/"+name+"/"+name+".css" });
			$("body").addClass("macOS");
			$("head").append( tag );
			this.current = name;
			$.cookie("theme", name, {expires:30, path:"/admin/"});
			admin.resizing(function(){
				admin.rebuild();
				if(callback && typeof(callback)=="function") callback();					
			});
		}
	},
	mode:{
		current:'state',
	
		state:{
			load:function( callback ){
				if( admin.mode.current=='state' ){
					if(callback && typeof(callback)=="function") callback();
					return;
				}

				admin.mode.current='state';
				
				if( admin.mode.air.meshIntervalId!=undefined ){
					clearInterval(admin.mode.air.meshIntervalId);
					admin.mode.air.meshIntervalId=undefined;
					$("#air").contents().find(".air-mode-overlay").fadeOut(200);
					$("#air").contents().find(".air-mode-helper-elem").fadeOut(200);
				}

				$("#toppanel").animate({opacity:0}, 200, function(){
					$("#toppanel .-forstate").removeClass("act").show();
					$("#toppanel .-forair").hide();
					$("#toppanel .togglemmenubutton").addClass("act");
					if( !admin.general.themesSupport ) $("#toppanel .-forstate.changetheme").hide();
						
					

				}).animate({opacity:1}, 200);

				admin.mainMenu.show();
				
				$(".air").fadeOut(130, function(){
					$(this).remove();
					$(".state").fadeIn(150, function(){
						if(callback && typeof(callback)=="function") callback();
					});
				});
			},
			unload:function(){
				$("#toppanel .-forstate").removeClass("act");
			}
		},
		air:{
			load:function(callback){
				if( admin.mode.current=='air' ){
					if(callback && typeof(callback)=="function") callback();
					return;
				}
				if(callback && typeof(callback)=="function") admin.mode.air.callback = callback;
				admin.mode.current = 'air';
			
				$("#toppanel").animate({opacity:0}, 200, function(){
					$("#toppanel .-forair").removeClass("act").show();
					$("#toppanel .-forstate").hide();
					$("#toppanel .editor").addClass("act");
					
				}).animate({opacity:1}, 200);
				
				admin.mainMenu.hide();
				
				$(window).resize(function(){
					 $("#air").css({width: $(window).width() + "px", height: ($(window).height() - $("#toppanel").outerHeight(true) ) + "px", marginTop: $("#toppanel").outerHeight(true) + "px"});
				});
				
				
				$(".state").fadeOut(130, function(){
					admin.block();
					admin.ajax("content", {airMode:"airMode", id:admin.currentPageId, action:"getUrl"}, function(url){
						var aircontainer = $("<div>", {class:"air"});
						var iframe = $("<iframe>", {name:"air", id:"air", src: document.location.protocol + "//" + document.location.host + url + "edited/" + Math.floor(Math.random()*9999)+"/", onload:"admin.mode.air.iframeOnLoad();"});
						$(".state").after( aircontainer );
						aircontainer.append( iframe );
					
					});
				});
			},
			unload:function(){
				$("#toppanel .-forair").removeClass("act");
				$("#toppanel .editor").addClass("act");
				if( admin.mode.air.meshIntervalId!=undefined ) clearInterval(admin.mode.air.meshIntervalId);
				admin.mode.air.meshIntervalId=undefined;
				$("#air").contents().find(".air-mode-overlay").fadeOut(200);
				$("#air").contents().find(".air-mode-helper-elem").fadeOut(200);
				$("#air").contents().find("body").html("");
			},
			saveContent:function(){
				if( $("#air").contents().find("textarea[name='air_mode_content']")==undefined ) return;
				admin.block();
				var content = $("#air").contents().find("textarea[name='air_mode_content']").val();
				var url = $("#air").attr("src");
				admin.ajax("content", {airMode:"airMode", content:content, url:url}, function(r){
					admin.unblock();
					admin.infotip("Контент успешно сохранен");
				});
			},
			iframeOnLoad:function(){

				$(window).resize();
			
				//front end main object
				var frontEngine = $("#air").get(0).contentWindow.core;
				//front end main DOM context
				var dom = $("#air").contents();
				
				//title box
				var istitle = dom.find("istitle:first");
				if(istitle.length!=0){
					$("#toppanel a.changetitle.disabled").removeClass("disabled");
					var titleHtml = istitle.html();
					var titleBox = dom.find("istitle:first").parent();
					istitle.addClass("air-mode-helper").attr("type", "air-mode-helper-h1");
					
					$(istitle).click(function(){
	
						var html =  '<div class="page_main_title_splash">';
							html += '<p>Заголовок раздела</p>';
							html += '<input type="text" name="page_main_title" value="'+titleHtml+'"/>';
							html += '<button name="savePageTitle">OK</button>';
							html += '</div>';
						$.splash(
							html, {
							closeCallback:function(){
								$("#toppanel a.changetitle").removeClass("act");
								$(".page_main_title_splash").off("keydown");
							},
							openCallback:function(){
								$("button[name='savePageTitle']").click(function(e){
									e.preventDefault();
									var title = $("input[name='page_main_title']").val();
									$.splashClose();
									admin.ajax("content", {action:"saveTitle", airMode: "airMode", title:title}, function(){
										istitle.html( title );
										admin.infotip("Заголовок раздела успешно изменен");
									});
									
								});
								
								$("input[name='page_main_title']").focus().select();
								
								$(".page_main_title_splash").on("keydown", "input[name='page_main_title']", function(e){
									if(e.keyCode == 13 ){
										$(".page_main_title_splash button[name='savePageTitle']").click();
									}
								});
								
							}
						});	
					});
				}else{
					$("#toppanel a.changetitle").addClass("disabled");
				}
				
				//content box
				var iscontent = dom.find("iscontent:first");
				
				if(iscontent.length!=0){	
					$("#toppanel a.editor.disabled").removeClass("disabled");
					var contentHtml = iscontent.html();
					var contentBox = dom.find("iscontent:first").parent();
					var contentBoxCss = {};
					
					contentBox.find("iscontent").remove();
					contentBox.html( contentHtml );
					contentBox.addClass("air_mode_content_box").addClass("air-mode-helper").attr("type", "air-mode-helper-content");
					contentBox.children("iscontent:first").remove();
					contentBoxCss.width = contentBox.outerWidth(true);
					contentBoxCss.height = contentBox.outerHeight(true);
	
					
					contentBox.html("");
					contentBox.append('<textarea name="air_mode_content" id="air_mode_content"></textarea>');
					dom.find("textarea#air_mode_content").css(contentBoxCss).html( contentHtml );
				}else{
					$("#toppanel a.editor").addClass("disabled");
				}
				var intID = setInterval(function(){
					if( typeof(frontEngine.initObject)!=undefined ){
						clearInterval(intID);
		
						frontEngine.initObject.init();
			
						window.parent.admin.unblock();
						$("#toppanel").animate({top:0, opacity:1}, 350, function(){
							$("#air").css({marginTop: $("#toppanel").outerHeight(true), position: "relative"});
							$(".air").fadeIn(50, function(){
								admin.mode.air.createMesh();
								if(window.parent.admin.mode.air.callback && typeof(window.parent.admin.mode.air.callback)=="function") window.parent.admin.mode.air.callback();
							});
						});
					}
				}, 200);
				
			},
			createMesh:function(){
					
				//create overlay
				$("#air").contents().find("body").append('<div class="air-mode-overlay"></div>');
				
				//create mesh for menu
				$("#air").contents().find("nav:first").children().addClass("air-mode-helper");
				
				//create other mesh
				$("#air").contents().find(".air-mode-helper").each(function(){
				
					var id = "air_" + Math.floor( Math.random() * 99999 );
					var type = $(this).attr("type");
					if(type==undefined) type = "air-mode-helper-nav";
					$(this).attr("data-air-mode-id", id);
					var position = ( $(this).css("position")=="fixed" ) ? "fixed" : "absolute";				
					var helper = $("<div>", {class:"air-mode-helper-elem "+type, dataairmodeparentid: id});
					var infoText = '';
					//append helper
					$("#air").contents().find("body").append(helper);
					
					
					switch(type){
						//if is module
						case 'air-mode-helper-module':
							infoText = 'Модуль';
							var that = $(this);
							helper.click(function(){
								var id = that.attr("data-mod-id");
								var name = that.attr("data-mod-name");
								$.splash('<p>Перейти к настройке данного модуля?</p><button type="button" onclick="$.splashClose(); admin.mod.view(\''+name+'\', '+id+'); return false">Перейти к настройке</button>');
							});
						break;
						
						case 'air-mode-helper-word':
							//if is word
							infoText = 'Изменяемый текст';
							var that = $(this);
							helper.click(function(){

								var text = that.html();
								text = text.replace(/\"/gi, "&quot;");
								var key = that.attr("data-word-key");
								var html =  '<div class="air_mode_edit_word_splash">';
									html += '<p>Введите текст</p>';
									html += '<input type="text" name="word_value" value="'+text+'"/>';
									html += '<button name="saveWord">OK</button>';
									html += '</div>';
								
								$.splash(
									html, {
									closeCallback:function(){
										$(".air_mode_edit_word_splash").off("keydown");
									},
									openCallback:function(){
										$(".air_mode_edit_word_splash button[name='saveWord']").click(function(e){
											e.preventDefault();
											var value = $(".air_mode_edit_word_splash input[name='word_value']").val();
											$.splashClose();
											admin.ajax("words", {airMode:"airMode", action:"editWord", key:key, value:value}, function(){
												admin.infotip("Сохранено");
												that.html(value);
											});
											
										});
										
										$(".air_mode_edit_word_splash input[name='word_value']").focus().select();
										
										$(".air_mode_edit_word_splash").on("keydown", "input[name='word_value']", function(e){
											if(e.keyCode == 13 ){
												$(".air_mode_edit_word_splash button[name='saveWord']").click();
											}
										});
										
									}
								});
							});
						break;
						
						case 'air-mode-helper-content':
							//if is content
							infoText = 'Основной контент';
							var that = $(this);
							helper.click(function(){
								var text = that.html();
								$.splash('<p>Контент вы можете изменить прямо в текстовом редакторе</p>');
							});
						break;	
						
						case 'air-mode-helper-nav':
							//if is main menu
							infoText = 'Главное меню';
							var that = $(this);
							helper.click(function(){
								var text = that.html();
								$.splash('<p>Главное меню. Вы можете изменить порядок следования разделов перемещая их между собой ухватив левой кнопкой мыши<br/>Для создания и удаления разделов, вы можете перейти в <a href="#"onclick="$.splashClose();admin.mode.state.load( function(){ admin.loadPanel(\'parts\');  } );return false;">панель разделов</a></p>');
							});
						break;	
						
						case 'air-mode-helper-h1':
							//if is main page title
							infoText = 'Заголовок раздела';
							var that = $(this);
							helper.click(function(){
								var id = that.attr("dataairmodeparentid");
								var text = that.html();
								text = text.replace(/\"/gi, "&quot;");
								
								
								var html =  '<div class="page_main_title_splash">';
									html += '<p>Заголовок раздела</p>';
									html += '<input type="text" name="page_main_title" value="'+text+'"/>';
									html += '<button name="savePageTitle">OK</button>';
									html += '</div>';
								$.splash(
									html, {
									closeCallback:function(){
										$("#toppanel a.changetitle").removeClass("act");
										$(".page_main_title_splash").off("keydown");
									},
									openCallback:function(){
										$("button[name='savePageTitle']").click(function(e){
											e.preventDefault();
											var title = $("input[name='page_main_title']").val();
											$.splashClose();
											admin.ajax("content", {action:"saveTitle", airMode:"airMode", title:title}, function(){
												that.html( title );
												admin.infotip("Заголовок раздела успешно изменен");
											});
											
										});
										
										$("input[name='page_main_title']").focus().select();
										
										$(".page_main_title_splash").on("keydown", "input[name='page_main_title']", function(e){
											if(e.keyCode == 13 ){
												$(".page_main_title_splash button[name='savePageTitle']").click();
											}
										});
										
									}
								});
							});
						break;
					}

					//append info to helper
					helper.prepend('<div class="air-mode-helper-elem-info">'+infoText+'</div>');
					
					helper.hover(
						function(){
							$("#air").contents().find(".air-mode-helper-elem").addClass("-hide");
							$(this).addClass("hover").removeClass("-hide").children(".air-mode-helper-elem-info").show();
						},
						function(){
							$("#air").contents().find(".air-mode-helper-elem.-hide").removeClass("-hide");
							$(this).removeClass("hover").children(".air-mode-helper-elem-info").hide();
						}
					);
				});	
				
				
				//click event on words
				$("#air").contents().find("[data-word-key]").click(function(){
					var that = $(this);
					var text = that.html();
					text = text.replace(/\"/gi, "&quot;");
					var key = that.attr("data-word-key");
					var html =  '<div class="air_mode_edit_word_splash">';
						html += '<p>Введите текст</p>';
						html += '<input type="text" name="word_value" value="'+text+'"/>';
						html += '<button name="saveWord">OK</button>';
						html += '</div>';
					
					$.splash(
						html, {
						closeCallback:function(){
							$(".air_mode_edit_word_splash").off("keydown");
						},
						openCallback:function(){
							$(".air_mode_edit_word_splash button[name='saveWord']").click(function(e){
								e.preventDefault();
								var value = $(".air_mode_edit_word_splash input[name='word_value']").val();
								$.splashClose();
								admin.ajax("words", {airMode:"airMode", action:"editWord", key:key, value:value}, function(){
									admin.infotip("Сохранено");
									that.html(value);
								});
								
							});
							
							$(".air_mode_edit_word_splash input[name='word_value']").select();
							
							$(".air_mode_edit_word_splash").on("keydown", "input[name='word_value']", function(e){
								if(e.keyCode == 13 ){
									$(".air_mode_edit_word_splash button[name='saveWord']").click();
								}
							});
							
						}
					});
				}); 
			},
			visibleMesh:function(){
				
				if(admin.mode.air.meshIntervalId==undefined){
					$("#toppanel a.visible").addClass("act");
					$("#air").contents().find(".air-mode-overlay").fadeIn(125);
					$("#air").contents().find(".air-mode-helper-elem").fadeIn(125);
					

					//set interval
					admin.mode.air.meshIntervalId = setInterval(function(){
					$("#air").contents().find("[data-air-mode-id]").each(function(){
						var block = $(this);
						var id = block.attr("data-air-mode-id");
						var position = ( block.css("position")=="fixed" ) ? "fixed" : "absolute";				
			
						var helper = $("#air").contents().find("[dataairmodeparentid='"+id+"']");

						helper.css({
							position: position,
							width:block.outerWidth(),
							height:block.outerHeight(),
							left:block.offset().left,
							top:block.offset().top,
							bottom:block.offset().bottom,
							right:block.offset().right
						});

					});
					}, 500);
				}else{
					$("#toppanel a.visible").removeClass("act");
					clearInterval(this.meshIntervalId);
					this.meshIntervalId=undefined;
					$("#air").contents().find(".air-mode-overlay").fadeOut(200);
					$("#air").contents().find(".air-mode-helper-elem").fadeOut(200);
				}

			},
			toggleEditor:function(){
				var editor = $("#air").contents().find(".air_mode_content_box").children(".redactor_box").children(".redactor_toolbar");
				if(editor==undefined) return;
				editor.slideToggle(200, function(){
					if( editor.is(":hidden") ){
						$("#toppanel a.editor").removeClass("act");
					}else{
						$("#toppanel a.editor").addClass("act");
					}
				});

			},
			reload:function(){
				admin.block();
				this.unload();
				$(".air").hide();
				$("#air").get(0).contentWindow.document.location.reload();
			}
		}

	},
	browser:{
		name:null,
		version:null,
		subversion:null,

		detect:function(){
			var data = this.getBrowser();
			if( data==false ) return;
			data[0] = data[0].toLowerCase();

			this.name = data[0];
			this.version = parseInt(data[1]);
			this.subversion = data[2];
			this[data[0]] = {}; 
			this[data[0]] = true;
		},
		browserDetectNav:function(chrAfterPoint){
		var 	UA=window.navigator.userAgent,
			OperaB = /Opera[ \/]+\w+\.\w+/i,
			OperaV = /Version[ \/]+\w+\.\w+/i,	
			FirefoxB = /Firefox\/\w+\.\w+/i,
			ChromeB = /Chrome\/\w+\.\w+/i,
			SafariB = /Version\/\w+\.\w+/i,
			IEB = /MSIE *\d+\.\w+/i,
			SafariV = /Safari\/\w+\.\w+/i,
			browser = new Array(),
			browserSplit = /[ \/\.]/i,
			OperaV = UA.match(OperaV),
			Firefox = UA.match(FirefoxB),
			Chrome = UA.match(ChromeB),
			Safari = UA.match(SafariB),
			SafariV = UA.match(SafariV),
			IE = UA.match(IEB),
			Opera = UA.match(OperaB);
				
				if ((!Opera=="")&(!OperaV=="")) browser[0]=OperaV[0].replace(/Version/, "Opera")
						else 
							if (!Opera=="")	browser[0]=Opera[0]
								else
									if (!IE=="") browser[0] = IE[0]
										else 
											if (!Firefox=="") browser[0]=Firefox[0]
												else
													if (!Chrome=="") browser[0] = Chrome[0]
														else
															if ((!Safari=="")&&(!SafariV=="")) browser[0] = Safari[0].replace("Version", "Safari");
		
			var outputData;
			
			if (browser[0] != null) outputData = browser[0].split(browserSplit);
			if (((chrAfterPoint == null)|(chrAfterPoint == 0))&(outputData != null)) 
				{
					chrAfterPoint=outputData[2].length;
					outputData[2] = outputData[2].substring(0, chrAfterPoint);
					return(outputData);
				}
					else
						if (chrAfterPoint != null) 
						{
							outputData[2] = outputData[2].substr(0, chrAfterPoint);
							return(outputData);					
						}
							else	return(false);
		},
		browserDetectJS:function() {
		var
			browser = new Array();
			
			if (window.opera) {
				browser[0] = "Opera";
				browser[1] = window.opera.version();
			}
				else 
				if (window.chrome) {
					browser[0] = "Chrome";
				}
					else
					if (window.sidebar) {
						browser[0] = "Firefox";
					}
						else
							if ((!window.external)&&(browser[0]!=="Opera")) {
								browser[0] = "Safari";
							}
								else
								if (window.ActiveXObject) {
									browser[0] = "MSIE";
									if (window.navigator.userProfile) browser[1] = "6"
										else 
											if (window.Storage) browser[1] = "8"
												else 
													if ((!window.Storage)&&(!window.navigator.userProfile)) browser[1] = "7"
														else browser[1] = "Unknown";
								}
			
			if (!browser) return(false)
				else return(browser);
		},
		getBrowser:function(chrAfterPoint) {
			var
				browserNav = this.browserDetectNav(chrAfterPoint),
				browserJS = this.browserDetectJS();
		
			if (browserNav[0] == browserJS[0]) return(browserNav)
			else if (browserNav[0] != browserJS[0]) return(browserJS)
			else return(false);
		},
		isItBrowser:function(browserCom, browserVer, detectMethod) {
			var browser;
			
			switch (detectMethod) {
				case 1: browser = this.browserDetectNav(); break;
				case 2: browser = this.browserDetectJS(); break;
				default: browser = this.getBrowser();
			};
		
			if ((browserCom == browser[0])&(browserVer == browser[1])) return(true)
				else
					if ((browserCom == browser[0])&((browserVer == null)||(browserVer == 0))) return(true)
						else return(false);
		}
		
	}
};