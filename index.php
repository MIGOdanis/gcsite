<?php
if(!isset($_POST['UFS'])){
?>
<meta charset='utf-8'>
<form action="" method="post">
　UNMODIFIED FORWARD SEQUENCE<br>
　<textarea name="UFS" cols="150" rows="40"></textarea><br>
　<input type="submit" value="輸出">
</form>
<?php
}else{
	//尺規總長
	$length = mb_strlen($_POST['UFS'], 'utf-8');
	$cgLineY = 300;
	$footY = 100;
	$font = "arial.ttf";
	//把cg加上逗號
	$UFS = str_replace ("CG","CG,",$_POST['UFS']);
	//用逗號切開保留cg
	$ufsArray = explode(",", $UFS);
	$ufsaCount = count($ufsArray);
	$firstTwoUFS = mb_substr($_POST['UFS'],0,2,"utf-8");
	$lastTwoUFS = mb_substr($_POST['UFS'],$length-2,$length,"utf-8");
	$lineArray = array();
	//在前後加入cg 除了第一個
	if($firstTwoUFS == "CG"){
		array_shift($ufsArray);
	}
	if($lastTwoUFS == "CG"){
		array_pop($ufsArray);
	}	
	foreach($ufsArray as $row){
		if($row != end($ufsArray) && $row != $ufsArray[0]){
			$lineArray[] = "CG".$row; //"CG".
		}else{
			if($row == end($ufsArray) || $firstTwoUFS == "CG"){
				$lineArray[] ="CG". $row; //"CG".
			}else{
				$lineArray[] = $row;
			}
		}
	}

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

	function CGLine($im, $color, $ll){
		global $font,$length,$cgLineY,$lineArray;
		$ys = $cgLineY-10;
		$ye = $cgLineY+10;
		$dex = 20;
		$partNum = ($length/100);
		$partNumN2 =  number_format($partNum, 2);
		$partPx = (($ll-20)/$partNum)/100;
		foreach($lineArray as $lines){
			$cgStrlength = mb_strlen($lines, 'utf-8');
			$fristGLine = (strpos($lines,"G",2));
			if($row != end($lineArray) && $fristGLine < 1){
				$fristGLine = $fristGLine++; //"CG".
			}
			$xNow = $dex + ($fristGLine * $partPx);
			imageline($im, $xNow, $ys, $xNow, $ye, $color);
			//imagettftext($im, 8, 0, $xNow-5, $cgLineY-20, $color, $font, $cgStrlength);
			if($lines == $lineArray[0]){
				$dex = $dex + ($cgStrlength * $partPx);
			}else{
				$dex = $dex + (($cgStrlength-2) * $partPx);
			}
			//imageline($im, $xs+1, $ys, $xs+1, $ye, $color);
		}

		return $im;
	}

	function rulers($im, $color){
		global $font,$length,$footY,$cgLineY;
		$partNum = ($length/100);
		$partNumN2 =  number_format($partNum, 2);
		$partPx = (800/$partNum);
		$lastPartPx = $partPx * $partNumN2 ;
		$tens = (int)$partNum - ((int)$partNum % 10);
		$sx = (20 + $partPx);
		$sy = $footY;
		$ex = (20 + $partPx);
		$ey = ($footY-10);

		//頭尾數量
		imagettftext($im, 15, 0, 20, ($footY-20), $color, $font, 0);
		
		if($partNum < 2 && $partNum > 1){
			imageline($im, $partPx, $sy, $partPx, $ey, $color);
			imageline($im, $partPx+1, $sy, $partPx+1, $ey, $color);
		}
		for($r=1;$r <= $partNum ;$r++){

			if ($r == ($tens/2) || $r == $tens){
				imagettftext($im, 15, 0, $sx-($partPx/2), ($footY-30), $color, $font, ($r*100));
				$ey = ($footY-20);
			}
			if($r == (int)$partNum)
				$ey = ($footY+10);
			imageline($im, $sx, $sy, $ex, $ey, $color);
			$dsx = ($sx+1);
			$dex = ($ex+1);
			imageline($im, $dsx, $sy, $dex, $ey, $color);
			if ($r == ($tens/2) || $r == $tens ){
				$ey = ($footY-10);
			}
			$sx = ($sx + $partPx);
			$ex = ($ex + $partPx);	
		}
		imagettftext($im, 15, 0, $dex, ($footY-20), $color, $font, $length);

		imageline($im, 20, $footY, $dex, $footY, $color);
		imageline($im, 20, $footY+1, $dex, $footY+1, $color);


		imageline($im, 20, $cgLineY, $dex, $cgLineY, $color);
		imageline($im, 20, $cgLineY+1, $dex, $cgLineY+1, $color);
		return array($im,$dex);
	}
	header("Content-type: image/png");
	//產生圖像
	$im = imagecreate(860,600); //860 600
	//產生背景
	$white = imagecolorallocate($im, 255, 255, 255);
	//產生線條
	$black = imagecolorallocate($im, 0, 0, 0);

	//產生時間
	imagettftext($im, 10, 0, 10, $cgLineY + 30, $black, $font, "CG site");
	//產生尺的頭尾
	$im = xLine($im, 20, 20, $footY, ($footY+10),$black);
	//產生尺碼間格
	$ima = rulers($im, $black);
	$im = $ima[0];
	$im = CGLine($im, $black, $ima[1]);
	imagepng($im);
	imagedestroy($im);
}
//	imagestring($im, 5, 0, 0, $sy.$ey, $color);
?>