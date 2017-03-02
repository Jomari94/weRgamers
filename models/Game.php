<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property integer $id
 * @property string $name
 * @property string $genre
 * @property string $released
 * @property string $developers
 *
 * @property GamesPlatforms[] $gamesPlatforms
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['released'], 'safe'],
            [['name', 'genre', 'developers'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'genre' => Yii::t('app', 'Genre'),
            'released' => Yii::t('app', 'Released'),
            'developers' => Yii::t('app', 'Developers'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGamesPlatforms()
    {
        return $this->hasMany(GamesPlatforms::className(), ['id_game' => 'id'])->inverseOf('idGame');
    }
}
