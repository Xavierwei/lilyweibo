<div class="page-right-header">
    <div class="page-title">Photos</div>
</div>
<div class="page-right-wrapper">
    <table id="photo_admin_table"  border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Preview</th>
            <th>Post Date</th>
            <th>User</th>
            <th>Vote</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $item): ?>
            <tr>
                <td><span><?php echo $item["photo_id"]?></span></td>
                <td><a class="photo_thumbnail" href=".<?php echo $item["path"]?>"><img src=".<?php echo $item["path"]?>" alt="" width="40" height="50"></a></td>
                <td><span><?php echo $item["datetime"]?></span></td>
                <td class="nickname" data-uid="<?php echo $item["user_id"]?>"><span><?php echo $item["nickname"]?></span></td>
                <td><span><?php echo $item["vote_count"]?></span></td>
                <td><a href="#" class="delete delete_photo" data="<?php echo $item["photo_id"]?>">Delete</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<p style="display:none" id="dialog">Are you sure to delete it ?</p>

<script type="text/x-handlebars-template" id="user-detail">
    <div class="user-detail-popup">
        <span class="arrow"></span>
        <div><label>Tel:</label>{{tel}}</div>
        <div><label>Email:</label>{{email}}</div>
        <div><label>From:</label>{{from}}</div>
        <div><label>Register:</label>{{datetime}}</div>
    </div>
</script>

<script type="text/javascript">
	(function ($) {
		$("#photo_admin_table").dataTable(
            {
                "fnFooterCallback": function() {
                    $('<div class="sep"></div>').insertBefore('#photo_admin_table');
                }
            }
        );

		$(".delete_photo").click(function (e) {
			var photo_id = $(this).attr("data");
			e.preventDefault();
			$("#dialog").dialog({
				buttons: [{
					text: "Confirm",
					click: function () {
						$.ajax({
							url: "./index.php?r=admin/delete&photo_id=" + photo_id,
							success: function () {
								window.location.reload();
							}
						});
						$( this ).dialog( "close" );
					}
				}, {
					text: "Cancel",
					click: function () {
						$(this).dialog("close");
					}
				}]
			});
		});

        $('body').on('mouseenter','.nickname',function(){
            var uid = $(this).data('uid');
            var top = $(this).offset().top+50;
            console.log(top);
            var left = $(this).offset().left+($(this).width() - 220)/2 + 3;
            $.ajax({
                type: "GET",
                url: "./index.php?r=admin/userdetail",
                data: {uid:uid},
                success: function(res) {
                    if(res.data.length > 0) {
                        var template = Handlebars.compile($('#user-detail').html());
                        var result = template(res.data[0]);
                        $('body').append(result);
                        $('.user-detail-popup').css({top:top,left:left});
                    }
                },
                dataType: 'json'
            });
        });

        $('body').on('mouseleave','.nickname',function(){
            $('.user-detail-popup').fadeOut(function(){
                $(this).remove();
            });
        });

        $('.photo_thumbnail').fancybox();

	})(jQuery);
</script>
