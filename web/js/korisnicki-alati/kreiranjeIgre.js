/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('div.Resenje').css({'top': $('#Resenje').parent().parent().height()/2,'left': $('#Resenje').parent().parent().width()/2.5});


$('div#A').css({'top': ($('#Resenje').parent().position().top - $('#Resenje').parent().outerHeight(true)), 
    'left': ($('#Resenje').parent().position().left - 0.8 * $('#A').width())});


$('div#B').css({'top': ($('#Resenje').parent().position().top -  $('#Resenje').parent().outerHeight(true)), 
    'left': ($('#Resenje').parent().position().left + 0.9 * $('#A').width())});

$('div#C').css({'top': ($('#Resenje').parent().position().top +  $('#Resenje').parent().outerHeight(true)), 
    'left': ($('#Resenje').parent().position().left - 0.9 * $('#A').width())});

$('div#D').css({'top': ($('#Resenje').parent().position().top +  $('#Resenje').parent().outerHeight(true)), 
    'left': ($('#Resenje').parent().position().left + 0.9 * $('#A').width())});

$('div.A').each(function(){
    $(this).css({
       'top' : $('#A').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#A').position().left
    });
    
    //$(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});

$('.B').each(function(){
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