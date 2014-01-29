/*
 * page base action
 */
LP.use(['jquery' , 'api', 'easing'] , function( $ , api ){
    var API_ROOT = "api";
    var submitting = false;

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
        .delegate('.keyword' , 'keyup' , function(e){
            if (e.which == 13 || $(this).val().length == 0) {
                e.preventDefault();
                LP.triggerAction('searchFriend');
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
            $('.step_loading').stop().fadeOut();
            res.data.image = API_ROOT + res.data.image;
            LP.compile( 'preview-template' ,
                res.data,
                function( html ){
                    $('#stepWaitApprove').append(html);
                    $('.stepWaitApprove').fadeIn();
                });
        }, function(){
            setTimeout(function(){
                $('.step_loading').stop().fadeOut();
            },200);
        });
    });

    LP.action('submit_dmx' , function( ){
        if(submitting) {
            return false;
        }
        submitting = true;
        api.ajax('dmx', function(res){
            submitting = false;
            if(res.error) {
                data = {};
            }
            else {
                if(res.data.offset > 0) {
                    res.data.direction = 'up';
                    res.data.direction_text = '上升';
                } else {
                    res.data.direction = 'down';
                    res.data.direction_text = '下降';
                }
                res.data.offset = Math.abs(res.data.offset);
                data = res.data;
            }

            LP.compile( 'popup-dmx-template' ,
                data,
                function( html ){
                    $('body').append(html);
                    $('#popup-dmx').fadeIn().dequeue().animate({top:'50%'}, 1000, 'easeOutElastic');
                });

            var myRank = $('#myRank').data('user');
            myRank.rank = res.data.newRank;
            $('#myRank').data('user', myRank);
            myRank.position = parseInt(((myRank.rank-1) / myRank.total)*100);
            if(myRank.total == myRank.rank) {
                myRank.position = 100
            }
            LP.compile( 'myrank-template' ,
                myRank,
                function( html ){
                    $('#myRank').html(html);
                });

        });
    });

    LP.action('submit_friends' , function( ){
        if(submitting) {
            return false;
        }
        submitting = true;
        api.ajax('invitedfriends', function(res){
            var invitedFriends = [];
            $.each(res.data, function(index, item){
                invitedFriends.push(item.friend_sns_uid);
            });
            $('#friendsList').data('invited', invitedFriends);

            submitting = false;
            var invitedNum = res.data.length;
            LP.compile( 'popup-friends-template', {invitedNum:invitedNum, leftNum: 20-invitedNum},
                function( html ){
                    $('body').append(html);
                    $('#popupFriends').fadeIn().dequeue().animate({top:'50%'}, 1000, 'easeOutElastic');
                    // Load Friends List
                    api.ajax('friends', function(res){
                        var pagenum = Math.ceil(res.data.users.length / 8);
                        for(var i = 1; i <= pagenum; i ++)
                        {
                            LP.compile( 'paginate-template' ,
                                {pagenum: i},
                                function( html ){
                                    $('#friendsPaginate').append(html);
                                } );
                        }
                        $('#friendsPaginate li').eq(0).addClass('on');
                        $('#friendsList').fadeIn().data('friends', res.data.users);
                        var allFriends = res.data.users.slice();
                        var friends = allFriends.splice(0,8);
                        $.each(friends, function(index, item){
                            if($.inArray(item.idstr, invitedFriends) != -1){
                                item.invited = true;
                            }
                            LP.compile( 'friend-item-template' ,
                                item,
                                function( html ){
                                    $('#friendsList').append(html);
                                } );
                        })
                    });
                });
        });

    });

    LP.action('change_friend_page', function(){
        var paginateItems = $('#friendsPaginate li');
        var index = $.inArray(this, paginateItems);
        var allFriends = $('#friendsList').data('friends').slice();
        var friends = allFriends.splice(index*8, 8);
        paginateItems.removeClass('on');
        $(this).addClass('on');
        $('#friendsList').fadeOut(function(){
            $(this).empty();
            $.each(friends, function(index, item){
                LP.compile( 'friend-item-template' ,
                    item,
                    function( html ){
                        $('#friendsList').append(html);
                    } );
            })
            $(this).fadeIn();
        });
    });

    LP.action('searchFriend', function(){
        var allFriends = $('#friendsList').data('friends');
        var keyword = $('#popupFriends .keyword').val();
        if(!keyword) {
            $('#friendsPaginate').fadeIn();
            $('#friendsPaginate li').eq(0).click();
            return;
        }
        $('#friendsPaginate').fadeOut();
        $('#friendsList').fadeOut(function(){
            $(this).empty();
            $.each(allFriends, function(index, item){
                if(item.screen_name.indexOf(keyword) != -1) {
                    LP.compile( 'friend-item-template' ,
                        item,
                        function( html ){
                            $('#friendsList').append(html);
                        } );
                }

            })
            $(this).fadeIn();
        });
    });

    LP.action('add_friend', function(){
        var name = $(this).data('name');
        var snsuid = $(this).data('snsuid');
        var textarea = $('#inviteText');
        var newTxt;
        var invitedNum = parseInt($('#invitedNum').html());
        if($(this).hasClass('selected')) {
            var selected = $('#friendsList').data('selected');
            var index = $.inArray(snsuid,selected);
            selected.splice(index, 1);
            $('#friendsList').data('selected',selected);
            newTxt = textarea.html().replace(' @'+name, '');
            textarea.html(newTxt);
            $(this).removeClass('selected');
            invitedNum --;
        }
        else {
            if(invitedNum >= 20) {
                return false;
            }
            newTxt = textarea.html() + " @"+name;
            if(newTxt.length < 140) {
                $(this).addClass('selected');
                textarea.html(newTxt);
                var selected = $('#friendsList').data('selected');
                if(!selected) {
                    selected = [];
                }
                selected.push(snsuid);
                $('#friendsList').data('selected',selected);
                invitedNum ++;
            }
        }
        $('#invitedNum').html(invitedNum);
        $('#leftNum').html(20-invitedNum);
    });

    LP.action('invite_friends', function(){
        var friends = $('#friendsList').data('selected').toString();
        var sharetext = $('#inviteText').html();
        var data = {friends: friends, sharetext: sharetext};
        api.ajax('invite', data, function(){
            LP.compile( 'popup-invited-template' ,
                {},
                function( html ){
                    $('body').append(html);
                    $('#popup-invited').fadeIn().dequeue().animate({top:'50%'}, 1000, 'easeOutElastic');
                });
        }, function(){
        });
        LP.triggerAction('close_popup');


    });

    LP.action('weibo_login' , function(){
        if(!$('.stepLoginTerm span').hasClass('checked')) {
            $('.stepLoginError').fadeIn();
            return false;
        }
        var iframeData = {url:$(this).data('url')};
        LP.compile( 'login-iframe-template' ,
            iframeData,
            function( html ){
                $('body').append(html);
                $('#weiboLoginForm').fadeIn().dequeue().animate({top:'50%'}, 1000, 'easeOutElastic');
            } );
    });

    LP.action('agree_term', function(){
        var checkbox = $(this).find('span');
        if(checkbox.hasClass('checked')) {
            checkbox.removeClass('checked');
        }
        else {
            checkbox.addClass('checked');
            $('.stepLoginError').fadeOut();
        }
    });

    LP.action('close_popup' , function(){
        $('.popup').fadeOut(1500,function(){
            $(this).remove();
        }).dequeue().animate({top:'-50%'}, 800, 'easeInOutElastic');
        $('.overlay').fadeOut(1500,function(){
            $(this).remove();
        });
    });

    var init = function(){
        //Get Rank List
        var dataList = {page:1, pagenum: 5};
        api.ajax('list', dataList, function(res){
            var items = res.data;
            $('#totalCountText').html(res.total);
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
                $('.stepLoginBtn').data('url',res.url);
            }
            else {
                if(res.data.status ==  undefined) {
                    $('.step1').fadeIn();
                }
                switch(res.data.status) {
                    case 0:
                        res.data.image = API_ROOT + res.data.image;
                        LP.compile( 'preview-template' ,
                            res.data,
                            function( html ){
                                $('#stepWaitApprove').append(html);
                                $('.stepWaitApprove').fadeIn();
                            });
                        break;
                    case 1:
                        if(res.data.rank) {
                            $('#myRank').data('user',res.data);
                            $('.step4').fadeIn();
                            $('.steps').addClass('steps_high');
                            res.data.image = API_ROOT + res.data.image;
                            res.data.position = parseInt(((res.data.rank-1) / res.data.total)*100);
                            if(res.data.total == res.data.rank) {
                                res.data.position = 100
                            }
                            LP.compile( 'myrank-template' ,
                                res.data,
                                function( html ){
                                    $('#myRank').append(html);
                                });
                        }
                        break;
                    case 2:
                        break;
                    case 3:
                        res.data.image = API_ROOT + res.data.image;
                        LP.compile( 'preview-template' ,
                            res.data,
                            function( html ){
                                $('#stepProduced').append(html);
                                $('.stepProduced').fadeIn();
                            });
                        break;
                    default:
                        break;
                }
            }
        }, function(){

        });
    }

    init();

});