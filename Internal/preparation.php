<?php

ini_set('memory_limit', '-1');
ini_set("gd.jpeg_ignore_warning", 1);


/* 共通処理呼び出し */

require_once 'common.php';



/* 通常出力 */


	//モザイクアートの元となった分割画像とモザイクアートの分割画像を合成する
	function MosaicSynthesis($file_back,$file_alpha){

		//$qualityを定義
		$quality = 100;

		//common.phpのload_dir関数を呼び出す
		$back_dir = load_dir($file_back);

		//common.phpのload_dir関数を呼び出す
		$alpha_dir = load_dir($file_alpha);

				//モザイクアートの元となった分割画像とモザイクアートの分割画像分、処理を行う
				for($count = 0; $count <= count($back_dir); $count++){

						//空の画像を生成
						//背景となる画像
						$image_back = @imagecreatefromjpeg($back_dir[$count]);
						//空の画像を生成
						//透過をする画像
						$image_alpha = @imagecreatefromjpeg($alpha_dir[$count]);

						//背景となる画像の情報を得る
						list($width, $height, $type) = @getimagesize($back_dir[$count]);

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
						@imagejpeg($image_back,$back_dir[$count],$quality);

						//$image_backを破棄
						@imagedestroy($image_back);
						//$image_alphaを破棄
						@imagedestroy($image_alpha);

				}

	}



	//細かく分割した画像を大まかに分割した画像に当てはめる
	function ImageChange($dir,$number,$flag){

		//common.phpのload_dir関数を呼び出す
		$large_dir = load_dir("../LargeSplit/");


			//$flagが2だったら
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

		//配列は0からスタートなので、1を引いて当てはまる分割画像に行くようにする
		$number = $number - 1;

		//現在読み込んでいる画像に当てはまる分割画像を定義
		$image = $large_dir[$number];

				//画像ファイルの情報を得る
				list($targetsize_x,$targetsize_y) = @getimagesize($image);

				//画像の名前を定義
				$outfile = $image;

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
				$small_dir = create_image($dir);

				//$xを定義
				$x = 0;
				//$yを定義
				$y = 0;
				//$indexを定義
				$index = 0;

					//処理が終わるまで無限ループさせる
					while(true){

						//$small_dirの配列に入っている画像を読み込んで、幅を定義
						$width = @imagesx($small_dir[$index]);
						//$small_dirの配列に入っている画像を読み込んで、高さを定義
						$height = @imagesy($small_dir[$index]);

						//画像を再サンプリングする
						@imagecopy(
							$new_image,
							$small_dir[$index],
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

							//現在の配列のキーが$small_dirの配列に入っている画像数より大きい場合
							if($index >= count($small_dir)){
								//新しく$indexを定義
								$index = 0;
							}

					}

			//画像を保存する
			//$flagが0の場合
			if($flag == 0){

			//通常出力
			//jpeg形式で画像を保存
			@imagejpeg($new_image,$outfile,$quality);

			//$flagが3の場合
			}else if($flag == 2){

			//形を変えて出力
			//png形式で画像を保存
			@imagepng($new_image,"../FormLargeSplit/".basename($outfile,".jpg").".png",$quality);

			//$flagが1の場合
			}else{

			//色を変えて出力
			//jpeg形式で画像を保存
			@imagejpeg($new_image,"../ColorLargeSplit/".basename($outfile),$quality);

			}

			//$new_imageを破棄
			@imagedestroy($new_image);

	}

?>