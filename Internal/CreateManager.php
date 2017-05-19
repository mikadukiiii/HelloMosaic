<?php

require_once 'create.php';
require_once 'common.php';

	//処理を遅らせる
	sleep(2);

	$count = $_GET['id'];
	$type = $_POST['type'];
	$min = $_POST['min'];
	$max = $_POST['max'];


	/*モザイクアートの素材となる画像フォルダを数える*/
	//photo_countの定義
	$photo_count = 0;
	//common.phpのcount_dir関数を呼び出す
	$photo_count = count(count_dir("../Material/photo_resize*",$photo_count));


	//ランダムで素材フォルダが選ばれるようにする
	$random = photo_random($photo_count);

	//photo_randomを定義
	$photo_random = 0;
	//flagを定義
	$flag = 0;

	//common.phpのload_dir関数を呼び出す
	//指定されたフォルダを読み込み$source配列に格納
	$source = load_dir("../SmallSplit/split".$count."/");

	//create.phpのcolors_detection関数を呼び出す
	//$source配列に格納されている画像の色情報を取得し、$source_colors配列に格納
	$source_colors = colors_detection($source,$type);

	//create.phpのsubstitution関数を呼び出す
	//モザイクアートの元となる画像をと素材の画像を置換する
	substitution("../Material/photo_resize".$random[$photo_random]."/",$source_colors,$source,$photo_random,$random,$photo_count,$min,$max,$type,$flag);

?>