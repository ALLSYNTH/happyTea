/* -------------------------------------
	TEAM PAGE - hover animation
------------------------------------- */

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

	// console.log($(window).height());


	//JS REQUETE AJAX FAVS

	//Ajout aux favotis
	$('body').on('click', '.fav', function(event){
		event.preventDefault();

		var path = $(this).data("path");
		var id = $(this).data("id");

		$.ajax({
			 url : path+'?id='+id,
			 type : 'GET',
			 success : function(statut){
	      $('.fav').addClass('supfav').html('Supprimer des favoris').removeClass('fav');
	     }
		});
	});

	//Remove des favoris
	$('body').on('click', '.supfav', function(event){
		event.preventDefault();

		var path = $(this).data("path");
		var id = $(this).data("id");

		$.ajax({
			 url : path+'?id='+id,
			 type : 'GET',
			 success : function(statut){
	      $('.supfav').addClass('fav').html('Ajouter des favoris').removeClass('supfav');
	     }
		});
	});


	// JS REQUETE AJAX ADMIN PAGE

	// requete JS de base

	$("body").on('click', '.action-button', function(event){
	  event.preventDefault();
	  // var path = $(this).attr("href");
	    var path = $(this).data("href");
	    $('.action-button').find('h3').css('color' , 'lightgray' );
	    $(this).find('h3').css('color' , 'white');
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

		//requete search

	 $("body").on('click', '#search-button', function(event){
	   event.preventDefault();

	     var path = $(this).data("href");
	     var search = $("#search").val();

	     var req = $(this).data("req");

	     console.log(req);



	   $.ajax({
	      url : path+'?search='+search+'&req='+req,
	      type : 'GET',
	      // data : data,

	      dataType : 'html',
	      success : function(code_html, statut){
	       $('#mainDiv').html(code_html);
	      }
	   });

	});

	  $("body").on('click', '#search-button-shop', function(event){
	    event.preventDefault();

	      var path = $(this).data("href");
	      var search = $("#search-shop").val();

	      var req = $(this).data("req");

	      console.log(req);



	    $.ajax({
	       url : path+'?search='+search+'&req='+req,
	       type : 'GET',
	       // data : data,

	       dataType : 'html',
	       success : function(code_html, statut){
	        $('#mainDiv').html(code_html);
	       }
	    });

	 });

	  $("body").on('click', '.button-article', function(event){
	    event.preventDefault();

	      var path = $(this).data("href");

	      var req = $(this).data("req");
	      var id = $(this).data("id");

	      // console.log(req);

	 /* -------------------------------------
	 	Main index- Isotope
	 ------------------------------------- */


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

	  $("body").on('click', '#check-article', function(event){
	    event.preventDefault();

	      var path = $(this).data("href");
	      var id = $(this).data("id");

	      var req = "check-article";



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



	  // requete ban shop

	  $("body").on('click', '.ban-button-shop', function(event){
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


	  /* JS AJAX UPDATE PRODUIT */

	  //  $("body").on('click', '#remove-product-button', function(event){
	  //    event.preventDefault();

	  //      var path = $(this).data("href");

	  //      var id = $(this).data("id");
	  //      var req = $(this).data("req");

	  //      console.log(req);



	  //    $.ajax({
	  //       url : path+'?id='+id+'&req='+req,
	  //       type : 'GET',
	  //       // data : data,

	  //       dataType : 'html',
	  //       success : function(code_html, statut){
	  //        $('#ajaxTab-product').html(code_html);
	  //       }
	  //    });

	  // });

// JAVASCRIPT POUR STAR-RATING

// $(".fa-star").mouseenter(function() {
// 	$(this).css( 'color' , 'yellowgreen' );
// 	console.log($(this));

// });
function recupStyle(elem, propriete){
    var prop = window.getComputedStyle(elem,null).getPropertyValue(propriete);
   return prop;
}


	$('.fa-star').css("color" , "lightgray");
	$('.fa-star').hover(function(){
    // $(this).css("background-color", "lightgray");
	
		// $(this).css("color", "yellowgreen");
			if($(this).css("color") == 'rgb(154, 205, 50)') {
				$(this).css("color", "lightgray");
			}
			else {
				$(this).css("color", "yellowgreen");
			}
    },
		function(){
			// $(this).css("background-color", "yellowgreen");
			element = $(this); 
			console.log($(this).css("color"));
			if($(this).css("color") == 'rgb(154, 205, 50)') {
				$(this).css("color", "lightgray");
			}
			else {
				$(this).css("color", "yellowgreen");
			}
		});

	$("body").on('click', '.fa-star', function(event){
	  event.preventDefault();

	    var path = $('.rating-star').data("href");
	    var id = $('.rating-star').data("id");
	   	var rate = $(this).data('rate');
	    var req = $('.rating-star').data("req");


	     // console.log(ban);

	  $.ajax({
	     url : path+'?id='+id+'&rate='+rate+'&req='+req,
	     type : 'GET',


	     dataType : 'html',
	     success : function(code_html, statut){
		     for(var i = 0; i < rate  ; i++) {
		     	$('.fa-star').eq(i).css("color", "yellowgreen");

		     }
	     }
	  });

	});

/*ISOTOPE*/

// Initialisation
	var $grid = $('.grid').isotope({
		itemSelector: '.element-item',
		layoutMode:'fitRows',
		getSortData: {
			name:'.category'
		}
	});

// filter functions
	var filterFns = {
	  // show if category ends with -ert
	  ert: function() {
	    var name = $(this).find('.type').text();
	    return name.match( /ert$/ );
	  },

	  lanc: function(){
	  	var name = $(this).find('.type').text();
	  	return name.match( /lanc$/);
	  },

	  //show if name ends with -oir
	  oir: function(){
	    var name = $(this).find('.type').text();
	    return name.match( /oir$/);
	  },

	  utre: function(){
	  	var name = $(this).find('.type').text();
	  	return name.match(/utre$/);
	  }
	};

	// bind filter button click
	$('#filters').on( 'click', 'button', function() {
	  var filterValue = $( this ).attr('data-filter');
	  // use filterFn if matches value
	  filterValue = filterFns[ filterValue ] || filterValue;
	  $grid.isotope({ filter: filterValue });
	});
});




/* -------------------------------------
	Product page - JQuery functions
------------------------------------- */

$(function(){

// Button - redirect user to external Shop Site

	// style on & out hover
	$('#redirect-shop').hover(function(){
    $(this).css("background-color", "#104a2c");
		$(this).css("color", "white");
    },
		function(){
			$(this).css("background-color", "#5cb85c");
			$(this).css("color", "white");
		});

	// redirection to shop site in new tab
	$('#redirect-shop').on('click', function(event) {
    event.preventDefault();
    var url = $(this).data('target');
    window.open(url);
	});

}); // END
