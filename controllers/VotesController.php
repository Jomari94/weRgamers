<?php

namespace app\controllers;

use Yii;
use app\models\Vote;
use dektrium\user\filters\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;

class VotesController extends Controller
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
                        'actions' => ['vote'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Se añade un voto del usuario actual a otro usuario
     * @return bool true cuando se completa la acción, false si no se ha completado correctamente
     */
    public function actionVote()
    {
        $voted = Yii::$app->request->post('voted_id');
        $positive = Yii::$app->request->post('positive');
        $vote = new Vote;
        $vote->id_voter = Yii::$app->user->id;
        $vote->id_voted = $voted;
        $vote->positive = $positive;
        $previous = Vote::findOne(['id_voted' => $voted, 'id_voter' => $vote->id_voter]);
        if ($previous != null) {
            if ($previous->positive != $vote->positive) {
                $previous->positive = $vote->positive;
                $previous->update();
            } else {
                $previous->delete();
            }
        } else {
            $vote->save();
        }
        $positive = Vote::find()->select('count(*)')->where(['id_voted' => $voted, 'positive' => true])->scalar();
        $negative = Vote::find()->select('count(*)')->where(['id_voted' => $voted, 'positive' => false])->scalar();
        return $positive - $negative;
    }
}
