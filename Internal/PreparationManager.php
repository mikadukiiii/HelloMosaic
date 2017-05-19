<?php

ini_set('memory_limit', '-1');

require_once 'preparation.php';
require_once 'common.php';

	//GET送信から受け取った値を$typeに定義
	$type = $_POST['type'];
	//GET送信から受け取った値を$numberに定義
	$number = $_GET['id'];


	//$typeに応じて処理を行う
	switch($type){

		/*モザイクアートを通常出力する*/
		case 1:

		//$flagを定義
		$flag = 0;

		//preparation.phpのImageChangeを呼び出す
		ImageChange('../SmallSplit/split'.$number.'/',$number,$flag);

		break;


		/*色を変えたモザイクアートを出力する*/
		case 2:

		//$flagを定義
		$flag = 1;

		//preparation.phpのImageChangeを呼び出す
		ImageChange('../ColorSmallSplit/split'.$number.'/',$number,$flag);

		break;


		/*形を変えたモザイクアートを出力する*/
		case 3:

		//$flagを定義
		$flag = 2;

		//preparation.phpのImageChangeを呼び出す
		ImageChange('../FormSmallSplit/split'.$number.'/',$number,$flag);

		break;


		/*モザイクアートを通常出力する(透過あり)*/
		case 4:

		//$flagを定義
		$flag = 0;

		//preparation.phpのMosaicSynthesisを呼び出す
		MosaicSynthesis('../SmallSplit/split'.$number.'/','../MaterialSmallSplit/split'.$number.'/');

		//preparation.phpのImageChangeを呼び出す
		ImageChange('../SmallSplit/split'.$number.'/',$number,$flag);

		break;


		/*色を変えたモザイクアートを出力する(透過あり)*/
		case 5:

		//$flagを定義
		$flag = 1;

		//preparation.phpのMosaicSynthesisを呼び出す
		MosaicSynthesis('../ColorSmallSplit/split'.$number.'/','../ColorMaterialSplit/split'.$number.'/');

		//preparation.phpのImageChangeを呼び出す
		ImageChange('../ColorSmallSplit/split'.$number.'/',$number,$flag);

		break;


		/*形を変えたモザイクアートを出力する(透過あり)*/
		case 6:

		//preparation.phpのMosaicSynthesisを呼び出す
		MosaicSynthesis('../SmallSplit/split'.$number.'/','../MaterialSmallSplit/split'.$number.'/');

		break;

	}

?>