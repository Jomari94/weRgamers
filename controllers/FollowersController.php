<?php

namespace app\controllers;

use Yii;
use app\models\Follower;
use app\models\Notification;
use dektrium\user\filters\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;

class FollowersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['follow', 'unfollow'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Añade al usuario actual como seguidor de otro usuario
     * @return bool true cuando se completa la acción
     */
    public function actionFollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = new Follower;
        $follower->id_follower = Yii::$app->user->id;
        $follower->id_followed = $followed;
        $follower->save();
        return Notification::create('follw',
            Yii::t('app', "{user} is now your follower",
                ['user' => $follower->follower->username]),
            [$follower->id_followed]
        );
    }

    /**
     * Elimina al usuario actual como seguidor de otro usuario
     * @return bool true cuando se completa la acción
     */
    public function actionUnfollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = Follower::findOne(['id_follower' => Yii::$app->user->id, 'id_followed' => $followed]);
        $follower->delete();
        return true;
    }
}
