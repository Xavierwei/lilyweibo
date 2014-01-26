<?php
class TextImage {
	public $bgImage = NULL;
	public $fontSize = 12;
	public $fontSize = 12;
	public $fontFile = null;
	public $lineHeight = NULL; //一行文字的高度
	
	public function __construct($bgImage, $text, $fontSize = null, $fontFile = null, $fontColor = '#fffff') {
		$this->bgImage = $bgImage;
		$this->text = $text;
		$this->fontSize = (int)$fontSize;
		$this->lineHeight = (int)($fontSize * 1.8);
		$this->fontFile = $fontFile;
		$this->fontColor = $fontColor;
	}
	
	public function generateTextImage($text) {
		$im = imagecreatefromjpeg($this->styleImages[1]); 
		$colorArray = str2rgb($userStyle[1]);
		$fontColor  = $this->str2rgb();
		$text = '张三的博客';
		$font = ROOT_PATH . '/font/msyh.ttf';
		$fontSize = 26;
		imagettftext($im, $fontSize,0, 10, 36, $fontColor ,$font, $text);
		header('Content-type: image/png');                 //即便是从jpg拷贝的图片，也能以png输出，  
		$image = imagepng($im, $this->styleImages[4]);  
		
		$totalLine = count($this->processText($text));
		$canvasHeight = $lineHeight * $textLen + $canvasHeight;
		$im = imagecreatetruecolor($canvasWidth, $canvasHeight); #定义画布
		$colorArray = str2rgb($userStyle[1]);
		imagefill($im, 0, 0, imagecolorallocate($im, $colorArray['red'], $colorArray['green'], $colorArray['blue']));
	}
	
	/**
	 * 把一段文字分割成多行
	 * @param type $text
	 * @return 返回分割后的文字数组
	 */
	private function processText($text) {
		$textArr = array();
		$tempArr = explode("\n", trim($text));
		$j = 0;
		foreach($tempArr as $v){
			$arr = $this->strDiv($v, 25);
			$textArr[] = array_shift($arr);
			foreach($arr as $v){
				$textArr[] = $v;
				$j ++;
				if($j > 100){ break; }
			}
			$j ++;
			if($j > 100){ break; }
		}
		return $textArr;
	}

	/**
	 * 把长行分割成多行
	 * @param type $str 文本
	 * @param type $width 显示宽度
	 * @return type
	 */
	private function strDiv($str, $width = 10){
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

	//将十六进制的颜色值分解成RGB形式  
	private function str2rgb($str) {
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
}
 