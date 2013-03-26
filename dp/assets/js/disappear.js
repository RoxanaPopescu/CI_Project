$(document).ready(function(){
// displaying errors from delivery address field and from the log in form

    if($('.delivery_address_error span').length){
        $('.delivery_address_error span').hide().fadeIn(2000).fadeOut(2500);
    }

    if($('.validation_error span').length){
        $('.validation_error span').hide().fadeIn(2000).fadeOut(2500);
    }

})