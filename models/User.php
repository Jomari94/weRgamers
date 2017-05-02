<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;

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
}
