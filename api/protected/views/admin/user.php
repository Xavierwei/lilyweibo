<div class="page-right-header">
    <div class="page-title">Photos</div>
</div>
<div class="page-right-wrapper">
    <a class="export-excel" target="_blank" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?r=admin/Export">Export Excel</a>
    <div class="page-right-tools">From:
        <select id="filter-from">
            <option value="">All</option>
            <option value="weibo">Weibo</option>
            <option value="tencent">Tencent</option>
            <option value="renren">Renren</option>
            <option value="qq">QQ</option>
        </select>
    </div>
    <table id="user_admin_table" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>nickname</th>
            <th>from</th>
            <th>email</th>
            <th>tel</th>
            <th>Register Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $item): ?>
            <tr>
                <td><span><?php echo $item["user_id"]?></span></td>
                <td><span><?php echo $item["nickname"]?></span></td>
                <td><span><?php echo $item["from"]?></span></td>
                <td><span><?php echo $item["email"]?></span></td>
                <td><span><?php echo $item["tel"]?></span></td>
                <td><span><?php echo $item["datetime"]?></span></td>
                <td><a href="#" class="delete delete_user" data="<?php echo $item["user_id"]?>">Delete</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<p style="display:none" id="dialog">Are you sure to delete it ?</p>

<script type="text/javascript">
	(function ($) {
		var dt = $("#user_admin_table").dataTable(
            {
                "fnFooterCallback": function() {
                    $('<div class="sep"></div>').insertBefore('#user_admin_table');
                }
            }
        );
        $('#filter-from').change(function(){
            dt.fnFilter($(this).val());
        });

		$(".delete_user").click(function (e) {
			var user_id = $(this).attr("data");
			e.preventDefault();
			$("#dialog").dialog({
				buttons: [{
					text: "Confirm",
					click: function () {
						$.ajax({
							url: "./index.php?r=admin/delete&user_id=" + user_id,
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
	})(jQuery);	
</script>