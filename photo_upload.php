<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<!-- CSSを呼び出す  -->
<link href="Internal/style.css" rel="stylesheet" type="text/css">
<title>Hello Mosaic!!</title>
<!-- jqueryを使用するため呼び出す  -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<!-- 使用される画像をプレビューとして表示する -->
<script type="text/javascript">
$(function(){
	//ファイルがドロップアウトしたときの処理
	$('#drag-area').bind('drop', function(e){
		// デフォルトの挙動を停止
		e.preventDefault();

		// ファイル情報を取得
		var files = e.originalEvent.dataTransfer.files;

		uploadFiles(files);

		}).bind('dragenter', function(){

		// デフォルトの挙動を停止
		return false;

		}).bind('dragover', function(){

		// デフォルトの挙動を停止
		return false;

	});

	//ダミーボタンが押された時の処理
	$('#btn').click(function() {
		//ダミーボタンとinput[type="file"]を連動
		$('input[type="file"]').click();
	});

	$('input[type="file"]').change(function(){

		//ファイル情報を取得
		var files = this.files;

		uploadFiles(files);

	});

	});

	function uploadFiles(files) {

		// FormDataオブジェクトを用意
		var fd = new FormData();

		// ファイルの個数を取得
		var filesLength = files.length;

	  	// ファイル情報を追加
	  	for (var i = 0; i < filesLength; i++) {
	    		fd.append("files[]", files[i]);
		}

		// Ajaxでアップロード処理をするファイルへ内容渡す
		$.ajax({
			url: 'Internal/upload2.php',
			type: 'POST',
			data: fd,
			processData: false,
			contentType: false,

			success: function(data) {
				alert("アップロードされました");
			}
		});
	}
</script>
</head>

<body>
<div class="back2">
 最大20枚まで同時アップロード可
 20枚以上アップロードされた場合、20枚以降は切り捨てられます
<div id="drag-area" style="border-style: dotted; background-color: #dfd7cc;">
  <p style="background-color: #dfd7cc">素材となる画像をドロップ</p>
  <p style="background-color: #dfd7cc">または</p>
  <div class="btn-group" style="background-color: #dfd7cc">
    <input type="file" multiple="multiple" style="display:none;" name="files"/>
    <button id="btn">ファイルを選択</button>
  </div>
</div>
</div>
</body>

</html>