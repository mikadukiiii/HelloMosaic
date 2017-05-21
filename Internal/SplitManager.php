<?php

require_once 'setting.php';
require_once 'common.php';

	//処理を遅らせる
	sleep(2);

	//GET送信で受け取った変数を定義
	$count = $_GET['id'];

	//GET送信から受け取った値を$piece_widthに定義
	$piece_width = $_POST['piece_width'];

	//GET送信から受け取った値を$piece_heightに定義
	$piece_height = $_POST['piece_height'];

	//GET送信から受け取った値を$patternに定義
	$pattern = $_POST['pattern'];

	//次の$flagを定義
	$flag = 1;

	$image_date = array('piece_width' => $piece_width , 'piece_height' => $piece_height);

	//common.phpのload_dir関数を呼び出し
	$image = load_dir("../LargeSplit/");

	//common.phpのcreate_dir関数を呼び出し
	$path = create_dir('../SmallSplit/');

	//common.phpのcreate_dir関数を呼び出し
	$path2 = create_dir('../MaterialSmallSplit/');

	//common.phpのcreate_dir関数を呼び出し
	$path = create_dir($path."split".($count + 1)."/");
	$change_path = create_dir($path2."split".($count + 1)."/");

	//setting.phpのSmallSplitImage関数を呼び出す
	SplitImage($image[$count],$path,$image_date,$pattern,$flag);
	SplitImage($image[$count],$change_path,$image_date,$pattern,$flag);

?>