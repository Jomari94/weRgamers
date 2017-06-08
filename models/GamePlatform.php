<?php

namespace app\models;

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
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'games_platforms';
    }

    /**
     * Reglas del modelo.
     * @return array
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
     * Labels de las propiedades del modelo.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id_game' => 'Id Game',
            'id_platform' => 'Platforms',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'id_game'])->inverseOf('gamePlatforms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'id_platform'])->inverseOf('gamesPlatform');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id_game' => 'id_game', 'id_platform' => 'id_platform'])->inverseOf('game');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('collections', ['id_game' => 'id_game', 'id_platform' => 'id_platform']);
    }
}
