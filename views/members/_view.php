<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

?>
<div>
    <?= Html::img($model->user->profile->getAvatar(), [
        'class' => 'img64 img-rounded img-responsive',
        'id' => $model->user->username,
        ]) ?>
</div>
<div>
    <h4><?= Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user]))?></h4>
    <p><?= $model->admin ? Yii::t('app', 'Administrator') : Yii::t('app', 'Member') ?></p>
</div>
