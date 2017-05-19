<?php

		//画像がアップロードされているかの確認
		if(is_uploaded_file($_FILES['photo']['tmp_name'])){

			//画像ファイルの情報を得る
			list($width, $height, $type) = @getimagesize($_FILES['photo']['tmp_name']);

				//拡張子によって処理を変える
				switch($type){

					//jpg形式の場合
					case IMAGETYPE_JPEG:
						//ファイルの保存先とファイル名を定義
						$uploadfile = './Source/original.jpg';
						//処理を終わらせる
						break;

					//png形式の場合
					case IMAGETYPE_PNG:
						//ファイルの保存先とファイル名を定義
						$uploadfile = './Source/original.png';
						//処理を終わらせる
						break;

					//gif形式の場合
					case IMAGETYPE_GIF:
						//ファイルの保存先とファイル名を定義
						$uploadfile = './Source/original.gif';
						//処理を終わらせる
						break;

					//その他の形式の場合
					default:
						//画像の拡張子を取得
						$image_type = @image_type_to_extension($type,false);
						//ファイルの保存先とファイル名を定義
						$uploadfile = './Source/original'.$image_type;

				}

				//エラーがなかった場合
				if($_FILES['photo']['error'] === 0) {

	    				//先ほど定義した$uploadfileを名前としてサーバに画像を保存
	    				if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)){
						chmod($uploadfile, 0777);
					}

				}else{

					//エラーページに飛ばす
					header("location: error.php");

				}

		}

?>