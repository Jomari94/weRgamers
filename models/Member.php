<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "members".
 *
 * @property integer $id_group
 * @property integer $id_user
 * @property boolean $accepted
 * @property boolean $admin
 *
 * @property Groups $idGroup
 * @property User $idUser
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @return string
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['id_group', 'id_user'], 'required'],
            [['id_group', 'id_user'], 'integer'],
            [['accepted'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['admin'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['id_group'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['id_group' => 'id']],
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
            'id_group' => Yii::t('app', 'Id Group'),
            'id_user' => Yii::t('app', 'Id User'),
            'accepted' => Yii::t('app', 'Accepted'),
            'admin' => Yii::t('app', 'Admin'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'id_group'])->inverseOf('members');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
