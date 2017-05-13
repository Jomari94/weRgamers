<?php

namespace app\controllers;

use Yii;
use app\models\Event;
use app\models\Group;
use app\models\Member;
use app\models\GroupSearch;
use app\models\Notification;
use dektrium\user\filters\AccessRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupsController implements the CRUD actions for Group model.
 */
class GroupsController extends Controller
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
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        $event = Event::findOne(['id_group' => $id]);
        if ($event === null) {
            $event = new Event;
        }

        if ($event->load(Yii::$app->request->post()) && $event->guarda($id)) {
            $members = $this->findModel($id)->members;
            $ids = [];
            foreach ($members as $member) {
                $ids[] = $member->id_user;
            }
            Notification::create('event', Yii::t('app', '{user} from {group} has created an event for {inicio}.', ['user' => Yii::$app->user->identity->username, 'group' => $this->findModel($id)->name, 'inicio' => $event->inicio]), $ids);
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'event' => $event,
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->refresh();
            $admin = new Member;
            $admin->id_group = $model->id;
            $admin->id_user = Yii::$app->user->id;
            $admin->accepted = true;
            $admin->admin = true;
            $admin->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Group model.
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
