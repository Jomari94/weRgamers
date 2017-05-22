<?php

namespace app\models;

use Yii;

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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->id_user = Yii::$app->user->id;
            return true;
        }
        return false;
    }

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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user'])->inverseOf('publications');
    }
}
