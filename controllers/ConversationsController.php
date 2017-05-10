<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Message;
use app\models\Conversation;
use app\models\Notification;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class ConversationsController extends \yii\web\Controller
{
    /**
     * Lista las conversaciones del usuario
     * @return mixed
     */
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $conversation = Conversation::find()
            ->where(['id_participant1' => Yii::$app->user->id, 'id_participant2' => $model->id_participant2])
            ->orWhere(['id_participant2' => Yii::$app->user->id, 'id_participant1' => $model->id_participant2])->one();
            if ($conversation == null) {
                $model->id_participant2 = User::find()->select('id')->where(['like', 'username', $model->username])->scalar();
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

    /**
     * Muestra los mensajes de la conversacion
     * @param  int $id Id de la conversacion
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = 'message';
        $message = new Message;
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Message::find()->where(['id_conversation' => $id])->orderBy('created DESC'),
            'pagination' => ['pageSize' => 20],
        ]);
        if ($message->load(Yii::$app->request->post()) && $message->validate(['content'])) {
            $message->id_sender = Yii::$app->user->id;
            $message->id_conversation = $id;
            $message->save();
            $receiver = Conversation::findOne($id)->receiver->id;
            Notification::create('messg',
                Yii::t('app', "{user} has send you: {content}",
                    ['user' => Yii::$app->user->identity->username, 'content' => $message->content]),
                [$receiver]
            );
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'message' => $message,
            'receiver' => $model->receiver,
            'id' => $id,
        ]);
    }

    /**
     * Busca juegos y los devuelve
     * @param  string $name Nombre del juego a buscar
     * @return array Nombres de los juegos encontrados
     */
    public function actionSearchUsers($name = null)
    {
        $users = [];
        if ($name != null || $name != '') {
            $users = User::find()
            ->select(['username'])
            ->where(['ilike', 'username', "$name"])
            ->column();
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
