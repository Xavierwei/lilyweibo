/*
 * page base action
 */
LP.use(['jquery' , 'api'] , function( $ , api ){


    // live for pic-item hover event
    $(document.body)
        .delegate('.step2Styles li' , 'click' , function(){
            $('.step2Styles li').removeClass('on');
            $(this).addClass('on');
            $('.step2BtnNext').fadeIn();
        })





    // ================== page actions ====================
    LP.action('submit_word' , function( ){
        $('.step1').fadeOut();
        $('.step2').delay(510).fadeIn();
    });

    LP.action('back_word' , function( ){
        $('.step2').fadeOut();
        $('.step1').delay(510).fadeIn();
    });

    LP.action('submit_style' , function( ){
        $('.step2').fadeOut();
        $('.step3').delay(510).fadeIn();
    });

    LP.action('back_style' , function( ){
        $('.step3').fadeOut();
        $('.step2').delay(510).fadeIn();
    });

    LP.action('submit_post' , function( ){
        $('.step3').fadeOut();
        $('.step4').delay(510).fadeIn();
    });



});