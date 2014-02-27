var start={
	queue:[],
	i:1,
	c:0,
	uid: undefined,
	username:undefined,
	multylang:undefined,
	currentLang:undefined,
	themes:{},
	mainTitle:"",
	urlPref:"",
	
	init:function(){
		var that = this;
		that.mainTitle = $("title").text();
		$.post('/admin/', {get:'init'}, function( responseArray ){
			try{
				that.queue = responseArray.replace(/\\(.)/g, "/");
				that.queue = $.parseJSON( responseArray );
				that.c = that.queue.length;
				that.queue = that.queue.reverse();
				that.username = ( that.queue[0]!='NULL' ) ? that.queue[0] : undefined;
				that.uid = ( that.queue[1]!='NULL' ) ? that.queue[1] : undefined;
				that.themes = ( that.queue[2]!='NULL' ) ? that.queue[2] : undefined;
				that.urlPref = ( that.queue[3]!='NULL' ) ? that.queue[3] : undefined;
				that.multylang = ( that.queue[4]!='NULL' ) ? that.queue[4] : undefined;
				that.currentLang = ( that.queue[5]!='NULL' ) ? that.queue[5] : undefined;
				that.queue.splice(0,6);
				$("#load_all div.bar>div").show();
			
				that.load();
			}catch(e){
				return false;
			}
		});
	},
	load:function(){
		var that = this;
		if( that.queue.length==0 ){
			$("#load_all div.bar>div").fadeOut(200);
			if( window.admin!==undefined ){ 
				$("title").text(  that.mainTitle );
				admin.uid = that.uid;
				admin.username = that.username;
				admin.themes = (that.themes!=undefined) ?  $.parseJSON(that.themes) : undefined;
				admin.urlPref = that.urlPref;
				admin.multylang = that.multylang;
				admin.currentLang = that.currentLang;
				admin.init();
				start = undefined;
			}
			return;
		}		
		var progress = Math.floor( (that.i/that.c)*100 );
		$("#load_all div.bar>div").css({width: progress + "%"});
		$("title").text( progress + "% " +  that.mainTitle  );
		var path = that.queue[ that.queue.length-1 ];
		try{
			$.getScript(path, function(){
				that.i++;
				that.queue.splice(that.queue.length-1,1);
				that.load();
			});
		}catch(e){
			console.error('Failed to load "'+path+'" script', e);
		}
	}
};

$(function(){
	start.init();
});