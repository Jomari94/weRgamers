<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="media-left">
    <?= Html::a(Html::img($model->cover, [
        'class' => 'img64 img-responsive',
    ]), Url::to(['games/view', 'id' => $model->id]))?>
</div>
<div class="media-body">
    <p class="media-heading"><?= Html::a($model->name, Url::to(['games/view', 'id' => $model->id]))?></p>
    <p class="pull-right"><?= Yii::$app->formatter->asDate($model->released) ?></p>
    <p><?= $model->namePlatforms ?></p>
    <div class="user-search-view row">
        <span class="col-xs-5">Score: <?= $model->score ?></span>
        <span class="col-xs-7"><?= $model->totalReviews ?> Reviews</span>
    </div>
</div>
