<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "votes".
 *
 * @property integer $id_voter
 * @property integer $id_voted
 * @property boolean $positive
 *
 * @property User $idVoter
 * @property User $idVoted
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_voter', 'id_voted', 'positive'], 'required'],
            [['id_voter', 'id_voted'], 'integer'],
            [['positive'], 'boolean'],
            [['id_voter'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_voter' => 'id']],
            [['id_voted'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_voted' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_voter' => Yii::t('app', 'Id Voter'),
            'id_voted' => Yii::t('app', 'Id Voted'),
            'positive' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoter()
    {
        return $this->hasOne(User::className(), ['id' => 'id_voter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoted()
    {
        return $this->hasOne(User::className(), ['id' => 'id_voted'])->inverseOf('votes');
    }
}
