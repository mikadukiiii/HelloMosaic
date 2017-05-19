<?php

ini_set('memory_limit', '-1');

require_once 'setting.php';
require_once 'common.php';

	//GET送信から受け取った値を$classに定義
	$class = $_POST['class'];

	//GET送信から受け取った値を$numberに定義
	$number = $_GET['id'];

	//GET送信から受け取った値を$piece_widthに定義
	$piece_width = $_POST['piece_width'];

	//GET送信から受け取った値を$piece_heightに定義
	$piece_height = $_POST['piece_height'];

	//POST送信から受け取った値を$patternに定義
	$pattern = $_POST['pattern'];

	//POST送信から受け取った値を$dirに定義
	$dir = $_POST['dir'];

	$path = '../Material/photo_resize'.$number.'/';

	//common.phpのload_dir関数を呼び出す
	$photo_dir = load_dir('../'.$dir);

	$image_date = array('piece_width' => $piece_width , 'piece_height' => $piece_height);

	$flag = 1;

	//$classの数分、処理を行う
	for($count = (($class * $number) - $class); $count < ($class * $number); $count++){

		$name = $count + 1;

		//配列の添字を$dir_countとして定義
		$dir_count = $_POST[$count];

		//$nameを$countを加算したのに定義する
		//setting.phpのResizeImage関数を呼び出す
		ResizeImage($photo_dir[$dir_count],$name,$path,$image_date,$pattern,$flag);
		//setting.phpのPhotoRotation関数を呼び出す
		PhotoRotation($path,$name);

	}

?>