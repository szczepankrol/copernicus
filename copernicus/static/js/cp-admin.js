jQuery(document).ready(function($) {
	page_order($);
	drag_n_drop($);
});

function page_order($) {
	$('#menu_order').hide();
	$('#menu_order').parent().hide();
	$('#menu_order').parent().prev('p').hide();
	$('#menu_order').parent().next('p').hide();
	
	menu_order = $('#menu_order').val();
	$('#menu_order').parent().parent().append('<input type="hidden" name="menu_order" value="'+menu_order+'" />');
}

function drag_n_drop($) {
	$('#the-list tr th').prepend('<div class="sort-handler"></div>');
	
	tr_w = $('#the-list tr').width();
	$('#the-list tr').css('width', tr_w);
	
	$('#the-list tr').each(
		function(){
			$(this).children('td').each(
				function(){
					tr_ww = $(this).width();
					$(this).css('width', tr_ww);
				}
			);
		}
	);
		
	$('#the-list').nestedSortable({
		handle : '.sort-handler',
		axis:'y',
		containment: 'tbody',
		update: function(event, ui) {
			$('#the-list tr').removeClass('alternate');
			$('#the-list tr:even').addClass('alternate');
		}
	});
}