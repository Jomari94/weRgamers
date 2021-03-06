<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Follower;
use app\models\Notification;
use dektrium\user\filters\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Controlador del modelo Follower
 */
class FollowersController extends Controller
{
    /**
     * Behaviors del controlador de Follower.
     * @return array
     */
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
     * Añade al usuario actual como seguidor de otro usuario.
     * @return int numero de followers
     */
    public function actionFollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = new Follower;
        $follower->id_follower = Yii::$app->user->id;
        $follower->id_followed = $followed;
        $follower->save();
        Notification::create(Notification::FOLLOW, [$follower->id_followed], $follower->id_follower);
        return User::findOne(['id' => $followed])->profile->totalFollowers;
    }

    /**
     * Elimina al usuario actual como seguidor de otro usuario.
     * @return int numero de followers
     */
    public function actionUnfollow()
    {
        $followed = Yii::$app->request->post('followed_id');
        $follower = Follower::findOne(['id_follower' => Yii::$app->user->id, 'id_followed' => $followed]);
        $follower->delete();
        return User::findOne(['id' => $followed])->profile->totalFollowers;
    }
}
