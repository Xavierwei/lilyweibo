<?php
$fontFile = 'simfang.ttf'; #字体文件名，请先拷贝一个字体到font目录下，然后修改此配置

function str_div($str, $width = 10){
	$strArr = array();
	$len = strlen($str);
	$count = 0;
	$flag = 0;
	while($flag < $len){
		if(ord($str[$flag]) > 128){
			$count += 1;
			$flag += 3;
		}
		else{
			$count += 0.5;
			$flag += 1 ;
		}
		if($count >= $width){
			$strArr[] = substr($str, 0, $flag);
			$str = substr($str, $flag);
			$len -= $flag;
			$count = 0;
			$flag = 0;
		}
	}
	$strArr[] = $str;
	return $strArr;
}

function str2rgb($str)
{
	$color = array('red'=>0, 'green'=>0, 'blue'=>0);
	$str = str_replace('#', '', $str);
	$len = strlen($str);
	if($len==6){
		$arr=str_split($str,2);
		$color['red'] = (int)base_convert($arr[0], 16, 10);
		$color['green'] = (int)base_convert($arr[1], 16, 10);
		$color['blue'] = (int)base_convert($arr[2], 16, 10);
		return $color;
	}
	if($len==3){
		$arr=str_split($str,1);
		$color['red'] = (int)base_convert($arr[0].$arr[0], 16, 10);
		$color['green'] = (int)base_convert($arr[1].$arr[1], 16, 10);
		$color['blue'] = (int)base_convert($arr[2].$arr[2], 16, 10);
		return $color;
	}
	return $color;
}


$oldtext = $text = $_POST['word'];
$haveBrLinker = trim($_POST['haveBrLinker']);
$userStyle = explode('|', $_POST['userStyle']);


if($text){
	$text = substr($text, 0, 30000); #截取前一万个字符
	$paddingTop = 20;
	$paddingLeft = 15;
	$paddingBottom = 20;
	$copyrightHeight = 36;
	
	$canvasWidth = 440;
	$canvasHeight = $paddingTop + $paddingBottom + $copyrightHeight;
	
	$fontSize = 12;
	$lineHeight = intval($fontSize * 1.8);
	
	$textArr = array();
	$tempArr = explode("\n", trim($text));
	$j = 0;
	foreach($tempArr as $v){
		$arr = str_div($v, 25);
		$textArr[] = array_shift($arr);
		foreach($arr as $v){
			$textArr[] = $haveBrLinker . $v;
			$j ++;
			if($j > 100){ break; }
		}
		$j ++;
		if($j > 100){ break; }
	}
	
	$textLen = count($textArr);
	
	$canvasHeight = $lineHeight * $textLen + $canvasHeight;
	$im = imagecreatetruecolor($canvasWidth, $canvasHeight); #定义画布
	$colorArray = str2rgb($userStyle[1]);
	imagefill($im, 0, 0, imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']));
	
	$colorArray = str2rgb('666666');
	$colorLine = imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']);
	$padding = 3;
	$x1 = $y1 = $x4 = $y2 = $padding;
	$x2 = $x3 = $canvasWidth - $padding - 1;
	$y3 = $y4 = $canvasHeight - $padding - 1;
	imageline($im, $x1, $y1, $x2, $y2, $colorLine);
	imageline($im, $x2, $y2, $x3, $y3, $colorLine);
	imageline($im, $x3, $y3, $x4, $y4, $colorLine);
	imageline($im, $x4, $y4, $x1, $y1, $colorLine);
	
	//字体路径
	$fontStyle = './font/' . $fontFile;
	if(!is_file($fontStyle)){
		exit('The font file does not exist!');
	}
	
	//写入四个随即数字
	$colorArray = str2rgb($userStyle[0]);
	$fontColor = imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']);
	
	foreach($textArr as $k=>$text){
		$offset = $paddingTop + $lineHeight * ($k + 1) - intval(($lineHeight-$fontSize) / 2);
		imagettftext($im, $fontSize, 0, $paddingLeft, $offset, $fontColor, $fontStyle, $text);
	}
	
	$fontColor = imagecolorallocate($im, 0, 0, 0);
	$offset += 18;
	$text = '-----------------------------------------------------------------------';
	imagettftext($im, 10, 0, $paddingLeft, $offset, $fontColor, $fontStyle, $text);
	
	$offset += 18;
	$fontColor = imagecolorallocate($im, 255, 0, 0);
	$text = '本图由红茶巴士[http://tw.hcbus.com]微博转图片免费生成';
	imagettftext($im, 10, 0, $paddingLeft + 20, $offset, $fontColor, $fontStyle, $text);
	
	
	$imghost = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'] , 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);
	$imgpath = 'img/' . date('Ymd/');
	if(!is_dir($imgpath)){ mkdir($imgpath); }
	$imgfile =  $imgpath . time() . rand(10000, 99999) . '.gif';
	$imgurl = $imghost . $imgfile;
	imagegif($im, $imgfile);
	imagedestroy($im);
	$url_title = urlencode(mb_substr($oldtext,0, 130, 'UTF-8'));
	$url_pic = urlencode($imgurl);
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>转换结果-红茶巴士【tw.hcbus.com】</title>
<style type="text/css">
body,html{ margin:0px; padding:0px; background:url(http://jscss.sinaapp.com/hctw/bg.gif); font-size:14px;}
#areas{ width:800px; margin:0px auto; text-align:center;}
p{ font-weight:bold;}
h1{ text-align:center; color:#F00;}
a{ color:#06F; text-decoration:none;}
</style>
</head>

<body>
<div id="areas">
	<h1>红茶巴士文字生成图片系统</h1>
	<p>
		<a href="javascript:;" onclick="window.history.go(-1)">后退重转</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="./">回到首页</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:;" onclick="window.close()">关闭</a>
	</p>
	<div>	
		<a href="http://service.weibo.com/share/share.php?appkey=2952401270&title=<?php echo $url_title; ?>&pic=<?php echo $url_pic; ?>" target="_blank"><img src="http://jscss.sinaapp.com/hctw/sina.png" width="160" height="32" border="0" alt="发布到新浪微博" /></a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="http://v.t.qq.com/share/share.php?appkey=801004392&title=<?php echo $url_title; ?>&pic=<?php echo $url_pic; ?>" target="_blank"><img src="http://jscss.sinaapp.com/hctw/qq.png" width="160" height="32" border="0" alt="发布到腾讯微博" /></a>
	</div>
	<img src="<?php echo $imgfile; ?>" />
	<br /><br />
	图片地址:<a href="<?php echo $imgurl; ?>" target="_blank"><?php echo $imgurl; ?></a><br /><br />
	<input type="button" onclick="if(window.clipboardData){window.clipboardData.setData('text', '<?php echo $imgurl; ?>');alert('复制成功');}else{alert('复制失败,请手工复制')}" value="复制图片地址到剪贴板" />
</div>
</body>
</html>
