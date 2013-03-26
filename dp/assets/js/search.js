$(document).ready(function(){
    $('input[name=search]').focus(function(){
       var value = $('input[name=search]').val();
       if(value == 'Search...' || value == ''){ // if the user hasn't typed anything, on focus, the search field becomes empty
            $('input[name=search]').val('');    // otherwise, the value stays the same
       }
    });

    /*$('input[name=search]').blur(function(){
        $('input[name=search]').val('Search...');
    });*/

  $('input[name=search]').blur(function(){
      var value = $('input[name=search]').val();
      if(value == 'Search...' || value == ''){ // if the user hasn't typed anything, on blur, the value becomes 'Search...'
          $('input[name=search]').val('Search...'); // otherwise, it stays the same
         
      }
      //alert('Da');
  });
  
})