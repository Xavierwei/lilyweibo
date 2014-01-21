<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
    "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" version="XHTML+RDFa 1.0" dir="ltr">
<head profile="http://www.w3.org/1999/xhtml/vocab">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="http://localhost:8888/gar_nier/data/misc/favicon.ico" type="image/vnd.microsoft.icon" />
    <meta name="Generator" content="Drupal 7 (http://drupal.org)" />
    <title></title>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" />
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/handlebars-v1.1.2.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/main.js"></script>
</head>
<body class="<?php print str_replace('/','-',$_GET['r']); ?>">
<div class="wrapper">
    <div class="page-left">
        <div class="logo"><img src="images/logo.png" /></div>
        <div class="menu-list">
            <div class="menu-title">Testimonial</div>
            <ul>
                <li><a href="../../data/node/add/comments">Add Comment</a></li>
                <li><a href="../../data/admin/comments">Edit Comment</a></li>
            </ul>
        </div>
        <div class="menu-list">
            <div class="menu-title">Ps Girl Cover</div>
            <ul>
                <li class="admin-photo"><a href="index.php?r=admin">Photos</a></li>
                <li class="admin-user"><a href="index.php?r=admin/user">Users</a></li>
            </ul>
        </div>
    </div>
    <a class="logout" href="index.php?r=admin/logout">LOGOUT</a>
    <div class="page-right">
        <?php echo $content; ?>
    </div>
</div>
</body>
</html>
