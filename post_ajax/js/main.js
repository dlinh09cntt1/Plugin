;(function($) {
   'use strict'
	var initLoadPagination = function(){
		$( '#result_ajaxp' ).on( 'click',' ul.pagination a', function( e ) {
			 /** Prevent Default Behaviour */
			 e.preventDefault();
			 /** Get data-page */
			 var data_page = $(this).attr( 'data-page' );
			 var posts_per_page = $('.ajax_pagination').attr( 'posts_per_page' );
			 var post_type = $('.ajax_pagination').attr( 'post_type' );
			 /** Ajax Call */
			 $.ajax({
				 cache: false,
				 timeout: 8000,
				 url: svl_array_ajaxp.admin_ajax,
				 type: "POST",
				 data: ({ 
					 action			:	'LoadPostPagination', 
					 data_page		:	data_page,
					 posts_per_page	:	posts_per_page,
					 post_type		:	post_type 
				 }),
				 beforeSend: function() {
				 $( '.loading_ajaxp' ).css( 'display','block' );
				 },
				 success: function( data, textStatus, jqXHR ){
				 	$( '#result_ajaxp' ).html( data );
				 },
				 error: function( jqXHR, textStatus, errorThrown ){
					 console.log( 'The following error occured: ' + textStatus, errorThrown );
				 },
				 complete: function( jqXHR, textStatus ){
				 }
			 });
		 });
	};
	var initLoadMoreArchives = function(){
		var _click = $('.load_possts a');
		_click.on('click', function(e){
			e.preventDefault();
			var _this = $(this),
				data = {
				'action': 'piala_loadMores',
				'attr': _this.data('attrs').posts,
				'posts_per_page': _this.data('attrs').posts_per_page,
				'page' : _this.data('attrs').current_pages
			};
			$.ajax({
				url : svl_array_ajaxp.admin_ajax,
				data : data,
				type : 'POST',
				beforeSend : function ( xhr ) {
					_this.text('Loading...');
				},
				success : function( data ){
					if( data ) { 
						_this.text( 'More posts' ).parent('.load_possts').before(data);
						_this.data('attrs').current_pages++;
						if ( _this.data('attrs').current_pages == _this.data('attrs').max_pages ) 
							_this.remove();
					} else {
						_this.remove();
					}
				}
			});
		})
	};
	var initMaxHeight = function(){
		jQuery(window).load(function(){  
		  var maxHeight = 0;
		  var _maxheight = $('.ajaxp_list_post');
		  if(_maxheight.length === 0) return;
		  jQuery(_maxheight).each(function(){
			 if (jQuery(this).height() > maxHeight) { 
					maxHeight = jQuery(this).height(); 
			 }
		  });
		  jQuery(_maxheight).height(maxHeight);
		});
	};
    // Dom Ready
    $(function(){
		initLoadPagination();
		initLoadMoreArchives();
		initMaxHeight();
    });
})(jQuery);