
<body ng-controller="MainCtrl">

<div class="header">
  <div class="logo"></div>
  <div class="logout"><a ng-click="logout()" href="javascript:void(0);"><span class="glyphicon glyphicon-log-out"></span>Logout</a></div>
  <ul top-tab class="nav nav-tabs">
    <li><a href="#/scarf/unapproved">Moderation</a></li>
    <li><a href="#/production/producing">Production</a></li>
  </ul>
</div>

<div class="page">
  <ul ng-show="showSubNav" sub-nav class="sub-nav nav nav-pills">
    <li><a href="#/scarf/rank">Top <span>({{counts.approved}})</span></a></li>
    <li><a href="#/scarf/produced">Produced <span>({{counts.produced}})</span></a></li>
    <li><a href="#/scarf/unapproved">Unapproved <span>({{counts.unapproved}})</span></a></li>
    <li><a href="#/scarf/all">All <span>({{counts.all}})</span></a></li>
  </ul>

  <div ng-click="refreshPage()" class="refresh glyphicon glyphicon-refresh"></div>
  <div class="clear"></div>
  <div ng-view></div>
</div>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/lib/angular/angular.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/lib/angular/angular-route.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/lib/angular/ui-bootstrap-0.9.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/lib/angular/ui-bootstrap-tpls-0.9.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/app.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/services.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/services/scarf.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/lib.controllers.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/controllers.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/controllers/scarf.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/filters.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/admin_asset/js/directives.js"></script>
</body>