<?php

if (isset($msg)) $text->msg = $msg; // 需要显示的文字
if (isset($font)) $text->font = $font; // 字体
if (isset($size)) $text->size = $size; // 文字大小
if (isset($rot)) $text->rot = $rot; // 旋转角度
if (isset($pad)) $text->pad = $pad; // padding
if (isset($red)) $text->red = $red; // 文字颜色
if (isset($grn)) $text->grn = $grn; // ..
if (isset($blu)) $text->blu = $blu; // ..
if (isset($bg_red)) $text->bg_red = $bg_red; // 背景颜色.
if (isset($bg_grn)) $text->bg_grn = $bg_grn; // ..
if (isset($bg_blu)) $text->bg_blu = $bg_blu; // ..
if (isset($tr)) $text->transparent = $tr; // 透明度 (boolean).
$text->msg = "中华人民\n共和国";
$text->draw();

class textImage {
	var $font = 'fonts/msyh.ttf'; //默认字体. 相对于脚本存放目录的相对路径.
	var $msg = "undefined"; // 默认文字.
	var $size = 24;
	var $rot = 0; // 旋转角度.
	var $pad = 0; // 填充.
	var $transparent = 1; // 文字透明度.
	var $red = 0; // 在黑色背景中...
	var $grn = 0;
	var $blu = 0;
	var $bg_red = 255; // 将文字设置为白色.
	var $bg_grn = 255;
	var $bg_blu = 255;

	public function __construct() {
		$this->font = $font;
	}
	function draw() {
		$width = 0;
		$height = 0;
		$offset_x = 0;
		$offset_y = 0;
		$bounds = array();
		$image = "";

		// 确定文字高度.
		$bounds = ImageTTFBBox($this->size, $this->rot, $this->font, "W");
		if ($this->rot < 0) {
			$font_height = abs($bounds[7]-$bounds[1]);
		} else if ($this->rot > 0) {
			$font_height = abs($bounds[1]-$bounds[7]);
		} else {
			$font_height = abs($bounds[7]-$bounds[1]);
		}
		$this->msg = iconv("GB2312", "UTF-8", $this->msg);

		// 确定边框高度.
		$bounds = ImageTTFBBox($this->size, $this->rot, $this->font, $this->msg);
		if ($this->rot < 0) {
			$width = abs($bounds[4]-$bounds[0]);
			$height = abs($bounds[3]-$bounds[7]);
			$offset_y = $font_height;
			$offset_x = 0;

		} else if ($this->rot > 0) {
			$width = abs($bounds[2]-$bounds[6]);
			$height = abs($bounds[1]-$bounds[5]);
			$offset_y = abs($bounds[7]-$bounds[5])+$font_height;
			$offset_x = abs($bounds[0]-$bounds[6]);

		} else {
			$width = abs($bounds[4]-$bounds[6]);
			$height = abs($bounds[7]-$bounds[1]);
			$offset_y = $font_height;;
			$offset_x = 0;
		}

		$image = imagecreate($width+($this->pad*2)+1,$height+($this->pad*2)+1);

		$background = ImageColorAllocate($image, $this->bg_red, $this->bg_grn, $this->bg_blu);
		$foreground = ImageColorAllocate($image, $this->red, $this->grn, $this->blu);

		if ($this->transparent) ImageColorTransparent($image, $background);
		ImageInterlace($image, false);

		// 画图.
		ImageTTFText($image, $this->size, $this->rot, $offset_x+$this->pad, $offset_y+$this->pad, $foreground, $this->font, $this->msg);

		// 输出为png格式.
		imagePNG($image);
	}
}

$text = new textPNG;

if (isset($msg)) $text->msg = $msg; // 需要显示的文字
if (isset($font)) $text->font = $font; // 字体
if (isset($size)) $text->size = $size; // 文字大小
if (isset($rot)) $text->rot = $rot; // 旋转角度
if (isset($pad)) $text->pad = $pad; // padding
if (isset($red)) $text->red = $red; // 文字颜色
if (isset($grn)) $text->grn = $grn; // ..
if (isset($blu)) $text->blu = $blu; // ..
if (isset($bg_red)) $text->bg_red = $bg_red; // 背景颜色.
if (isset($bg_grn)) $text->bg_grn = $bg_grn; // ..
if (isset($bg_blu)) $text->bg_blu = $bg_blu; // ..
if (isset($tr)) $text->transparent = $tr; // 透明度 (boolean).
$text->msg = "中华人民\n共和国";
$text->draw();



function generateImg($source, $text1, $text2, $text3, $font = './msyhbd.ttf') {
	$date = '' . date ( 'Ymd' ) . '/';
	$img = $date . md5 ( $source . $text1 . $text2 . $text3 ) . '.jpg';
	if (file_exists ( './' . $img )) {
		return $img;
	}

	$main = imagecreatefromjpeg ( $source );

	$width = imagesx ( $main );
	$height = imagesy ( $main );

	$target = imagecreatetruecolor ( $width, $height );

	$white = imagecolorallocate ( $target, 255, 255, 255 );
	imagefill ( $target, 0, 0, $white );

	imagecopyresampled ( $target, $main, 0, 0, 0, 0, $width, $height, $width, $height );

	$fontSize = 18;//像素字体
	$fontColor = imagecolorallocate ( $target, 255, 0, 0 );//字的RGB颜色
	$fontBox = imagettfbbox($fontSize, 0, $font, $text1);//文字水平居中实质
	imagettftext ( $target, $fontSize, 0, ceil(($width - $fontBox[2]) / 2), 190, $fontColor, $font, $text1 );

	$fontBox = imagettfbbox($fontSize, 0, $font, $text2);
	imagettftext ( $target, $fontSize, 0, ceil(($width - $fontBox[2]) / 2), 370, $fontColor, $font, $text2 );

	$fontBox = imagettfbbox($fontSize, 0, $font, $text3);
	imagettftext ( $target, $fontSize, 0, ceil(($width - $fontBox[2]) / 2), 560, $fontColor, $font, $text3 );

	//imageantialias($target, true);//抗锯齿，有些PHP版本有问题，谨慎使用

	imagefilledpolygon ( $target, array (10 + 0, 0 + 142, 0, 12 + 142, 20 + 0, 12 + 142), 3, $fontColor );//画三角形
	imageline($target, 100, 200, 20, 142, $fontColor);//画线
	imagefilledrectangle ( $target, 50, 100, 250, 150, $fontColor );//画矩形

	//bof of 合成图片
	$child1 = imagecreatefromjpeg ( 'http://gtms01.alicdn.com/tps/i1/T1N0pxFEhaXXXxK1nM-357-88.jpg' );
	imagecopymerge ( $target, $child1, 0, 400, 0, 0, imagesx ( $child1 ), imagesy ( $child1 ), 100 );
	//eof of 合成图片

	@mkdir ( './' . $date );
	imagejpeg ( $target, './' . $img, 95 );

	imagedestroy ( $main );
	imagedestroy ( $target );
	imagedestroy ( $child1 );
	return $img;
}
//http://my.oschina.net/cart/
generateImg ( 'http://1.popular.sinaapp.com/munv/pic.jpg', 'my.oschina.net/cart', 'PHP文字水平居中', '3个字' );
exit ();
?>