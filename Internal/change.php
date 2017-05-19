<?php

	//現在の月から季節を取得し、イメージカラーで置き換える
	function color_image($red,$green,$blue,$number){

		//$flagを定義
		$flag = 0;

		//common.phpのcreate_dir関数を呼び出す
		$path = create_dir("../ColorSmallSplit/split".$number."/");
		//common.phpのcreate_dir関数を呼び出す
		$path2 = create_dir("../ColorMaterialSmallSplit/split".$number."/");
		//common.phpのload_dir関数を呼び出す
		$source = load_dir("../SmallSplit/split".$number."/");
		//common.phpのload_dir関数を呼び出す
		$material = load_dir("../MaterialSmallSplit/split".$number."/");

			//ファイル内の画像分、処理を回す
			foreach((array)$source as $sources){

				//output.phpのColorChangeImageを呼び出す
				ColorChangeImage($sources,$path,$red,$green,$blue,$flag);

			}

			//ファイル内の画像分、処理を回す
			foreach((array)$material as $materials){

				//output.phpのColorChangeImageを呼び出す
				ColorChangeImage($materials,$path2,$red,$green,$blue,$flag);

			}


		//output.phpのMosaicSynthesisを呼び出す
		MosaicSynthesis("../ColorSmallSplit/split".$number."/","../ColorMaterialSmallSplit/split".$number."/");

	}


	//テキストファイルのパスを指定する
	function ColorSelect(){

		//現在の月を取得して定義
		$data = date('n');

			//月が3～5月のいずれかかを確認
			if($data == 3 || $data == 4 || $data == 5){

				//春の場合
				//$seasonを1として定義
				$season = 1;

			//月が6～8月のいずれかかを確認
			}else if($data == 6 || $data == 7 || $data == 8){

				//夏の場合
				//$seasonを2として定義
				$season = 2;

			//月が9～11月のいずれかかを確認
			}else if($data == 9 || $data == 10 || $data == 11){

				//秋の場合
				//$seasonを3として定義
				$season = 3;

			//月が12～2月のいずれかかを確認
			}else if($data == 12 || $data == 1 || $data == 2){

				//冬の場合
				//$seasonを4として定義
				$season = 4;

			}

		//$seasonの数字によって処理を変える
		switch ($season){

			//春の場合
			case 1:
			//$fileを春色のRGB値が保存されてるテキストファイルに定義
  			$file = './Source/RGBText/Spring.txt';

			//処理を終わる
			break;

			//夏の場合
			case 2:
			//$fileを夏色のRGB値が保存されてるテキストファイルに定義
			$file = './Source/RGBText/Summer.txt';

			//処理を終わる
			break;

			//秋の場合
			case 3:
			//$fileを秋色のRGB値が保存されてるテキストファイルに定義
			$file = './Source/RGBText/Autumn.txt';

			//処理を終わる
			break;

			//冬の場合
			case 4:
			//$fileを冬色のRGB値が保存されてるテキストファイルに定義
			$file = './Source/RGBText/Winter.txt';

			//処理を終わる
			break;

		}

		//テキストファイルの中身を1行ずつ配列に格納
		$array = @file($file, FILE_IGNORE_NEW_LINES);
		//配列をシャッフル
		shuffle($array);
		//配列の先頭に格納された内容を$colorに定義
		$color = reset($array);

		//取得したRGB値を返す
		return $color;

	}


	//R,G,Bで値を指定してファイル内の画像の色を変える
	function ColorChangeImage($image,$path,$red,$green,$blue,$flag){

		//空の画像を生成
		$new_image = @imagecreatefromjpeg($image);

			//画像が生成されており、imagefilter関数が正常に動いた場合
			if($new_image && @imagefilter($new_image, IMG_FILTER_COLORIZE, $red, $green, $blue)){

				//画像を保存する
				//$flagが0の場合
				if($flag == 0){
					//SmallSplit内の画像の色を変える場合
					//jpeg形式で保存
    					@imagejpeg($new_image,$path.basename($image));
				}else{
					//resize.jpgの画像の色を変える場合
					//jpeg形式で保存
					@imagejpeg($new_image,$path);
				}
			}

				//$new_imageを破棄
				@imagedestroy($new_image);

	}

	//mask画像を適切な形にリサイズする
	function FormResize($mask){

		//画像ファイルの情報を得る
		list($width,$height) = @getimagesize('./Source/Mask/'.$mask.'.png');

		//画像ファイル内の1つの画像を取り出す(絶対に存在する画像を引っ張り出す)
		$image = @imagecreatefromjpeg('./SmallSplit/split1/img00_00.jpg');

		//リサイズするmask画像を定義
		$mask_image = @imagecreatefrompng('./Source/Mask/'.$mask.'.png');

		//mask画像の幅を定義
		$new_width    = @imagesx($image);
		//mask画像の高さを定義
		$new_height   = @imagesy($image);

		//上記の幅、高さを参照して新しい画像を生成する
		$new_image = @imagecreatetruecolor($new_width, $new_height);

		//ブレンドモードを無効にする
		@imagealphablending($new_image,false);
		//完全なアルファチャネル情報を保存するフラグをonにする
		@imagesavealpha($new_image,true);

		//画像を再サンプリング
		@imagecopyresampled(
				//背景画像
				$new_image,
				//コピー元画像
				$mask_image,
				//背景画像の x 座標
				0,
				//背景画像の y 座標
				0,
				//コピー元画像の x 座標
				0,
				//コピー元画像の y 座標
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

		//png形式で画像を保存
		@imagepng($new_image,'./Source/Mask/'.$mask.'_resize.png');

		//$imageを破棄
		@imagedestroy($image);
		//$new_imageを破棄
		@imagedestroy($new_image);

	}


	//ランダムに形を変形させる。
	function form_image($mask,$number){

				//common.phpのcreate_dir関数を呼び出す
				$path = create_dir("../FormSmallSplit/split".$number."/");
				//common.phpのload_dir関数を呼び出す
				$source = load_dir("../SmallSplit/split".$number."/");

					//ファイル内の画像分、処理を回す
					foreach((array)$source as $sources){

						//output.phpのFormChangeImageを呼び出す
						FormChangeImage($sources,$path,$mask);

					}


	}


	//ユーザから選択された値を指定してファイル内の画像を変える
	function FormChangeImage($image_name,$path,$form){

		//空の画像を作成
		//切り取られる画像
		$image = @imagecreatefromjpeg($image_name);
		//空の画像を作成
		//切り取る形をした透過画像
		$mask  = @imagecreatefrompng('../Source/Mask/'.$form."_resize.png");

		//切り取られる画像の幅を定義
		$width    = @imagesx($image);
		//切り取られる画像の高さを定義
		$height   = @imagesy($image);

		//切り取る形をした透過画像の幅を取得
		$mask_width  = @imagesx($mask);
		//切り取る形をした透過画像の高さを取得
		$mask_height = @imagesy($mask);

		//切り取る形をした透過画像の幅を新しい幅として定義
		$new_width    = $mask_width;
		//切り取る形をした透過画像の幅を新しい高さとして定義
		$new_height   = $mask_height;
		//上記の幅、高さを参照して新しい画像を生成する
		$new_image = @imagecreatetruecolor($new_width, $new_height);

		//ブレンドモードを無効にする
		@imagealphablending($new_image, false);
		//完全なアルファチャネル情報を保存するフラグをonにする
		@imagesavealpha($new_image, true);
		//画像で使用する色を透過度を指定して生成
		$transparent = @imagecolorallocatealpha($new_image, 0, 0, 0, 127 );
		//$transparentで定義した色で画像を塗りつぶす
		@imagefill($new_image, 0, 0, $transparent);


		//中心から切り抜くための調整
		$top     = round(($width - $mask_width) / 2);
		$left    = round(($height - $mask_height) / 2);

			//定義した新しいサイズが$yより高さがあったら処理する
			for($y = 0; $y < $new_height; $y++){

			//定義した新しいサイズが$xより幅があったら処理する
				for($x = 0; $x < $new_width; $x++){

					//ピクセルの色のインデックスを取得する
					$rgb     = @imagecolorat($mask, $x, $y);
					//カラーインデックスからカラーを取得する
					$index   = @imagecolorsforindex($mask, $rgb);
					//透過情報を$alphaに定義
					$alpha   = $index['alpha'];

					//ピクセルの色のインデックスを取得する
        				$current = @imagecolorat($image, $x + $top, $y + $left);
					//カラーインデックスからカラーを取得する
        				$index   = @imagecolorsforindex($image, $current);
					//画像で使用する色を透過度を指定して画像を生成
        				$color   = @imagecolorallocatealpha($new_image, $index['red'], $index['green'], $index['blue'], $alpha);
					//点を生成
        				@imagesetpixel($new_image, $x, $y, $color);
				}
			}

		//png形式で画像を保存
		@imagepng($new_image,$path.basename($image_name,".jpg").".png");

		//$new_imageを破棄
		@imagedestroy($new_image);
		//$imageを破棄
		@imagedestroy($image);
		//$maskを破棄
		@imagedestroy($mask);

	}


?>