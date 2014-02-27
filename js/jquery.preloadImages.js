$.preloadImages = function () {
    if (typeof arguments[arguments.length - 1] == 'function') {
        var callback = arguments[arguments.length - 1];
    } else {
        var callback = false;
    }

    if (typeof arguments[0] == 'object') {
        var images = arguments[0];
        var n = images.length;
    } else {
        var images = arguments;
        var n = images.length - 1;
    }
    var not_loaded = n;
   
    if( not_loaded==0 ){
        if(typeof callback == 'function') callback();
        return;
    }

    for (var i = 0; i < n; i++) {

    	if(images[i]==undefined){
    		if( typeof callback == 'function' ) callback();
    		return;
    	}

        $(new Image()).attr('src', images[i]).load(function() {
            if (--not_loaded < 1 && typeof callback == 'function') {
                callback();
            }
        });
    }
}