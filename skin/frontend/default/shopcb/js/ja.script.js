if (window.jQuery && jQuery.noConflict && (typeof $('body') == 'object')) {
	jQuery.noConflict();
}
function switchFontSize (ckname,val){
	var bd = $$('body')[0];
	switch (val) {
		case 'inc':
		if (CurrentFontSize+1 < 7) {
			bd.removeClassName('fs'+CurrentFontSize);
			CurrentFontSize++;
			bd.addClassName('fs'+CurrentFontSize);
		}
		break;
		case 'dec':
		if (CurrentFontSize-1 > 0) {
			bd.removeClassName('fs'+CurrentFontSize);
			CurrentFontSize--;
			bd.addClassName('fs'+CurrentFontSize);
		}
		break;
		default:
		bd.removeClassName('fs'+CurrentFontSize);
		CurrentFontSize = val;
		bd.addClassName('fs'+CurrentFontSize);
	}
	createCookie(ckname, CurrentFontSize,365);
}

function switchTool (ckname, val) {
	createCookie(ckname, val, 365);
	window.location.reload();
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };

function menuFistLastItem () {
	if ((menu = $('nav')) && (children = menu.childElements()) && (children.length)) {
		children[0].addClassName ('first');
		children[children.length-1].addClassName ('last');
	}
}

//Add span to module title
function addSpanToTitle () {
	//var colobj = document.getElementById ('ja-col');
	//if (!colobj) return;
	var modules = $$('.side-col .box .head h4','.jm-product-list h2','.jm-products-slider-listing h2','.jm-product-list-bycat h2');
	if (!modules) return;
	modules.each (function(title){
		var html = title.innerHTML;
		var text = html.stripTags();
		var pos = text.indexOf(' ');
		if (pos!=-1) {
			text = text.substr(0,pos);
		}
		title.update(html.replace(new RegExp (text), '<span class="first-word">'+text+'</span>'));
	});
}

document.observe("dom:loaded", function() {   
	// initially hide all containers for tab content $$('div.tabcontent').invoke('hide'); 
	menuFistLastItem();
	navMouseHover();
	addSpanToTitle();
	//makeEqualHeight ($$('.category-listing .jm-boxwrap'));
	//makeEqualHeight ($$('#ja-botsl .box'));
}); 

//Add hover event for li - hack for IE6
function navMouseHover () {
	var lis = $$('#nav li');
	if (lis && lis.length) {
		lis.each (function(li){
			li.onMouseover = toggleMenu (li, 1);
			li.onMouseout = toggleMenu (li, 0);
		});
	}
}

toggleMenu = function (el, over) {
	  var iS = false;
    if (Element.childElements(el) && Element.childElements(el).length > 1) {
	    var uL = Element.childElements(el)[1];
			iS = true;
		}
    if (over) {
        Element.addClassName(el, 'over');
				Element.addClassName (el.down('a'), 'over');
        if(iS){ uL.addClassName('shown-sub')};
    }
    else {
        Element.removeClassName(el, 'over');
				Element.removeClassName (el.down('a'), 'over');
        if(iS){ uL.removeClassName('shown-sub')};
    }
}

function makeEqualHeight(divs, offset) {
	if (!offset) offset = 0;
	if(!divs || divs.length < 2) return;
	var maxh = 0;
	divs.each(function(el){
		var ch = el.getHeight();
		maxh = (maxh < ch) ? ch : maxh;
	});
	maxh += offset;
	divs.each(function(el){
		el.setStyle({height: (maxh-parseInt(el.getStyle('padding-top'))-parseInt(el.getStyle('padding-bottom'))) + 'px'});
	});
}