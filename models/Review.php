<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property integer $id
 * @property string $content
 * @property integer $score
 * @property string $created
 * @property integer $id_user
 * @property integer $id_game
 *
 * @property Games $idGame
 * @property User $idUser
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'score'], 'required'],
            [['score', 'id_user', 'id_game'], 'integer'],
            [['created'], 'safe'],
            [['content'], 'string', 'max' => 500],
            [['id_game'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['id_game' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'score' => Yii::t('app', 'Score'),
            'created' => Yii::t('app', 'Created'),
            'id_user' => Yii::t('app', 'User'),
            'id_game' => Yii::t('app', 'Game'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'id_game'])->inverseOf('reviews');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
