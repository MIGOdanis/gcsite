<?php
//尺規總長
$length = 800;
$footX = 100;
$font = "arial.ttf";
function xLine($im, $sx,$ex,$sy,$ey,$color){
	imageline($im, $sx, $sy, $ex, $ey, $color);
	$sx++;
	$ex++;
	imageline($im, $sx, $sy, $ex, $ey, $color);
	return $im;
}

function yLine($im, $sx,$ex,$sy,$ey,$color){
	imageline($im, $sx, $sy, $ex, $ey, $color);
	$sy++;
	$ey++;
	imageline($im, $sx, $sy, $ex, $ey, $color);
	return $im;
}

function rulers($im, $color){
	global $font,$length,$footX;
	$partNum = ($length/100);
	$partPx = (800/$partNum);
	$sx = (20 + $partPx);
	$sy = $footX;
	$ex = (20 + $partPx);
	$ey = ($footX-10);

	//頭尾數量
	imagettftext($im, 15, 0, 20, ($footX-20), $color, $font, 0);
	imagettftext($im, 15, 0, 800, ($footX-20), $color, $font, $length);

	for($r=1;$r < $partNum ;$r++){
		if ($r == ($partNum/2)){
			imagettftext($im, 15, 0, $sx, ($footX-30), $color, $font, ($r*100));
			$ey = ($footX-20);
		}
		imageline($im, $sx, $sy, $ex, $ey, $color);
		$dsx = ($sx+1);
		$dex = ($ex+1);
		imageline($im, $dsx, $sy, $dex, $ey, $color);
		if ($r == ($partNum/2)){
			$ey = ($footX-10);
		}
		$sx = ($sx + $partPx);
		$ex = ($ex + $partPx);

	}
	return $im;
}
header("Content-type: image/png");
//產生圖像
$im = imagecreate(840, 600);
//產生背景
$white = imagecolorallocate($im, 255, 255,0);
//產生線條
$black = imagecolorallocate($im, 0, 0, 0);

//產生時間
imagettftext($im, 20, 0, 385, 30, $black, $font, "CG site");
//產生尺的頭尾
$im = yLine($im, 20, 800, $footX, $footX,$black);
$im = xLine($im, 20, 20, $footX, ($footX+10),$black);
$im = xLine($im, 800, 800, $footX, ($footX+10),$black);
//產生尺碼間格
$im = rulers($im, $black);
// 5. 做成 png 圖檔並輸出
imagepng($im);

imagedestroy($im);

//	imagestring($im, 5, 0, 0, $sy.$ey, $color);
?>