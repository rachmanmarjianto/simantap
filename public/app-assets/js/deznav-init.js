(function($) {
	
var direction =  getUrlParams('dir');
if(direction != 'rtl')
{direction = 'ltr'; }
	
new dezSettings({
	typography: "roboto",
	version: "light",
	layout: "Vertical",
	headerBg: "color_1",
	navheaderBg: "color_6",
	sidebarBg: "color_6",
	sidebarStyle: "full",
	sidebarPosition: "fixed",
	headerPosition: "fixed",
	containerLayout: "full",
	direction: direction
}); 

})(jQuery);