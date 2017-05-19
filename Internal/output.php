<?php

ini_set('memory_limit', '-1');
ini_set("gd.jpeg_ignore_warning", 1);


/* 共通処理呼び出し */

require_once 'common.php';

/* 通常出力 */

	//大まかに分割した画像をもと画像のサイズに合わせて組み立てる
	function ImageSetUp($dir,$outfile,$flag){

			//$flagが3だったら
			//形を変えたモザイクアートを出力する場合
			if($flag == 2){
				//pngの場合
				//$qualityを定義
				$quality = 9;
			}else{
				//jpgの場合
				//$qualityを定義
				$quality = 100;
			}

		//画像ファイルの情報を得る
		list($targetsize_x,$targetsize_y) = @getimagesize("./Material/resize.jpg");

		//取得した情報を参照して画像を生成する
		$new_image = @imagecreatetruecolor($targetsize_x,$targetsize_y);

			//$flagが2だったら
			//形を変えたモザイクアートを出力する場合
			if($flag == 2){
				//ブレンドモードを無効にする
				@imagealphablending($new_image, false);
				//完全なアルファチャネル情報を保存するフラグをonにする
				@imagesavealpha($new_image, true);
			}

		//common.phpのcreate_image関数を呼び出す
		$large_dir = create_image($dir);

		//$xを定義
		$x = 0;
		//$yを定義
		$y = 0;
		//$indexを定義
		$index = 0;

			//処理が終わるまで無限ループさせる
			while(true){

				//$large_dirの配列に入っている画像を読み込んで、幅を定義
				$width = @imagesx($large_dir[$index]);
				//$large_dirの配列に入っている画像を読み込んで、高さを定義
				$height = @imagesy($large_dir[$index]);

				//画像を再サンプリング
				@imagecopy(
					$new_image,
					$large_dir[$index],
					$x,
					$y,
					0,
					0,
					$width,
					$height
				);


			//新しい幅を定義
			$x = $x + $width;
				//ファイル内の画像の幅が現在の幅以上の場合
				if($x >= $targetsize_x){
					//新しく$xを定義
					$x = 0;
					//新しい高さを定義
					$y = $y + $height;

						//ファイル内の画像の高さが現在の高さ以上の場合
						if($y >= $targetsize_y){
							//処理を終わらせる
							break;
						}
				}

			//次の配列に移る
			$index++;

				//現在の配列のキーが$large_dirの配列に入っている画像数より大きい場合
				if($index >= count($large_dir)){
					//新しく$indexを定義
					$index = 0;
				}
		}

		//画像を保存する
		//$flagが2の場合
		if($flag == 2){

		//形を変えて出力
		//png形式で画像を保存
		@imagepng($new_image,$outfile,$quality);

		}else{

		//通常出力
		//色を変えて出力
		//jpeg形式で画像を保存
		@imagejpeg($new_image,$outfile,$quality);

		}

		//$new_imageを破棄
		@imagedestroy($new_image);
	}


	//モザイクアートの元となった画像とモザイクアートの画像を合成する
	function ImageSynthesis($back,$alpha,$filename){

		//$qualityを定義
		$quality = 9;

		//空の画像を生成
		//背景となる画像
		$image_back = @imagecreatefromjpeg($back);
		//空の画像を生成
		//透過をする画像
		$image_alpha = @imagecreatefrompng($alpha);

		//背景となる画像の情報を得る
		list($width, $height, $type) = @getimagesize($back);

		//ブレンドモードを無効にする
		@imagealphablending($image_alpha,false);
		//完全なアルファチャネル情報を保存するフラグをonにする
		@imagesavealpha($image_alpha,true);

		//再サンプリング
		@imagecopymerge(
			//背景画像
			$image_back,
			//コピー元画像
			$image_alpha,
			//背景画像の x 座標
			0,
			//背景画像の y 座標
			0,
			//コピー元の x 座標
			0,
			//コピー元の y 座標
			0,
			//コピー元画像ファイルの幅
			$width,
			//コピー元画像ファイルの高さ
			$height,
			//透過度(%)
			30
		);

		//jpeg形式で画像を保存
		@imagepng($image_back,$filename,$quality);

		//$image_backを破棄
		@imagedestroy($image_back);
		//$image_alphaを破棄
		@imagedestroy($image_alpha);

	}

?>