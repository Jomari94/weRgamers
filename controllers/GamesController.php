<?php

namespace app\controllers;

use Yii;
use app\models\Game;
use app\models\Review;
use app\models\Platform;
use app\models\GameSearch;
use app\models\GamePlatform;
use app\models\PlatformSearch;
use app\models\Collection;
use dektrium\user\filters\AccessRule;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
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
                    'addGame' => ['POST'],
                    'dropGame' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'update', 'addGame', 'dropGame'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['addGame', 'dropGame'],
                        'roles' => ['@'],
                    ],
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
        $dataProviderGame->pagination->pageParam = 'pageG';
        $dataProviderGame->sort->sortParam = 'sortG';
        $searchPlatform = new PlatformSearch();
        $dataProviderPlatform = $searchPlatform->search(Yii::$app->request->queryParams);
        $dataProviderPlatform->pagination->pageParam = 'pageP';
        $dataProviderPlatform->sort->sortParam = 'sortP';

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
        $review = new Review;
        $reviewProvider = new ActiveDataProvider([
            'query' => Review::find()->where(['id_game' => $id])->orderBy('created ASC'),
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'review' => $review,
            'reviewProvider' => $reviewProvider
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

    /**
     * Añade un juego a la colección
     * @return bool True si se añade el juego a la coleccion, false en caso contrario
     */
    public function actionAddgame()
    {
        $post = Yii::$app->request->post('datos');
        $datos = json_decode($post, true);
        $model = new Collection;
        $model->id_user = Yii::$app->user->id;
        $model->id_game =$datos['id_game'];
        $model->id_platform = $datos['id_platform'];
        if ($model->save()) {
            Yii::$app->session->setFlash('anadido', Yii::t('app', 'The game {game} ({platform}) has been added', [
                'game' => $model->game->game->name,
                'platform' => $model->game->platform->name
            ]));
            return true;
        }
        return true;
    }

    /**
     * Elimina juego de la coleccion del usuario
     * @return bool true si se ha borrado, false en caso contrario
     */
    public function actionDropgame()
    {
        $post = Yii::$app->request->post('datos');
        $datos = json_decode($post, true);
        $id_user = Yii::$app->user->id;
        $id_game =$datos['id_game'];
        $id_platform = $datos['id_platform'];
        $model = Collection::findOne(['id_user' => $id_user, 'id_game' => $id_game, 'id_platform' => $id_platform]);
        if ($model->delete() != false) {
            Yii::$app->session->setFlash('eliminado', Yii::t('app', 'The game {game} ({platform}) has been deleted', [
                'game' => $model->game->game->name,
                'platform' => $model->game->platform->name
            ]));
            return true;
        }
        return false;
    }

    /**
     * Busca juegos y los devuelve
     * @param  string $game Nombre del juego a buscar
     * @return array        Nombres de los juegos encontrados
     */
    public function actionSearchAjax($game = null)
    {
        $games = [];
        if ($game != null || $game != '') {
            $games = Game::find()
            ->select(['id', 'name'])
            ->where(['ilike', 'name', "$game"])
            ->all();
            $games = ArrayHelper::map($games, 'name', 'id');
        }
        return Json::encode($games);
    }

    /**
     * Busca las plataformas de un juego y las devuelve
     * @param  string $name Nombre del juego a buscar
     * @return array Nombres de las plataformas encontrados
     */
    public function actionPlatformsAjax($name = null)
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
