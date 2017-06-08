<?php

namespace app\controllers\user;

use Yii;
use app\models\User;
use app\models\Vote;
use app\models\Follower;
use app\models\Collection;
use app\models\Publication;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use dektrium\user\controllers\ProfileController as BaseProfileController;

/**
 * Controlador del modelo Profile que hereda de dektrium\user\controllers\ProfileController
 */
class ProfileController extends BaseProfileController
{


    /**
     * Behaviors del controlador de Profile
     * @return [type] [description]
     */
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
     * Redirige al perfil del usuario actual.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => \Yii::$app->user->getId()]);
    }

    /**
     * Muestra el perfil del usuario.
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

        $publication = new Publication();
        $positive = Vote::find()->select('count(*)')->where(['id_voted' => $profile->user_id, 'positive' => true])->scalar();
        $negative = Vote::find()->select('count(*)')->where(['id_voted' => $profile->user_id, 'positive' => false])->scalar();
        $karma = $positive - $negative;
        $collection = new ActiveDataProvider([
            'query' => Collection::find()->where(['id_user' => $profile->user_id])->orderBy('id_game DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $collection->pagination->pageParam = 'collectionPage';
        $followers = Follower::find()->select('id_follower')->where(['id_followed' => $id]);
        $followerProvider = new ActiveDataProvider([
            'query' => User::find()->where(['in', 'id', $followers]),
            'pagination' => false,
        ]);
        $followings = Follower::find()->select('id_followed')->where(['id_follower' => $id]);
        $followingProvider = new ActiveDataProvider([
            'query' => User::find()->where(['in', 'id', $followings]),
            'pagination' => false,
        ]);
        $publicationProvider = new ActiveDataProvider([
            'query' => Publication::find()->where(['id_user' => $profile->user_id])->orWhere(['in', 'id_user', $followings])->orderBy('created DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if ($publication->load(Yii::$app->request->post()) && $publication->validate(['content'])) {
            $publication->save(false);
            $publication->file = UploadedFile::getInstance($publication, 'file');
            if ($publication->file !== null && !$publication->upload()) {
                $publication->delete();
            }
            return $this->refresh();
        }
        return $this->render('show', [
            'profile' => $profile,
            'karma' => $karma,
            'collection' => $collection,
            'publication' => $publication,
            'publicationProvider' => $publicationProvider,
            'followerProvider' => $followerProvider,
            'followingProvider' => $followingProvider,
        ]);
    }
}
