<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Message;
use app\models\Conversation;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class ConversationsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'message';
        Conversation::vacias();
        $dataProvider = new ActiveDataProvider([
            'query' => Conversation::find()->where(['or', ['id_participant1' => Yii::$app->user->id], ['id_participant2' => Yii::$app->user->id]]),
            'pagination' => ['pageSize' => 5],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Conversation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = 'message';
        $model = new Conversation();

        if ($model->load(Yii::$app->request->post())) {
            $conversation = Conversation::find()
            ->where(['id_participant1' => Yii::$app->user->id, 'id_participant2' => $model->id_participant2])
            ->orWhere(['id_participant2' => Yii::$app->user->id, 'id_participant1' => $model->id_participant2])->one();
            if ($conversation == null) {
                $model->save();
                $model->refresh();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['view', 'id' => $conversation->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id)
    {
        $this->layout = 'message';
        $message = new Message;
        $dataProvider = new ActiveDataProvider([
            'query' => Message::find()->where(['id_conversation' => $id])->orderBy('created DESC'),
            'pagination' => ['pageSize' => 20],
        ]);
        if ($message->load(Yii::$app->request->post()) && $message->validate(['content'])) {
            $message->id_sender = Yii::$app->user->id;
            $message->id_conversation = $id;
            $message->save();
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'message' => $message,
            'id' => $id,
        ]);
    }

    /**
     * Busca juegos y los devuelve
     * @param  string $q Nombre del juego a buscar
     * @return array Nombres de los juegos encontrados
     */
    public function actionSearchUsers($q = null)
    {
        $users = [];
        if ($q != null || $q != '') {
            $users = User::find()
            ->select(['id', 'username'])
            ->where(['ilike', 'username', "$q"])
            ->all();
            $users = ArrayHelper::map($users, 'username', 'id');
        }
        return Json::encode($users);
    }

    /**
     * Finds the Conversation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Conversation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }
}
