<?php

ini_set('memory_limit', '-1');

require_once 'output.php';
require_once 'common.php';
require_once 'change.php';
require_once 'ParallelManager.php';


	//モザイクアートを出力する
	function MosaicActOutput(){

		//$flagを定義
		$flag = 0;

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtPreparation(1);

		/* モザイクアートの画像の数を数える */
		//$mosaic_countの定義
		$mosaic_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$mosaic_count = count(count_dir('./Material/mosaicart_1_*',$mosaic_count));

		//画像ファイル名を決める
		//画像を合成しないで出力
		$mosaic1 = './Material/mosaicart_1_'.$mosaic_count.'.jpg';
		//画像を合成して出力
		$mosaic2 = './Material/mosaicart_2_'.$mosaic_count.'.jpg';

		//output.phpのImageSetUpを呼び出す
		ImageSetUp('./LargeSplit/',$mosaic1,$flag);

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtPreparation(4);

		//output.phpのImageSetUpを呼び出す
		ImageSetUp('./LargeSplit/',$mosaic2,$flag);

		//画像ファイル名を返す
		return $mosaic2;

	}


	//色を変えたモザイクアートを出力する
	function ColorMosaicActOutput(){

		//$flagを定義
		$flag = 1;

		//change.phpのColorSelectを呼び出す
		$color = ColorSelect();

		//カンマで区切った数字を$red, $green, $blueの中に格納する
		list($red, $green, $blue) = explode(',', $color);

		//$flagを再定義
		$flag = 1;

		//change.phpのColorChangeImageを呼び出す
		ColorChangeImage("./Material/resize.jpg","./Material/colorresize.jpg",$red,$green,$blue,$flag);

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtChange($color,2);

		//common.phpのcreate_dir関数を呼び出す
		$dir = create_dir('./ColorLargeSplit/');

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtPreparation(2);

		/* モザイクアートの画像の数を数える */
		//$mosaic_countの定義
		$mosaic_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$mosaic_count = count(count_dir('./Material/colormosaicart_1_*',$mosaic_count));

		//画像ファイル名を決める
		//画像を合成しないで出力
		$mosaic1 = './Material/colormosaicart_1_'.$mosaic_count.'.jpg';
		//画像を合成して出力
		$mosaic2 = './Material/colormosaicart_2_'.$mosaic_count.'.jpg';

		//output.phpのImageSetUpを呼び出す
		ImageSetUp($dir,$mosaic1,$flag);

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtPreparation(5);

		//output.phpのImageSetUpを呼び出す
		ImageSetUp($dir,$mosaic2,$flag);

		//画像ファイル名を返す
		return $mosaic2;
	}


	//形を変えたモザイクアートを出力する
	function FormMosaicActOutput(){

		//$flagを定義
		$flag = 2;

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtPreparation(6);

		//ランダムで1～5の中から形を選ぶ
		$mask = mt_rand(1,5);

		//change.phpのFormResize関数を呼び出す
		FormResize($mask);

		//ParallelManager.phpのMosaicartPreparation関数を呼び出す
		MosaicArtChange($mask,3);

		//リサイズした画像を消す
		unlink('./Source/Mask/'.$mask.'_resize.png');

		//common.phpのcreate_dir関数を呼び出す
		$dir = create_dir('./FormLargeSplit/');

		//ParallelManager.phpのMosaicartPreparationを呼び出す
		MosaicArtPreparation(3);

		/* モザイクアートの画像の数を数える */
		//$mosaic_countの定義
		$mosaic_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$mosaic_count = count(count_dir('./Material/formmosaicart_1_*',$mosaic_count));

		//画像ファイル名を決める
		$mosaic = './Material/formmosaicart_1_'.$mosaic_count.'.png';

		//output.phpのImageSetUpを呼び出す
		ImageSetUp($dir,$mosaic,$flag);

		//画像ファイル名を返す
		return $mosaic;

	}

?>