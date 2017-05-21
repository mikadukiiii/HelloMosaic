<?php


ini_set('memory_limit', '-1');


/* 共通処理呼び出し */

require_once 'common.php';


/* モザイクアート元となる画像処理 */


	//画像を指定されたサイズにリサイズする
	function ResizeImage($image,$name,$dir,$image_date,$pattern,$flag){

			//common.phpのcreate_dir関数を呼び出し
		       	$dir = create_dir($dir);

				//画像かどうかをチェックする
				if(!@exif_imagetype($image)){
						//フラグが0の場合
						if($flag == 0){

							//エラーページに飛ばす
							header("location: error.php");

						}else{

							//処理を中断する
							//※画像以外が読み込まれた場合はスルー
							exit;

						}
					}else{

						//画像ファイルの情報を得る
						list($width,$height,$type) = @getimagesize($image);

							//フラグが1の場合
							//モザイクアートの素材となるモザイクピースの処理をする
							if($flag == 1){

								//横長で画像サイズが横より高さのほうが長い、もしくは縦長で画像サイズが高さより横のほうが長かったら、90度反転させる
								if(($height > $width && $pattern == 'Landscape') || ($height < $width && $pattern == 'Portrait')){

									//高さを逆転させる
									$rotate_width = $height;
									//幅を逆転させる
									$rotate_height = $width;
									//画像の名前を定義
									$filename = $image;
									//逆転した横幅、高さを参照して画像を生成する
									$canvas = @imagecreatetruecolor($rotate_width, $rotate_height);

									//common.phpのget_type関数を呼び出す
									$create_image = get_type($type);
									//空の画像を作成
									//※imagecreatefrombmpの場合のみcommon.phpのimagecreatefrombmp関数を呼び出す
									$source = @$create_image($filename);

									//画像を再サンプリング
									@imagecopyresampled(
											$canvas,
											$source,
											0,
											0,
											0,
											0,
											$rotate_width,
											$rotate_height,
											$width,
											$height
									);

									//反転させる角度を定義
									$degrees = 90;
									//imagerotate関数を使用し、sourceを90度回転
									$rotate = @imagerotate($source,$degrees, 0);

									//jpeg形式で画像を保存
									@imagejpeg($rotate,$filename);

									//$rotateを破棄
									@imagedestroy($rotate);

									//listに反転させた画像の横幅、高さの情報を入れる
									list($width,$height) = @getimagesize($image);

								}

								//新しく設定される幅
								$new_width = $image_date["piece_width"];
								 //新しく設定される高さ
								$new_height = $image_date["piece_height"];

							}else{

								//フラグが1ではなかった場合
								//モザイクアートにする一枚絵の処理を行う

								//画像の比率を求める
								$ratio_image = round(($height / $width) , 4);

								//ImageSize関数を呼び出す
								$image_size = ImageSize($image_date,$ratio_image,$pattern);

								//新しく設定される高さ
								$new_height = ($image_size["piece_height"] * $image_size["tile_height"]);
								//新しく設定される幅
								$new_width = ($image_size["piece_width"] * $image_size["tile_width"]);

							}

						//上で定義した横幅、高さをもとに画像を生成
						//モザイクアートにする一枚絵、モザイクピースの両方に適用
						$resize_image = @imagecreatetruecolor($new_width,$new_height); 

						//common.phpのget_type関数を呼び出す
						$create_image = get_type($type);
						//空の画像を作成
						//※imagecreatefrombmpの場合のみcommon.phpのimagecreatefrombmp関数を呼び出す
						$new_image = @$create_image($image);


						//画像を再サンプリング
						@imagecopyresampled(
								//背景画像
								$resize_image,
								//コピー元画像
								$new_image,
								// 背景画像の x 座標
								0,
								// 背景画像の y 座標
								0,
								// コピー元の x 座標
								0,
								// コピー元の y 座標
								0,
								//背景画像の幅
								$new_width,
								//背景画像の高さ
								$new_height,
								//コピー元画像ファイルの幅
								$width,
								//コピー元画像ファイルの高さ
								$height
						);

						//jpeg形式で画像を保存
						@imagejpeg($resize_image,$dir.$name.".jpg");

						//resize_imageを破棄
						@imagedestroy($resize_image);
						//new_imageを破棄
						@imagedestroy($new_image);

				}

		//フラグが0の場合
		//使いまわしできるようにタイルサイズを返す
		if($flag == 0){

			//格納した縦のタイル数、モザイクピースの高さ、横幅を返す
			return $image_size;


		}

	}


	//横のタイル数に応じて画像のリサイズするサイズ比を決める
	function ImageSize($image_date,$ratio_image,$pattern){

		//縦のタイル数、モザイクピースの高さ、横幅を格納するために配列を定義
		$image_size = array();

		//$patternがPortraitだったら
		if($pattern == 'Portrait'){

			//縦長の場合
			//指定された横のタイル数とモザイクピースの比率を使って、横のタイル数を求める
			$image_size["tile_width"] = floor(($image_date['tile_width'] / $image_date['ratio_width']) * $image_date['ratio_height']);
			//指定された横のタイル数とモザイクピースの比率を使って、縦のタイル数を求める
			$image_size["tile_height"] = floor((($image_size["tile_width"] / $image_date['ratio_height']) * $image_date['ratio_width']) * $ratio_image);

		//$patternがLandscapeだったら
		}else if($pattern == 'Landscape'){

			//横長の場合
			//横のタイル数をそのまま使う
			$image_size["tile_width"] = $image_date['tile_width'];
			//指定された横のタイル数とモザイクピースの比率と変換する画像比率を使って、縦のタイル数を求める
			$image_size["tile_height"] = floor((($image_size["tile_width"] / $image_date['ratio_width']) * $image_date['ratio_height']) * $ratio_image);

		}

			//拡大してもモザイクピースが綺麗に見れるように解像度を調整する
			//サイズはA3で固定
			//if($image_date['tile_width'] <= 100){

					if($pattern == 'Landscape'){

						//指定された横のタイル数が20～50の範囲内の場合
						//300PPIでA3サイズの最大の縦幅が3425なので、それを先ほど求めた縦のタイル数で割り、モザイクピースの高さを求める
						$image_size["piece_width"] = floor(3425 / $image_size["tile_width"]);
						//300PPIでA3サイズの最大の横幅が3425なので、それを先ほど求めた横のタイル数で割り、モザイクピースの横幅を求める
						$image_size["piece_height"] = floor($image_size["piece_width"] / $image_date['ratio_height'] * $image_date['ratio_width']);

					}else if($pattern == 'Portrait'){

						if($ratio_image > 1.0){

							$ratio_size = 4960;

						}else{

							$ratio_size = 3425;
						}

						//指定された横のタイル数が20～50の範囲内の場合
						//300PPIでA3サイズの最大の横幅が4960なので、それを先ほど求めた縦のタイル数で割り、モザイクピースの高さを求める
						$image_size["piece_height"] = floor($ratio_size / $image_size["tile_height"]);
						//300PPIでA3サイズの最大の横幅が3425なので、それを先ほど求めた横のタイル数で割り、モザイクピースの横幅を求める
						$image_size["piece_width"] = floor($image_size["piece_height"] / $image_date['ratio_height'] * $image_date['ratio_width']);

					}

		//格納した縦のタイル数、モザイクピースの高さ、横幅を返す
		return $image_size;

	}

	//画像にモザイクをかける
	function MosaicImage($path,$image_date){

		//モザイクの粗さ
		$mosaic = $image_date['tile_width'];

		//画像ファイルの情報を得る
		list($width,$height) = @getimagesize($path);

		//空の画像を作る
		$image = @imagecreatefromjpeg($path);

		//画像をいったん縮小する
		$mosaic_width = intval($width/$mosaic);
		$mosaic_height = intval($height/$mosaic);

		//縮小されたサイズを参照して画像を生成する
		$small_image = @imagecreatetruecolor($mosaic_width,$mosaic_height);

		//画像を再サンプリング
		@imagecopyresampled(
				//背景画像
				$small_image,
				//コピー元画像
				$image,
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0,
				//コピー元画像の x 座標
				0,
				//コピー元画像の y 座標
				0,
				//背景画像の幅
				$mosaic_width,
				//背景画像の高さ
				$mosaic_height,
				//コピー元画像ファイルの幅
				$width,
				//コピー元画像ファイルの高さ
				$height
		);

		// 画像を元のサイズに拡大する
		$mosaic_image = @imagecreatetruecolor($width,$height);
		@imagecopyresampled(
						//背景画像
						$mosaic_image,
						//コピー元画像
						$small_image,
						//背景画像の x 座標
						0,
						//背景画像の y 座標
						0,
						//コピー元画像の x 座標
						0,
						//コピー元画像の y 座標
						0,
						//背景画像の幅
						$width,
						//背景画像の高さ
						$height,
						//コピー元画像ファイルの幅
						$mosaic_width,
						//コピー元画像ファイルの高さ
						$mosaic_height
						);

		//jpeg形式で画像を保存
		@imagejpeg($mosaic_image,'./Material/mosaic.jpg');

		//imageを破棄
		@imagedestroy($image);
		//mosaic_imageを破棄
		@imagedestroy($mosaic_image);
		//small_imageを破棄
		@imagedestroy($small_image);
	}



/* 素材の画像処理 */


		//フォルダに入る最大ファイル数を決める
		function photo_class($dir){

			//100以上だと現在の仕様では処理が止まってしまうため
			//最大ファイル数を100未満にする必要がある

			//反転画像数を考慮して4を乗算する
			$class = $dir * 4;

			//25以下になるまで処理をする //25
			while($class >= 25){

				//countを定義する
				$count = 2;

				//$dir%$countの余りが0になるまで処理をする
				while(0 != $class%$count){
					$count++;
				}

				if($class != $count){
					//素数じゃない場合
					//ファイル数/先ほど出した余りの出ない数
					$class = $class/$count;

				}else{
					//素数の場合
					//ファイル数/2、小数点以下は切り上げ
					$class = ceil($class/2);
				}

			}


		//最大フォルダ数を返す
		return $class;

		}


		//素材となる画像を回転させる
		function PhotoRotation($path,$number){

		//countを定義する
		$count = 0;

			//垂直、水平、その両方を適応した画像を作り、保存する
			while($count < 3){
				//画像のパスを定義
				$file    = $path.$number.".jpg";
				//空の画像を作成
				$image   = @imagecreatefromjpeg($file);

					//countの数字に合わせて処理する
					switch($count){
						case 0:
						//水平反転
						@imageflip($image, IMG_FLIP_HORIZONTAL);
						//jpeg形式で水平反転した画像を保存
						@imagejpeg($image , $path.$number."_1.jpg");
						break;

						case 1:
						//垂直反転
						@imageflip($image, IMG_FLIP_VERTICAL);
						//jpeg形式で垂直反転した画像を保存
						@imagejpeg($image , $path.$number."_2.jpg");
						break;

						case 2:
						//水平・垂直反転
						@imageflip($image, IMG_FLIP_BOTH);
						//jpeg形式で水平・垂直反転した画像を保存
						@imagejpeg($image , $path.$number."_3.jpg");
						break;
					}

				//imageを破棄
				@imagedestroy($image);
				//countを増やす
				$count++;

			}

		}



/* モザイクアートの元となる画像の分割処理 */


	//画像を指定された幅と高さで分割する
	function SplitImage($image,$path,$image_size,$pattern,$flag){

		//common.phpのcreate_dir関数を呼び出し
		$path = create_dir($path);

		//空の画像を作る
		$source = @imagecreatefromjpeg($image);

		//$sourceに入っている画像を読み込んで、幅を取得
		$source_width = imagesx($source);
		//$sourceに入っている画像を読み込んで、高さを取得
		$source_height = imagesy($source);

			//$flagが0だった場合
			if($flag == 0){

				//大幅に分割の場合
				//分割する際の高さを定義
				$height = floor($image_size["piece_height"] * 10);
				//分割する際の横幅を定義
				$width = floor($image_size["piece_width"] * 10);

			}else{

				//細かく分割の場合
				//分割する際の高さを定義
				$height = $image_size["piece_height"];
				//分割する際の横幅を定義
				$width = $image_size["piece_width"];

			}


			//$sourceの高さ/指定された高さのサイズが$rowより高さがあったら処理
    			for($row = 0; $row < $source_height / $height; $row++){

				//$sourceの幅/指定された幅のサイズが$colより幅があったら処理
				for($col = 0; $col < $source_width / $width; $col++){

					//$colと$rowを対象の値として、指定された形式に基づいた文字列を作成
        				$filename = sprintf($path."img%02d_%02d.jpg", $row,$col);
					//指定した横幅、高さをもとに画像を生成
        				$new_image = @imagecreatetruecolor($width,$height);

					//再サンプリング
        				@imagecopyresized(
							//背景画像
							$new_image ,
							//コピー元画像
							$source ,
							//背景画像の x 座標
							0 ,
							//背景画像の y 座標
							0 ,
							//コピー元画像の x 座標
        						$col * $width ,
							//コピー元画像の ｙ 座標
							$row * $height ,
							//背景画像の幅
							$width ,
							//背景画像の高さ
							$height,
							//コピー元画像ファイルの幅
        						$width ,
							//コピー元画像ファイルの高さ
							$height
						);

						//jpeg形式で画像を保存
						@imagejpeg($new_image,$filename);

					//new_imageの破棄
    					@imagedestroy($new_image);
    				}
			}

	}

?>