;(function($){
	'use strict'
	  $.fn.shuffle = function() {
			return this.each(function(){
				var items = $(this).children();
				return (items.length)
					? $(this).html($.shuffle(items,$(this)))
				: this;
			});
		}
        $.fn.validate = function() {
            var res = false;
            this.each(function(){
                var arr = $(this).children();
                res =    ((arr[0].innerHTML=="1")&&
                    (arr[1].innerHTML=="2")&&
                    (arr[2].innerHTML=="3")&&
                    (arr[3].innerHTML=="4")&&
                    (arr[4].innerHTML=="5")&&
                    (arr[5].innerHTML=="6"));
            });
            return res;
        }
        $.shuffle = function(arr,obj) {
            for(
            var j, x, i = arr.length; i;
            j = parseInt(Math.random() * i),
            x = arr[--i], arr[i] = arr[j], arr[j] = x
			);
            if(arr[0].innerHTML=="1") obj.html($.shuffle(arr,obj))
            else return arr;
        }
	var initSlider = function(){
		$("#sortable").sortable();
        $("#sortable").disableSelection();
        $('#sortable').shuffle();
        $("#formsubmit").click(function(){
            ($('ul.ok_fixs').validate()) ? alert("Yeah, ok!") : alert("No, sorry!");
        });
	};
	var initMasonry = function(){
		var list = $('ul.ok_test');
		list.isotope({  
		  transformsEnabled: false
		  , itemSelector: '.isotopey'
		  , onLayout: function() {
			list.css('overflow', 'visible');
		  }  
		});
		list.sortable({
		  cursor: 'move'
		  , start: function(event, ui) {                        
			ui.item.addClass('grabbing moving').removeClass('isotopey');
			ui.placeholder
			  .addClass('starting')
			  .removeClass('moving')
			  .css({
				top: ui.originalPosition.top
				, left: ui.originalPosition.left
			  })
			  ;
			list.isotope('reloadItems');                    
		  }                
		  , change: function(event, ui) {
			ui.placeholder.removeClass('starting');
			list
			  .isotope('reloadItems')
			  .isotope({ sortBy: 'original-order'})
			;
		  }
		  , beforeStop: function(event, ui) {
			ui.placeholder.after(ui.item);                    
		  }
		  , stop: function(event, ui) {      
			ui.item.removeClass('grabbing').addClass('isotopey');
			list
			  .isotope('reloadItems')
			  .isotope({ sortBy: 'original-order' }, function(){
				console.log(ui.item.is('.grabbing')); 
				if (!ui.item.is('.grabbing')) {
				  ui.item.removeClass('moving');                        
				}
			});
		  }
		});
	};
	/*New Jquery*/
	$('#checkbox').change(function(){
		setInterval(function () {
			moveRight();
		}, 3000);
	});
	var slideCount = $('#slider ul li').length;
	var slideWidth = $('#slider ul li').width();
	var slideHeight = $('#slider ul li').height();
	var sliderUlWidth = slideCount * slideWidth;
	$('#slider').css({ width: slideWidth, height: slideHeight });
	$('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });
    $('#slider ul li:last-child').prependTo('#slider ul');
    function moveLeft() {
        $('#slider ul').animate({
            left: + slideWidth
        }, 200, function () {
            $('#slider ul li:last-child').prependTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };
    function moveRight() {
        $('#slider ul').animate({
            left: - slideWidth
        }, 200, function () {
            $('#slider ul li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };
    $('a.control_prev').click(function (e){
		e.preventDefault();
        moveLeft();
    });
    $('a.control_next').click(function (e){
		e.preventDefault();
        moveRight();
    });
	var initAutoImage = function(){
		$( '#ri-grid' ).gridrotator( {
			w320 : {
				rows : 3,
				columns : 4
			},
			w240 : {
				rows : 3,
				columns : 3
			}
		});
	};
	/*End Jquery*/
	$(function(){
		initSlider();
		initMasonry();
		initAutoImage();
	});
})(jQuery);