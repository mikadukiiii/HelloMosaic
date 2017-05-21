<?php

require_once 'common.php';

	//ファイルのアップロード数を数える
	$upload_count = count($_FILES['files']['tmp_name']);

	//ファイルのアップロード数分処理する
	for($count = 0; $upload_count > $count; $count++){

		//画像がアップロードされているかの確認
		if(is_uploaded_file($_FILES['files']['tmp_name'][$count])){

				//日付(月と日)と時間帯(時と分と秒)を取得
				$date = date('njGis');

				//ソースを作る
				$path = create_dir('../Source/Photo/');

				//ファイルの保存先とファイル名を定義
				$uploadfile = $path.$date.$_FILES['files']['name'][$count];

				//エラーがなかった場合
				if($_FILES['files']['error'][$count] === 0) {

	    				//先ほど定義した$uploadfileを名前としてサーバに画像を保存
	    				if(move_uploaded_file($_FILES['files']['tmp_name'][$count], $uploadfile)){
						chmod($uploadfile, 0777);
					}

				}else{

					//エラーページに飛ばす
					header("location: error.php");

				}

		}
	}

?>