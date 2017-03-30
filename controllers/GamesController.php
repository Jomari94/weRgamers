<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Platform;
use app\models\GamePlatform;
use app\models\PlatformSearch;
use app\models\GamePlatformSearch;
use app\models\Collection;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GamesController implements the CRUD actions for Game model.
 */
class GamesController extends Controller
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
                    'addgame' => ['POST'],
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
        // $searchGame = new GameSearch();
        // $dataProviderGame = $searchGame->search(Yii::$app->request->queryParams);
        $searchGame = new GamePlatformSearch();
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
     * @param int $id
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
            'scenario' => Game::ESCENARIO_RECORD,
        ]);
        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile !== null) {
                $model->upload();
            }
            return $this->redirect(['view', 'id' => $model->id]);
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
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Game::ESCENARIO_RECORD;

        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name');
        $platformsChecked = GamePlatform::find()->select('id_platform')->where(['id_game' => $id])->column();
        $model->platforms = $platformsChecked;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->imageFile !== null) {
                $model->upload();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'platforms' => $platforms,
                'platformsChecked' => $platformsChecked,
            ]);
        }
    }

    public function actionAddgame()
    {
        $post = file_get_contents('php://input');
        $datos = json_decode($post, true);
        $model = new Collection;
        $model->id_user = Yii::$app->user->id;
        $model->id_game =$datos['id_game'];
        $model->id_platform = $datos['id_platform'];
        $model->save();
        return true;
    }

    /**
     * Elimina juego de la coleccion del usuario
     * @return [type] [description]
     */
    public function actionDropgame()
    {
        $post = file_get_contents('php://input');
        $datos = json_decode($post, true);
        $id_user = Yii::$app->user->id;
        $id_game =$datos['id_game'];
        $id_platform = $datos['id_platform'];
        $model = Collection::findOne([$id_user, $id_game, $id_platform]);
        $model->delete();
        return true;
    }

    /**
     * Finds the Game model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
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
