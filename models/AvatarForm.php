<?php

namespace app\models;

use Yii;
use phpDocumentor\Reflection\Types\Boolean;
use yii\base\Model;
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
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
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
     * @return Boolean
     */
    public function upload()
    {
        if ($this->validate()) {
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
