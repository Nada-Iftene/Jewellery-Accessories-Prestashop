document.addEventListener("DOMContentLoaded", function (event) {    
    $( "#crazy_update_bt" ).click(function() {
      $( ".update-ajax-loader" ).show();
      var durl = $(this).data('down_url');
      $.ajax({                          
        url: ajax_update,          
        type: 'POST',                                               
        data: {
          action: 'ajax_down_func',
          down_update : 1,
          down_url: durl,
          down_v: $(this).data('down_vs'),
        },
        success: function(result){
          $( "#crazy_update_bt" ).hide();
          $( ".update-ajax-loader" ).hide();
          $( ".update_msg" ).html("Your Version is Up to Date");
          $( ".update_vsn" ).html("Updated");
        },
        });  
    });
    $("#close_ad").click(function(){
      $.ajax({                          
        url: ajax_update,          
        type: 'POST',                                               
        data: {
          action: 'ajax_close_ad'
        },
        success: function(result){
          $(".promo-area").remove();
        },
        });
    });
});