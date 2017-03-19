<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "games_platforms".
 *
 * @property integer $id_game
 * @property integer $id_platform
 *
 * @property Games $idGame
 * @property Platforms $idPlatform
 */
class GamePlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'games_platforms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_game', 'id_platform'], 'required'],
            [['id_game', 'id_platform'], 'integer'],
            [['id_game'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['id_game' => 'id']],
            [['id_platform'], 'exist', 'skipOnError' => true, 'targetClass' => Platform::className(), 'targetAttribute' => ['id_platform' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_game' => 'Id Game',
            'id_platform' => 'Id Platform',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'id_game'])->inverseOf('gamePlatforms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'id_platform'])->inverseOf('gamePlatforms');
    }
}
