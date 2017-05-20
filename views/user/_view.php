<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

?>
<div class="media-left">
    <?= Html::a(Html::img($model->profile->getAvatar(), [
        'class' => 'img64 img-rounded img-responsive',
        ]), Url::to(['user/profile/show', 'id' => $model->id]))?>
</div>
<div class="media-body">
    <p class="media-heading"><?= Html::a($model->username, Url::to(['user/profile/show', 'id' => $model->id]))?></p>
    <p>
        <?= $model->profile->bio ?>
    </p>
    <div class="user-searched-view row">
        <span class="col-xs-1"><?= $model->karma ?> Karma</span>
        <span class="col-xs-2"><?= $model->profile->totalFollowers ?> Followers</span>
    </div>
</div>
