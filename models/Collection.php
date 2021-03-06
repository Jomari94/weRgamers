<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "collections".
 *
 * @property integer $id_user
 * @property integer $id_game
 * @property integer $id_platform
 *
 * @property GamesPlatforms $idGame
 * @property User $idUser
 */
class Collection extends \yii\db\ActiveRecord
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'collections';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['id_user', 'id_game', 'id_platform'], 'required'],
            [['id_user', 'id_game', 'id_platform'], 'integer'],
            [['id_game', 'id_platform'], 'exist', 'skipOnError' => true, 'targetClass' => GamePlatform::className(), 'targetAttribute' => ['id_game' => 'id_game', 'id_platform' => 'id_platform']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * Labels de las propiedades del modelo.
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id_user' => Yii::t('app', 'Id User'),
            'id_game' => Yii::t('app', 'Id Game'),
            'id_platform' => Yii::t('app', 'Id Platform'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(GamePlatform::className(), ['id_game' => 'id_game', 'id_platform' => 'id_platform']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
