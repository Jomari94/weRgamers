<?php

namespace app\controllers;

use Yii;
use app\models\Member;
use app\models\MemberSearch;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MembersController extends \yii\web\Controller
{
    /**
    * Deletes an existing Member model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param int $id_group
    * @param int $id_user
    * @return mixed
    */
    public function actionLeave($id_group, $id_user)
    {
        $this->findModel($id_group, $id_user)->delete();

        return $this->redirect(['/groups/index']);
    }

    /**
     * Crea una solicitud de unión al grupo
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
        }
        return $this->redirect(['/groups/view', 'id' => $id_group]);
    }

    /**
     * Lista de las solicitudes de unión al grupo
     * @param  int $id_group Id del grupo]
     * @return mixed
     */
    public function actionRequests($id_group)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Member::find()->where(['accepted' => false, 'id_group' => $id_group])
        ]);

        return $this->render('requests', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Se acepta una solicitud de unión
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
        }
        $this->redirect(['requests', 'id_group' => $id_group]);
    }

    /**
     * Se rechaza una solicitud de unión
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
        $this->redirect(['requests', 'id_group' => $id_group]);
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
