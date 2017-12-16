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






            // JS REQUETE AJAX ADMIN PAGE 

            // requete JS de base 

          $("body").on('click', '.action-button', function(event){
              event.preventDefault();
              // var path = $(this).attr("href");
                var path = $(this).data("href");
                $('.action-button').removeClass('color-title');
                $(this).addClass('color-title'); 
              // var idElement = $(this).attr("id");
              $.ajax({
                 url : path,
                 type : 'GET',
                 dataType : 'html',
                 success : function(code_html, statut){
                  $('#mainDiv').html(code_html);
                 }
              });
              
          });

          // requete changement de role 

          $("body").on('change', '.select-role', function(event){
              event.preventDefault();
           
                var path = $(this).data("href");
                var id = $(this).data("id"); 
                var value = $(this).val(); 

                console.log(value); 

              $.ajax({
                 url : path+'?id='+id+'&value='+value,
                 type : 'GET',
               
                 dataType : 'html',
                 success : function(code_html, statut){
                  $('#mainDiv').html(code_html);
                 }
              });
              
          });

          // requete ban 

            $("body").on('click', '.ban-button', function(event){
              event.preventDefault();
             
                var path = $(this).data("href");
                var id = $(this).data("id"); 
                var ban = $(this).data("ban"); 
                var req = $(this).data("req"); 


                 console.log(ban); 

              $.ajax({
                 url : path+'?id='+id+'&ban='+ban+'&req='+req,
                 type : 'GET',
               
                 
                 dataType : 'html',
                 success : function(code_html, statut){
                  $('#mainDiv').html(code_html);
                 }
              });
              
          });

                      // requete change auto 

            $("body").on('click', '.check-button', function(event){
              event.preventDefault();
            
                var path = $(this).data("href");
                var id = $(this).data("id"); 
            ; 
                var req = "check"; 


             
              $.ajax({
                 url : path+'?id='+id+'&req='+req,
                 type : 'GET',
                 // data : data, 
                 
                 dataType : 'html',
                 success : function(code_html, statut){
                  $('#mainDiv').html(code_html);
                 }
              });
              
          });



});