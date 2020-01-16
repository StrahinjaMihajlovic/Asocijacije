/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$('.pregledaj').each(function(){$(this).on('click', function(event){
    event.preventDefault();
    
    console.log(this.href);
    $.post(this.href).done(function(data){
        $('.modal-body').html(data);
        console.log(data);
    }).fail(function(data,dada,xhr){
        console.log(xhr);
    });
})})