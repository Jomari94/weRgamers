<?php

namespace app\models;

use Yii;
use app\models\Message;
use app\models\Conversation;
use dektrium\user\models\User as BaseUser;
use dektrium\rbac\models\Assignment;

class User extends BaseUser
{
    /**
     * Determina si el usuario es seguidor de otro
     * @param  int  $id id del usuario seguido
     * @return bool ture si el usuario lo sigue, false en caso contrario
     */
    public function isFollower($id)
    {
        $follower = Follower::findOne(['id_follower' => $this->id, 'id_followed' => $id]);
        return $follower !== null;
    }

    /**
     * indica si el usuarario ha votado al usuario indicado
     * @param  int  $id ID del usuario votado
     * @return bool     true si ha votado a ese usuario, false en caso contrario
     */
    public function hasVoted($id)
    {
        $vote = Vote::findOne(['id_voter' => $this->id, 'id_voted' => $id]);
        return $vote !== null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSended()
    {
        return $this->hasMany(Message::className(), ['id_sender' => 'id'])->inverseOf('sender');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations1()
    {
        return $this->hasMany(Conversation::className(), ['id_participant1' => 'id'])->inverseOf('participant1');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations2()
    {
        return $this->hasMany(Conversation::className(), ['id_participant2' => 'id'])->inverseOf('participant2');
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->refresh();
            $model = Yii::createObject([
                'class'   => Assignment::className(),
                'user_id' => $this->id,
            ]);
            $model->items = [0 => 'User'];
            $model->updateAssignments();
        }
    }
}
