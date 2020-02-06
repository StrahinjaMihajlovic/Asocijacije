/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


podesavanjeElemenata();

function podesavanjeElemenata(){
/*$('.Resenje').css({'top': $('#Resenje').parent().height()/2,'left': $('#Resenje').parent().width()/2.5});



$('#A').css({'top': ($('#Resenje').position().top - $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.8 * $('#A').width())});



$('#B').css({'top': ($('#Resenje').position().top -  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});

$('#C').css({'top': ($('#Resenje').parent().position().top +  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.9 * $('#A').width())});

$('#D').css({'top': ($('#Resenje').parent().position().top +  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});
*/


/*$('.B').each(function(){
    $(this).css({
       'top' : $('#B').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#B').position().left + $('#B').outerWidth() - $(this).outerWidth(true)
    });

});

$('.C').each(function(){
    $(this).css({
       'top' : $('#C').position().top +( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#C').position().left 
    });
});

$('.D').each(function(){
    $(this).css({
       'top' : $('#D').position().top +( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#D').position().left + $('#D').outerWidth() - $(this).outerWidth(true)
    });
});

$('#Resenje').parent().height($('#C4').position().top - $('#A4').position().top);*/
    
    $('.tekstPolje').each(function(){
        $(this).css({'top': $(this).parent().height()/2,'left': $(this).parent().width()/2.5});
    });
    
    $('.podPolje').each(function(){
        switch($(this).attr('name')){
            case 'A':
                $(this).css({'top': ($(this).siblings(".tekstPolje").position().top - $(this).siblings(".tekstPolje").outerHeight(true)), 
                'left': ($(this).siblings(".tekstPolje").position().left - 0.8 * $(this).siblings(".tekstPolje").width())});
                break;
            case 'B':
                $(this).css({'top': ($(this).siblings(".tekstPolje").position().top - $(this).siblings(".tekstPolje").outerHeight(true)), 
                'left': ($(this).siblings(".tekstPolje").position().left + 0.8 * $(this).siblings(".tekstPolje").width())});
                break;
            case 'C':
                $(this).css({'top': ($(this).siblings(".tekstPolje").position().top + $(this).siblings(".tekstPolje").outerHeight(true)), 
                'left': ($(this).siblings(".tekstPolje").position().left - 0.8 * $(this).siblings(".tekstPolje").width())});
                break;
            case 'D':
                $(this).css({'top': ($(this).siblings(".tekstPolje").position().top + $(this).siblings(".tekstPolje").outerHeight(true)), 
                'left': ($(this).siblings(".tekstPolje").position().left + 0.8 * $(this).siblings(".tekstPolje").width())});
                break;
        }
    });
    
    $('.A').each(function(){
    $(this).css({
       'top' : $(this).siblings(".podPolje[name='A']").position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $(this).siblings(".podPolje[name='A']").position().left
    });});
    
    $('.B').each(function(){
    $(this).css({
       'top' : $(this).siblings(".podPolje[name='B']").position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $(this).siblings(".podPolje[name='B']").position().left + $(this).siblings(".podPolje[name='B']").outerWidth() - $(this).outerWidth(true)
    });});
    
    $('.C').each(function(){
    $(this).css({
       'top' : $(this).siblings(".podPolje[name='C']").position().top +( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $(this).siblings(".podPolje[name='C']").position().left
    });});
    
    $('.D').each(function(){
    $(this).css({
       'top' : $(this).siblings(".podPolje[name='D']").position().top +( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $(this).siblings(".podPolje[name='D']").position().left + $(this).siblings(".podPolje[name='D']").outerWidth() - $(this).outerWidth(true)
    });
    });
$('.tekstPolje').each(function(){
            $(this).parent().height($(this).siblings(".C[name=C4]").position().top - $(this).siblings(".A[name=4]").position().top);
            
        });
}