<form action="index.php?r=photo/uploadimage" enctype="multipart/form-data" method="post" id="uploadimage"
      xmlns="http://www.w3.org/1999/html">
    <?php if ($tmpImage):?>
        <img src="<?php echo Yii::app()->request->baseUrl?><?php echo $tmpImage?>" />
        <p>
            <?php echo CHtml::link("分享微薄", "#")?>
            <?php echo CHtml::link("分享人人", "#")?>
            <?php echo CHtml::link("分享腾讯微薄", "#")?>
        </p>
    <?php else: ?>
        <div><input type="file" name="image" /></div>
        <div><textarea name="image_base64"></textarea>
        <div><input type="text" name="width" value="100" /></div>
        <div><input type="text" name="height" value='100'/></div>
        <div><input type="text" name="x" value='50'/></div>
        <div><input type="text" name="y" value='50'/></div>
            <div><input type="text" name="cid" value='1'/></div>
        <div><input type="text" name="rotate" value='10'/></div>
        <div><input type="submit" value="Submit"></div>
    <?php endif;?>
</form>