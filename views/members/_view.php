<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

?>
<div>
    <?= Html::img($model->user->profile->getAvatar(), [
        'class' => 'img64 img-rounded img-responsive',
        ]) ?>
</div>
<div>
    <h4><?= Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user]))?></h4>
    <p><?= Yii::t('app', 'Role') ?>:</p>
</div>
