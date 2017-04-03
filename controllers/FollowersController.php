<?php

namespace app\controllers;

use Yii;
use app\models\Follower;
use yii\web\Controller;

class FollowersController extends Controller
{
    public function actionFollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = new Follower;
        $follower->id_follower = Yii::$app->user->id;
        $follower->id_followed = $followed;
        $follower->save();
        return Yii::$app->request->post('followed_id');
    }

    public function actionUnfollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = Follower::findOne(['id_follower' => Yii::$app->user->id, 'id_followed' => $followed]);
        $follower->delete();
        return true;
    }
}
