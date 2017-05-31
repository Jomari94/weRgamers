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
     * Nombre del juego en el formulario
     * @var string
     */
    public $game_name;

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
            [['name', 'id_platform', 'game_name'], 'required'],
            [['id_game', 'id_platform'], 'integer'],
            [['name', 'game_name'], 'string', 'max' => 255],
            [['game_name'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_name' => 'name', 'id_game' => 'id']],
            // [['id_game', 'id_platform'], 'exist', 'skipOnError' => true, 'targetClass' => GamePlatform::className(), 'targetAttribute' => ['id_game' => 'id_game', 'id_platform' => 'id_platform']],
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
            'game_name' => Yii::t('app', 'Game'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['id_group' => 'id'])->inverseOf('group');
    }

    /**
     * @return string
     */
    public function getGameName()
    {
        return $this->hasOne(Game::className(), ['id' => 'id_game']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessage::className(), ['id_group' => 'id'])->inverseOf('group');
    }

    /**
     * Comprueba si el usuario indicado es miembro aceptado del grupo
     * @param  int  $id ID del usuario
     * @return bool True si es miembro, false en caso contrario
     */
    public function isMember($id)
    {
        $member = Member::findOne(['id_group' => $this->id, 'id_user' => $id, 'accepted' => true]);
        return $member !== null;
    }

    /**
     * Comprueba si el usuario indicado es administrador del grupo
     * @param  int  $id ID del usuario
     * @return bool True si es miembro, false en caso contrario
     */
    public function isAdmin($id)
    {
        $member = Member::findOne(['id_group' => $this->id, 'id_user' => $id, 'admin' => true]);
        return $member !== null;
    }

    /**
     * Comprueba si el usuario indicado es miembro pendiente de aceptar del grupo
     * @param  int  $id ID del usuario
     * @return bool True si es miembro, false en caso contrario
     */
    public function isPending($id)
    {
        $member = Member::findOne(['id_group' => $this->id, 'id_user' => $id, 'accepted' => false]);
        return $member !== null;
    }

    /**
     * Devuelve el número total de miembros del grupo
     * @return int número de miembros
     */
    public function getTotalMembers()
    {
        return Member::find()->where(['id_group' => $this->id, 'accepted' => true])->count();
    }
}
