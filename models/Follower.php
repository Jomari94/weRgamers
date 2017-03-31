<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "followers".
 *
 * @property integer $id_follower
 * @property integer $id_followed
 *
 * @property User $idFollower
 * @property User $idFollowed
 */
class Follower extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'followers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_follower', 'id_followed'], 'required'],
            [['id_follower', 'id_followed'], 'integer'],
            [['id_follower'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_follower' => 'id']],
            [['id_followed'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_followed' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_follower' => Yii::t('app', 'Id Follower'),
            'id_followed' => Yii::t('app', 'Id Followed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollower()
    {
        return $this->hasOne(User::className(), ['id' => 'id_follower']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowed()
    {
        return $this->hasOne(User::className(), ['id' => 'id_followed']);
    }
}
