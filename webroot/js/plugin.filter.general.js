(function($) 
{

$.widget( "nihfo.pluginFilter", 
{
	options: {},
	
	// initialize the element
	_create: function() {
		var self = this;
		self.element.addClass( this.option('className') );
		self.refresh( );
	},
	
	_destroy: function() {
		var self = this;
		self.element.removeClass( this.option('className') );
	},
	
	refresh: function() {
		var self = this;
		self.createToggleButtons();
		self.watchAddForm();
		self.hide();
		self.attachSelects();
		self.toggleCheck();
	},
	
	createToggleButtons: function() {
		var self = this;
		
		// find the listings_options div
		var listing_options_div = self.element.parent().find('.listings_options');
		
		if(!listing_options_div)
			return;
		
		// create the toggle-show button
		var toggleButtonShow = $( "<a />" )
				.addClass( self.option('className')+'-button-toggle-show' )
				.addClass( 'button' )
				.text( self.option('toggleButtonText').show );
		
		listing_options_div.append($(toggleButtonShow));
		
		// create the toggle-hide button
		var toggleButtonHide = $( "<a />" )
				.addClass( self.option('className')+'-button-toggle-hide' )
				.addClass( 'button' )
				.text( self.option('toggleButtonText').hide )
				.hide();
		
		listing_options_div.append($(toggleButtonHide));
		
		// add a click event to the show button
		toggleButtonShow.click(function(event){
			event.preventDefault();
			self.show();
		});
		
		// add a click event to the hide button
		toggleButtonHide.click(function(event){
			event.preventDefault();
			self.hide();
		});
	},
	
	show: function() {
		var self = this;
		
		self.element.show();
		
		// find the show button
		var listing_options_button_show = self.element.parent().find('.listings_options .'+self.option('className')+'-button-toggle-show');
		if(listing_options_button_show)
		{
			listing_options_button_show.hide();
		}
			
		// find the hide button
		var listing_options_button_hide = self.element.parent().find('.listings_options .'+self.option('className')+'-button-toggle-hide');
		if(listing_options_button_hide)
		{
			listing_options_button_hide.show();
			listing_options_button_hide.css('display', 'inline');
		}
	},
	
	hide: function() {
		var self = this;
		
		self.element.hide();
		
		// find the show button
		var listing_options_button_show = self.element.parent().find('.listings_options .'+self.option('className')+'-button-toggle-show');
		if(listing_options_button_show)
		{
			listing_options_button_show.show();
			listing_options_button_show.css('display', 'inline');
		}
			
		// find the hide button
		var listing_options_button_hide = self.element.parent().find('.listings_options .'+self.option('className')+'-button-toggle-hide');
		if(listing_options_button_hide)
		{
			listing_options_button_hide.hide();
		}
	},
	
	attachSelects: function() {
		var self = this;
		
		self.element.find('form select').each(function(){
			var select = $(this);
			// if one of them has a value, show the filters
			if(select.val() != '')
			{
				self.show();
			}
			
			// watch the arrow, if clicked focus to the select
			$(this).parent().find('span.plugin-filter-arrow').each(function(){
				$(this).click(function(event){
					event.preventDefault();
					select.focus();
					select.select();
				});
			});
			
			// watch for a change
			select.change(function(){
				var uri = self.compileUri(self.option('urlHere'), $(this).data('field'), $(this).val());
				if(!self.option("ajaxLoaded"))
				{
					window.location.href = uri;
				}
			});
		});
	},
	
	toggleCheck: function() {
	},
	
	compileUri: function(uri, key, value) {
		key_escaped = key.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		var re = new RegExp('\/' + key_escaped + ':(\\d+)', "i");
		var redash = new RegExp('\/+', "i");
		
		if (uri.match(re)) {
			if(value)
				uri = uri.replace(re, '/' + key + ":" + value);
			else
				uri = uri.replace(re, '');
				
			
			uri = uri.replace(redash, '/');
			return uri;
		}
		else if(value) {
			uri = uri + '/' + key + ":" + value;
			uri = uri.replace(redash, '/');
			return uri;
		}
		else
		{
			return uri;
		}
	},
	
	watchAddForm: function() {
		var self = this;
		
		var saveButton = self.element.find('input.plugin-filter-save-button');
		var saveInput = self.element.find('input.plugin-filter-save-text');
		var saveUrl = self.option('saveUrl');
		
		saveButton.click(function(event){
			event.preventDefault();
			
			// do an ajax call to the 
			if(!saveUrl)
				return false;
			
			if(!saveInput.val())
				return false;
			
			self.ajax({
				url: saveUrl,
				type: "POST",
				data: {data: { SavedFilter: { name: saveInput.val(), url: window.location.href }} }
			})
			.done(function(data, textStatus, jqXHR){
				window.location.reload();
			});
		});
	},
	
	ajax: function(ajax_options) {
		var self = this;
		
		ajax_options = $.extend( this.options.ajaxOptions, ajax_options );
		var jqxhr = $.ajax(ajax_options);
		jqxhr.fail(function( jqXHR, textStatus, errorThrown ) { self.ajaxFail(jqXHR, textStatus, errorThrown); });
		jqxhr.done(function(data, textStatus, jqXHR){ self.ajaxDone(data, textStatus, jqXHR); });
		return jqxhr;
	},
	
	ajaxDone: function(data, textStatus, jqXHR){ 
	},
	
	ajaxFail: function(jqXHR, textStatus, errorThrown){
	}
});


// the default options
$.nihfo.pluginFilter.prototype.options = {
	className: 'nihfo-plugin-filter',
	toggleButtonText: {show: 'Show Filters', hide: 'Hide Filters'},
	ajaxLoaded: false,
	urlHere: '',
	saveUrl: false
}

})(jQuery);