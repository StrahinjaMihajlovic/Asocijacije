/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function posaljiPost(nazivPolja){
    console.log(nazivPolja.data.nazivPolja);
    $.post(window.location, {kliknuto : nazivPolja.data.nazivPolja}).done(function(data){
         $('.x_panel').parent().html(data);
         podesavanjeElemenata();
    });
}

function posaljiPostTekst(){
    var a = {polje : $(this).attr('id'), unos : $(this).val()};
    console.log(a);
     $.post(window.location, {polje : a["polje"], unos : a["unos"]}).fail(function(xhr, status, error){
         console.log(xhr); // note za sebe: ovako se prikazuje greska kad php nece da je prikaze
     }).done(function(data){
         $('.x_panel').parent().html(data);
         podesavanjeElemenata();
     });
}

podesavanjeElemenata();

function podesavanjeElemenata(){
//$('#Resenje').css({'top': $('#Resenje').parent().height()/2,'left': $('#Resenje').parent().width()/2.5});
$('#Resenje').on('focusout',posaljiPostTekst);
$('#A').on('focusout', posaljiPostTekst);
$('#B').on('focusout',posaljiPostTekst);
$('#C').on('focusout',posaljiPostTekst);
$('#D').on('focusout',posaljiPostTekst);
$('.A').each(function(){
$(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});
$('.B').each(function(){
$(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});
$('.container').on('click', '#novaIgra',function(){
    $.post(window.location, {novaIgra : true}).done(function(data){
    $('.x_panel').parent().html(data);
    });
});
/*$('#A').css({'top': ($('#Resenje').position().top - $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.8 * $('#A').width())});



$('#B').css({'top': ($('#Resenje').position().top -  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});


$('#C').css({'top': ($('#Resenje').position().top +  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left - 0.9 * $('#A').width())});


$('#D').css({'top': ($('#Resenje').position().top +  $('#Resenje').outerHeight(true)), 
    'left': ($('#Resenje').position().left + 0.9 * $('#A').width())});

    $(this).css({
       'top' : $('#A').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#A').position().left
    });
    console.log(this.id);

    $(this).css({
       'top' : $('#B').position().top -( $(this).data('value') * $(this).outerHeight(true)),
       'left' : $('#B').position().left + $('#B').outerWidth() - $(this).outerWidth(true)
    });
    */

}