<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

class AvatarForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'image', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024*8],
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Avatar',
        ];
    }

    /**
     * Guarda una imagen en avatars
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            $avatars = Yii::getAlias('@avatars');
            $files = FileHelper::findFiles($avatars);

            if (isset($files[0])) {
                foreach ($files as $file) {
                    $archivo = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
                    $nombre = substr($archivo, 0, strlen($archivo) - 4);
                    if (strlen($nombre) === 1 && intval($nombre) === Yii::$app->user->id) {
                        unlink(Yii::getAlias('@app/web/' . $file));
                    }
                }
            }

            $nombre = Yii::getAlias('@avatars/')
                . \Yii::$app->user->id . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($nombre);
            Image::thumbnail($nombre, 225, 225)
                ->save($nombre, ['quality' => 80]);
            return true;
        } else {
            return false;
        }
    }
}
