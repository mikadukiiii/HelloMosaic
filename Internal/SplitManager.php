<?php

require_once 'setting.php';
require_once 'common.php';

	//������x�点��
	sleep(2);

	//GET���M�Ŏ󂯎�����ϐ����`
	$count = $_GET['id'];

	//GET���M����󂯎�����l��$piece_width�ɒ�`
	$piece_width = $_POST['piece_width'];

	//GET���M����󂯎�����l��$piece_height�ɒ�`
	$piece_height = $_POST['piece_height'];

	//GET���M����󂯎�����l��$pattern�ɒ�`
	$pattern = $_POST['pattern'];

	//����$flag���`
	$flag = 1;

	$image_date = array('piece_width' => $piece_width , 'piece_height' => $piece_height);

	//common.php��load_dir�֐����Ăяo��
	$image = load_dir("../LargeSplit/");

	//common.php��create_dir�֐����Ăяo��
	$path = create_dir('../SmallSplit/');

	//common.php��create_dir�֐����Ăяo��
	$path2 = create_dir('../MaterialSmallSplit/');

	//common.php��create_dir�֐����Ăяo��
	$path = create_dir($path."split".($count + 1)."/");
	$change_path = create_dir($path2."split".($count + 1)."/");

	//setting.php��SmallSplitImage�֐����Ăяo��
	SplitImage($image[$count],$path,$image_date,$pattern,$flag);
	SplitImage($image[$count],$change_path,$image_date,$pattern,$flag);

?>