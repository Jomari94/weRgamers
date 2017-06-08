<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "platforms".
 *
 * @property integer $id
 * @property string $name
 *
 * @property GamesPlatforms[] $gamesPlatforms
 */
class Platform extends \yii\db\ActiveRecord
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'platforms';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamesPlatform()
    {
        return $this->hasMany(GamePlatform::className(), ['id_platform' => 'id'])->inverseOf('platform');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::className(), ['id' => 'id_game'])->via('gamesPlatform');
    }
}
