<?php

require_once 'parallel.php';
require_once 'common.php';

	//モザイクアートを生成する
	function MosaicArtCreate($type,$min,$max){

		/* モザイクアートの元となる画像フォルダを数える */
		//split_countの定義
		$split_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$split_count = count_dir("./SmallSplit/split*",$split_count);

		//$urlListを定義
		$urlList = array();

			//splitファイルの数分、処理が回る
			foreach((array)$split_count as $split){
				//$urlに処理させるCreateManager.phpのURLを格納
				$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/CreateManager.php?id=".$split;
				//$urlListにURLを格納
				$urlList[] = $url;
			}

		$data = array('type' => $type , 'max' => $max , 'min' => $min);

		//parallel.phpを呼び出す
		$flag = multi_execute($urlList,$data);

			//$flagがtrueかどうか
			if ($flag){

			//処理をしない

			}else{

			//エラーページに飛ばす
			header("location: error.php");

			}

	}

	//モザイクアートを出力する前の準備を行う
	function MosaicArtPreparation($output){

		/* モザイクアートの元となる画像フォルダを数える */
		//split_countの定義
		$split_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$split_count = count_dir("./SmallSplit/split*",$split_count);

		//$urlListを定義
		$urlList = array();

			//大まかに分割した画像の数分、処理が回る
			foreach((array)$split_count as $split){

				//$urlに処理させるCreateManager.phpのURLを格納
				$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/PreparationManager.php?id=".$split;
				//$urlListにURLを格納
				$urlList[] = $url;

			}

		$data = array('type' => $output);

		//parallel.phpを呼び出す
		$flag = multi_execute($urlList,$data);

			//$flagがtrueかどうか
			if ($flag){

				//処理をしない

			}else{

				//エラーページに飛ばす
				header("location: error.php");

			}

	}


	//色、形を変えてモザイクアートを出力する前の準備を行う
	function MosaicArtChange($change,$output){

		//$outputが2だったら
		if($output == 2){

			//色を変えて出力の場合
			//カンマで区切った数字を$red, $green, $blueの中に格納する
			list($red, $green, $blue) = explode(',', $change);

		}

		/* モザイクアートの元となる画像フォルダを数える */
		//split_countの定義
		$split_count = 1;
		//common.phpのcount_dir関数を呼び出す
		$split_count = count_dir("./SmallSplit/split*",$split_count);

		//$urlListを定義
		$urlList = array();

			//大まかに分割した画像の数分、処理が回る
			foreach((array)$split_count as $split){

				//$outputが2だったら
				if($output == 2){

					//$urlに処理させるChangeManager.phpのURLを格納
					$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/ChangeManager.php?id=".$split;
					//$urlListにURLを格納
					$urlList[] = $url;

				}else{

					//$urlに処理させるChangeManager.phpのURLを格納
					$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/ChangeManager.php?id=".$split;
					//$urlListにURLを格納
					$urlList[] = $url;

				}

			}

		//$outputが2だったら
		if($output == 2){

			$data = array('type' => $output , 'red' => $red , 'green' => $green , 'blue' => $blue);

		}else{

			$data = array('type' => $output , 'mask' => $change);
		}

		//parallel.phpを呼び出す
		$flag = multi_execute($urlList,$data);

			//$flagがtrueかどうか
			if ($flag){

				//処理をしない

			}else{

				//エラーページに飛ばす
				header("location: error.php");

			}

	}


	//画像の小さな分割を行う
	function OriginalSmallSplit($Large_dir,$image_size,$pattern){

	//$urlListを定義
	$urlList = array();

	//$countを定義
	$count = 0;

	//大まかに分割した画像の数分、処理が回る
	foreach((array)$Large_dir as $dir){
		//$urlに処理させるCreateManager.phpのURLを格納
		$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/SplitManager.php?id=".$count;
		//$urlListにURLを格納
		$urlList[] = $url;
		//$countを加算
		$count++;
	}

	$data = array('pattern' => $pattern);

	//配列を結合する
	$data = array_merge_recursive($data ,$image_size);

	//parallel.phpを呼び出す
	$flag = multi_execute($urlList,$data);

		//$flagがtrueかどうか
		if ($flag){

		//処理をしない

		}else{

		//エラーページに飛ばす
		header("location: error.php");

		}

	}


	//画像の小さな分割を行う
	function ResizePhoto($dir,$class,$image_size,$pattern){

		//$urlListを定義
		$urlList = array();

		//common.phpのload_dir関数を呼び出す
		$photo_dir = load_dir($dir);

		//$countの初期値を定義する
		$count = 0;

		//$randomを定義
		$random = array();

		//ランダムな数にするために$photo_dirの数分、処理を回す
		foreach((array)$photo_dir as $photo){

			//$random配列の中に$countを格納
			$random[] = $count;

			//$countを加算
			$count++;

		}

		shuffle($random);

		//$countを定義
		$dir_count = ceil((count($photo_dir) * 4) / ($class * 4));

		//大まかに分割した画像の数分、処理が回る
		for($count = 1; $count <= $dir_count; $count++){
			//$urlに処理させるCreateManager.phpのURLを格納
			$url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://").$_SERVER["HTTP_HOST"] ."/mosaic/Internal/ResizeManager.php?id=".$count;
			//$urlListにURLを格納
			$urlList[] = $url;
		}

		$data = array('dir' => $dir , 'class' => $class , 'pattern' => $pattern);

		//配列を結合する
		$data = array_merge_recursive($data ,$random);

		//配列を結合する
		$data = array_merge_recursive($data ,$image_size);

		//parallel.phpを呼び出す
		$flag = multi_execute($urlList,$data);

			//$flagがtrueかどうか
			if ($flag){

			//処理をしない

			}else{

			//エラーページに飛ばす
			header("location: error.php");

			}

	}
?>