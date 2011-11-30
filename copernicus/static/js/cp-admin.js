jQuery(document).ready(function($) {
	page_order($);
});

function page_order($) {
	$('#menu_order').hide();
	$('#menu_order').parent().hide();
	$('#menu_order').parent().prev('p').hide();
	$('#menu_order').parent().next('p').hide();
	
	menu_order = $('#menu_order').val();
	$('#menu_order').parent().parent().append('<input type="hidden" name="menu_order" value="'+menu_order+'" />');
}