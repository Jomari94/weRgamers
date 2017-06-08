<?php

namespace app\models;

use Yii;
use DateTime;
use DateTimeZone;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property integer $id_group
 * @property string $inicio
 * @property string $fin
 * @property string $activity
 *
 * @property Groups $idGroup
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['activity', 'inicio'], 'required'],
            [['fin'], 'safe'],
            [['id_group'], 'integer'],
            [['activity'], 'string', 'max' => 250],
            [['id_group'], 'unique'],
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
            'id_group' => Yii::t('app', 'Id Group'),
            'inicio' => Yii::t('app', 'Begin'),
            'fin' => Yii::t('app', 'End'),
            'activity' => Yii::t('app', 'Activity'),
        ];
    }

    /**
     * guarda el evento con el id del grupo
     * @param  int $group id del grupo
     * @return bool        true si se ha guardado, false en caso contrario
     */
    public function guarda($group)
    {
        $this->id_group = $group;
        $user = Yii::$app->user->identity;
        if ($user && $user->profile->timezone) {
            $tz = new DateTimeZone($user->profile->timezone);
        } else {
            $tz = new DateTimeZone('UTC');
        }
        $iniciotz = new DateTime($this->inicio, $tz);
        $this->inicio = $iniciotz->format('Y-m-d H:i:sO');
        if ($this->fin) {
            $fintz = new DateTime($this->fin, $tz);
            $this->fin = $fintz->format('Y-m-d H:i:sO');
        }
        return $this->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'id_group'])->inverseOf('event');
    }
}
