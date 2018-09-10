(function ($, window, document) {
	"use strict";
	$.fn.fillBox = function() {
		
		this.each(function(){
			var el = $(this),
		    src = el.attr('src'),
		    parent = el.parent();
			
		    parent.css({
		    	'background-image'    : 'url(' + src + ')',
		    	'background-size'     : 'cover',
		    	'background-position' : 'center'
		    });
			
		    el.hide();
		});
		
	};
	
}(jQuery, window, document));
