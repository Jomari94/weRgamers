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
            [['id_group'], 'integer'],
            [['inicio', 'fin'], 'safe'],
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
            'inicio' => Yii::t('app', 'Inicio'),
            'fin' => Yii::t('app', 'Fin'),
            'activity' => Yii::t('app', 'Activity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'id_group'])->inverseOf('event');
    }
}
