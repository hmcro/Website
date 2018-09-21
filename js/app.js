var app = {
	
    init: function( settings ) {
        app.config = {
            
        };
 
        // Allow overriding the default config
        $.extend( app.config, settings );
 
        app.setup();
    },
 
    setup: function() {

		// setup button events
		$('a[href="#requirements"]').click(function(){
			app.goto('#requirements');
		});

		$('a[href="#welcome"]').click(function(){
			app.goto('#welcome');
		});

		$('a[href="#privacy"]').click(function(){
			app.goto('#privacy');
		});

		// start with the welcome page
		app.goto("#welcome");

	},

	goto: function( hash ){

		// hide all pages except welcome
		$('#js-error').hide();
		$('#welcome').hide();
		$('#requirements').hide();
		$('#map').hide();
		$('#privacy').hide();

		// show the new page
		$(hash).show();
	},
	
};

$( document ).ready( app.init );