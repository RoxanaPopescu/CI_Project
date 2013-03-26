$(document).ready(function(){
   $('a.add_more').hide();// hide the 'Order more items' links when the page loads
   $('ul.products form').submit(function(){// when a product is added to the cart
       var sendTo;
       var resetValue = 0;
        if($(this).is('.add')){ // if the form has the class 'add' attached to it
                                 // (the form comes from the products page)
             sendTo = '/dp/cart/add';
             resetValue = '1'; //reset the value in the quantity field
        }
        else if($(this).is('.add_universal')){ // if the form has the class 'add_universal' attached to it
                                     // (the form comes from the universal products page)
             sendTo = '/dp/cart/add_universal';
             resetValue = '1';
        }
        else if($(this).is('.update_universal')){ // if the form has the class 'update_universal' attached to it
                                                 // (the form comes from the 'view cart' page)
            sendTo = '/dp/cart/update_universal';
        }
        else{ // if the form has the class 'update' attached to it
              // (the form comes from the 'view cart' page)
            sendTo = '/dp/cart/update';
        }
        // get the product id and the quantity chosen by the user
        var product_id = $(this).find('input[name=product_id]').val();
        var quantity = $(this).find('input[name=quantity]').val();
        var category = $(this).find('input[name=product_category]').val(); // get also the category (id) of the product
                                                                           // to know if the product is a machine part or
                                                                           // an universal product
        var cct = $(this).find('input[name=csrf_test_name]').val();// get the csrf_token_name value of the hidden
                                                                   // input field, in order to post it
                                                                   // together with the product id and
                                                                   // the quantity chosen
        // if the cct value is not posted, it will cause a 500 internal server error

        var intRegex = /^\d+$/;// regular expression stating that the variable is an integer
        // post the retrieved data to the cart controller and request the add function
        $.post(sendTo, {product_id: product_id, quantity: quantity, category: category, ajax: '1', csrf_test_name: cct}, function(data){
            if(data.indexOf('|') >= 0){
                var total = 0;
                var x = data.split('|');
                if(x[0] == 'true'){

                    // when the product is finally added, reset everything on the page
                    $('form span.error_qty_class').html('');

                    $('#success_qty_' + product_id + '_' + category).html('√ Added').hide().fadeIn(1500);
                    $('#update_qty_' + product_id + '_' + category).html('√ Updated').hide().fadeIn(1500);

                    if(resetValue == 1){
                        $('input[name=quantity]').val('1'); // if the form comes from the products' page
                                                            // reset the quantity value to 1
                    }
                  
                    $('.add_more').hide();

                    if(x[1] > 0){
                        $('.cart_desc').html('Products in your cart ');
                    }
                    $('.cart_nr').html(x[1]);
  
                    if(x.length == 7){
                       // alert(x[4]);
                        $('.cart_products li#' + 'display_' + x[2] + '_' + x[6] + ' span.display_qty').html(' ' + x[3]);
                        $('.cart_products li#' + 'display_' + x[2] + '_' + x[6] + ' span.display_subtotal').html(x[4] + ' RON');
                        $('.cart_products span.total_sum').html(x[5] + ' RON');
   
                    }
                    if(x.length == 10){
                        $('.cart_products ul').append('<li id="display_' + x[2] + '_' + x[9] + '">' + x[3] + ' ' + x[4] +
                                                 '<span class="display_qty"> ' + x[5] + '</span>x' + x[6] +
                                                 ' RON=' + '<span class="display_subtotal">' + x[7] + ' RON</span></li>'
                                                );
                        $('.cart_products span.total_sum').html(x[8] + ' RON');
                    }
                }
            }
            else if(intRegex.test(data)){// if the data returned is an integer
                $('#error_qty_' + product_id + '_' + category).html('You can order maximum <strong>' + data +
                             '</strong> items from this product.<br />\n\
                               You can order more items, here:').hide().fadeIn(1500);

                $('#add_more_' + product_id + '_' + category).hide().fadeIn(1500); // show the 'Order more items' link

                $('form span.success_qty_class').html(''); // reset the success message
                $('form span.update_qty_class').html(''); // reset the update message
            }
            else{
                $('.add_more').hide();
                $('#error_qty_' + product_id + '_' + category).html(data).hide().fadeIn(1500); // echo the error, if there is one, and prevent
                                                          // the product to be added to the cart

                if(data != 'Please specify a quantity!' &&
                   data != 'Please enter a numeric value for the quantity chosen!'){
                    $('#add_more_' + product_id + '_' + category).fadeIn(1500); // show the 'Order more items' link
                }

                $('form span.success_qty_class').html(''); // reset the success message
                $('form span.update_qty_class').html(''); // reset the update message
               
            }

           // alert('Da');
        });

        return false;
    });


})

