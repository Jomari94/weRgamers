<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $id_receiver
 * @property integer $id_user
 * @property integer $id_group
 *
 * @property User $receiver
 * @property User $user
 * @property Group $group
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @var int
     */
    const MESSAGE = 0;

    /**
     * @var int
     */
    const FOLLOW = 1;

    /**
     * @var int
     */
    const EVENT = 2;

    /**
     * @var int
     */
    const EVENT_CANCELLED = 3;

    /**
     * @var int
     */
    const REQUEST = 4;

    /**
     * @var int
     */
    const CONFIRMATION = 5;

    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['id_receiver', 'type'], 'required'],
            [['id_receiver', 'type'], 'integer'],
            [['type'], 'in', 'range' => [0, 1, 2, 3, 4, 5]],
            [['id_receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_receiver' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['id_group' => 'id']],
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
            'type' => Yii::t('app', 'Type'),
            'id_receiver' => Yii::t('app', 'Id Receiver'),
            'id_user' => Yii::t('app', 'Id User'),
            'id_group' => Yii::t('app', 'Id Group'),
        ];
    }

    /**
     * Crea notificaciones para todos los que tienen que recibirla.
     * @param  int   $type      Tipo de notificacion
     * @param  array $receivers Array con los ids de los usuarios que recibiran las notificaciones
     * @param  int   $user      Usuario referenciado en la notificación
     * @param  int   $group     Grupo referenciado en la notificación
     * @return bool             True si las ha creado todas, false si no
     */
    public static function create($type, $receivers, $user = null, $group = null)
    {
        $save = true;
        foreach ($receivers as $receiver) {
            $notification = new Notification;
            $notification->type = $type;
            $notification->id_receiver = $receiver;
            $notification->id_user = $user;
            $notification->id_group = $group;
            $save = $save && $notification->save();
        }
        return $save;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'id_receiver']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'id_group']);
    }
}
