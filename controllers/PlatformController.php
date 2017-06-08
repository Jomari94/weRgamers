<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Platform;
use app\models\PlatformSearch;
use dektrium\user\filters\AccessRule;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controlador del modelo Platform
 */
class PlatformController extends Controller
{
    /**
     * Behaviors del controlador de Platform.
     * @return [type] [description]
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['search-ajax'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lista los modelos Platform.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlatformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Crea un nuevo modelo Platform.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Platform();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['games/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Modifica un modelo Platform.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['games/index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Busca las plataformas de un juego y las devuelve.
     * @param  string $name Nombre del juego a buscar
     * @return array Nombres de las plataformas encontrados
     */
    public function actionSearchAjax($name = null)
    {
        $platforms = [];
        if ($name != null || $name != '') {
            $platforms = Game::find()
            ->where(['like', 'name', $name])
            ->one()
            ->getPlatforms()
            ->all();
            $platforms = ArrayHelper::map($platforms, 'id', 'name');
        }
        return Json::encode($platforms);
    }

    /**
     * Finds the Platform model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Platform the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Platform::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
