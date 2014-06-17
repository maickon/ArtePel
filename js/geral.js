$(document).ready(function(){
	$("input").addClass('radius5');
	$("textarea").addClass('radius5');
	$("fieldset").addClass('radius5');
	$("fieldset").addClass('radius5');
	$("sidebar li").addClass('radius5');
	$("select").addClass('radius5');
	
	//acordion
	$('#accordion a.itens').click(function(){
		$('#accordion li').children('ul').slideUp('fast');
		$('#accordion li').each(function(){
			$(this).removeClass('active');
		});
		$(this).siblings('ul').slideDown('fast');
		$(this).parent().addClass('active');
		return false;
	});
}); 

$(
		function(){
			$('.bg_imagem')
				.mouseover(function(){
					$(this).children().stop().fadeTo('fast',0.3);
				})
				.mouseleave(function(){
					$(this).children().stop().fadeTo('fast',1);
				});
		}
);