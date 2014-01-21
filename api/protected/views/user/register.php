<form action="index.php?r=user/register" method="POST" id="registerForm" enctype="multipart/form-data">
    <div><label for="">nick name</label>
        <input type="text" name="nickname" value="<?php echo $user["nickname"]?>"/>
    </div>
    <div><label for="">Password</label>
        <input type="text" name="password"/>
    </div>
    <div><label for="">Email</label>
        <input type="text" name="email"/>
    </div> 
    <div><label for="">Tel</label>
        <input type="text" name="tel"/>
    </div>
    <div><label for="">Avadar</label>
        <input type="text" name="avadar" value="<?php echo $user["avadar"]?>"/>
    </div>
    <div><input type="submit" name="submit" /></div>
</form>

