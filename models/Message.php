<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id_sender
 * @property string $content
 * @property boolean $seen
 * @property integer $id
 * @property string $created
 * @property integer $id_conversation
 *
 * @property Conversations $idConversation
 * @property User $idSender
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['id_sender', 'id_conversation', 'content'], 'required'],
            [['id_sender', 'id_conversation'], 'integer'],
            [['content'], 'string'],
            [['seen'], 'boolean'],
            [['created'], 'safe'],
            [['id_conversation'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['id_conversation' => 'id']],
            [['id_sender'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_sender' => 'id']],
        ];
    }

    /**
     * Labels de las propiedades del modelo.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id_sender' => Yii::t('app', 'Id Sender'),
            'content' => Yii::t('app', 'Content'),
            'seen' => Yii::t('app', 'Seen'),
            'id' => Yii::t('app', 'ID'),
            'created' => Yii::t('app', 'Created'),
            'id_conversation' => Yii::t('app', 'Id Conversation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['id' => 'id_conversation'])->inverseOf('messages');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'id_sender']);
    }
}
