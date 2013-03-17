jQuery().ready(function(){
	jQuery('#ja-sidenav li.level0 > a').addClass ('subhead');
	jQuery('#ja-sidenav li.level0 > a').after ('<a href="#" title="" class="toggle">&nbsp;</a>');
	// second simple accordion with special markup
	jQuery('#ja-sidenav').accordion({
		active: '.active',
		header: '.toggle',
		navigation: true,
		event: 'click',
		fillSpace: false,
		autoheight: false,
		alwaysOpen: false, 
		animated: 'easeslide'
	});
});