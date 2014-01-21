<form action="<?php echo Yii::app()->createUrl("photo/vote")?>" enctype="multipart/form-data" name="vote" id="vote" method="post">
    <input type="hidden" name="photo_id" value="<?php echo $photo->photo_id?>"/>
    <input type="hidden" name="user_id" value="<?php echo $user['user_id']?>" />
    <img src="<?php echo Yii::app()->request->baseUrl. $photo->path?>" alt="" />
    <button>Vote</button>
    <div id="res"></div>
</form>

<script type="text/javascript">
    (function ($) {
        $(function () {
//            $("#vote").ajaxForm({
//                success: function (res) {
//                    alert(res.data.toString());
//                }
//            });
        });
    })(jQuery);
</script>