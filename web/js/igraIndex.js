/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function posaljiPost(nazivPolja){
    console.log(nazivPolja.data.nazivPolja);
    $.post(window.location, {kliknuto : nazivPolja.data.nazivPolja}).done(function(data){
        var element = $('.x_panel').parent();
        var vrednost = data;
        $('.x_panel').parent().html(data);
         podesavanjeElemenataAsset();
         podesavanjeElemenata();
    });
}

function posaljiPostTekst(){
    var a = {polje : $(this).parent().attr('id'), unos : $(this).val()};
    console.log(a);
     $.post(window.location, {polje : a["polje"], unos : a["unos"]}).fail(function(xhr, status, error){
         console.log(xhr); // note za sebe: ovako se prikazuje greska kad php nece da je prikaze
     }).done(function(data){
         $('.x_panel').parent().html(data);
         podesavanjeElemenataAsset();
         podesavanjeElemenata();
     });
}

podesavanjeElemenata();

function podesavanjeElemenata(){
//$('#Resenje').css({'top': $('#Resenje').parent().height()/2,'left': $('#Resenje').parent().width()/2.5});
$('#Resenje>input').on('focusout',posaljiPostTekst);
$('#A>input').on('focusout', posaljiPostTekst);
$('#B>input').on('focusout',posaljiPostTekst);
$('#C>input').on('focusout',posaljiPostTekst);
$('#D>input').on('focusout',posaljiPostTekst);
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
$('.C').each(function(){
    $(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});
$('.D').each(function(){
    $(this).on('click',{nazivPolja : $(this).attr('id')}, posaljiPost);
});


}