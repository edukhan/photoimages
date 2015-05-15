var Banner = {};
var $j = jQuery.noConflict();
Banner.init = function(config) {
	$j(document).ready(function(){
		$j('.activated-banner-' + config.id).flexslider({
			// Unconfigurable settings
			namespace		: "activated-",
			selector		: ".activated-slides > li",
			
			// General settings
			animation		: config.effect,
			slideshowSpeed	: config.speed,
			
			// Advanced settings
			controlNav		: config.navigation,
			pauseOnHover	: config.pauseOnHover
		});
	});
};


