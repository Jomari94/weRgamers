<?php

namespace app\controllers;

class FollowersController extends \yii\web\Controller
{
    public function actionFollow()
    {
        return $this->render('follow');
    }

    public function actionUnfollow()
    {
        return $this->render('unfollow');
    }

}
