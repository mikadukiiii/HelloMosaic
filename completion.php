<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link href="Internal/style.css" rel="stylesheet" type="text/css">
<title>Hello Mosaic!!</title>
<?php

ini_set( 'display_errors', 1 );

require_once 'Internal/OutputManager.php';

//モザイクアートとなる画像の定義
$image = "Source/original.jpg";

//出力のしかたを定義
$type = $_GET['id'];

		//出力のタイプによって分岐する
		switch ($type){

			//通常出力の場合
			case 1:

				//OutputManager.phpのMosaicActOutput関数を呼び出す
				$filename = MosaicActOutput();

				//処理を終わらせる
				break;

			//色を変えて出力の場合
			case 2:

				//OutputManager.phpのColorMosaicActOutput関数を呼び出す
				$filename = ColorMosaicActOutput();

				//処理を終わらせる
				break;

			//形を変えて出力の場合
			case 3:

				//OutputManager.phpのFormMosaicActOutput関数を呼び出す
				$filename = FormMosaicActOutput();

				//処理を終わらせる
				break;

		}

?>
</head>
<body>
<div class="title" style="width: 728px">Hello Mosaic!!</div><br/><br/>
<div style="float:left; margin: 50px;">
<div class="text" style="background-color: #ffffff;">Before</div><br/>
<img alt="モザイクアートの元の画像" height="700" src="<?php echo $image; ?>" width="500">
</div>
<div style="float:left; margin: 50px;">
<div class="text" style="background-color: #ffffff;">After</div><br/>
<img alt="モザイクアート後の画像" height="700" src="<?php echo $filename; ?>" width="500">
</div>
<br clear="both"/>