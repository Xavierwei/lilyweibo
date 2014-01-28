<body>
  <div class="loginpage">
    <div class="loginlogo"></div>
    <form role="form" action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/admin/login" method="post">
      <div class="form-group">
        <label for="username">Email address</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
      </div>
      <div class="form-group">
        <label for="password">Email address</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
      </div>

      <div><input class="btn btn-default btn-lg" type="submit" value="submit" /></div>
    </form>


  </div>
</body>