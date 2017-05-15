<?php

namespace app\models;

use Yii;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity', 'inicio'], 'required'],
            [['id_group'], 'integer'],
            [['fin'], 'safe'],
            [['activity'], 'string', 'max' => 250],
            [['id_group'], 'unique'],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['id_group' => 'id']],
        ];
    }

    /**
     * @inheritdoc
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
