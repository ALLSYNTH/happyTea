/*JQ sur la page team*/

$(function(){
	$('#monsieurcarpe')
	.mouseover(function(){
		var img = $(this).attr('data-hover');
		$(this).fadeOut(150, function () {
			$(this).attr('src', img);
		}).fadeIn(150);
		
	})
	.mouseout(function(){
		var original = $(this).attr('data-original');
		$(this).fadeOut(150, function () {
			$(this).attr('src', original);
		}).fadeIn(150);

	});

	$('#claire')
	.mouseover(function(){
		var img = $(this).attr('data-hover');
		$(this).fadeOut(150, function () {
			$(this).attr('src', img);
		}).fadeIn(150);
		
	})
	.mouseout(function(){
		var original = $(this).attr('data-original');
		$(this).fadeOut(150, function () {
			$(this).attr('src', original);
		}).fadeIn(150);

	});

	$('#allsynth')
	.mouseover(function(){
		var img = $(this).attr('data-hover');
		$(this).fadeOut(150, function () {
			$(this).attr('src', img);
		}).fadeIn(150);
		
	})
	.mouseout(function(){
		var original = $(this).attr('data-original');
		$(this).fadeOut(150, function () {
			$(this).attr('src', original);
		}).fadeIn(150);

	});

	$('#antoine')
	.mouseover(function(){
		var img = $(this).attr('data-hover');
		$(this).fadeOut(150, function () {
			$(this).attr('src', img);
		}).fadeIn(150);
		
	})
	.mouseout(function(){
		var original = $(this).attr('data-original');
		$(this).fadeOut(150, function () {
			$(this).attr('src', original);
		}).fadeIn(150);

	});

	console.log($(window).height());
});