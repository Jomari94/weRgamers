<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $id_receiver
 * @property string $content
 * @property string $type
 *
 * @property User $idReceiver
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_receiver'], 'required'],
            [['id_receiver'], 'integer'],
            [['content'], 'string', 'max' => 250],
            [['type'], 'string', 'max' => 5],
            [['id_receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_receiver' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_receiver' => Yii::t('app', 'Id Receiver'),
            'content' => Yii::t('app', 'Content'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * Crea notificaciones para todos los que tienen que recibirla
     * @param  string  $type      Tipo de notificacion
     * @param  string  $content   Contenido de la notificacion
     * @param  array   $receivers Array con los ids de los usuarios que recibiran las notificaciones
     * @return bool               True si las ha creado todas, false si no
     */
    public static function create($type, $content, $receivers)
    {
        $save = true;
        foreach ($receivers as $receiver) {
            $not = new Notification;
            $not->type = $type;
            $not->content = $content;
            $not->id_receiver = $receiver;
            $save = $save && $not->save();
        }
        return $save;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'id_receiver'])->inverseOf('notifications');
    }
}
