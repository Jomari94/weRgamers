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
}
