<?php

ini_set('memory_limit', '-1');
ini_set("gd.jpeg_ignore_warning", 1);


/* ���ʏ����Ăяo�� */

require_once 'common.php';

/* �ʏ�o�� */

	//��܂��ɕ��������摜�����Ɖ摜�̃T�C�Y�ɍ��킹�đg�ݗ��Ă�
	function ImageSetUp($dir,$outfile,$flag){

			//$flag��3��������
			//�`��ς������U�C�N�A�[�g���o�͂���ꍇ
			if($flag == 2){
				//png�̏ꍇ
				//$quality���`
				$quality = 9;
			}else{
				//jpg�̏ꍇ
				//$quality���`
				$quality = 100;
			}

		//�摜�t�@�C���̏��𓾂�
		list($targetsize_x,$targetsize_y) = @getimagesize("./Material/resize.jpg");

		//�擾���������Q�Ƃ��ĉ摜�𐶐�����
		$new_image = @imagecreatetruecolor($targetsize_x,$targetsize_y);

			//$flag��2��������
			//�`��ς������U�C�N�A�[�g���o�͂���ꍇ
			if($flag == 2){
				//�u�����h���[�h�𖳌��ɂ���
				@imagealphablending($new_image, false);
				//���S�ȃA���t�@�`���l������ۑ�����t���O��on�ɂ���
				@imagesavealpha($new_image, true);
			}

		//common.php��create_image�֐����Ăяo��
		$large_dir = create_image($dir);

		//$x���`
		$x = 0;
		//$y���`
		$y = 0;
		//$index���`
		$index = 0;

			//�������I���܂Ŗ������[�v������
			while(true){

				//$large_dir�̔z��ɓ����Ă���摜��ǂݍ���ŁA�����`
				$width = @imagesx($large_dir[$index]);
				//$large_dir�̔z��ɓ����Ă���摜��ǂݍ���ŁA�������`
				$height = @imagesy($large_dir[$index]);

				//�摜���ăT���v�����O
				@imagecopy(
					$new_image,
					$large_dir[$index],
					$x,
					$y,
					0,
					0,
					$width,
					$height
				);


			//�V���������`
			$x = $x + $width;
				//�t�@�C�����̉摜�̕������݂̕��ȏ�̏ꍇ
				if($x >= $targetsize_x){
					//�V����$x���`
					$x = 0;
					//�V�����������`
					$y = $y + $height;

						//�t�@�C�����̉摜�̍��������݂̍����ȏ�̏ꍇ
						if($y >= $targetsize_y){
							//�������I��点��
							break;
						}
				}

			//���̔z��Ɉڂ�
			$index++;

				//���݂̔z��̃L�[��$large_dir�̔z��ɓ����Ă���摜�����傫���ꍇ
				if($index >= count($large_dir)){
					//�V����$index���`
					$index = 0;
				}
		}

		//�摜��ۑ�����
		//$flag��2�̏ꍇ
		if($flag == 2){

		//�`��ς��ďo��
		//png�`���ŉ摜��ۑ�
		@imagepng($new_image,$outfile,$quality);

		}else{

		//�ʏ�o��
		//�F��ς��ďo��
		//jpeg�`���ŉ摜��ۑ�
		@imagejpeg($new_image,$outfile,$quality);

		}

		//$new_image��j��
		@imagedestroy($new_image);
	}


	//���U�C�N�A�[�g�̌��ƂȂ����摜�ƃ��U�C�N�A�[�g�̉摜����������
	function ImageSynthesis($back,$alpha,$filename){

		//$quality���`
		$quality = 9;

		//��̉摜�𐶐�
		//�w�i�ƂȂ�摜
		$image_back = @imagecreatefromjpeg($back);
		//��̉摜�𐶐�
		//���߂�����摜
		$image_alpha = @imagecreatefrompng($alpha);

		//�w�i�ƂȂ�摜�̏��𓾂�
		list($width, $height, $type) = @getimagesize($back);

		//�u�����h���[�h�𖳌��ɂ���
		@imagealphablending($image_alpha,false);
		//���S�ȃA���t�@�`���l������ۑ�����t���O��on�ɂ���
		@imagesavealpha($image_alpha,true);

		//�ăT���v�����O
		@imagecopymerge(
			//�w�i�摜
			$image_back,
			//�R�s�[���摜
			$image_alpha,
			//�w�i�摜�� x ���W
			0,
			//�w�i�摜�� y ���W
			0,
			//�R�s�[���� x ���W
			0,
			//�R�s�[���� y ���W
			0,
			//�R�s�[���摜�t�@�C���̕�
			$width,
			//�R�s�[���摜�t�@�C���̍���
			$height,
			//���ߓx(%)
			30
		);

		//jpeg�`���ŉ摜��ۑ�
		@imagepng($image_back,$filename,$quality);

		//$image_back��j��
		@imagedestroy($image_back);
		//$image_alpha��j��
		@imagedestroy($image_alpha);

	}

?>