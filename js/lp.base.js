/*
 * page base action
 */
LP.use(['jquery' , 'api'] , function( $ , api ){
    var API_ROOT = "lilyweibo";

    // live for pic-item hover event
    $(document.body)
        .delegate('.step2Styles li' , 'click' , function(){
            var $list = $('.step2Styles li');
            var index = $.inArray(this, $list);
            $list.removeClass('on');
            $(this).addClass('on');
            $('.step2BtnNext').removeClass('step2BtnNextDisabled');
            $('#val_type').val(index+1);
        })
        .delegate('#val_word' , 'keyup' , function(e){
            var word = $(this).val();
            var wordLenth = word.length;
            if(wordLenth > 15) {
                $('.step1Error').fadeIn();
            }
            if(wordLenth > 0 && wordLenth < 16) {
                $('.step1BtnNext').removeClass('step1BtnNextDisabled');
                $('.step1Error').fadeOut();
            }else{
                $('.step1BtnNext').addClass('step1BtnNextDisabled');
                return false;
            }
            if (e.which == 13 ) {
                e.preventDefault();
                LP.triggerAction('submit_word');
            }
        })





    // ================== page actions ====================
    LP.action('submit_word' , function(){
        if($(this).hasClass('step1BtnNextDisabled')) {
            return false;
        }
        $('.step1').fadeOut();
        $('.step2').delay(510).fadeIn();
    });

    LP.action('back_word' , function( ){
        $('.step2').fadeOut();
        $('.step1').delay(510).fadeIn();
    });

    LP.action('submit_style' , function( ){
        if($(this).hasClass('step2BtnNextDisabled')) {
            return false;
        }
        $('.step_loading').fadeIn();
        var data = {
            content: $('#val_word').val(),
            style: $('#val_type').val()
        }
        api.ajax('getimage', data, function(res){
            $('.step_loading').stop().fadeOut();
            $('.step2').fadeOut();
            $('.step3').delay(510).fadeIn();
            $('#step3PreviewImg').attr('src', API_ROOT + res.image);
            $('#val_image').val(res.image);
        }, function(){
            setTimeout(function(){
                $('.step_loading').stop().fadeOut();
            },200);
        });
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
            style: $('#val_type').val(),
            image: $('#val_image').val()
        }
        api.ajax('post', data, function(res){
            var myRank = res;
            myRank.image = API_ROOT + myRank.image;
            LP.compile( 'myrank-template' ,
                myRank,
                function( html ){
                    $('#myRank').append(html);
                    $('.step_loading').stop().fadeOut();
                });
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

    LP.action('weibo_login' , function(){
        var iframeData = {url:$(this).data('url')};
        LP.compile( 'login-iframe-template' ,
            iframeData,
            function( html ){
                $('body').append(html);
                $('#weiboLoginForm').fadeIn();
            } );
    });

    LP.action('close_login_iframe' , function(){
       $('#weiboLoginForm').fadeOut(function(){
           $(this).remove();
       });
    });

    var init = function(){
        //Get Rank List
        var dataList = {page:1, pagenum: 5};
        api.ajax('list', dataList, function(res){
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

        //Get User Status
        api.ajax('myrank', function(res){
            if(res.error == 1001) {
                $('.stepLogin').fadeIn();
                $('.stepLoginBtn').data('url',res.data);
            }
            else {
                $('.step1').fadeIn();
            }
        }, function(){
            $('#list_wrap').html('加载失败，请刷新页面再试一次。');
        });
    }

    init();

});