
TODO LIST:

1）UserController.php
line 106 $access_token 未传入

2）第一次登陆授权的时候那里，返回的$newUser有问题。

3）MyRank和User/Login合并下：逻辑为：如果已登陆返回myrank信息(如果发布过微博显示，如果没发过微博给一个errorCode)，如果没登陆返回微博授权登陆连接(weiboUrl)

4）授权成功后跳转到地址：      $this->redirect('../../../logined.html');  

5）文字坐标          imagettftext($im, $fontSize,0, 122, 79, $fontColor ,$font, $text);

6）scarf/post 保存到数据库之后需要发送到微博上，调用微博API statuses/upload方法，发布文字和图片。