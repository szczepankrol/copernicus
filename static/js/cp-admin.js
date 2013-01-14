jQuery(document).ready(function($){
	language_tabs($);
	
	post_edit($);
	//publish_validation($);
});

function language_tabs($) {
	$('.langs > span').click(function(){
		$(this).parent().children('span').removeClass('active');
		$(this).parent().find('div').removeClass('active');
		$(this).addClass('active');
		
		var id = $(this).attr('id');
		$('#div'+id).addClass('active');
	});
}

function post_edit($) {
	if ($('#main-post-title').length > 0) {
		$('#titlewrap label').hide();
		$('#titlewrap input').clone().attr('type','hidden').insertAfter('#titlewrap input').prev().remove();
		$('#main-post-title').keyup(function(){
			$('#title').val($(this).val());
		});
		
	}
}

function publish_validation($) {
	$('#publish').click(function(){
		alert('asd');
	});
}