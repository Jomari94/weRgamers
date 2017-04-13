<?php

namespace app\controllers;

use app\models\Member;
use yii\web\NotFoundHttpException;

class MembersController extends \yii\web\Controller
{
    public function actionJoin()
    {
        return true;
    }

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
