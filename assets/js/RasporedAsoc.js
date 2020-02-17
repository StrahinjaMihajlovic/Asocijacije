/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function podesavanjeElemenataAsset(){
    $('.wrapAsocijacije').each(function(){
                       $(this).height($(this).children('.tekstPolje').height() * 13);
                       $(this).css({"min-width": ($(this).children('.tekstPolje').width() * 3)} + 'px');
    });
    $('.tekstPolje').each(function(){
        
        $(this).position({
            my: "center center",
            at: "center center",
            of: $(this).parent()
            
        });
    });
    
    $('.podPolje').each(function(){
        switch($(this).attr('name')){
            case 'A':
                
                $(this).position({
                    my: "right bottom",
                    at: "left top",
                    of: $(this).siblings(".tekstPolje").children('input'),
                    collision: 'fit',
                            within: $(this).parent()
                });
                break;
            case 'B':
                $(this).position({
                    my: "left bottom",
                    at: "right top",
                    of: $(this).siblings(".tekstPolje").children('input'),
                    collision: 'fit',
                            within: $(this).parent
                });
                break;
            case 'C':
                $(this).position({
                    my: "right top",
                    at: "left bottom",
                    of: $(this).siblings(".tekstPolje").children('input'),
                    collision: 'fit',
                            within: $(this).parent
                });
                break;
            case 'D':
                $(this).position({
                    my: "left top",
                    at: "right bottom",
                    of: $(this).siblings(".tekstPolje").children('input'),
                    collision: 'fit',
                            within: $(this).parent
                });
                break;
        }
    });
    
    $('.A').each(function(){
        var vrednost = parseInt($(this).data('value'));
        if( vrednost > 1){
            $(this).position({
                            my: "center bottom",
                            at: "center top",
                            of: $(this).prev(),
                            collision: 'fit',
                            within: $(this).parent
            });
        }else{
            $(this).position({
                            my: "right bottom",
                            at: "center top",
                            of: $(this).siblings('.podPolje[name="A"]'),
                            collision: 'fit',
                            within: $(this).parent
            });
        }
    });
    
    $('.B').each(function(){
     var vrednost = parseInt($(this).data('value'));
        if( vrednost > 1){
            $(this).position({
                            my: "center bottom",
                            at: "center top",
                            of: $(this).prev(),
                            collision: 'fit',
                            within: $(this).parent
            });
        }else{
            $(this).position({
                            my: "left bottom",
                            at: "center top",
                            of: $(this).siblings('.podPolje[name="B"]'),
                            collision: 'fit',
                            within: $(this).parent
            });
        }
    });
    
    $('.C').each(function(){
     var vrednost = parseInt($(this).data('value'));
        if( vrednost > 1){
            $(this).position({
                            my: "center top",
                            at: "center bottom",
                            of: $(this).prev(),
                            collision: 'fit',
                            within: $(this).parent
            });
        }else{
            $(this).position({
                            my: "right top",
                            at: "center bottom",
                            of: $(this).siblings('.podPolje[name="C"]'),
                            collision: 'fit',
                            within: $(this).parent
            });
        }
    });
    
    $('.D').each(function(){
     var vrednost = parseInt($(this).data('value'));
        if( vrednost > 1){
            $(this).position({
                            my: "center top",
                            at: "center bottom",
                            of: $(this).prev(),
                            collision: 'fit',
                            within: $(this).parent
            });
        }else{
            $(this).position({
                            my: "left top",
                            at: "center bottom",
                            of: $(this).siblings('.podPolje[name="D"]'),
                            collision: 'fit',
                            within: $(this).parent
            });
        }
    });

                   
}

$(function(){
podesavanjeElemenataAsset();

$(window).resize( function(){
    podesavanjeElemenataAsset()
});


});