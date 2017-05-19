<?php


/* 複数並列で実行します。*/
function multi_execute($urlList, $data ,$timeout = 0) {

	//$urlListと$dataに何もなかったらfalseを返す
	if (empty($urlList) && empty($data)) {
		//falseが返る
		return false;
	}

	//$chListを定義
	$chList = array();

	//格納されているURLの分、処理を回す
	foreach($urlList as $url) {

		//初期化
		$ch = curl_init();
		//取得するURLを設定
		curl_setopt($ch, CURLOPT_URL, $url);
		//ヘッダーの内容は出力しない
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//"Location:"があった場合 ヘッダーの内容をたどる
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		//HTTPのリダイレクト先を追いかける最大値
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		//POSTするデータを送る
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//出力結果を何も加工せずに返す
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//実行にかけられる時間の最大値 0は無制限
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		//接続の試行を待ち続ける秒数 0は無制限
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		//$chListに格納する
		$chList[] = $ch;

	}

	//$chListに何もなかったらfalseを返す
	if (empty($chList)) {
		//falseを返す
		return false;
	}

	//複数の cURL ハンドルを非同期で実行できるようにする
	$mh = curl_multi_init();
	//格納されてるハンドルの分、処理を回す
	foreach((array)$chList as $ch) {

		//通常のハンドルを追加する
		curl_multi_add_handle($mh,$ch);

	}

	//$runningを定義する
	$running = null;
	//$mhの数分、処理をする。
	do {

		//現在のハンドルからサブ接続を開始
		curl_multi_exec($mh, $running);
		//マイクロ秒単位で実行を遅延する
		usleep(1000);

	//$runningが0より小さいので処理を続ける
	} while ($running > 0);


	//格納されてるハンドルの分、処理を回す
	foreach((array)$chList as $ch) {

		//ハンドルを削除する
		curl_multi_remove_handle($mh, $ch);

	}

	//ハンドルのセットを閉じる
	curl_multi_close($mh);

        // trueが返る
	return true;

}