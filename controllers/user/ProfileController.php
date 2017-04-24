<?php

namespace app\controllers\user;

use app\models\Vote;
use app\models\Collection;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use dektrium\user\controllers\ProfileController as BaseProfileController;

class ProfileController extends BaseProfileController
{


    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['show'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => \Yii::$app->user->getId()]);
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        $positive = Vote::find()->select('count(*)')->where(['id_voted' => $profile->user_id, 'positive' => true])->scalar();
        $negative = Vote::find()->select('count(*)')->where(['id_voted' => $profile->user_id, 'positive' => false])->scalar();
        $karma = $positive - $negative;
        $collection = new ActiveDataProvider([
            'query' => Collection::find()->where(['id_user' => $profile->user_id])->orderBy('id_game DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('show', [
            'profile' => $profile,
            'karma' => $karma,
            'collection' => $collection,
        ]);
    }
}