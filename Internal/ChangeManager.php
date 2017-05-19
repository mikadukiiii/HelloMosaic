<?php

ini_set('memory_limit', '-1');

require_once 'change.php';
require_once 'common.php';

	//GET送信から受け取った値を$typeに定義
	$type = $_POST['type'];
	//GET送信から受け取った値を$numberに定義
	$number = $_GET['id'];

	//$typeに定義されている数字が2だったら
	if($type == 2){

		//色を変えて出力
		//GET送信から受け取った値を$redに定義
		$red = $_POST['red'];
		//GET送信から受け取った値を$greenに定義
		$green = $_POST['green'];
		//GET送信から受け取った値を$blueに定義
		$blue = $_POST['blue'];

	}else{

		//形を変えて出力
		//GET送信から受け取った値を$maskに定義
		$mask = $_POST['mask'];

	}

	//$typeに応じて処理を行う
	switch($type){

		/*色を変えたモザイクアートを出力する*/
		case 2:

		//change.phpのcolor_image関数を呼び出す
		color_image($red,$green,$blue,$number);

		break;


		/*形を変えたモザイクアートを出力する*/
		case 3:

		//change.phpのform_image関数を呼び出す
		form_image($mask,$number);

		break;

	}

?>