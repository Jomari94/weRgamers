<?php

namespace app\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "games".
 *
 * @property integer $id
 * @property string $name
 * @property string $genre
 * @property string $released
 * @property string $developers
 *
 * @property GamesPlatforms[] $gamesPlatforms
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['released'], 'safe'],
            [['released'], 'date', 'format'=>'php:Y-m-d'],
            [['name', 'genre', 'developers'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'genre' => Yii::t('app', 'Genre'),
            'released' => Yii::t('app', 'Released'),
            'developers' => Yii::t('app', 'Developers'),
            'imageFile' => Yii::t('app', 'Cover'),
        ];
    }

    /**
     * Guarda una imagen en covers
     * @return Boolean
     */
    public function upload()
    {
        if ($this->validate(['imageFile'])) {
            $nombre = Yii::getAlias('@covers/')
                . $this->id . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($nombre);
            Image::thumbnail($nombre, null, 500)
                ->save($nombre);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamePlatform()
    {
        return $this->hasMany(GamePlatform::className(), ['id_game' => 'id'])->inverseOf('idGame');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlatforms()
    {
        return $this->hasMany(Platform::className(), ['id' => 'id_platform'])->via('gamePlatform');
    }
}
