<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "conversations".
 *
 * @property integer $id
 * @property integer $id_participant1
 * @property integer $id_participant2
 *
 * @property User $idParticipant1
 * @property User $idParticipant2
 * @property Messages[] $messages
 */
class Conversation extends \yii\db\ActiveRecord
{
    /**
     * @var string  Nombre del usuario con el que se va a conversar
     */
    public $username;

    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'conversations';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['id_participant1', 'id_participant2'], 'integer'],
            [['id_participant1'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_participant1' => 'id']],
            [['id_participant2'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_participant2' => 'id']],
            [['username'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['username' => 'username'],
                'message' => Yii::t('app', "The username doesn't exist, make sure you type it properly"),
            ],
        ];
    }

    /**
     * Labels de las propiedades del modelo.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_participant1' => Yii::t('app', 'Id Participant1'),
            'id_participant2' => Yii::t('app', 'Id Participant2'),
            'username' => Yii::t('app', 'Username'),
        ];
    }

    /**
     * Se ejecuta antes del save().
     * @param  bool $insert Indica si se va a hacer un insert o un update
     * @return bool         True si es insert, false si es update
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->id_participant1 = Yii::$app->user->id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Borra las conversaciones del usuario sin mensajes.
     * @return void
     */
    public static function vacias()
    {
        $ids = Message::find()->select('id_conversation')->column();
        $conversations = self::find()->where(['not in', 'id', $ids])->all();
        foreach ($conversations as $conversation) {
            $conversation->delete();
        }
    }

    /**
     * Obtiene el último mensaje de la conversación.
     * @return ActiveRecord Último mensaje de la conversación.
     */
    public function getLast()
    {
        return Message::find()->where(['id_conversation' => $this->id])->orderBy('created DESC')->one();
    }

    /**
     * Busca quien es la persona con la que conversa el usuario actual.
     * @return ActiveRecord Usuario participante en la conversación
     */
    public function getReceiver()
    {
        if ($this->id_participant1 == Yii::$app->user->id) {
            return $this->participant2;
        } else {
            return $this->participant1;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant1()
    {
        return $this->hasOne(User::className(), ['id' => 'id_participant1'])->inverseOf('conversations1');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant2()
    {
        return $this->hasOne(User::className(), ['id' => 'id_participant2'])->inverseOf('conversations2');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id_conversation' => 'id'])->inverseOf('conversation');
    }
}
