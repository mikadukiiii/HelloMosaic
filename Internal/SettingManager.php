<?php

ini_set('memory_limit', '-1');
ini_set("max_execution_time",0);

require_once 'setting.php';
require_once 'ParallelManager.php';

	//画像をリサイズしてモザイクをかける
	function OriginalResize($image,$image_date,$pattern){
		//モザイクアートの場合flagは0と定義
		$flag = 0;
		//setting.phpのResizeImage関数を呼び出す
		$image_size = ResizeImage($image,"resize","./Material/",$image_date,$pattern,$flag);
		//setting.phpのMosaicImage関数を呼び出す
		MosaicImage("./Material/resize.jpg",$image_date);

		//格納した縦のタイル数、モザイクピースの高さ、横幅を返す
		return $image_size;
	}


	//素材の画像フォルダの分を指定された幅に合わせる
	function PhotoResize($dir,$image_size,$pattern){

		//common.phpのload_dir関数を呼び出す
		$photo_dir = load_dir($dir);

			//素材の画像フォルダが100以下だった場合
			if(count($photo_dir) <= 100){

				//ランダムでSamplePhotoを選択する
				$random_photo = "./Source/SamplePhoto".rand(1, 2)."/";

				//common.phpのload_dir関数を呼び出す
				$random_dir = load_dir($random_photo);

				//配列を結合する
				$photo_dir = array_merge_recursive($photo_dir ,$random_dir);

			}

		//setting.phpのphoto_class関数を呼び出す
		$class = photo_class(count($photo_dir)); 

		ResizePhoto($dir,$class,$image_size,$pattern);

	}


	//画像を分割する
	function OriginalSplit($size_image,$pattern){

		//初期の$flagを定義
		$flag = 0;

		//setting.phpのSplitImage関数を呼び出す
		SplitImage("./Material/mosaic.jpg","./LargeSplit/",$size_image,$pattern,$flag);
		//common.phpのload_dir関数を呼び出す
		$Large_dir = load_dir("./LargeSplit/");

		//ParallelManager.phpのOriginalSmallSplit関数を呼び出す
		OriginalSmallSplit($Large_dir,$size_image,$pattern);
	}
?>