<?php

namespace app\controllers;

use Yii;
use app\models\GamePlatform;
use app\models\GamePlatformSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GamePlatformsController implements the CRUD actions for GamePlatform model.
 */
class GamePlatformsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all GamePlatform models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GamePlatformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GamePlatform model.
     * @param integer $id_game
     * @param integer $id_platform
     * @return mixed
     */
    public function actionView($id_game, $id_platform)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_game, $id_platform),
        ]);
    }

    /**
     * Creates a new GamePlatform model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GamePlatform();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_game' => $model->id_game, 'id_platform' => $model->id_platform]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GamePlatform model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_game
     * @param integer $id_platform
     * @return mixed
     */
    public function actionUpdate($id_game, $id_platform)
    {
        $model = $this->findModel($id_game, $id_platform);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_game' => $model->id_game, 'id_platform' => $model->id_platform]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GamePlatform model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_game
     * @param integer $id_platform
     * @return mixed
     */
    public function actionDelete($id_game, $id_platform)
    {
        $this->findModel($id_game, $id_platform)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GamePlatform model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_game
     * @param integer $id_platform
     * @return GamePlatform the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_game, $id_platform)
    {
        if (($model = GamePlatform::findOne(['id_game' => $id_game, 'id_platform' => $id_platform])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
