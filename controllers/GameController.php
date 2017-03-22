<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Platform;
use app\models\GameSearch;
use app\models\GamePlatform;
use app\models\PlatformSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
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
     * Lists all Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchGame = new GameSearch();
        $dataProviderGame = $searchGame->search(Yii::$app->request->queryParams);
        $searchPlatform = new PlatformSearch();
        $dataProviderPlatform = $searchPlatform->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchGame' => $searchGame,
            'dataProviderGame' => $dataProviderGame,
            'searchPlatform' => $searchPlatform,
            'dataProviderPlatform' => $dataProviderPlatform,
        ]);
    }

    /**
     * Displays a single Game model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Game model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Game([
            'scenario' => Game::ESCENARIO_CREATE,
        ]);
        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save(true, ['name', 'genre', 'released', 'developers'])) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile !== null) {
                $model->upload();
            }

            if ($model->savePlatforms()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'platforms' => $platforms,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'platforms' => $platforms,
            ]);
        }
    }

    /**
     * Updates an existing Game model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name');
        $platformsChecked = Platform::find()->select('id')->joinWith('gamesPlatforms')->where(['id_game' => $id])->column();
        $model->platforms = $platformsChecked;

        if ($model->load(Yii::$app->request->post()) && $model->save(true, ['name', 'genre', 'released', 'developers'])) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile !== null) {
                $model->upload();
            }

            if ($model->savePlatforms()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'platforms' => $platforms,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'platforms' => $platforms,
                'platformsChecked' => $platformsChecked,
            ]);
        }
    }

    /**
     * Deletes an existing Game model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Game the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Game::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}