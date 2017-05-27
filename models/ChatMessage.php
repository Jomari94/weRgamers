<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat_messages".
 *
 * @property integer $id
 * @property string $content
 * @property string $created
 * @property integer $id_user
 * @property integer $id_group
 *
 * @property Groups $idGroup
 * @property User $idUser
 */
class ChatMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'id_user', 'id_group'], 'required'],
            [['created'], 'safe'],
            [['id_user', 'id_group'], 'integer'],
            [['content'], 'string', 'max' => 500],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['id_group' => 'id']],
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
            'created' => Yii::t('app', 'Created'),
            'id_user' => Yii::t('app', 'Id User'),
            'id_group' => Yii::t('app', 'Id Group'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'id_group'])->inverseOf('chatMessages');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
