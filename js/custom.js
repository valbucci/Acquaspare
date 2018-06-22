
$(document).ready(function(){
  $(".rowlist").toggle()
  $(".whoops4").toggle()
  $(".whoops2").toggle()
  $('.whoops3').toggle()
  $('.toggled').toggle()
  $(".popup").toggle()
  //RIMOZIONE VETRINA showcase_management.php
  $(".rem_shcs-oview").click(function(){
	 var showcase = ($(this).attr("id"))
	 $.ajax({
   		type: 'post',
   		url: 'remove_showcase.php',
   		data: {
   			source1: showcase
   		},
   		success: function() {
   			$("div.vetshcs.container#"+showcase).remove()
   		}
   	})
  })

  $('#carousel-custom .item').click(function(){
	  var filename = $(this).attr('filename');
	  $('#'+filename).modal('show');
	  $('#'+filename+' .zoomed-prod').css('display','flex')
  })

  $('.zoomed-prod .modal-content button.close').click(function(){
	  var filename = $(this).attr('filename');
	  $('#'+filename).modal('hide');
	  $('#'+filename+' .zoomed-prod').css('display','hidden')
  })

  $('.zoomed-prod').click(function(e){
	  var container	=	$(this).find('.modal-content');
	  if (!container.is(e.target) && container.has(e.target).length === 0){
	  	  $(this).parent().modal('hide');
	  }
  })

  //RIMOZIONE VETRINA move_showcase.php
  $(".shRemoveVet").click(function(){
    var showcase = ($(this).attr("id"))
    $.ajax({
  		type: 'post',
  		url: 'remove_showcase.php',
  		data: {
  			source1: showcase
  		},
  		success: function() {
  			$('#'+showcase).remove()
  			$(".whoops2").slideToggle()
  		}
  	})
  	if($(".shcs").length){
  	  $("#whoops").toggleClass("hide")
  	}
  })
  $(".refundButton").click(function(){
    var id    = ($(this).attr("id"))
    $(".popup").toggle()
    $(".refund").click(function(){
  	if($('input.checkbox').is(':checked')){
  	  var message = $("textarea").val()
  	}
  	$.ajax({
  		  type: 'post',
  		  url: 'redirect_group/send_refund.php',
  		  data: {
  			  id  : id,
  			  msg : message
  		  },
  		  success: function( data ) {
  			  console.log( data )
  			  $("#"+id).toggle()
  			  $(".popup").toggle()
  			  $('#toggle'+id).slideToggle()
  		  }
  	  })
    })
  })
  $(".flush").click(function(){
    var id  = ($(this).attr("id"))
    $.ajax({
  		type: 'post',
  		url: '../redirect_group/cart_flush.php',
  		data: {
  			id  : id
  		},
  		success: function( data ) {
  			console.log( data )
  			location.reload()
  		}
  	})
  })
  $(".user").click(function(){
    var id  = ($(this).attr("id"))
    $('#toggle'+id).slideToggle()
  })
  $(".send").click(function(){
    var id    = ($(this).attr("id"))
    var email = ($(this).attr("email"))
    var ordine = ($(this).attr("ordine"))
    $.ajax({
  		type: 'post',
  		url: 'redirect_group/send_product.php',
  		data: {
  			send:   id,
  			email:  email,
  			ordine: ordine
  		},
  		success: function( data ) {
  			console.log( data )
  			location.reload();
  		}
  	})
  })
  $(".removeproduct").click(function(){
    var product = ($(this).attr("id"))
    var showcase= ($(this).attr("vetrina"))
    $.ajax({
  		type: 'post',
  		url: 'remove_from_showcase.php',
  		data: {
  			product: product,
  			showcase: showcase
  		},
  		success: function() {
  			$("DIV[id='"+product+"']").hide()
  		}
  	})
  })
  $("A[class='btn btn-success vetrina']").click(function(){
    var redirect = ($(this).attr("id"))
    $.ajax({
  		type: 'post',
  		url: 'redirect_showcase.php',
  		data: {
  			source1: redirect
  		},
  		success: function( data ) {
  			console.log( data );
  		}
  	})
  })

/*  $("A[class='btn btn-info seeprod']").click(function(){
  var id = ($(this).attr("id"))
  $.redirect('visualizza_prodotto.php', {
        'prodid': id
  })
  })*/

  $('.spoiler').click(function(){
  var id = ($(this).attr("id"))
  $(this).toggleClass('fa-minus')
  $(this).toggleClass('fa-plus')
  $('#'+id+'.rowlist').slideToggle()
  })

  $("A[class='btn btn-danger remove']").click(function(){
  var del = ($(this).attr("id"))
  $.ajax({
        type: 'post',
        url: 'remove_cart.php',
        data: {
            source1: del
        },
        success: function( data ) {
            console.log( data );
        }
    });
    $('.prod-container#'+del).remove()
    location.reload()
  })

  $("A[class='btn btn-warning quantita']").click(function(){
  var id = ($(this).attr("id"))
  $('DIV[class="btn-group btn-group-justified center-block '+id+'"]').css('display', 'none');
  $('FORM[class="quantita '+id+'"]').css('display', 'inline')


  })

  $("A[class='btn btn-success products']").click(function(){
  var id = ($(this).attr("id"))
  $.redirect('payment/payments.php', {
        'cmd': '_xclick',
        'no_shipping': '2',
        'no_note': '1',
        'lc': 'IT',
        'currency_code': 'EUR',
        'bn': 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest',
        'item_id':  id
  })
  })

  $("A[class='btn btn-success cart']").click(function(){
  var id = ($(this).attr("id"))
  $.redirect('payment/payments.php', {
        'cmd': '_xclick',
        'no_shipping': '2',
        'no_note': '1',
        'lc': 'IT',
        'currency_code': 'EUR',
        'bn': 'PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest',
        'carrello': '1'
  })
  })

  $("DIV[class='btn btn-danger trash']").click(function(){
  var delfile = ($(this).attr("id"))
  $.ajax({
        type: 'post',
        url: 'delete.php',
        data: {
            source1: delfile
        },
        success: function( data ) {
            console.log( data )
            $("#imgcnt").remove()
        }
    })
  })
	$(".add-showcase").bind('keypress', function(e){
		//Prevent character different from
		//a-z (97-122)
		//A-Z (65-90)
		//0-9 (48-57)
		if(e.which<48 ||
		  (e.which>57 && e.which<65) ||
	 	  (e.which>90 && e.which<97) ||
	  	   e.which>122){
			   e.preventDefault()
	   	}
	})
})
