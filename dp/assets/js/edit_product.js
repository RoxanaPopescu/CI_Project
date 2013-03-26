$(document).ready(function(){
    
    $('.admin_edit_product').on('click', '.details_list', function(){

        if(!$(this).is(':checked')){
            var currentId = $(this).attr('id');

            var substr = currentId.split('_');
            var last = substr[2];

            $('#value_field_' + last).hide();

        }
        else{
             currentId = $(this).attr('id');

             substr = currentId.split('_');
             last = substr[2];

             $('#value_field_' + last).show();
        }

   });

    $('.admin_edit_product').on('click', 'button.add_detail', function(){
            var new_detail = $(this).parent().find('.new_detail').val();
            var cct = $(this).parents().find('input[name=csrf_test_name]').val();
            var pn_id = $(this).parents().find('input[name=pn_id]').val();

            var test1 = $(this).parent().find('.new_detail');
            var test2 = $(this).parent().find('button.add_detail');
            var test3 = $(this).parent();



             $.post('/dp/admin/add_detail', {new_detail: new_detail, pn_id: pn_id, csrf_test_name: cct}, function(data){
                 if(data.indexOf('|') >= 0){
                     var data_array = data.split('|');
                     
                     if(data_array[0] == 'true'){
                         //alert($(this).parent().find('.new_detail').val());
                        /* $(this).parent().find('.add_detail').remove();*/
                         test3.prepend('<input type="checkbox" name="product_details[]" id="product_detail_'
                                                                            + data_array[1] +'" class="details_list" value="' + data_array[1] + '" />\n\
                                                                          <label for="product_detail_' + data_array[1] + '">' + new_detail + '</label>\n\
                                                                           <input type="text" name="value_field_' + data_array[1] + '" id="value_field_' + data_array[1] + '"\n\
                                                                            size="6" maxlength="4" style="display: none;"/>');
                         test1.remove();
                         test2.remove();
                     }
                     else {
                         $.scrollTo('#product_detail_' + data_array[0], 1000,{axis:'y', offset: -50} );

                          a = $('#product_detail_' + data_array[0]).parent();

                          a.effect("highlight", {'color' : 'red'}, 3000);

                          $('#product_detail_' + data_array[0]).parent().after('<span class="already">' + data_array[1] + '</span>');

                            setTimeout(function(){
                                $('.already').fadeOut(3000);
                            }, 2000);
                     }
                 }
                 else {
                     test3.prepend('<input type="checkbox" name="product_details[]" id="product_detail_'
                                                                            + data + '" class="details_list" value="' + data + '" />\n\
                                                                          <label for="product_detail_' + data + '">' + new_detail + '</label>\n\
                                                                           <input type="text" name="value_field_' + data + '" id="value_field_' + data + '"\n\
                                                                            size="6" maxlength="4" style="display: none;"/>');
                     test1.remove();
                     test2.remove();
                 }


             });
    });

    /*$('.admin_edit_product').on('click', 'button.remove_detail', function(){
         $(this).parent().find('.new_detail').remove();
         $(this).parent().find('button.add_detail').remove();
         $(this).parent().find('button.remove_detail').remove();
    });*/

   $('.details_list').each(function(){
       var currentId = $(this).attr('id');

       var substr = currentId.split('_');
       var last = substr[2];

       /*if($(this).is(':checked')){
           $('#value_field_' + last).show();
       }*/

       if(!$(this).is(':checked') && !$('#value_field_' + last).val()){
            $('#value_field_' + last).hide();
        }
   })

   /*$('.admin_edit_product').on('click', '.details_list', function(){

        if(!$(this).is(':checked')){
            var currentId = $(this).attr('id');

            var substr = currentId.split('_');
            var last = substr[2];

            $('#value_field_' + last).hide();

        }
        else{
             currentId = $(this).attr('id');

             substr = currentId.split('_');
             last = substr[2];

             $('#value_field_' + last).show();
        }

   });*/

   $('#add_more').on("click", function(){
       $(this).parents().find('div.description').append('<div class="enclose_adding"><input type="text" name="new_detail" class="new_detail" />\n\
                                                         <button type="button" class="add_detail">Add</button></div><br />');

   });
 
   /*function highlight(a){
       a.hide();
       a.css({'background-color': 'red'});
       a.fadeIn(2000).fadeOut(2500);
   }*/
})


