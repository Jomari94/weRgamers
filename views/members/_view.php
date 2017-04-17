<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Member */

?>
<article class="row panel panel-default panel-body">
    <div class="col-xs-2">
        <?= Html::img($model->user->profile->getAvatar(), [
            'class' => 'img64 img-rounded img-responsive',
            ]) ?>
    </div>
    <div class="col-xs-offset-1 col-xs-9">
        <h4><?= Html::a($model->user->username, Url::to(['user/profile/show', 'id' => $model->id_user]))?></h4>
        <p><?= Yii::t('app', 'Role') ?>:</p>
    </div>
</article>
