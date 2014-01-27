
<div class="login-box">
    <div class="login-logo"><img src="images/logo2.png" /></div>
    <?php $form = $this->beginWidget("CActiveForm")?>
    <?php echo $form->errorSummary($model)?>
    <div class="username_input row">
        <?php echo $form->textField($model, "username",array('placeholder'=>'Username'))?>
    </div>
    <div class="password_input row">
        <?php echo $form->passwordField($model, "password",array('placeholder'=>'Password'))?>
    </div>
    <div class="submit row">
        <?php echo CHtml::submitButton("Login")?>
    </div>
    <?php $this->endWidget()?>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/login.css" />