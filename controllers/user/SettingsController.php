<?php

namespace app\controllers\user;

use Yii;
use app\models\AvatarForm;
use app\models\GamePlatformSearch;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use dektrium\user\models\Profile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class SettingsController extends BaseSettingsController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'networks', 'disconnect', 'delete', 'collection'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionProfile()
    {
        $avatar = new AvatarForm;
        if (Yii::$app->request->isPost) {
            $avatar->imageFile = UploadedFile::getInstance($avatar, 'imageFile');
            $avatar->upload();
        }
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }
        $event = $this->getProfileEvent($model);
        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        return $this->render('profile', [
            'model' => $model,
            'avatar' => $avatar,
        ]);
    }

    /**
     * Muestra todos los juegos almacenados en la db marcando los del usuario
     * @return mixed
     */
    public function actionCollection()
    {
        $searchModel = new GamePlatformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('collection', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
