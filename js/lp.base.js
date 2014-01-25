/*
 * page base action
 */
LP.use(['jquery' , 'api'] , function( $ , api ){


    // live for pic-item hover event
    $(document.body)
        .delegate('.step2Styles li' , 'click' , function(){
            var $list = $('.step2Styles li');
            var index = $.inArray(this, $list);
            $list.removeClass('on');
            $(this).addClass('on');
            $('.step2BtnNext').fadeIn();
            $('#val_type').val(index);
        })
        .delegate('#val_word' , 'keyup' , function(e){
            var word = $(this).val();
            var wordLenth = word.length;
            if(wordLenth > 15) {
                $('.step1Error').fadeIn();
            }
            if(wordLenth > 0 && wordLenth < 16) {
                $('.step1BtnNext').fadeIn();
                $('.step1Error').fadeOut();
            }else{
                $('.step1BtnNext').fadeOut(100);
                return false;
            }
            if (e.which == 13 ) {
                e.preventDefault();
                LP.triggerAction('submit_word');
            }
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
        $('.step_loading').fadeIn();
        //TODO
        var data = {
            content: $('#val_word').val(),
            type: $('#val_type').val()
        }
        api.ajax('getimage', data, function(res){
            console.log(res);
        }, function(){
            setTimeout(function(){
                $('.step_loading').stop().fadeOut();
            },200);
        });
        $('#val_image').val('image.jpg');
    });

    LP.action('back_style' , function( ){
        $('.step3').fadeOut();
        $('.step2').delay(510).fadeIn();
    });

    LP.action('submit_post' , function( ){
        $('.step3').fadeOut();
        $('.step4').delay(510).fadeIn();
        $('.step_loading').fadeIn();
        var data = {
            content: $('#val_word').val(),
            type: $('#val_type').val(),
            image: $('#val_image').val()
        }
        api.ajax('post', data, function(res){
            console.log(res);
        }, function(){
            setTimeout(function(){
                $('.step_loading').stop().fadeOut();
            },200);
        });
    });

    LP.action('submit_dmx' , function( ){
        api.ajax('dmx', function(res){
            console.log(res);
        });
    });

    var init = function(){
        var data = {page:1, pagenum: 5};
        api.ajax('list', data, function(res){
            var items = res;
            $.each( items , function( index , item ){
                LP.compile( 'list-item-template' ,
                    item,
                    function( html ){
                        // render html
                        $('#list_wrap').append(html);
                    } );
            });
        }, function(){
            $('#list_wrap').html('加载失败，请刷新页面再试一次。');
        });
    }

    init();

});