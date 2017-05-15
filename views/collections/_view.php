<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

?>
<div>
    <?= Html::img($model->game->game->getCover(), [
        'class' => 'img64',
        ]) ?>
</div>
<div>
    <h4><?= Html::a($model->game->game->name, Url::to(['/games/view', 'id' => $model->id_game])) ?></h4>
    <p><?= Yii::t('app', 'Platform') ?>: <?= $model->game->platform->name?></p>
</div>
