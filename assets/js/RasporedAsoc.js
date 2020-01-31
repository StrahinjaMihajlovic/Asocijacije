/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


podesavanjeElemenata();

function podesavanjeElemenata(){
$('#Resenje').css({'top': $('#Resenje').parent().height()/2,'left': $('#Resenje').parent().width()/2.5});

$('#A').css({'top': ($('#Resenje').position().top - $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.8 * $('#A').width())});



$('#B').css({'top': ($('#Resenje').position().top -  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});

$('.A').each(function(){
    $(this).css({
       'top' : $('#A').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#A').position().left
    });
    console.log(this.id);
   
});

$('.B').each(function(){
    $(this).css({
       'top' : $('#B').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#B').position().left + $('#B').outerWidth() - $(this).outerWidth(true)
    });

});

}