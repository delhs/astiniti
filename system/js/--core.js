/** main core javascript object */
//return string trim
String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};

//return string left trim
String.prototype.ltrim=function(){return this.replace(/^\s+/,'');};

//return string right trim
String.prototype.rtrim=function(){return this.replace(/\s+$/,'');};

//return string full trim
String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};
		
//return true if string is integer
String.prototype.isInt=function(){return !isNaN(parseFloat(this)) && isFinite(this);};

//return string md5 hash
String.prototype.md5=function(){function h(a,b){var c,d,f,e,g;f=a&2147483648;e=b&2147483648;c=a&1073741824;d=b&1073741824;g=(a&1073741823)+(b&1073741823);return c&d?g^2147483648^f^e:c|d?g&1073741824?g^3221225472^f^e:g^1073741824^f^e:g^f^e}function k(a,b,c,d,f,e,g){a=h(a,h(h(b&c|~b&d,f),g));return h(a<<e|a>>>32-e,b)}function l(a,b,c,d,f,e,g){a=h(a,h(h(b&d|c&~d,f),g));return h(a<<e|a>>>32-e,b)}function m(a,b,d,c,e,f,g){a=h(a,h(h(b^d^c,e),g));return h(a<<f|a>>>32-f,b)}function n(a,b,d,c,f,e,g){a=h(a,h(h(d^(b|~c),f),g));return h(a<<e|a>>>32-e,b)}function p(a){var b="",d="",c;for(c=0;3>=c;c++)d=a>>>8*c&255,d="0"+d.toString(16),b+=d.substr(d.length-2,2);return b}var f;f=[];var e,q,r,s,t,a,b,c,d;f=function(a){a=a.replace(/\r\n/g,"\n");for(var b="",d=0;d<a.length;d++){var c=a.charCodeAt(d);128>c?b+=String.fromCharCode(c):(127<c&&2048>c?b+=String.fromCharCode(c>>6|192):(b+=String.fromCharCode(c>>12|224),b+=String.fromCharCode(c>>6&63|128)),b+=String.fromCharCode(c&63|128))}return b}(this);f=function(b){var a,c=b.length;a=c+8;for(var d=16*((a-a%64)/64+1),e=Array(d-1),f=0,g=0;g<c;)a=(g-g%4)/4,f=8*(g%4),e[a]|=b.charCodeAt(g)<<f,g++;a=(g-g%4)/4;e[a]|=128<<8*(g%4);e[d-2]=c<<3;e[d-1]=c>>>29;return e}(f);a=1732584193;b=4023233417;c=2562383102;d=271733878;for(e=0;e<f.length;e+=16)q=a,r=b,s=c,t=d,a=k(a,b,c,d,f[e+0],7,3614090360),d=k(d,a,b,c,f[e+1],12,3905402710),c=k(c,d,a,b,f[e+2],17,606105819),b=k(b,c,d,a,f[e+3],22,3250441966),a=k(a,b,c,d,f[e+4],7,4118548399),d=k(d,a,b,c,f[e+5],12,1200080426),c=k(c,d,a,b,f[e+6],17,2821735955),b=k(b,c,d,a,f[e+7],22,4249261313),a=k(a,b,c,d,f[e+8],7,1770035416),d=k(d,a,b,c,f[e+9],12,2336552879),c=k(c,d,a,b,f[e+10],17,4294925233),b=k(b,c,d,a,f[e+11],22,2304563134),a=k(a,b,c,d,f[e+12],7,1804603682),d=k(d,a,b,c,f[e+13],12,4254626195),c=k(c,d,a,b,f[e+14],17,2792965006),b=k(b,c,d,a,f[e+15],22,1236535329),a=l(a,b,c,d,f[e+1],5,4129170786),d=l(d,a,b,c,f[e+6],9,3225465664),c=l(c,d,a,b,f[e+11],14,643717713),b=l(b,c,d,a,f[e+0],20,3921069994),a=l(a,b,c,d,f[e+5],5,3593408605),d=l(d,a,b,c,f[e+10],9,38016083),c=l(c,d,a,b,f[e+15],14,3634488961),b=l(b,c,d,a,f[e+4],20,3889429448),a=l(a,b,c,d,f[e+9],5,568446438),d=l(d,a,b,c,f[e+14],9,3275163606),c=l(c,d,a,b,f[e+3],14,4107603335),b=l(b,c,d,a,f[e+8],20,1163531501),a=l(a,b,c,d,f[e+13],5,2850285829),d=l(d,a,b,c,f[e+2],9,4243563512),c=l(c,d,a,b,f[e+7],14,1735328473),b=l(b,c,d,a,f[e+12],20,2368359562),a=m(a,b,c,d,f[e+5],4,4294588738),d=m(d,a,b,c,f[e+8],11,2272392833),c=m(c,d,a,b,f[e+11],16,1839030562),b=m(b,c,d,a,f[e+14],23,4259657740),a=m(a,b,c,d,f[e+1],4,2763975236),d=m(d,a,b,c,f[e+4],11,1272893353),c=m(c,d,a,b,f[e+7],16,4139469664),b=m(b,c,d,a,f[e+10],23,3200236656),a=m(a,b,c,d,f[e+13],4,681279174),d=m(d,a,b,c,f[e+0],11,3936430074),c=m(c,d,a,b,f[e+3],16,3572445317),b=m(b,c,d,a,f[e+6],23,76029189),a=m(a,b,c,d,f[e+9],4,3654602809),d=m(d,a,b,c,f[e+12],11,3873151461),c=m(c,d,a,b,f[e+15],16,530742520),b=m(b,c,d,a,f[e+2],23,3299628645),a=n(a,b,c,d,f[e+0],6,4096336452),d=n(d,a,b,c,f[e+7],10,1126891415),c=n(c,d,a,b,f[e+14],15,2878612391),b=n(b,c,d,a,f[e+5],21,4237533241),a=n(a,b,c,d,f[e+12],6,1700485571),d=n(d,a,b,c,f[e+3],10,2399980690),c=n(c,d,a,b,f[e+10],15,4293915773),b=n(b,c,d,a,f[e+1],21,2240044497),a=n(a,b,c,d,f[e+8],6,1873313359),d=n(d,a,b,c,f[e+15],10,4264355552),c=n(c,d,a,b,f[e+6],15,2734768916),b=n(b,c,d,a,f[e+13],21,1309151649),a=n(a,b,c,d,f[e+4],6,4149444226),d=n(d,a,b,c,f[e+11],10,3174756917),c=n(c,d,a,b,f[e+2],15,718787259),b=n(b,c,d,a,f[e+9],21,3951481745),a=h(a,q),b=h(b,r),c=h(c,s),d=h(d,t);return(p(a)+p(b)+p(c)+p(d)).toLowerCase()};

//return translit string to url
String.prototype.translit=function(){var c={"\u0410":"A","\u0430":"a","\u0411":"B","\u0431":"b","\u0412":"V","\u0432":"v","\u0413":"G","\u0433":"g","\u0414":"D","\u0434":"d","\u0415":"E","\u0435":"e","\u0401":"Yo","\u0451":"yo","\u0416":"Zh","\u0436":"zh","\u0417":"Z","\u0437":"z","\u0418":"I","\u0438":"i","\u0419":"Y","\u0439":"y","\u041a":"K","\u043a":"k","\u041b":"L","\u043b":"l","\u041c":"M","\u043c":"m","\u041d":"N","\u043d":"n","\u041e":"O","\u043e":"o","\u041f":"P","\u043f":"p","\u0420":"R","\u0440":"r","\u0421":"S","\u0441":"s","\u0422":"T","\u0442":"t","\u0423":"U","\u0443":"u","\u0424":"F","\u0444":"f","\u0425":"Kh","\u0445":"kh","\u0426":"Ts","\u0446":"ts","\u0427":"Ch","\u0447":"ch","\u0428":"Sh","\u0448":"sh","\u0429":"Sch","\u0449":"sch","\u042a":"","\u044a":"","\u042b":"Y","\u044b":"y","\u042c":"","\u044c":"","\u042d":"E","\u044d":"e","\u042e":"Yu","\u044e":"yu","\u042f":"Ya","\u044f":"ya"," ":"_","-":"_","@":"_","!":"_","#":"_","%":"_",$:"_","^":"_","&":"_","*":"_","(":"_",")":"_","'":"_",'"':"_","\u2116":"_",";":"_",":":"_","?":"_","+":"_","<":"_",">":"_",".":"_",",":"_"},a="",b;for(b in c)a+=b;a=RegExp("["+a+"]","g");b=function(a){return a in c?c[a]:""};return function(){return this.replace(a,b)}}();

//return path basename
String.prototype.basename=function(){parts = this.split( '/' );return parts[parts.length-1];};

//convert angle to rad
Number.prototype.toRad=function(){return(this*Math.PI)/180;};

//convert rad to angle
Number.prototype.toAngle=function(){ return Math.ceil((this*180)/Math.PI);};


var core = {
	
	init:function(){
		//browser detect
		this.browser.detect();

		//replace for email address
		$("thecat").replaceWith("@");

		//init dmenu jquery plugin
		$("ul.mmenu:first").dmenu();
		
		//init formStyler jquery plugin
		$('input, select').styler();
		
		//init fancyBox jquery plugin
		this.fancyBoxLoad();

		this.youTubeFix();
	},
	reload:function(){
		document.location.reload();
	},
	fancyBoxLoad:function(){

		if( window.editedFromAdmin==undefined ){
		
			$("img[data-fancy='on']").each(function(){
				if( $(this).parent("a[rel='tofancy']").length>0 ) return;
				var src=$(this).attr("src");
				$(this).wrap('<a href="'+src+'" rel="tofancy"></a>');
			});
		}

		$("a[rel='tofancy']").fancybox({
				prevEffect	: 'elastic',
				nextEffect	: 'elastic',
				openEffect 	: 'elastic',
				closeEffect 	: 'elastic',
				helpers	: 	{
								title	: {
											type: 'outside'
											},
								thumbs	: {
											width	: 50,
											height	: 50
											}
							}
		});		
	},
	youTubeFix:function(){
		$("iframe[src]").load(function(){
			if($(this).data('wmode')==undefined){
				var src= $(this).attr("src"); 
				if (src.match(/\?/)) src = src + "&wmode=opaque"; else src = src + "?wmode=opaque";
				$(this).attr("src",src).data("wmode","opaque");	
			}
		});
	},
	/*block the screen*/
	blockScreen:function(){
		if( document.querySelector("#blockscreen") !== null ) return;
		
		var block = document.createElement("div");
		block.setAttribute("id", "blockscreen");
		document.body.appendChild(block);

		var loader = document.createElement("div");
		loader.setAttribute("id", "blockloader");
		block.appendChild(loader);
	},

	/*unblock the screen if screen is blocked*/
	unblockScreen:function(){
		var block = document.querySelector("#blockscreen");
		if( block === null ) return;
		document.body.removeChild( block );
	},

	/** methods for moduls */
	mod:{
		tmp:undefined,
		/** ajax request and validate forms */
		ajax:function( modName, postArray, formHandle, callback ){

			formHandle = arguments[2];
			callback = arguments[3];

			if( callback==undefined ){
				callback = formHandle;
				formHandle = undefined;
			}

			$.post("/", {mod_name:modName, post:postArray }, function(r){
				if( formHandle!=undefined ){
					$.validate({form:formHandle, res:r,
						success:function(){ 
							if(typeof(callback)=='function') callback(r);
							return;
						},
						callback:function(){
							core.unblockScreen();
						}
					});
				}else{
					if(typeof(callback)=='function')callback(r);
				}
				
			});
		},
		/** upload file */
		/** @param necessarily STRING inputId - id of input type="file" */
		/** @param necessarily STRING modName - panel class name */
		/** @param necessarily STRING action     - just action param, for example: "uploadMyFile"   */
		/** @param FUNCTION callback - callback function */
		upload:function( inputId, modName, action, callback ){	
			if( $("#xfrm").size()==0 ){
				var filename = $("#"+inputId).attr('name');
				core.mod.tmp = callback;
				$("#"+inputId).wrap('<form id="xfrm" method="post" target="xiframe" action="/" enctype="multipart/form-data" style="border:0;background:none;box-shadow:none">');
				$("#xfrm").append('<input type="hidden" id="xsrviceaction" name="xsrviceaction" value="xsrviceactionupload" />');
				$("#xfrm").append('<input type="hidden" id="xmodname" name="mod_name" value="'+modName+'" />');
				$("#xfrm").append('<input type="hidden" id="xaction" name="action" value="'+action+'" />');
				$("#xfrm").append('<input type="hidden" id="xfilename" name="xfilename" value="'+filename+'" />');
				$("body").append('<iframe id="xiframe" name="xiframe" style="display:none;">');
			}else{
				$("#xiframe").remove();
				$("#xaction").remove();
				$("#xmodname").remove();
				$("#xsrviceaction").remove();
				$("#xfilename").remove();
				$("#"+inputId).unwrap();
				
				return core.mod.upload( inputId, modName, action, callback );
			}
			$("#xfrm").submit();
		},
		uploadResponse:function( filename, errorMessage ){
			if( errorMessage != '' ){
				filename = 'failed';
			}
			
			if( core.mod.tmp != undefined &&  typeof(core.mod.tmp) == 'function'){
				core.mod.tmp(filename, errorMessage);
			}
		}
	},
	/** methods for moduls */
	plug:{
		tmp:undefined,
		/** ajax request and validate forms */
		ajax:function( plugName, postArray, formHandle, callback ){

			formHandle = arguments[2];
			callback = arguments[3];

			if( callback==undefined ){
				callback = formHandle;
				formHandle = undefined;
			}

			$.post("/", {plug_name:plugName, post:postArray }, function(r){
				if( formHandle!=undefined ){
					$.validate({form:formHandle, res:r,
						success:function(){ 
							if(typeof(callback)=='function') callback(r);
							$(document).trigger("ready");
							return;
						},
						callback:function(){ 
							core.unblockScreen();
						}
					});
				}else{
					if(typeof(callback)=='function')callback(r);
				}
				
			});
		},
		/** upload file */
		/** @param necessarily STRING inputId - id of input type="file" */
		/** @param necessarily STRING plugName - panel class name */
		/** @param necessarily STRING action     - just action param, for example: "uploadMyFile"   */
		/** @param FUNCTION callback - callback function  */
		upload:function( inputId, plugName, action, callback ){	
			if( $("#xfrm").size()==0 ){
				var filename = $("#"+inputId).attr('name');
				core.plug.tmp = callback;
				$("#"+inputId).wrap('<form id="xfrm" method="post" target="xiframe" action="/" enctype="multipart/form-data" style="border:0;background:none;box-shadow:none">');
				$("#xfrm").append('<input type="hidden" id="xsrviceaction" name="xsrviceaction" value="xsrviceactionupload" />');
				$("#xfrm").append('<input type="hidden" id="xplugname" name="plug_name" value="'+plugName+'" />');
				$("#xfrm").append('<input type="hidden" id="xaction" name="action" value="'+action+'" />');
				$("#xfrm").append('<input type="hidden" id="xfilename" name="xfilename" value="'+filename+'" />');
				$("body").append('<iframe id="xiframe" name="xiframe" style="display:none;">');
			}else{
				$("#xiframe").remove();
				$("#xaction").remove();
				$("#xplugname").remove();
				$("#xsrviceaction").remove();
				$("#xfilename").remove();
				$("#"+inputId).unwrap();
				
				return core.plug.upload( inputId, plugName, action, callback );
			}
			$("#xfrm").submit();
		},
		uploadResponse:function( filename, errorMessage ){
			if( errorMessage != '' ){
				filename = 'failed';
				return false;
			}
			
			if( core.plug.tmp != undefined &&  typeof(core.plug.tmp) == 'function'){
				core.plug.tmp(filename, errorMessage);
			}
			
		}

	},
	number_format:function(number, decimals, dec_point, thousands_sep){
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		
		toFixedFix = function (n, prec){
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};

		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	},
	declOfNum:function( number, titles ){  
		cases = [2, 0, 1, 1, 1, 2];  
		return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
	},
	/* confirmation dialog box */
	confirmBox:function( confirmMessage, yesCallback, noCallback ){
		
	var confirmMessage = 'message';

		var elem = null,
			box = null,
			html = null;

		html = document.createElement('div');

		box = document.createElement('div');
		box.setAttribute('class', 'confirmbox');
		html.appendChild(box);

		elem = document.createElement('span');
		elem.setAttribute('class', 'icon confirm');
		box.appendChild(elem);

		elem = document.createElement('p');
		elem.innerHTML = confirmMessage;		
		box.appendChild(elem);

		elem = document.createElement('button');
		elem.setAttribute('type', 'button');
		elem.setAttribute('id', 'confirmYes');
		elem.innerHTML = 'Да';
		box.appendChild(elem);

		elem = document.createElement('button');
		elem.setAttribute('type', 'button');
		elem.setAttribute('id', 'confirmNo');
		elem.innerHTML = 'Нет';
		box.appendChild(elem);		

		$.splash(html.innerHTML, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){

			document.querySelector("#confirmYes").addEventListener('click', function(e){
				e.preventDefault();
				if(yesCallback && typeof(yesCallback)=='function') yesCallback();
				if( $.splashClose != undefined ) $.splashClose();
			}, false);


			document.querySelector("#confirmNo").addEventListener('click', function(e){
				e.preventDefault();
				if(noCallback && typeof(noCallback)=='function') noCallback();
				if( $.splashClose != undefined ) $.splashClose();
			}, false);

			$(document).off('keydown.confirmBox');
			$(document).on('keydown.confirmBox', function(e){
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
			
			$(document).off('keydown.errorBox');
			$(document).on('keydown.errorBox',function(e){
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
			
			$(document).off('keydown.infoBox');
			$(document).on('keydown.infoBox',function(e){
				if(e.keyCode == 13){
					$("#ok").click();
				}
			});
		}});
	},
	messageBox:function( infoMessage, delay, callback ){
		var hideDelay = 1000;
		var callbackF = undefined;

		var html = '<div class="infobox">';
		html += '<span class="icon info"></span><p>'+infoMessage+'</p>';
		html += '</div>';

		$.splash(html, {closeBtn:false, closeOutClick:false, closeToEscape:false, durationOpen:200, durationClose:150, openCallback:function(){
			if( callback && typeof(callback)=='function' ) callbackF = callback;
			if( delay && typeof(delay)=='function' ) callbackF = delay;
			if( delay && typeof(delay)=='number') hideDelay = delay;
			setTimeout(function(){
				if(callbackF && typeof(callbackF)=='function') callbackF();
				if($.splashClose != undefined ) $.splashClose();
			}, hideDelay);
		}});
	},	
	wait:function(delay, callback){
		if( callback==undefined || typeof(callback)!="function" ) return false;
		setTimeout(callback,delay);
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

$(document).on('ready', function(){
	core.init();
});