<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "publications".
 *
 * @property integer $id
 * @property string $content
 * @property string $created
 * @property integer $id_user
 *
 * @property User $idUser
 */
class Publication extends \yii\db\ActiveRecord
{
    /**
     * @var $file Object archivo adjunto de la publicación
     */
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'publications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['created'], 'safe'],
            [['id_user'], 'integer'],
            [['content'], 'string', 'max' => 500],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'gif', 'mp3', 'mp4'], 'maxSize' => 1024*1024*1024*8],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'created' => Yii::t('app', 'Created'),
            'id_user' => Yii::t('app', 'Id User'),
        ];
    }

    /**
     * Añade el creador de la publicación antes de guardarla
     * @param  bool $insert Indica si el save() va a hacer una inserción
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->id_user = Yii::$app->user->id;
            return true;
        }
        return false;
    }

    /**
     * Guarda el archivo adjunto de la publicación junto con el id
     * @return bool
     */
    public function upload()
    {
        if ($this->file && $this->validate()) {
            $this->file->saveAs('attachments/' . $this->id . '-' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve la ruta a el archivo adjunti de la publicación si tiene
     * @return string|null
     */
    public function getAttachment()
    {
        $attachments = Yii::getAlias('@attachments');
        $files = FileHelper::findFiles($attachments);
        if (isset($files[0])) {
            foreach ($files as $file) {
                $archivo = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
                $nombre = substr($archivo, 0, stripos($archivo, '-'));
                if (intval($nombre) === $this->id) {
                    return "/$attachments/$archivo";
                }
            }
        }
        return null;
    }

    /**
     * Devuelve la extensión del archivo adjunto
     * @return string
     */
    public function getAttachmentType()
    {
        $attachments = Yii::getAlias('@attachments');
        $files = FileHelper::findFiles($attachments);
        if (isset($files[0])) {
            foreach ($files as $file) {
                $archivo = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
                $nombre = substr($archivo, 0, stripos($archivo, '-'));
                $ext = substr($archivo, strrpos($archivo, '.') + 1);
                if (intval($nombre) === $this->id) {
                    return "$ext";
                }
            }
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user'])->inverseOf('publications');
    }
}
