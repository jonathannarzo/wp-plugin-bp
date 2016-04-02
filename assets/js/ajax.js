jQuery(document).ready(function($){
	$(document).on('click', '#testajax', function(){
		$.ajax({
			url: ajaxRequest.ajaxurl,
			dataType: 'json',
			type: 'POST',
			data: {'action': ajaxRequest.ajaxfunction},
			success:function(data){}
		});
	});
});