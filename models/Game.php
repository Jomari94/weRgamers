<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

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
     * Escenario de creación de juego
     * @var string
     */
    const ESCENARIO_RECORD = 'create';

    /**
     * Escenario de subida de caratula
     * @var string
     */
    const ESCENARIO_UPLOAD = 'upload';

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * Variable para guardar las plataformas que tiene el modelo
     * @var string[]
     */
    public $platforms;

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
            [['released', 'platforms'], 'safe'],
            [['released'], 'date', 'format'=>'php:Y-m-d'],
            [['name', 'genre', 'developers'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, gif'],
            [['imageFile'], 'file', 'skipOnEmpty' => false],
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
            'namePlatforms' => Yii::t('app', 'Platforms'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::ESCENARIO_RECORD] = ['name', 'genre', 'released', 'developers', 'platforms'];
        $scenarios[self::ESCENARIO_UPLOAD] = ['imageFile'];
        return $scenarios;
    }

    /**
     * Guarda una imagen en covers
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->scenario = self::ESCENARIO_UPLOAD;
        return $this->savePlatforms();
    }

    /**
    * Guarda las plataformas del juego
    * @return bool true si ha guardado todas las plataformas, false en caso contrario
    */
    public function savePlatforms()
    {
        $this->refresh();
        GamePlatform::deleteAll(['id_game' => $this->id]);
        $saved = true;
        if ($this->platforms !== '') {
            foreach ($this->platforms as $value) {
                $gamePlatform = new GamePlatform;
                $gamePlatform->id_game = $this->id;
                $gamePlatform->id_platform = $value;
                $saved = $gamePlatform->save() && $saved;
            }
        }
        return $saved;
    }

    /**
     * Devuelve la ruta a la caratula del juego
     * @return string Ruta de la caratula del juego
     */
    public function getCover()
    {
        $covers = Yii::getAlias('@covers');
        $files = FileHelper::findFiles($covers);
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $archivo = substr($file, strrpos($file, '/') + 1);
                $nombre = substr($archivo, 0, strlen($archivo) - 4);
                if (intval($nombre) === $this->id) {
                    return "/$covers/$archivo";
                }
            }
        }
        return "/$covers/default.png";
    }


    /**
     * Devuelve los nombres de las plataformas del juego concatenadas
     * @return string Nombres de las plataformas del juego
     */
    public function getNamePlatforms()
    {
        return implode(', ', Platform::find()
                        ->select('name')
                        ->joinWith('gamesPlatform')
                        ->where(['id_game' => $this->id])
                        ->column());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamePlatforms()
    {
        return $this->hasMany(GamePlatform::className(), ['id_game' => 'id'])->inverseOf('game');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlatforms()
    {
        return $this->hasMany(Platform::className(), ['id' => 'id_platform'])->via('gamePlatforms');
    }
}
