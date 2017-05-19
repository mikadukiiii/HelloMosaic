<?php

/* 共通処理 */

require_once 'common.php';

	//$sourceの配列を読み込んで、色情報を取得する
	function colors_detection($source,$type){

		//source_colorsを定義する
		$source_colors = array();
			//読み込んだ画像のファイル分処理する
			foreach((array)$source as $sources){

				//空の画像を作成
				$source_image = imagecreatefromjpeg($sources);

					//タイプによって分岐する
					switch($type){

						//RGBの場合
						case 1:
						//image_rgb関数を呼び出す
						$image_rgb  = image_rgb($source_image);
						//$source_imageを破棄
						imagedestroy($source_image);

						//$source_colorsの配列に取得した色情報を入れる
    						$source_colors[] = $image_rgb;

						//処理を終わらせる
						break;


						//HSVの場合
						case 2:
						//image_hsv関数を呼び出す
						$image_hsv  = image_hsv($source_image);
						//$source_imageを破棄
						imagedestroy($source_image);

						//$source_colorsの配列に取得した色情報を入れる
    						$source_colors[] = $image_hsv;

						//処理を終わらせる
						break;


						//Labの場合
						case 3:
						//image_lab関数を呼び出す
						$image_lab  = image_lab($source_image);
						//$source_imageを破棄
						imagedestroy($source_image);

						//$source_colorsの配列に取得した色情報を入れる
    						$source_colors[] = $image_lab;

						//処理を終わらせる
						break;


					}

			}

		//取得した色情報を返す
		return $source_colors;

	}


	//元画像と素材画像の色情報を比較し、近い色合いに置換していく
	function substitution($photo_dir,$source_colors,$source,$photo_random,$random,$photo_count,$min,$max,$type,$flag){

		//モザイクアートの分割された画像が格納されている$source配列のキーを定義
		$count = 0;

		//枚数を重視する際に画像を重複しないように格納する$duplication配列を定義
		$duplication =array();
		//画像を重複しないように選ばれた画像が格納されている$duplication配列のキーを定義
		$duplication_count = 0;

			//取得されている色情報の分処理する
			foreach((array)$source_colors as $color){

				//common.phpのload_dir関数を呼び出す
				$photo_colors = load_dir($photo_dir);

				//現在見ている素材の画像とモザイクアートの元となる分割した画像の色差を格納するための配列を定義
				$approximate = array();

					//取得したモザイクアートの素材画像の分処理する
					foreach((array)$photo_colors as $colors){

						//空の画像を作成
						$image = imagecreatefromjpeg($colors);

						  	//タイプによって分岐する
							switch($type){

								//RGBの場合
								case 1:
								//image_rgb関数を呼び出す
								$rgb = image_rgb($image);
								//$imageを破棄
								imagedestroy($image);

								//$nameに素材画像の名前を定義
								$name = basename($colors);

								//rgb_distance関数を呼び出す
								//$approximate[$name]に色差を格納
								$approximate[$name] = rgb_distance($color, $rgb);

								//処理を終わらせる
								break;


								//HSVの場合
								case 2:
								//要素の重要度
								$priority = array(
									//色相を定義
									'h' => 2,
									//彩度を定義
									's' => 1,
									//明度を定義
									'v' => 3
								);
								//image_hsv関数を呼び出す
								$hsv = image_hsv($image);
								//$imageを破棄
								imagedestroy($image);

								//$nameに素材画像の名前を定義
								$name = basename($colors);

								//hsv_distance関数を呼び出す
								//$approximate[$name]に色差を格納
								$approximate[$name] = hsv_distance($color, $hsv, $priority);

								//処理を終わらせる
								break;


								//Labの場合
								case 3:
								//image_lab関数を呼び出す
								$lab = image_lab($image);
								//$imageを破棄
								imagedestroy($image);

								//$nameに素材画像の名前を定義
								$name = basename($colors);

								//$distanceを定義
								$distance = 0;

									//取得されている色情報の分処理をする
									foreach((array)$color as $key => $value){
										//lab_distance関数を呼び出す
										$distance = $distance + lab_distance($value, $lab[$key]);
									}

								//$approximate[$name]に色差を格納
								$approximate[$name] = $distance;

								//処理を終わらせる
								break;


								}

					}

		//$approximate配列を昇順に並び替え
		asort($approximate);
		//配列のキーを取得して定義
		$result = array_keys($approximate);

			//$minと$maxが両方とも0だったら
			if($min == 0 && $max == 0){

				//精密さ重視の場合
				//先頭のキーを参照して配列の値を定義
				$result = reset($result);

			}else{

				//$evacuation配列を定義
				$evacuation = array();
				//$result配列を$evacuation配列に退避させる
				$evacuation = $result;

				//比較していた画像の分処理する
				foreach((array)$result as $value => $images){

					//重複した画像の分処理する
					foreach((array)$duplication as $image){

						//前に使用された画像の名前と比較していた画像の名前が一致していたら処理する
						if($images == $image){

							//重複していた画像の名前を$result配列から探す
							$key = array_search($image, $result);

							//画像の名前を$result配列から削除する
							unset($result[$key]);

							//$result配列を詰める
							$result = array_values($result);

						}

					}

				}

				//$result配列が空っぽだったら
				if(empty($result)){

					//退避していた$evacuation配列を$result配列に格納し直す
					$result = $evacuation;

						//比較していた画像の分処理する
						foreach((array)$duplication as $value => $images){

							//重複した画像の分処理する
							foreach((array)$result as $image){

								//前に使用された画像の名前と比較していた画像の名前が一致していたら処理する
								if($images == $image){

									//重複していた画像の名前を$result配列から探す
									$key = array_search($image, $duplication);

									//画像の名前を$result配列から削除する
									unset($duplication[$key]);

									//$result配列を詰める
									$duplication = array_values($duplication);

								}

							}

						}

					//併せて$duplication配列のキーを定義し直す
					$duplication_count = count($duplication);

				}

			//枚数重視の場合
			//0～4の内ランダムで取得したキーを参照して配列の値を定義
			$result = $result[mt_rand(0,4)];

				//$resultを退避させるため$cutを定義
				$cut = $result;

				//定義した画像のファイル名に_が含まれていないか確認
				if(strpos($cut,'_')){

					//含まれていたらその部分以降を削除する
					$cut = substr($cut, 0, strcspn($cut,'_'));

				}

					//重複した画像の名前を$duplication[$duplication_count]に追加する
					$duplication[$duplication_count] = $cut;

					//$duplication配列のキーを次に移す
					$duplication_count++;

					//重複した画像の別の画像(反転など)も使わせないため、処理を行う
					for($d_count = 1; $d_count <= 3; $d_count++){

						//重複した画像の名前を$duplication[$duplication_count]に追加する
						$duplication[$duplication_count] = basename($cut,".jpg")."_".$d_count.".jpg";

						//$duplication配列のキーを次に移す
						$duplication_count++;

					}

			}

		//置換する画像を$targetImageと定義
		$targetImage = $photo_dir.$result;

		//置換する画像ファイルの情報を得る
		list($image_width, $image_height) = @getimagesize($targetImage);

		//出力する画像サイズを参照して新しい画像を生成する
		$canvas = @imagecreatetruecolor($image_width, $image_height);

		//空の画像を作成
		$image = @imagecreatefromjpeg($targetImage);

		//再サンプリング
		@imagecopyresampled(
				//背景画像
				$canvas,
				//コピー元画像
				$image, 
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0, 
				//コピー元の x 座標
				0, 
				//コピー元の y 座標
				0, 
				//背景画像の幅
				$image_width,
				//背景画像の高さ
				$image_height, 
				//コピー元画像ファイルの幅
				$image_width, 
				//コピー元画像ファイルの高さ
				$image_height
		);

		//jpeg形式で画像を保存
		@imagejpeg(
			//背景画像
			$canvas, 
			//出力するファイル名
			$source[$count] , 
			//画像精度
			100
        	);

		//$canvasを破棄
		@imagedestroy($canvas);

		//$source配列のキーを次に移す
		$count++;

		//$photo_random配列のキーを次に移す
		$photo_random++;

		//common.phpのcheck_array関数を呼び出す
		$flag = check_array($random,$photo_random);

			//フラグが1だったら処理を行う
			if($flag == 1){
				//ランダムで素材フォルダが選ばれるようにする
				$random = photo_random($photo_count);
				//$photo_randomを定義
				$photo_random = 0;
				//$flagを定義
				$flag = 0;
			}

		//$photo_dirに新しく読み込む画像フォルダを定義
		$photo_dir = "../Material/photo_resize".$random[$photo_random]."/";

		}

	}


/* RGB */


	//RGB値から平均色を算出
	function image_rgb($image){

		//$imageに入っている画像を読み込んで、幅を取得
		$width = @imagesx($image);
		//$imageに入っている画像を読み込んで、高さを取得
		$height = @imagesy($image);

		//$block_widthを定義
		$block_width   = 16;
		//$block_heightを定義
		$block_height   = 16;
		//$block_widthを幅、$block_heightを高さとして画像を生成
		$block = @imagecreatetruecolor($block_width, $block_height);

		//画像を再サンプリング
		@imagecopyresampled(
				//背景画像
				$block,
				//コピー元画像
				$image,
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0,
				//コピー元の x 座標
				0,
				//コピー元の y 座標
				0,
				//背景画像の幅
				$block_width,
				//背景画像の高さ
				$block_height,
				//コピー元画像ファイルの幅
				$width,
				//コピー元画像ファイルの幅
				$height
		);

		//$redを定義
		$red   = 0;
		//$greenを定義
		$green = 0;
		//$blueを定義
		$blue  = 0;

			//$block_widthで指定されたサイズが$xより幅があったら処理
			for($x = 0; $x < $block_width; $x++){

				//$block_heightで指定されたサイズが$yより高さがあったら処理
				for($y = 0; $y < $block_height; $y++){

						//ピクセルの色のインデックスを取得する
						$index   = @imagecolorat($block, $x, $y);
						//カラーインデックスからカラーを取得する
						$rgb   = @imagecolorsforindex($block, $index);
						//返ってきた$rgbから赤の値を$redに加算して定義
						$red   = $red + $rgb['red'];
						//返ってきた$rgbから緑の値を$greenに加算して定義
						$green   = $green + $rgb['green'];
						//返ってきた$rgbから青の値を$blueに加算して定義
						$blue   = $blue + $rgb['blue'];
				}
			}

		//$averageを配列として定義
		$average = array();
		//$block_widthの幅と$block_heightの高さから$pixelを定義
		$pixel = $block_width * $block_height;
		//$redに格納されている数値/先ほど定義した$pixelをし、$averageの中に格納
		$average['red']     = round($red / $pixel);
		//$greenに格納されている数値/先ほど定義した$pixelをし、$averageの中に格納
		$average['green']   = round($green / $pixel);
		//$blueに格納されている数値/先ほど定義した$pixelをし、$averageの中に格納
		$average['blue']   = round($blue / $pixel);

		//取得した色の平均を返す
		return $average;

	}


	// 2つの座標を比較し色差を返す
	function rgb_distance($rgb1, $rgb2){

		//$distanceを定義
		$distance = 0;
		//現在見ている素材の画像の赤の値 - モザイクアートの元となる分割した画像の赤の値の絶対値を求め、$distanceに加算
		$distance = $distance + abs($rgb1['red']   - $rgb2['red']);
		//現在見ている素材の画像の緑の値 - モザイクアートの元となる分割した画像の緑の値の絶対値を求め、$distanceに加算
		$distance = $distance + abs($rgb1['green']   - $rgb2['green']);
		//現在見ている素材の画像の青の値 - モザイクアートの元となる分割した画像の青の値の絶対値を求め、$distanceに加算
		$distance = $distance + abs($rgb1['blue']   - $rgb2['blue']);

		//求めた絶対値の中で最も大きな値が返される
		return $distance;

	}



/* HSV */


	//RGB値から平均色を算出した後、HSV変換させる
	function image_hsv($image){

		//$imageに入っている画像を読み込んで、幅を取得
		$width     = @imagesx($image);
		//$imageに入っている画像を読み込んで、高さを取得
		$height    = @imagesy($image);

		//$block_widthを定義
		$block_width   = 16;
		//$block_heightを定義
		$block_height  = 16;
		//$block_widthを幅、$block_heightを高さとして画像を生成
		$block = @imagecreatetruecolor($block_width, $block_height);

		//画像を再サンプリング
		@imagecopyresampled(
				//背景画像
				$block,
				//コピー元画像
				$image,
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0,
				//コピー元の x 座標
				0,
				//コピー元の y 座標
				0,
				//背景画像の幅
				$block_width,
				//背景画像の高さ
				$block_height,
				//コピー元画像ファイルの幅
				$width,
				//コピー元画像ファイルの幅
				$height
		);

		//$redを定義
		$red   = 0;
		//$greenを定義
		$green = 0;
		//$blueを定義
		$blue  = 0;

			//$block_widthで指定されたサイズが$xより幅があったら処理
			for($x = 0; $x < $block_width; $x++){

				//$block_heightで指定されたサイズが$yより高さがあったら処理
				for($y = 0; $y < $block_height; $y++){

					//ピクセルの色のインデックスを取得する
					$index   = @imagecolorat($block, $x, $y);
					//カラーインデックスからカラーを取得する
					$rgb   = @imagecolorsforindex($block, $index);
					//返ってきた$rgbから赤の値を$redに加算して定義
					$red   = $red + $rgb['red'];
					//返ってきた$rgbから緑の値を$greenに加算して定義
					$green   = $green + $rgb['green'];
					//返ってきた$rgbから青の値を$blueに加算して定義
					$blue   = $blue + $rgb['blue'];

				}
			}

		//$averageを配列として定義
		$average = array();
		//$block_widthの幅と$block_heightの高さから$pixelを定義
		$pixel = $block_width * $block_height;
		//$redに格納されている数値/先ほど定義した$pixelをし、$average['red']の中に格納
		$average['red']     = round($red / $pixel);
		//$greenに格納されている数値/先ほど定義した$pixelをし、$average['green']の中に格納
		$average['green']   = round($green / $pixel);
		//$blueに格納されている数値/先ほど定義した$pixelをし、$average['blue']の中に格納
		$average['blue']   = round($blue / $pixel);

		//取得した色の平均をrgb2hsv関数に渡す
		return rgb2hsv($average);

	}

	//RGB値をHSV色空間に変換する
	function rgb2hsv($rgb){

		//RGBからHSVに変換の前にRGBの値を0から1の実数値にする
		//赤の値を Red / 255で求める
		$r = $rgb['red'] / 255;
		//緑の値を Green / 255で求める
		$g = $rgb['green'] / 255;
		//青の値を Blue / 255で求める
		$b = $rgb['blue'] / 255;

		//$r, $g, $bの中から最大値を取得
		$max = max($r, $g, $b);

		//$r, $g, $bの中から最小値を取得
		$min = min($r, $g, $b);

		//明度はRGBの最大値に定義
		$v = $max;

			//RGBの最大値とRGBの最小値が同じ場合
			if($max === $min){

				//色相を0で定義
				$h = 0;

			//Red、Green、Blueのなかで、いちばん値の大きいものがRedの場合
			} else if($r === $max){

				//色相を 60 *((緑の値 - 青の値) / (RGBの最大値 - RGBの最小値)) + 0で求める
				$h = 60 * ( ($g - $b) / ($max - $min) ) + 0;

			//Red、Green、Blueのなかで、いちばん値の大きいものがGreenの場合
			} else if($g === $max){

				//色相を 60 *((青の値 - 赤の値) / (RGBの最大値 - RGBの最小値)) + 120で求める
				$h = 60 * ( ($b - $r) / ($max - $min) ) + 120;

			//Red、Green、Blueのなかで、いちばん値の大きいものがBlueの場合
			} else {

				//色相を 60 *((赤の値 - 緑の値) / (RGBの最大値 - RGBの最小値)) + 240で求める
				$h = 60 * ( ($r - $g) / ($max - $min) ) + 240;

			}

		//色相が0以下の場合
		if($h < 0){

			//色相を色相 + 360で求める
			$h = $h + 360;

		}

		//明度が0以外の場合
		if($v != 0){

			//彩度をRGBの最大値 - RGBの最小値 / RGBの最大値で求める
			$s = ($max - $min) / $max;

		//明度が0の場合
		}else{

			//彩度を0で定義
			$s = 0;

		}

		//求めたHSVの値を$hsvに格納
		$hsv = array(
			//色相を定義
			"h" => $h,
			//彩度を定義
			"s" => $s,
			//明度を定義
			"v" => $v
		);

		//取得した$hsv配列を返す
		return $hsv;

	}


	// 2つの座標を比較し色差を返す
	function hsv_distance($hsv1, $hsv2, $priority){

		//現在見ている素材の画像の色相 - モザイクアートの元となる分割した画像の色相の絶対値を求める
		$dist_h = abs($hsv1['h']   - $hsv2['h']);
		//$distanceを定義
		$distance = 0;
		//$dist_h,360-$dist_hの中から最小値を求め、色相を乗算する
		$distance = $distance + min($dist_h, 360 - $dist_h) * $priority['h'];
		//現在見ている素材の画像の彩度 - モザイクアートの元となる分割した画像の彩度の絶対値を求める
		$distance = $distance + abs($hsv1['s']   - $hsv2['s']) * 180 * $priority['s'];
		//現在見ている素材の画像の明度 - モザイクアートの元となる分割した画像の明度の絶対値を求める
		$distance = $distance + abs($hsv1['v']   - $hsv2['v']) * 180 * $priority['v'];

		//求めた絶対値の中で最も大きな値が返される
		return $distance;

	}



/* Lab */


	// 画像をリサイズしピクセルごとのLab色空間上の座標を取得する
	function image_lab($image){

		//$imageに入っている画像を読み込んで、幅を取得
		$width   = @imagesx($image);
		//$imageに入っている画像を読み込んで、高さを取得
		$height  = @imagesy($image);

		//$block_widthを定義
		$block_width     = 4;
		//$block_heightを定義
		$block_height    = 4;
		//$block_widthを幅、$block_heightを高さとして画像を生成
		$block = @imagecreatetruecolor($block_width, $block_height);

		//画像を再サンプリング
		@imagecopyresampled(
				//背景画像
				$block,
				//コピー元画像
				$image,
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0,
				//コピー元の x 座標
				0,
				//コピー元の y 座標
				0,
				//背景画像の幅
				$block_width,
				//背景画像の高さ
				$block_height,
				//コピー元画像ファイルの幅
				$width,
				//コピー元画像ファイルの幅
				$height
		);

		//$labを配列として定義
		$lab = array();
		//$redを定義
		$red     = 0;
		//$greenを定義
		$green   = 0;
		//$blueを定義
		$blue    = 0;

			//$block_widthで指定されたサイズが$xより幅があったら処理
			for($x = 0; $x < $block_width; $x++){

				//$block_heightで指定されたサイズが$yより高さがあったら処理
				for($y = 0; $y < $block_height; $y++){

					//ピクセルの色のインデックスを取得する
					$index   = @imagecolorat($block, $x, $y);
					//カラーインデックスからカラーを取得する
					$rgb     = @imagecolorsforindex($block, $index);
					//rgb2lab関数を呼び出す
					//RGBからLabに変換して、$lab配列に格納する
					$lab[]   = rgb2lab( array($rgb['red'], $rgb['green'], $rgb['blue']) );

				}
			}

		//$lab配列に格納した情報を返す
		return $lab;

	}


	// rgb値をlab色空間上の座標に変換する
	function rgb2lab($rgb) {

		//rgb2xyz関数を呼び出す
		//RGBから直接Labには変換できないため、xyzにまず変換する
		$xyz = rgb2xyz($rgb);
		//xyz2lab関数を呼び出す
		//xyzからLabに変換する
		$lab = xyz2lab($xyz);

		//変換されたLabの情報を返す
		return $lab;

	}


	//RGB値をXYZ色空間上の座標に変換する
	function rgb2xyz($rgb) {

		//RGBからXYZに変換の前にRGBの値を0から1の実数値にする
		//赤の値を $rgb[0] / 255で求める
		$r = $rgb[0] / 255;
		//緑の値を $rgb[1] / 255で求める
		$g = $rgb[1] / 255;
		//青の値を $rgb[2] / 255で求める
		$b = $rgb[2] / 255;

		//RGB値からXYZ値に変換する際は逆補正を行ってスクリーン用のRGB値から本来のRGB値に戻す
		//今回はsRGBで値を求める
		//先ほど求めた赤の値($r)が0.04045以上の場合
		if($r > 0.04045){

			$r = pow(($r + 0.055) / 1.055, 2.4);

		//先ほど求めた赤の値($r)が0.04045以下の場合
		}else{

			$r = $r / 12.92;

		}

		//先ほど求めた緑の値($g)が0.04045以上の場合
		if($g > 0.04045){

			$g = pow(($g + 0.055) / 1.055, 2.4);

		//先ほど求めた緑の値($g)が0.04045以下の場合
		}else{

			$g = $g / 12.92;

		}

		//先ほど求めた青の値($b)が0.04045以上の場合
		if($b > 0.04045){

			$b = pow(($b + 0.055) / 1.055, 2.4);

		//先ほど求めた青の値($b)が0.04045以下の場合
		}else{

			$b = $b / 12.92;

		}

		//上記で計算されたXYZ値を100倍にすると、求めているXYZ値になる
		//赤の値 * 100で求める
		$r = $r * 100;
		//緑の値 * 100で求める
		$g = $g * 100;
		//青の値 * 100で求める
		$b = $b * 100;

		//$xyzを配列として定義
		$xyz = array();

		//参照:http://www.brucelindbloom.com/index.html?Eqn_RGB_XYZ_Matrix.html
		//参照で定義されているsRGBのD50を使用
		$xyz[] = $r * 0.4360747 + $g * 0.3850649 + $b * 0.1430804;
		$xyz[] = $r * 0.2225045 + $g * 0.7168786 + $b * 0.0606169;
		$xyz[] = $r * 0.0139322 + $g * 0.0971045 + $b * 0.7141733;

		//取得した$xyz配列を返す
		return $xyz;

	}


	// xyz色空間上の座標をlab色空間上の座標に変換する
	function xyz2lab($xyz) {

		//実際のCIE標準で$thresholdを定義
		$threshold = 0.008856;

		//Photoshop CS6のカラーピッカーに近似するD50を使用
		//D50とは標準となる光のこと
		//白色点のX座標を定義
		$ref_x = 0.96422;
		//白色点のY座標を定義
		$ref_y = 1.0000;
		//白色点のZ座標を定義
		$ref_z = 0.82521;

		//Xの値 / (白色点のX座標 × 100)をして、最大値を1に揃える
		$var_x = $xyz[0] / ($ref_x * 100);
		//Yの値 / (白色点のY座標 × 100)をして、最大値を1に揃える
		$var_y = $xyz[1] / ($ref_y * 100);
		//Zの値 / (白色点のZ座標 × 100)をして、最大値を1に揃える
		$var_z = $xyz[2] / ($ref_z * 100);

		//先ほど求めたXの値($var_x)が0.008856以上の場合
		if($var_x > $threshold){

			$var_x = pow($var_x, 1/3);

		//先ほど求めたXの値($var_x)が0.008856以下の場合
		}else{

			$var_x = (7.787 * $var_x) + (16 / 116);

		}

		//先ほど求めたYの値($var_y)が0.008856以上の場合
		if($var_y > $threshold){

			$var_y = pow($var_y, 1/3);

		//先ほど求めたYの値($var_y)が0.008856以下の場合
		}else{

			$var_y = (7.787 * $var_y) + (16 / 116);

		}

		//先ほど求めたZの値($var_z)が0.008856以上の場合
		if($var_z > $threshold){

			$var_z = pow($var_z, 1/3);

		//先ほど求めたZの値($var_z)が0.008856以下の場合
		}else{

			$var_z = (7.787 * $var_z) + (16 / 116);

		}

		//上記で計算されたXYZ値を使うと、求めているLabの値になる
		$l = ( 116 * $var_y ) - 16;
		$a = 500 * ( $var_x - $var_y );
		$b = 200 * ( $var_y - $var_z );


		//$labを配列として定義
		$lab = array();
		//求めたLabの値を$labに格納
		$lab = array(
			//明度を定義
			$l,
			//(マイナス方向の緑-プラス方向の赤)を示す色度を定義
			$a,
			//(プラス方向の青-マイナス方向の黄)を示す色度を定義
			$b
		);

		//取得した$lab配列を返す
		return $lab;

	}


	//2つの座標を比較し色差を返す
	function lab_distance($p1, $p2){

		//Labの色差の求め方はLabの差の2乗値の和の平方根で求められる

		//現在見ている素材の画像の明度 - モザイクアートの元となる分割した画像の明度を減算、その後に2乗をして求める
		//現在見ている素材の画像の(マイナス方向の緑-プラス方向の赤)を示す色度 - モザイクアートの元となる分割した画像の(マイナス方向の緑-プラス方向の赤)を示す色度を減算、その後に2乗をして求める
		//現在見ている素材の画像の(プラス方向の青-マイナス方向の黄)を示す色度 - モザイクアートの元となる分割した画像の(プラス方向の青-マイナス方向の黄)を示す色度を減算、その後に2乗をして求める
		//上記で求めた数字の平方根を求め、$distanceに加算
		$distance = sqrt(pow($p2[0] - $p1[0], 2) + pow($p2[1] - $p1[1], 2) + pow($p2[2] - $p1[2], 2));

		//求めた色差を返す
		return $distance;

	}
?>