<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

?>

<article class="row panel panel-default panel-body">
    <div class="col-xs-2">
        <?= Html::img($model->game->game->getCover(), [
            'class' => 'img64',
            ]) ?>
    </div>
    <div class="col-xs-offset-1 col-xs-9">
        <h4><?= $model->game->game->name?></h4>
        <p><?= Yii::t('app', 'Platform') ?>: <?= $model->game->platform->name?></p>
    </div>
</article>
