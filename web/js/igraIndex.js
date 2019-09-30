/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function posaljiPost(nazivPolja){
    console.log(nazivPolja.data.nazivPolja);
    $.post(window.location, {kliknuto : nazivPolja.data.nazivPolja});
}

function posaljiPostTekst(){
    var a = {polje : $(this).attr('id'), unos : $(this).val()};
    console.log(a);
     $.post(window.location, {polje : a["polje"], unos : a["unos"]}).fail(function(xhr, status, error){
         console.log(xhr); // note za sebe: ovako se prikazuje greska kad php nece da je prikaze
     }).done(function(xhr){
         console.log(xhr);
     });
}


$('#Resenje').css({'top': $('#Resenje').parent().height()/2,'left': $('#Resenje').parent().width()/2.5});

$('#A').css({'top': ($('#Resenje').position().top - $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.8 * $('#A').width())});

$('#A').on('focusout', posaljiPostTekst);

$('#B').css({'top': ($('#Resenje').position().top -  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});
$('#B').on('focusout', posaljiPostTekst);
$('.A').each(function(){
    $(this).css({
       'top' : $('#A').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#A').position().left
    });
    console.log(this.id);
    $(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});

$('.B').each(function(){
    $(this).css({
       'top' : $('#B').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#B').position().left + $('#B').outerWidth() - $(this).outerWidth(true)
    });
    $(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});