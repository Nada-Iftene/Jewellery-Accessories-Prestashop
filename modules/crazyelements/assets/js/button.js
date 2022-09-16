
document.addEventListener("DOMContentLoaded", function (event) {
  //do work

    if(IS_CUSTOM == 'false'){

      $('textarea[name^=resource_]').each(function (i, tag) {

        if(DONT_EDIT == 'true'){
          var id = tag.id.split('_').pop();
    
          var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
    
          var btnObj = $(content_html).insertBefore(tag);
    
    
    
              $(tag).remove();
    
    
        }else{
          var id = tag.id.split('_').pop();
    
          var button_html = $('#edit_with_button').html();
    
          var btnObj = $(button_html).insertBefore(tag);
    
          var href = btnObj.attr('href');
          var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
          btnObj.attr('href', modifiedHref);
    
              $(tag).remove();
    
    
        }
    
      });
      //------------------------------------------------==============================------------------------------------------//
      
      
      
        // if(_PS_VERSION_ >= '1.7.6.0'){
            
          $('textarea[id^=cms_page_content_]').each(function (i, tag) {
      
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
      
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
                if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
      
            }
        
          });
          // category
                    
          $('textarea[id^=category_description_]').each(function (i, tag) {
            var button_html = $('#edit_catg_with_crazy').html();
            $(button_html).insertBefore(tag); 
          });
          //product
          $('#form_step1_description textarea[id^=form_step1_description_]').each(function (i, tag) {
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }
          });
          
          //supplier
          $('textarea[id^=description_]').each(function (i, tag) {
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }
      
        
          });
      
          $('textarea[id^=supplier_description_]').each(function (i, tag) {
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }
      
      
          });
          
          // manu
          $('textarea[id^=manufacturer_description_1]').each(function (i, tag) {
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
      
            }
        
          });
          
          
          
          
        // }else{
      
        // usually cms
          $('textarea[name^=content_]').each(function (i, tag) {
      
            if(DONT_EDIT == 'true'){
              var id = tag.id.split('_').pop();
      
              var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
      
              var btnObj = $(content_html).insertBefore(tag);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }else{
              var id = tag.id.split('_').pop();
      
      
              var button_html = $('#edit_with_button').html();
      
              var btnObj = $(button_html).insertBefore(tag);
      
              var href = btnObj.attr('href');
      
              var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
              btnObj.attr('href', modifiedHref);
      
              if(ALLOW_PRESTA_EDITOR =='no'){
                $(tag).remove();
              }
            }
        
          });
        // }
    }else{

      $('textarea[name^='+FIELD_NAME+'_]').each(function (i, tag) {
        
        if(DONT_EDIT == 'true'){
          var id = tag.id.split('_').pop();
  
          var content_html = "<h2 style='margin:0px;'>"+DONT_EDIT_MESSAGE+"</h2>";
  
          var btnObj = $(content_html).insertBefore(tag);

        }else{
          var id = tag.name.split('_').pop();
  
          var button_html = $('#edit_with_button').html();
  
          var btnObj = $(button_html).insertBefore(tag);
  
          var href = btnObj.attr('href');
  
          var modifiedHref = href.replace('&id_lang=', '&id_lang=' + id);
          btnObj.attr('href', modifiedHref);
        }
    
      });

    }
  

});