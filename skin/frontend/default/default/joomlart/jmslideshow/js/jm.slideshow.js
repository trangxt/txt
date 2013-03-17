// JavaScript Document
/*! Copyright (c) 2009 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * Version: 3.0.2
 * 
 * Requires: 1.2.2+
 */

(function($) {

var types = ['DOMMouseScroll', 'mousewheel'];

$.event.special.mousewheel = {
	setup: function() {
		if ( this.addEventListener )
			for ( var i=types.length; i; )
				this.addEventListener( types[--i], handler, false );
		else
			this.onmousewheel = handler;
	},
	
	teardown: function() {
		if ( this.removeEventListener )
			for ( var i=types.length; i; )
				this.removeEventListener( types[--i], handler, false );
		else
			this.onmousewheel = null;
	}
};

$.fn.extend({
	mousewheel: function(fn) {
		return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
	},
	
	unmousewheel: function(fn) {
		return this.unbind("mousewheel", fn);
	}
});


function handler(event) {
	var args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true;
	
	event = $.event.fix(event || window.event);
	event.type = "mousewheel";
	
	if ( event.wheelDelta ) delta = event.wheelDelta/120;
	if ( event.detail     ) delta = -event.detail/3;
	
	// Add events and delta to the front of the arguments
	args.unshift(event, delta);

	return $.event.handle.apply(this, args);
}

})(jQuery);
// JavaScript Document
(function($) {   // Compliant with jquery.noConflict()
$.fn.Slideshow2 = function(options) {
    var defaults = $.extend({
			navPos:'horizontal',
			source:'images',
      		startItem: 0,
			showItem: 4,
			mainWidth: 360,
			mainHeight: 240,
			itemWidth: 60,
			itemHeight: 60,
			navItemWidth:300,
			navItemHeight:60,
			duration: 10,
			interval: 1000,
			transition: 'faceOut',
			thumbOpacity:'0.8',			
			maskDesc : 'maskDesc',
			showDesc : false,
			but_prev: 'jm-slide-prev',
			but_next: 'jm-slide-next',
			but_play: 'jm-slide-play',
			but_stop: 'jm-slide-stop',
			but_playback: 'jm-slide-playback',
			maskOpacity: '0.8',
			buttonOpacity: '0.4',
			overlap: 0,
			navigation: '',
			animation: 'fade',
			thumbSpaces: [3,3],
			urls: [],
			autoPlay: false

    }, options || {});
	
    if( defaults.source == 'products' ){
    	options.itemHeight = 90;
    	options.itemHeight=defaults.navItemHeight;
    	options.itemWidth = defaults.navItemWidth;
    }
	var spSize = options.itemHeight;
	// alert( options.itemWidth );
	var modes ={horizontal:['left','width'], vertical_left:['top','height'],vertical_right:['top','height']};
	var options = $.extend(defaults, options); 
	
	/*Get elements*/
	var items = $($(this).find('.jm-slide-item'));
	var thumb_items = $($(this).find('.jm-slide-thumb'));	
	var mask_items  = $($(this).find('.jm-slide-thumbs-mask')); 
	var thumbs      = $($(this).find('.jm-slide-thumbs'));
	var descs 	    = $($(this).find('.jm-slide-descs .jm-slide-desc'));
	var thumbs_handles =  $($(this).find('.jm-slide-thumbs-handles'));	
	var maskDesc = $($(this).find('.maskDesc'));
	var readon = $($(this).find('.readon'));
	
	/*Check validate*/
	if (options.startItem > items.length - 1 ) {
		options.startItem = items.length -1 ;
	}
	
	if (options.startItem < 0 ) {
		options.startItem = 0;	
	}
	
	if (options.showItem < 0 ) {
		options.showItem = 0;
	}
	if( thumb_items.length < options.showItem  ){
	    options.showItem = thumb_items.length;
	}
	
	var timeOutFn = null;
	var timeAfterClick = null;
	var timeStart = null;
	var timeCurrent = null;	
	var inc = 0;
	var currentItem = null;
	var lastNo = options.startItem;
	var currNo = options.startItem;
	var isClick = false;	
	var next = true;
	var typePlay = 'next';
	var conWidth = options.overlap ? '100%' :  options.mainWidth;
	
	currentItem = options.startItem ? items[options.startItem] : null;
	
	/*Set attribute of elements*/
	$(this).css({'visibility' : 'visible', 'width' : '100%', 'height' : options.mainHeight});
	$($(this).find('.jm-slide-main-wrap')).css({'width' : conWidth, 'height' : options.mainHeight});
		
	items.each(function(i) {	
		$(items[i]).css({'width': conWidth, 'height': options.mainHeight, 'position': 'absolute' , 'left': '0px',  'top' : '0px', 'display': 'none', 'visibility' : 'visible', 'opacity': '1' ,  'z-index': '9'});
	});
	
	thumb_items.each(function(i) {	
		$($(thumb_items[i]).find('img')).css({'margin-left' : options.thumbSpaces[0] - 1, 'margin-top' : options.thumbSpaces[1] - 1});
	});

	$(items[options.startItem]).css('display', 'block');
	
	if (items.length > options.showItem) {
		if( options.navPos == 'vertical_left' || options.navPos == 'vertical_right' ){	
			$($(this).find('.jm-slide-thumbs-wrap')).css({'width': options.itemWidth, 'height' : options.itemHeight * options.showItem});
		} else {
			$($(this).find('.jm-slide-thumbs-wrap')).css({'width': options.itemWidth * options.showItem, 'height' : options.itemHeight});
		}
	} else {
		if( options.navPos == 'vertical_left' || options.navPos == 'vertical_right' ){	
			$($(this).find('.jm-slide-thumbs-wrap')).css({'width': options.itemWidth , 'height' : options.itemHeight* items.length});
		} else {
			$($(this).find('.jm-slide-thumbs-wrap')).css({'width': options.itemWidth * items.length, 'height' : options.itemHeight});
		}
	}
	$(thumbs.find('.jm-slide-thumb')).css({'width': options.itemWidth, 'height': options.itemHeight});
	
	$(mask_items.find('.jm-slide-thumbs-mask-left')).css ({'height' : options.itemHeight,'width' : 2000,'opacity' : options.thumbOpacity});
	$(mask_items.find('.jm-slide-thumbs-mask-right')).css ({'height' : options.itemHeight,'width' : 2000,'opacity' : options.thumbOpacity});
	$(mask_items.find('.jm-slide-thumbs-mask-center')).css ({'height' : options.itemHeight,'width' : options.itemWidth,'opacity' : options.thumbOpacity});
	mask_items.css({'left' : options.startItem * options.itemWidth-2000,'width' : 5000});	
	
	// using for mouse wheel up or up
	 thumbs_handles.bind('mousewheel', function(event, delta) {
            var dir = delta > 0 ? 'Up' : 'Down',
                vel = Math.abs(delta);
			if( delta > 0 ){
				clickPrev();	
			} else {
				clickNext();		
			}
            return false;
        });
	/*For jm-slide-thumbs-handles*/
	thumbs_handles.css('left', '0px');
	$($(this).find('.jm-slide-thumbs-handles > span')).css({'width': options.itemWidth, 'height': options.itemHeight});
	
	if ($($(this).find('.jm-slide-buttons') ) && options.overlapOpacity <1 )  {
		$($(this).find('.jm-slide-buttons')).css('opacity', options.overlapOpacity);	
	}
	
	 if (options.showDesc == 'desc' || options.showDesc == 'desc-readmore') {
		 	maskDesc.css({'display': 'block', 'position':'absolute', 'top': '0px', 'left': '0px', 'width':  conWidth , 'height': options.mainHeight, 'visibility': 'visible', 'opacity':  '0.01'});
			$(maskDesc.find('.jm-slide-desc')).css({width:options.mainWidth-20});
			if (options.descMode == 'mouseover') {	
				maskDesc.attr('id', '-1');
				maskDesc.hover (function() {
					
					/*Stop when hover*/	
					clearInterval(timeOutFn);
					clearInterval(timeAfterClick);

					if (maskDesc.attr('id') == currNo) {
						return false;
					}
					
					if (options.showDesc == 'desc') {
						if (descs[currNo].innerHTML != '') {
							$(maskDesc.find('.jm-slide-desc')).html(descs[currNo].innerHTML);	
						}					
						 maskDesc.stop(false, true).animate({'opacity': options.descOpacity},'slow');
					}	
					
					if (options.showDesc == 'desc-readmore') {
						
						if (descs[currNo].innerHTML != '') {
							$(maskDesc.find('.jm-slide-desc')).html(descs[currNo].innerHTML);
							maskDesc.stop(false, true).animate({'opacity': options.descOpacity},'slow');
						} else {
							maskDesc.stop(false, true).animate({'opacity': '0.01'},'fast');	
						}
						 
						
						/*Update link*/
						if (readon && options.urls[currNo]) {							
							readon.attr('href', options.urls[currNo] );
						}
			
					}
					
					maskDesc.attr('id', currNo);
				}, function() {
					maskDesc.stop(false, true).animate({'opacity': '0.01'},'slow');	
					maskDesc.attr('id', '-1');
					/*Continute*/
					fadeElement();
				});
			}
		
	 }
	 
	
	
	 var fadeElement = function() {
		 if (options.autoPlay == true) {
			if(items.length > 1) {			
				timeOutFn = setInterval(makeAnimation, options.duration);				
			} else {
				if (items.length=1) makeAnimation(); 
				//else console.log("oh...");
			}
		} else {
			makeAnimation(); 
		}
	}
	
	/*Mask*/	
	$(thumbs_handles).find('span').each(function(k) {
			$(this).click (function (){	
				if (currNo != k ) {
					next = false;
					clearInterval(timeOutFn);
					clickImg(k);
				} else {
					return false;	
				}
			});				
	 });
	
	/*Setting for button*/	
	if ($($(this).find(options.but_prev))) {
		$($(this).find(options.but_prev)).click (function() {
				clickPrev();
		});
	}
	
	if ($($(this).find(options.but_next))) {
		
		$($(this).find(options.but_next)).click (function() {
				clickNext();
		});
	}
	
		
	if ($($(this).find(options.but_play))) {
		$($(this).find(options.but_play)).click (function() {
				clickPlay();
		});
	}
	
	if ($($(this).find(options.but_playback))) {
		$($(this).find(options.but_playback)).click (function() {				
				clickPlayBack();
		});
	}
	
	if ($($(this).find(options.but_stop))) {
		$($(this).find(options.but_stop)).click (function() {
				clickPause();
		});
	}
	
	/*For clicking the Next button*/
	var clickNext = function () {
			clearInterval(timeOutFn);			
			currNo      = currNo + 1;						
			if (currNo > (items.length - 1)) {	
				currNo = 0;				
			}			
			next = false;			
			clickImg(currNo);
	}
	
	/*For clicking the Previous button*/
	var clickPrev = function () {		
			clearInterval(timeOutFn);			
			currNo  =  currNo - 1;			
			if (currNo < 0) {
				currNo = items.length - 1;				
			} 			
			next = false;			
			clickImg(currNo);
	}
	
	/*For clicking the Pause button*/
	var clickPause = function () {
		options.autoPlay = false;
		clearInterval(timeOutFn);
	}
	
	/*For clicking the Play button*/
	var clickPlay = function () {
		options.autoPlay = true;
		typePlay = 'next';
		clickNext(); //Play the next element
	}
	
	/*For clicking the PlayBack button*/
	var clickPlayBack = function () {
		options.autoPlay = true;
		typePlay = 'back';
		clickPrev(); //Play the previous element
	}
	
	/*For clicking a thumb image*/
	var clickImg = function(k) {		
		currNo = k;					
		currentItem = items[currNo];
		
		/*Check animation*/
		switch(options.animation) {
			case 'basic':
				$(items[lastNo]).hide('fast');
				$(items[currNo]).show(options.interval/2);
				break;
			case 'sliding':
				$(items[lastNo]).slideUp('fast');
				$(items[currNo]).slideDown(options.interval/2);
				break;
			case 'fading':
				$(items[lastNo]).fadeOut('fast');
				$(items[currNo]).fadeIn(options.interval/2);
				break;
			case 'custom':
				$(items[lastNo]).stop(false, true).animate({'left': '-1000px'},'fast');
				$(items[currNo]).stop(false, true).animate({'left': '0px'},options.interval/2);
				break;	
			default:
				$(items[lastNo]).fadeOut('fast');
				$(items[currNo]).fadeIn(options.interval/2);
		}
				
		lastNo = currNo;
		moveThums();
		isClick = true;
		timeStart = new Date();
		animateAfterClick();
	}
	
	/*Make an animation*/
	var makeAnimation = function() {
			
			if (typePlay == 'next') {
				currNo      = jQuery.inArray(currentItem, items) + 1;
				currNo = (currNo > items.length) ? 0 : (currNo - 1);
				
				if (currNo < 0 ) {
					currNo = 0;
				}				
				lastIdx = (currNo <= 0 ) ? items.length - 1 : (currNo - 1);
			} else {
				currNo      = jQuery.inArray(currentItem, items) - 1;
				currNo = (currNo > items.length) ? 0 : (currNo + 1);
				
				if (currNo < 0 ) {
					currNo = items.length - 1;
				}
				
				lastIdx = currNo + 1;
			}
			
			lastNo = currNo
			
			if (isClick && inc == 0) {
				inc++;				
			}
			
			if(next == true) {	
               		next = false;                    
					$(items[currNo]).stop(false, true).animate({opacity: 1},'slow');					
					currentItem = items[currNo];
					
					switch(options.animation) {
					case 'basic':
						$(items[currNo]).show(options.interval, function() {								
								$(items[lastIdx]).hide(options.duration / 2);
						});
						break;
					case 'sliding':						
						$(items[currNo]).slideDown(options.interval, function() {
							$(items[lastIdx]).slideUp(options.duration / 2);										  
						});
						break;
					case 'fading':						
						$(items[currNo]).fadeIn(options.interval, function() {
							$(items[lastIdx]).fadeOut(options.duration / 2);										  
						});
						break;
					case 'custom':						
						$(items[currNo]).stop(false, true).animate({'left': '0px'}, options.interval);
						break;	
					default:
						$(items[currNo]).fadeIn(options.interval, function() {
							$(items[lastIdx]).faceOut(options.duration / 2);										  
						});
					}
					
					/*Move thumb images*/
					moveThums();					
					
            } else {				
				   next = true;
				  	
					if (typePlay == 'next') { 
						currentItem = items[currNo + 1];
					} else {
						currentItem = items[currNo - 1];	
					}			
			}
			
			 if (options.autoPlay == false) {
				clearInterval(timeOutFn);
			 }
    }
        
		/*For move a thumb*/
		var moveThums = function () {				
				if (options.descMode != 'mouseover') {
					if (options.showDesc == 'desc' || options.showDesc == 'desc-readmore') {
						
						/*Update link*/
						if (readon && options.urls[currNo]) {
							readon.attr('href', options.urls[currNo] );
						}
						
						if (descs[currNo].innerHTML != '' ) {
							$(maskDesc.find('.jm-slide-desc')).html(descs[currNo].innerHTML);
							maskDesc.stop(false, true).animate({'opacity': options.descOpacity},'slow');
						} else {
							maskDesc.stop(false, true).animate({'opacity': '0.01'},'fast');
						}
					}				
				}
				
				$(thumb_items[currNo]).addClass('active');				
				if ( currNo >= (options.showItem - 2)) {					
					if (currNo < (items.length - 1)) {	
						obj = eval("({"+modes[options.navPos][0]+": -"+ ( 2000 - ((options.showItem - 2) * spSize) )+"})");
						mask_items.stop(false, true).animate(obj, options.interval);	

						obj2 = eval("({"+modes[options.navPos][0]+": -"+ (((currNo + 1) - (options.showItem - 1)) *spSize )+"})");
						thumbs.stop(false, true).animate(obj2, options.interval);
						thumbs_handles.stop(false, true).animate(obj2, "slow");
					} else {		
						obj = eval("({"+modes[options.navPos][0]+": -"+( 2000 - ((options.showItem - 1) * spSize) )+"})");
						mask_items.stop(false, true).animate(obj, options.interval);
						obj2 = eval("({"+modes[options.navPos][0]+": -"+(((currNo) - (options.showItem - 1)) * spSize )+"})");
						thumbs.stop(false, true).animate(obj2, options.interval);
					}									
				} else {		
					obj = eval("({"+modes[options.navPos][0]+": -"+( 2000 - (currNo * spSize) ) +"})");
					mask_items.stop(false, true).animate(obj, options.interval);	

					obj2 = eval("({"+modes[options.navPos][0]+": 0})");					
					thumbs.stop(false, true).animate(obj2, "slow")
					thumbs_handles.stop(false, true).animate(obj2, options.interval);
				}
				
				for ($i = 0; $i < (items.length) ; $i++) {
					if (currNo != $i) {
						 $(thumb_items[$i]).removeClass('active');
					}
				}
		}
		
		
		var animateAfterClick = function() {
			clearInterval(timeAfterClick);
			timeAfterClick = setInterval(function() { 
				timeCurrent = new Date();
				
				if (timeCurrent.getSeconds() - timeStart.getSeconds() > 2) {
						clearInterval(timeAfterClick);
						fadeElement();
				}
			}, 1000);
		}
		/*start animation*/		
		fadeElement();
}
})(jQuery);
