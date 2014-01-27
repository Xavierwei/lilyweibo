/*
 * page base action
 */
LP.use(['jquery' , 'api'] , function( $ , api ){
    var API_ROOT = "api";
    var submitting = false;

    var loadRank = function(dataList) {
        api.ajax('list', dataList, function(res){
            if(res.error && res.error.code == 1001) {
                $('.list_more').fadeOut();
                return false;
            }
            var items = res.data;
            $.each( items , function( index , item ){
                LP.compile( 'list-item-template' ,
                    item,
                    function( html ){
                        // render html
                        $('#list_wrap').append(html);
                    } );
            });
        }, function(){
            //$('#list_wrap').html('加载失败，请刷新页面再试一次。');
        });
    }


    // ================== page actions ====================
    LP.action('read_more',function() {
        var dataList = $('#list_wrap').data('page');
        dataList.page ++;
        $('#list_wrap').data('page',dataList);
        loadRank(dataList);
    });


    var init = function(){
        //Get Rank List
        var dataList = {page:1, pagenum: 5};
        $('#list_wrap').data('page',dataList);
        loadRank(dataList);
    }

    init();

});