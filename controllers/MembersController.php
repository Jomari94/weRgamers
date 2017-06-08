<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\Group;
use app\models\Notification;
use dektrium\user\filters\AccessRule;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Controlador del modelo Member
 */
class MembersController extends \yii\web\Controller
{
    /**
     * Behaviors del controlador de Member.
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'leave' => ['POST'],
                    'ban' => ['POST'],
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
                        'actions' => ['join'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['leave'],
                        'roles' => ['leaveGroup'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['requests', 'confirm', 'reject', 'index', 'ban', 'promote'],
                        'roles' => ['manageRequests'],
                    ],
                ],
            ],
        ];
    }

    /**
    * Borra un modelo Member.
    * @param int $id_group
    * @param int $id_user
    * @return mixed
    */
    public function actionLeave($id_group, $id_user)
    {
        $this->findModel($id_group, $id_user)->delete();

        if (Member::findOne(['id_group' => $id_group, 'accepted' => true]) === null) {
            Member::deleteAll(['id_group' => $id_group, 'accepted' => false]);
            Group::findOne(['id' => $id_group])->delete();
        }

        return $this->redirect(['/groups/index']);
    }

    /**
     * Crea una solicitud de unión al grupo.
     * @param  int $id_group Id del grupo
     * @return mixed
     */
    public function actionJoin($id_group)
    {
        $model = new Member();
        $model->id_group = $id_group;
        $model->id_user = Yii::$app->user->id;
        $model->accepted = false;

        if (!$model->save()) {
            Yii::$app->session->setFlash('Error', Yii::t('app', 'There was a problem submitting your request'));
        } else {
            $admins = Member::find()->select('id_user')->where(['id_group' => $id_group, 'admin' => true])->column();
            Notification::create(Notification::REQUEST, $admins, Yii::$app->user->id, $id_group);
        }
        return $this->redirect(['/groups/view', 'id' => $id_group]);
    }

    /**
     * Lista de las solicitudes de unión al grupo.
     * @param  int $id_group Id del grupo]
     * @return mixed
     */
    public function actionIndex($id_group)
    {
        $requestProvider = new ActiveDataProvider([
            'query' => Member::find()->where(['accepted' => false, 'id_group' => $id_group])
        ]);
        $requestProvider->pagination->pageParam = 'pageR';
        $requestProvider->sort->sortParam = 'sortR';
        $memberProvider = new ActiveDataProvider([
            'query' => Member::find()->where(['accepted' => true, 'id_group' => $id_group])
        ]);
        $memberProvider->pagination->pageParam = 'pageM';
        $memberProvider->sort->sortParam = 'sortM';
        $group = Group::findOne($id_group);

        return $this->render('index', [
            'requestProvider' => $requestProvider,
            'memberProvider' => $memberProvider,
            'group' => $group,
        ]);
    }

    /**
     * Se acepta una solicitud de unión.
     * @param  int $id_group Id del grupo
     * @param  int $id_user  Id del miembro
     * @return mixed
     */
    public function actionConfirm($id_group, $id_user)
    {
        $model = Member::findOne(['id_group' => $id_group, 'id_user' => $id_user]);
        if ($model !== null) {
            $model->accepted = true;
            $model->update();
            Notification::create(Notification::CONFIRMATION, [$id_user], null, $id_group);
        }
        $this->redirect(['index', 'id_group' => $id_group]);
    }

    /**
     * Se rechaza una solicitud de unión.
     * @param  int $id_group Id del grupo
     * @param  int $id_user  Id del miembro
     * @return mixed
     */
    public function actionReject($id_group, $id_user)
    {
        $model = Member::findOne(['id_group' => $id_group, 'id_user' => $id_user]);
        if ($model !== null) {
            $model->delete();
        }
        $this->redirect(['index', 'id_group' => $id_group]);
    }

    /**
     * Se expulsa a un miembro del grupo.
     * @param  int $id_group Id del grupo
     * @param  int $id_user  Id del miembro
     * @return mixed
     */
    public function actionBan($id_group, $id_user)
    {
        $model = Member::findOne(['id_group' => $id_group, 'id_user' => $id_user]);
        if ($model !== null) {
            $model->delete();
        }
        $this->redirect(['index', 'id_group' => $id_group]);
    }

    /**
     * Se asciende a un miembro del grupo a administrador.
     * @param  int $id_group Id del grupo
     * @param  int $id_user  Id del miembro
     * @return mixed
     */
    public function actionPromote($id_group, $id_user)
    {
        $model = Member::findOne(['id_group' => $id_group, 'id_user' => $id_user]);
        if ($model !== null) {
            $model->admin = true;
            $model->update();
        }
        $this->redirect(['index', 'id_group' => $id_group]);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_group
     * @param int $id_user
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_group, $id_user)
    {
        if (($model = Member::findOne(['id_group' => $id_group, 'id_user' => $id_user])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
