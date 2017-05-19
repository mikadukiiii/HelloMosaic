<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<!-- CSSを呼び出す  -->
<link href="Internal/style.css" rel="stylesheet" type="text/css">
<title>Hello Mosaic!!</title>
<?php

/*処理の呼び出し*/

ini_set( 'display_errors', 1 );

require_once 'Internal/ParallelManager.php';

/*GETから送信された情報を受け取る*/
//画像の置換をする変換方法の数字を定義
$type = $_GET['id'];
//画像のしきい値の最小値を定義
$min = $_GET['first'];
//画像のしきい値を最大値を定義
$max = $_GET['last'];

//ParallelManager.phpを呼び出す
MosaicArtCreate($type,$min,$max);

?>
<script type="text/javascript">
<!-- サンプル画像を別窓で呼び出し  -->
function ImageUp($image) {
<!-- サンプル画像を別窓で呼び出し  -->
window.open($image ,"Sample","width=4000");
}
</script>
</head>

<body>
<div class="title" style="width: 728px">Hello Mosaic!!</div><br/><br/>
<div class="text">
通常出力→普通に画像を出力します。<br/>
色を変えて出力→季節に応じて、その月に合った色を出力します。 出来上がり例:<a href="javascript:ImageUp('./Source/Sample/color.jpg');">Sample</a><br/>
形を変えて出力→ランダム(全6種類)で形を変えて、出力をします。 出来上がり例:<a href="javascript:ImageUp('./Source/Sample/form.png');">Sample</a><br/>
</div><br/>
<div class="back2">
<a class="button2" href="completion.php?id=1">通常出力</a>
<a class="button2" href="completion.php?id=2">色を変えて出力</a>
<a class="button2" href="completion.php?id=3">形を変えて出力</a>
</div>
</body>

</html>
