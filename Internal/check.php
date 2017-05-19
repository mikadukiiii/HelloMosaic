<?php

	//POSTからデータを受け取る
	//サイズ比を取得
	if(isset($_POST['ratio']) && ($_POST['ratio']=='Square' || $_POST['ratio']=='DSLR' || $_POST['ratio']=='Rectangle' || $_POST['ratio']=='Movie')){

		//サイズ比ごとに処理
		switch($_POST['ratio']){

			//正方形の場合
			case 'Square':

			//横の比率を定義
			$ratio_width = 1;
			//縦の比率を定義
			$ratio_height = 1;

			//処理を終わる
			break;


			//DSLRの場合
			case 'DSLR':

			//横の比率を定義
			$ratio_width = 2;
			//縦の比率を定義
			$ratio_height = 3;

			//処理を終わる
			break;


			//長方形の場合
			case 'Rectangle':

			//横の比率を定義
			$ratio_width = 3;
			//縦の比率を定義
			$ratio_height = 4;

			//処理を終わる
			break;


			//ワイドの場合
			case 'Movie':

			//横の比率を定義
			$ratio_width = 9;
			//縦の比率を定義
			$ratio_height = 16;

			//処理を終わる
			break;

		}

	}else{

		//エラーページに飛ばす
		header("location: error.php");

	}


	//パターンを取得
	if(isset($_POST['pattern']) && ($_POST['pattern']=='Landscape' || $_POST['pattern']=='Portrait')){

		//取得したパターンで定義
		$pattern = $_POST['pattern'];

	}else{

		//エラーページに飛ばす
		header("location: error.php");

	}

	//タイルの枚数を取得
	if(isset($_POST['tile'])){

		//タイルの枚数を取得
		$tile_width = $_POST['tile'];

	}else{

		//エラーページに飛ばす
		header("location: error.php");

	}

?>