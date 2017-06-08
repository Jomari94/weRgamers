<?php

namespace app\controllers;

use Yii;
use DateTime;
use DateTimeZone;
use app\models\Game;
use app\models\Event;
use app\models\Group;
use app\models\Member;
use app\models\ChatMessage;
use app\models\GroupSearch;
use app\models\Notification;
use dektrium\user\filters\AccessRule;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Controlador del modelo Group
 */
class GroupsController extends Controller
{
    /**
     * Behaviors del controlador de Group.
     * @return array
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
            'only' => ['create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['admin'],
                ]
            ],
        ],
        ];
    }

    /**
     * Lista los modelos Group.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Group;
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un modelo Group.
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        $event = Event::findOne(['id_group' => $id]);
        if ($event === null) {
            $event = new Event;
        } else {
            $user = Yii::$app->user->identity;
            if ($user && $user->profile->timezone) {
                $tz = new DateTimeZone($user->profile->timezone);
            } else {
                $tz = new DateTimeZone('UTC');
            }
            $iniciotz = new DateTime($event->inicio, $tz);
            $event->inicio = $iniciotz->format('Y-m-d H:i');
            if ($event->fin) {
                $fintz = new DateTime($event->fin, $tz);
                $event->fin = $fintz->format('Y-m-d H:i');
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Member::find()->where(['id_group' => $id, 'accepted' => true]),
            'pagination' => false,
        ]);
        $messagesProvider = new ActiveDataProvider([
            'query' => ChatMessage::find()->where(['id_group' => $id])->orderBy('created ASC'),
            'pagination' => false,
        ]);

        if (Yii::$app->request->post('cancel') !== null) {
            if ($event->load(Yii::$app->request->post())) {
                $members = $this->findModel($id)->members;
                $ids = [];
                foreach ($members as $member) {
                    $ids[] = $member->id_user;
                }
                Notification::create(Notification::EVENT_CANCELLED, $ids, Yii::$app->user->id, $id);
                $event->delete();
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        if ($event->load(Yii::$app->request->post()) && $event->guarda($id)) {
            $members = $this->findModel($id)->members;
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member->id_user;
            }
            Notification::create(Notification::EVENT, $ids, Yii::$app->user->id, $id);
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'event' => $event,
            'dataProvider' => $dataProvider,
            'messagesProvider' => $messagesProvider,
        ]);
    }

    /**
     * Guarda un mensaje del chat una vez se ha mandado.
     * @return void
     */
    public function actionMessageSended()
    {
        $group = Yii::$app->request->post('group');
        $user = Yii::$app->request->post('user');
        $message = Yii::$app->request->post('message');

        $chatMessage = new ChatMessage;
        $chatMessage->id_user = $user;
        $chatMessage->id_group = $group;
        $chatMessage->content = $message;
        $chatMessage->save();
    }

    /**
     * Crea un modelo Group.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group;
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post())) {
            $model->id_game = Game::find()
                ->select('id')
                ->where(['ilike', 'name', $model->game_name])
                ->scalar();
            if ($model->save()) {
                $model->refresh();
                $admin = new Member;
                $admin->id_group = $model->id;
                $admin->id_user = Yii::$app->user->id;
                $admin->accepted = true;
                $admin->admin = true;
                $admin->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        } else {
            return $this->render('index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Borra un modelo Group.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $members = Member::find()->where(['id_group' => $id])->all();
        foreach ($members as $member) {
            $member->delete();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
