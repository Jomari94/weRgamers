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
    <div class="follows-partial">
        <div class="user-statistics-partial">
            <p><?= $model->karma ?></p>
            <p>karma</p>
        </div>
        <div class="user-statistics-partial">
            <p><?= $model->profile->totalFollowers ?></a>
            <p><?= Yii::t('app', 'followers') ?></p>
        </div>
        <div class="user-statistics-partial">
            <p><?= $model->profile->totalFollowed ?></a>
            <p><?= Yii::t('app', 'following') ?></p>
        </div>
    </div>
</div>
