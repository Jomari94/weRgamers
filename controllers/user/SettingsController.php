<?php

namespace app\controllers\user;

use Yii;
use app\models\AvatarForm;
use dektrium\user\controllers\SettingsController as BaseSettingsController;
use dektrium\user\models\Profile;
use yii\web\UploadedFile;

class SettingsController extends BaseSettingsController
{
    public function actionProfile()
    {
        $avatar = new AvatarForm;
        if (Yii::$app->request->isPost) {
            $avatar->imageFile = UploadedFile::getInstance($avatar, 'imageFile');
            $avatar->upload();
        }
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }
        $event = $this->getProfileEvent($model);
        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        return $this->render('profile', [
            'model' => $model,
            'avatar' => $avatar,
        ]);
    }
}
