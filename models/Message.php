<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id_sender
 * @property integer $id_receiver
 * @property string $content
 * @property boolean $seen
 *
 * @property User $idSender
 * @property User $idReceiver
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sender', 'id_receiver'], 'required'],
            [['id_sender', 'id_receiver'], 'integer'],
            [['content'], 'string'],
            [['seen'], 'boolean'],
            [['id_sender'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_sender' => 'id']],
            [['id_receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_receiver' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sender' => Yii::t('app', 'Id Sender'),
            'id_receiver' => Yii::t('app', 'Id Receiver'),
            'content' => Yii::t('app', 'Content'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'id_sender'])->inverseOf('messages');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'id_receiver'])->inverseOf('messages0');
    }
}
