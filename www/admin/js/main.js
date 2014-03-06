/*Declare new function in jQuery namespace*/

//Declare function reset for redeclaration action button reset
jQuery.fn.reset = function () {
    $(this).find('input').val('');
}

$(function(){

    //Redeclaration action button reset
    $('input[type=reset], button[type=reset]').bind('click', function(){
        $('form').reset();
        return false;
    });

});