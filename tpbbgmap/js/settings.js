(function($){

	FLBuilder.registerModuleHelper('tpbbgmap', {

		rules: {
			lat: {
				required: true
			},
			lng: {
				required: true
			},
			zoom: {
				required: true,
				number: true
			},
			height: {
				required: true,
				number: true
			}
		},
		init: function()
		{
		}

	});

})(jQuery);