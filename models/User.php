<?php

namespace app\models;

use Yii;
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
