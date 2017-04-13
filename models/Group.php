<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_game
 * @property integer $id_platform
 *
 * @property GamesPlatforms $idGame
 * @property Members[] $members
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_game', 'id_platform'], 'required'],
            [['id_game', 'id_platform'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['id_game', 'id_platform'], 'exist', 'skipOnError' => true, 'targetClass' => GamePlatform::className(), 'targetAttribute' => ['id_game' => 'id_game', 'id_platform' => 'id_platform']],
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
            'id_game' => Yii::t('app', 'Game'),
            'id_platform' => Yii::t('app', 'Platform'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(GamePlatform::className(), ['id_game' => 'id_game', 'id_platform' => 'id_platform'])->inverseOf('groups');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['id_group' => 'id'])->inverseOf('group');
    }

    /**
     * Comprueba si el usuario indicado es miembro del grupo
     * @param  int  $id ID del usuario
     * @return bool True si es miembro, false en caso contrario
     */
    public function isMember($id)
    {
        $member = Member::findOne(['id_group' => $this->id, 'id_user' => $id]);
        return $member !== null ? true : false;
    }
}
