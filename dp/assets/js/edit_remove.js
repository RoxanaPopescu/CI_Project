$(document).ready(function(){

   $('.clickable').click(function(){

        if($(this).parent().parent().find('.modify_options').css('display') != 'none'){
            $(this).html('+');
        }
        else {
            $(this).html('-');
        }

        $(this).parent().parent().find('.modify_options').slideToggle();
        
   });

})

